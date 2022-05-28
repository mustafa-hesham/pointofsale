<?php
    include 'init.php';
    include_once $classes.'myAutoloader.php';

    $productIDs       = array();
    $productInfo      = array();
    $invoiceNumber    = $_POST['invoiceNumber'];
    $providerName     = $_POST['provider'];
    $discount         = $_POST['discount'];
    $discountpercent  = $_POST['discountpercent'];
    $providerID       = $_POST['providerID'];
    $total            = floatval($_POST['total']);

    foreach($_POST as $key=>$value){
        if (strpos($key, "price") === 0){
            $productID = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
            array_push($productIDs, $productID);
        }
    }

    for ($c = 0; $c < count($productIDs); $c++){
        $productInfo[$productIDs[$c]]['price']    = $_POST['price'. $productIDs[$c]];
        $productInfo[$productIDs[$c]]['amount']   = $_POST['amount'. $productIDs[$c]];
        $productInfo[$productIDs[$c]]['cost']     = $_POST['cost'. $productIDs[$c]];
        $productInfo[$productIDs[$c]]['VAT']      = $_POST['VAT'. $productIDs[$c]];
    }
    $now = date('Y-m-d H:i:s');
    $invoiceObj = new Invoice($invoiceNumber, $providerID, $now, $discount, $discountpercent, $productInfo, $total);
    $invoiceObj->saveInvoice($productIDs);
?>