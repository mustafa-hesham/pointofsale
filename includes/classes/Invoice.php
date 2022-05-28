<?php

class Invoice{

    private $invoiceID;
    private $invoiceNumber;
    private $providerID;
    private $invoiceDateTime;
    private $discount;
    private $discountpercent;
    private $productsInfo;
    private $total;
    private connect $connection;

    // ## Setters ## //

    public function setInvoiceID($ID){
        $this->invoiceID = $ID;
    }

    public function setInvoiceNumber($number){
        $this->invoiceNumber = $number;
    }

    public function setProviderID($provider_id){
        $this->providerID = $provider_id;
    }

    public function setInvoiceDateTime($dateTime){
        $this->invoiceDateTime = $dateTime;
    }

    public function setDiscount($discount){
        $this->discount = $discount;
    }

    public function setDiscountPercent($discountpercent){
        $this->discountpercent = $discountpercent;
    }

    public function setProductsInfo($productsInfo){
        $this->productsInfo = $productsInfo;
    }

    // ## Getters ## //

    public function getInvoiceID(){
        return $this->invoiceID;
    }

    public function getInvoiceNumber(){
        return $this->invoiceNumber;
    }

    public function getProviderID(){
        return $this->providerID;
    }

    public function getInvoiceDateTime(){
        return $this->invoiceDateTime;
    }

    public function getDiscount(){
        return $this->discount;
    }

    public function getDiscountPercent(){
        return $this->discountpercent;
    }

    public function getProductsInfo(){
        return $this->productsInfo;
    }

    // Construct

    function __construct($number, $provider, $dateTime, $discount, $discountpercent, $productsInfo, $total){
        $this->invoiceNumber    = $number;
        $this->providerID       = $provider;
        $this->invoiceDateTime  = $dateTime;
        $this->discount         = $discount;
        $this->discountpercent  = $discountpercent;
        $this->productsInfo     = $productsInfo;
        $this->total            = $total;
        $this->connection       = new connect();
    }




    // Insert data methods

    public static function checkRepeatedInvoiceNumber($number, $providerID){
        $conn = new connect();
        $duplicateInvoiceNumberQuery = "SELECT id FROM purchase_invoice WHERE invoice_number = '$number' AND provider_id = '$providerID'";
        $duplicateInvoiceNumberQueryResult = $conn->connectDB()->query($duplicateInvoiceNumberQuery);
        $conn->connectDB()->close();
        echo $duplicateInvoiceNumberQueryResult->num_rows == 0? 'false' : 'true';
    }

