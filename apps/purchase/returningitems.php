<?php
    include 'init.php';
    include_once $classes.'myAutoloader.php';

    $productIDs       = array();
    $productInfo      = array();
    $invoiceNumber    = $_POST['invoiceNumberReturns'];
    $providerName     = $_POST['provider'];
    $returnsTotal     = floatval($_POST['returnsTotal']);

    foreach($_POST as $key=>$value){
        if (strpos($key, "amount") === 0){
            $productID = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);
            array_push($productIDs, $productID);
        }
    }

    for ($c = 0; $c < count($productIDs); $c++){
        $productInfo[$productIDs[$c]]['amount']   = $_POST['amount'. $productIDs[$c]];
        if (isset($_POST['returns'. $productIDs[$c]]))
        $productInfo[$productIDs[$c]]['returns']  = $_POST['returns'. $productIDs[$c]];
        else
        $productInfo[$productIDs[$c]]['returns']  = 0;
    }

    Invoice::returnItems($invoiceNumber, $providerName, $productIDs, $productInfo, $returnsTotal);
    unset($_SESSION['invoiceDetails']);
    unset($_SESSION['invoiceNumber']);
    unset($_SESSION['invoiceProvider']);
    


?>