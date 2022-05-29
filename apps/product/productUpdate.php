<?php
    include 'init.php';
    include_once $classes.'myAutoloader.php';
    include $generalFunctions.'generalFunctions.php';
    global $target_file;
    $_SESSION['duplicateProductName']   = false;
    $_SESSION['duplicatebarcode']       = false;
    $_SESSION['successfullUpdate']      = false;
    $_SESSION['imageErrors']            = array();

    if (isset($_POST['editproductSubmit']) && Product::getProductByName($_SESSION['productName']) != null){
        $productObj         = new Product($_POST['productName'], floatval($_POST['price']), $_FILES['productImage']['name'], $_POST['barcode']);
        $oldProductobj      = Product::getProductByName($_SESSION['productName']);
        $oldProductobj->setID(Product::getIdByName($oldProductobj->getName()));

        // Check if the new name is already in the database

        if ($productObj->getName() != $oldProductobj->getName() 
                && intval(Product::getIdCountByName($productObj->getName())) != 0){

            $_SESSION['duplicateProductName']   = true;
            $_SESSION['duplicateName']          = $_POST['productName'];
            header("Location:editproduct");
        }
        
        if ($productObj->getBarcode() != $oldProductobj->getBarcode() 
        && intval(Product::getBarcodeCountByBarcode($productObj->getBarcode())) != 0){
            $_SESSION['duplicatebarcode']        = true;
            $_SESSION['duplicateProductBarcode'] = $_POST['barcode'];
            header("Location:editproduct");
        }

        if (!$_SESSION['duplicateProductName'] && !$_SESSION['duplicatebarcode']){
            if ($_FILES['productImage']['name'] == ''){
                $imageFileExtension = strtolower(pathinfo($oldProductobj->getImagePath(),PATHINFO_EXTENSION));
                $targetDestination  = $productImage . $productObj->getName() . '_' . $productObj->getPrice().'.' .$imageFileExtension;
                $targetDestination  = str_replace(' ', '_', $targetDestination);
                rename($oldProductobj->getImagePath(), $targetDestination);
                $productObj->setImagePath($targetDestination);
            }
                
            else{
                if (is_file($oldProductobj->getImagePath())) unlink($oldProductobj->getImagePath());
                uploadImage($productImage, $_FILES['productImage'], $GLOBALS['target_file'], $_POST['productName'], $_POST['price']);
                $productObj->setImagePath($GLOBALS['target_file']);
            }
            if (!count($_SESSION['imageErrors'])){
                $productObj->updateProduct($oldProductobj->getID());
                $_SESSION['successfullUpdate'] = true;
                emptySessionVariables();
                header("Location:editproduct");
            }
            else header("Location:editproduct");
        }

        
    }
    function emptySessionVariables(){
        $_SESSION['productName']                 = '';
        $_SESSION['productBarcode']              = '';
        $_SESSION['productPrice']                = '';
        $_SESSION['productImage']                = '';
        $_SESSION['imageErrors']                 = array();
        $GLOBALS['target_file']                  = '';
        $_SESSION['duplicateProductBarcode']     = '';
        $_SESSION['duplicateName']               = '';
        unset($_SESSION['imageName']);
        unset($_FILES['productImage']);
    }
?>