    public function saveInvoice($productsIds){
        
        for ($c = 0; $c < count($this->productsInfo); $c++){
            $insertInvoiceQuery = "INSERT INTO purchase_invoice(invoice_number, provider_id, invoice_dateTime, discount, 
                                    percentDiscount, product_id, productSellPrice, productAmount, productCost, productVAT)
                                    VALUES('$this->invoiceNumber', '$this->providerID', '$this->invoiceDateTime', '$this->discount',
                                    '$this->discountpercent', '$productsIds[$c]', '{$this->productsInfo[$productsIds[$c]]['price']}',
                                     '{$this->productsInfo[$productsIds[$c]]['amount']}', '{$this->productsInfo[$productsIds[$c]]['cost']}'
                                     , '{$this->productsInfo[$productsIds[$c]]['VAT']}')";
            
            $this->connection->connectDB()->query($insertInvoiceQuery);

        }
        $updateProviderBalanceQuery = "UPDATE provider SET provider.balance = provider.balance + $this->total WHERE provider.ID = '$this->providerID'";
        $this->connection->connectDB()->query($updateProviderBalanceQuery);
        echo ($this->connection->showLastID())? 'Success' : $this->connection->getLastError();
        $this->connection->connectDB()->close();
    }

    public static function getInvoice($number, $provider, $fromDate, $toDate){
        $invoices = array();
        $fromDateFormatted = date('Y-m-d H:i:s', strtotime($fromDate. ' +10 seconds'));
        $toDateFormatted = date('Y-m-d H:i:s', strtotime($toDate. ' +10 seconds'));

        $where = " WHERE invoice_dateTime BETWEEN '$fromDateFormatted' AND '$toDateFormatted' ";

        if (!empty($number)) $where .= " AND invoice_number = '$number' ";
        if (!empty($provider)) $where .= " AND provider_name = '$provider' ";
        $getInvoiceQuery = "SELECT provider_name, invoice_dateTime, discount, percentDiscount, invoice_number, 
        ROUND(SUM(productCost*productAmount), 2) AS CostSum, ROUND(SUM(productVAT*productAmount), 2) AS VATSum, 
        COUNT(invoice_dateTime) AS ProductsNum, 
        ROUND(SUM(productCost*productAmount), 2) + ROUND(SUM(productVAT*productAmount), 2) - ROUND(discount, 2) - ROUND(ROUND(SUM(productCost*productAmount)+SUM(productVAT*productAmount), 2) * percentDiscount/100 ,2) AS Total
        FROM purchase_invoice INNER JOIN provider ON (provider_id = provider.ID)
        ".$where."
        GROUP BY provider_name, invoice_dateTime, invoice_number, discount, percentDiscount
        ORDER BY invoice_dateTime, provider_name DESC";
        $conn = new connect();
        $getInvoiceQueryResult = $conn->connectDB()->query($getInvoiceQuery);
        if ($getInvoiceQueryResult->num_rows > 0){
            while ($row  = $getInvoiceQueryResult->fetch_assoc()){
                array_push($invoices, array("provider" => $row['provider_name'], "date" => $row['invoice_dateTime'], "discount" => $row['discount'], "percentdiscount" => $row['percentDiscount'],
                "invoiceNumber" => $row['invoice_number'], "cost" => $row['CostSum'], "vat" => $row['VATSum'], "numberOfProducts" => $row['ProductsNum'], "total" => $row['Total']));
            }
        }
        $conn->connectDB()->close();
        echo json_encode($invoices);
    }

    public static function getInvoiceByNumber($number, $providerName){
        $invoiceRows                      = array();
        $conn                             = new connect();
        $getInvoiveByNumberQuery          = "SELECT provider_name, provider.balance, invoice_number, invoice_dateTime, product.name, product.quantity, productSellPrice, productAmount, productCost, productVAT, discount, percentDiscount,
                                                product_id, returns FROM purchase_invoice INNER JOIN provider ON (provider_id = provider.ID) INNER JOIN product on (product_id = product.ID) WHERE invoice_number = '$number' AND provider_name = '$providerName'";
        $getInvoiveByNumberQueryResult    = $conn->connectDB()->query($getInvoiveByNumberQuery);
        if ($getInvoiveByNumberQueryResult->num_rows > 0){
            while($row = $getInvoiveByNumberQueryResult->fetch_assoc()){
                $invoiceRows[] = $row;
            }
        }
        $conn->connectDB()->close();
        return $invoiceRows;
    }

    public static function returnItems($invoiceNumber, $providerName, $productsIds, $productsInfo, $returnsTotal){
        $providerID = Provider::getProviderIdByName($providerName);
        $conn   = new connect();
        for ($c = 0; $c < count($productsInfo); $c++){
            if (isset($productsInfo[$productsIds[$c]]['returns']) && floatval($productsInfo[$productsIds[$c]]['returns']) > 0){
            $updatePurchaseInvoiceQuery = "UPDATE purchase_invoice SET returns = '{$productsInfo[$productsIds[$c]]['returns']}'
            WHERE provider_id = '$providerID' AND invoice_number = '$invoiceNumber' AND product_id = '$productsIds[$c]' AND productAmount = '{$productsInfo[$productsIds[$c]]['amount']}'";
            $conn->connectDB()->query($updatePurchaseInvoiceQuery);
            }
        }
        $conn->connectDB()->close();
        $conn   = new connect();
        if (floatval($returnsTotal) > 0){
        $now = date('Y-m-d H:i:s');
        $insertNewReturnQuery = "INSERT INTO returns(invoiceNumber, provider_id, return_dateTime, total) VALUES('$invoiceNumber', '$providerID', '$now', '$returnsTotal')";
        $conn->connectDB()->query($insertNewReturnQuery);
        $conn->connectDB()->close();
        $checkInsertQuery       = "SELECT invoiceNumber FROM returns  WHERE invoiceNumber = '$invoiceNumber' AND provider_id='$providerID' AND return_dateTime = '$now'";
        $checkInsertQueryResult = $conn->connectDB()->query($checkInsertQuery);
        if ($checkInsertQueryResult->num_rows == 1) echo 'Success';
        }
        else echo 'Zero';
        $conn->connectDB()->close();
        
    }
}


?>