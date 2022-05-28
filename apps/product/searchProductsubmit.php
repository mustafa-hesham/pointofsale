<?php
include 'init.php';
include_once $classes.'myAutoloader.php';


if (isset($_POST['searchProductName']) && strlen($_POST['searchProductName']) > 0) 
    $productObj = Product::getProductByName($_POST['searchProductName']);
    if (isset($productObj)){
        setSessionVariables($productObj);
    }
    else
        unsetSessionVariables();
    
if (isset($_POST['searchProductName']) && strlen($_POST['searchProductName']) == 0){
        $_SESSION['Searchterm']         = '';
        $_SESSION['DoesNotExist']       = false;
        $_SESSION['successfullUpdate']  = false;
}
    unset($productObj);
    header("Location: editproduct");


    function setSessionVariables($productObj){
        $_SESSION['Searchterm']         = '';
        $_SESSION['productName']        = $productObj->getName();
        $_SESSION['productBarcode']     = $productObj->getBarcode();
        $_SESSION['productPrice']       = $productObj->getPrice();
        $_SESSION['productImage']       = $productObj->getImagePath();
        $_SESSION['imageName']          = basename($productObj->getImagePath());
        $_SESSION['DoesNotExist']       = false;
    }

    function unsetSessionVariables(){
        $_SESSION['Searchterm']         = $_POST['searchProductName'];
        $_SESSION['productName']        = "";
        $_SESSION['productBarcode']     = "";
        $_SESSION['productPrice']       = "";
        $_SESSION['productImage']       = "";
        $_SESSION['imageName']          = "";
        $_SESSION['DoesNotExist']       = true;

    }

?>