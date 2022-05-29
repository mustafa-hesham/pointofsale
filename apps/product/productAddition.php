<?php
    include 'init.php';
    include_once $classes.'myAutoloader.php';
    include $generalFunctions.'generalFunctions.php';
    global $target_file;
    $_SESSION['duplicateProductName']   = false;
    $_SESSION['duplicatebarcode']       = false;
    $_SESSION['successfullAddition']    = false;
    $_SESSION['imageErrors']            = array();
    $_SESSION['errorMessages']          = true;
?>
<?php

    if(isset($_POST['productSubmit'])){

        if(Product::DuplicateName($_POST['productName'])){
            $_SESSION['duplicateProductName'] = true;
            setSessionVariables();
        } 
        if(Product::DuplicateBarcode($_POST['barcode'])){
            $_SESSION['duplicatebarcode'] = true;
            setSessionVariables();
        } 

        if (!Product::DuplicateName($_POST['productName']) && !Product::DuplicateBarcode($_POST['barcode'])){
            uploadImage($productImage, $_FILES['productImage'], $GLOBALS['target_file'], $_POST['productName'], $_POST['price']);
            if (count($_SESSION['imageErrors']) == 0){
            $productObj = new Product($_POST['productName'], floatval($_POST['price']), $GLOBALS['target_file'], intval($_POST['barcode']));
            $_SESSION['errorMessages'] = $productObj->saveProduct(); 
            if (!$_SESSION['errorMessages']) {
                $_SESSION['successfullAddition'] = false;
                unlink($productObj->getImagePath());
            }
            else{
                emptySessionVariables();
                $_SESSION['successfullAddition'] = true;
            }
            }
            else setSessionVariables();
        }
        header("Location:addproduct");

    }

    function setSessionVariables(){
        $_SESSION['productName']    = $_POST['productName'];
        $_SESSION['productBarcode'] = $_POST['barcode'];
        $_SESSION['productPrice']   = $_POST['price'];
    }

    function emptySessionVariables(){
        $_SESSION['productName']    = '';
        $_SESSION['productBarcode'] = '';
        $_SESSION['productPrice']   = '';
        $_SESSION['imageErrors']    = array();
        $GLOBALS['target_file']     = '';
        $_SESSION['errorMessages']  = true;
    }
?>
