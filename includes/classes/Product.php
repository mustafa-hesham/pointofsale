<?php

class Product{

    //Properties

    private $ID;
    private $name;
    private $quantity;
    private $price;
    private $isAvailable;
    private $imagePath;
    private $barcode;
    private connect $connection;
    private static $allProducts = array();

    // Constructor

    function __construct($name, $price, $imagePath, $barcode)
    {
        $this->name = $name;
        $this->price = $price;
        $this->imagePath = $imagePath;
        $this->barcode = $barcode;
        $this->connection = new connect();
    }

    // Public functions
    //              ## Setters ##
    
    public function setID($ID){
        $this->ID = $ID;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setQuantity($quantity){
        $this->quantity = $quantity;
    }

    public function setPrice($price){
        $this->price = $price;
    }

    public function setImagePath($imagePath){
        $this->imagePath = $imagePath;
    }

    public function setBarcode($barcode){
        $this->barcode = $barcode;
    }

    public function setAvailable($isAvailable){
        $this->isAvailable = $isAvailable;
    }

    //              ## Gettters ##

    public function getID(){
        return $this->ID;
    }

    public function getName(){
        return $this->name;
    }

    public function getQuantity(){
        return $this->quantity;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getImagePath(){
        return $this->imagePath;
    }

    public function getBarcode(){
        return $this->barcode;
    }

    public function getAvailable(){
        return $this->isAvailable;
    }

    // Insert new product methods

    public function checkDuplicateName(){
        $checkDuplicateName = "SELECT name FROM product WHERE name = '$this->name'";
        $checkDuplicateNameresult = $this->connection->connectDB()->query($checkDuplicateName);
        $this->connection->connectDB()->close();
        return $checkDuplicateNameresult->num_rows > 0? true : false;
    }

    public function checkDuplicateBarcode(){
        $checkDuplicateBarcode = "SELECT barcode FROM product WHERE barcode = '$this->barcode'";
        $checkDuplicateBarcoderesult = $this->connection->connectDB()->query($checkDuplicateBarcode);
        $this->connection->connectDB()->close();
        return $checkDuplicateBarcoderesult->num_rows > 0? true : false;
    }

    public function saveProduct(){

        if (!$this->checkDuplicateName() && !$this->checkDuplicateBarcode()){
            $insertQuery = "INSERT INTO product (name, price, image, barcode) VALUES('$this->name', '$this->price', '$this->imagePath' ,'$this->barcode');";
            $this->connection->connectDB()->query($insertQuery);
            $checkInsertSuccessfulQuery         = "SELECT name FROM product WHERE name = '$this->name' AND image = '$this->imagePath' and barcode = '$this->barcode'";
            $checkInsertSuccessfulQueryResult   = $this->connection->connectDB()->query($checkInsertSuccessfulQuery);
            $this->connection->connectDB()->close();
            return ($checkInsertSuccessfulQueryResult->num_rows > 0)? true : false;
            
        }
        else{
            if ($this->checkDuplicateName()) return false;
            if ($this->checkDuplicateBarcode()) return false;
        }
        
    }

    public static function DuplicateName($checkName){
        $conn = new connect();
        $DuplicateName = "SELECT name FROM product WHERE name = '$checkName'";
        $DuplicateNameresult = $conn->connectDB()->query($DuplicateName);
        $conn->connectDB()->close();
        return $DuplicateNameresult->num_rows > 0? true : false;

    }

    public static function DuplicateBarcode($checkBarcode){
        $conn = new connect();
        $DuplicateBarcode = "SELECT barcode FROM product WHERE barcode = '$checkBarcode'";
        $DuplicateBarcoderesult = $conn->connectDB()->query($DuplicateBarcode);
        $conn->connectDB()->close();
        return $DuplicateBarcoderesult->num_rows > 0? true : false;

    }

    public function updateProduct($productID){
        $updateQuery = "UPDATE product SET name='$this->name', price='$this->price', image='$this->imagePath', barcode='$this->barcode' 
            WHERE id='$productID'";
        $this->connection->connectDB()->query($updateQuery);
        $this->connection->connectDB()->close();
    }

    // Get products data from database

    public static function getAllProducts(){
        $conn = new connect();
        $getAllProductQuery       = "SELECT name, price, image, barcode from product";
        $getAllProductQueryResult = $conn->connectDB()->query($getAllProductQuery);
        if ($getAllProductQueryResult->num_rows > 0){
            while($row = $getAllProductQueryResult->fetch_assoc()){
                self::$allProducts[] = new Product($row['name'], $row['price'], $row['image'], $row['barcode']);
            }
        }
        $conn->connectDB()->close();
        return self::$allProducts;
    }

    public static function getProductByName($productname){
        $conn = new connect();
        $getByNameQuery         = "SELECT name, price, image, barcode FROM product WHERE name='$productname'";
        $getByNameQueryResult   = $conn->connectDB()->query($getByNameQuery);
        $conn->connectDB()->close();
        if ($getByNameQueryResult->num_rows == 1){
            while ($row  = $getByNameQueryResult->fetch_assoc()){
                $productObj = new Product($row['name'], $row['price'], $row['image'], $row['barcode']);
            }
            return $productObj;
        }
        return null;
    }

    public static function getProductsByNameAutocomplete($productname){
        $conn = new connect();
        $nameQuery       = "SELECT name FROM product WHERE name LIKE '$productname%' LIMIT 5";
        $nameQueryResult = $conn->connectDB()->query($nameQuery);
        if ($nameQueryResult->num_rows > 0){
            while ($row  = $nameQueryResult->fetch_assoc()){
                $productsNames[] = $row['name'];
                }
            }
        $conn->connectDB()->close();
        echo json_encode($productsNames);
    }

    public static function getIdByName($productname){
        $row['ID']      = '';
        $conn           = new connect();
        $IDQuery        = "SELECT ID FROM product WHERE name = '$productname'";
        $IDQueryResult  = $conn->connectDB()->query($IDQuery);
        if ($IDQueryResult->num_rows > 0)
            $row = $IDQueryResult->fetch_assoc();
        $conn->connectDB()->close();
        return $row['ID'];
    }

    public static function getNameByID($productID){
        $row['name']     = '';
        $conn            = new connect();
        $nameQuery       = "SELECT name FROM product WHERE ID = '$productID'";
        $nameQueryResult = $conn->connectDB()->query($nameQuery);
        if ($nameQueryResult->num_rows > 0)
            $row = $nameQueryResult->fetch_assoc();
        $conn->connectDB()->close();
        return $row['name'];
    }

    public static function getIdCountByName($productname){
        $conn           = new connect();
        $IDQuery        = "SELECT COUNT(ID) FROM product WHERE name = '$productname'";
        $IDQueryResult  = $conn->connectDB()->query($IDQuery);
        $row = $IDQueryResult->fetch_assoc();
        $conn->connectDB()->close();
        return $row['COUNT(ID)'];
    }

    public static function getBarcodeByName($productname){
        $row['barcode']      = '';
        $conn                = new connect();
        $barcodeQuery        = "SELECT barcode FROM product WHERE name = '$productname'";
        $barcodeQueryResult  = $conn->connectDB()->query($barcodeQuery);
        if ($barcodeQueryResult->num_rows > 0)
            $row = $barcodeQueryResult->fetch_assoc();
        $conn->connectDB()->close();
        return $row['barcode'];
    }

    public static function getBarcodeCountByBarcode($productBarcode){
        $conn                = new connect();
        $barcodeQuery        = "SELECT COUNT(barcode) FROM product WHERE barcode = '$productBarcode'";
        $barcodeQueryResult  = $conn->connectDB()->query($barcodeQuery);
        $row = $barcodeQueryResult->fetch_assoc();
        $conn->connectDB()->close();
        return $row['COUNT(barcode)'];
    }

    public static function getProductInfo($searchparameter){
        $conn                = new connect();
        $productInfo        = array();
        if (!is_numeric($searchparameter)){
            $searchQuery        = "SELECT * from product WHERE name LIKE '$searchparameter%' LIMIT 5";
            $searchQueryResult  = $conn->connectDB()->query($searchQuery);
            $conn->connectDB()->close();
            if ($searchQueryResult->num_rows > 0){
                while ($row  = $searchQueryResult->fetch_assoc()){
                    $productInfo[] = $row;
                    }
                }
            return json_encode($productInfo);
        }
        else{
            $searchQuery        = "SELECT * from product WHERE barcode='$searchparameter'";
            $searchQueryResult  = $conn->connectDB()->query($searchQuery);
            if ($searchQueryResult->num_rows > 0){
                $productInfo[]  = $searchQueryResult->fetch_assoc();
                return json_encode($productInfo);
            }
            else{
                $searchQuery        = "SELECT * from product WHERE ID='$searchparameter'";
                $searchQueryResult  = $conn->connectDB()->query($searchQuery);
                $productInfo[]      = $searchQueryResult->fetch_assoc();
                return json_encode($productInfo);
            }
            $conn->connectDB()->close();
        }
        $conn->connectDB()->close();
        
    }

    }
?>