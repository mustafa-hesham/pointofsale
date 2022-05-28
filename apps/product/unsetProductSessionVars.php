<?php
// Product session variables
if (isset($GLOBALS['target_file']))
    unset($GLOBALS['target_file']);

foreach($_SESSION as $key=>$value){
    if ($key != 'username')
    unset($_SESSION[$key]);
}

// $_SESSION['Searchterm']              = "";
// $_SESSION['productName']             = "";
// $_SESSION['productBarcode']          = "";
// $_SESSION['productPrice']            = "";
// $_SESSION['productImage']            = "";
// $_SESSION['imageName']               = "";
// $_SESSION['DoesNotExist']            = false;
// $_SESSION['imageErrors']             = array();
// $GLOBALS['target_file']              = '';
// $_SESSION['duplicateProductName']    = false;
// $_SESSION['duplicatebarcode']        = false;
// $_SESSION['successfullUpdate']       = false;
// $_SESSION['successfullAddition']     = false;
// $_SESSION['duplicateName']           ='';
// $_SESSION['duplicateProductBarcode'] = '';  


?>