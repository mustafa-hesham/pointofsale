<?php
include 'init.php';
include_once $classes.'myAutoloader.php';


if (isset($_GET['term'])) echo Product::getProductInfo($_GET['term']);

if (isset($_POST['provider_name']) && isset($_POST['function']) && $_POST['function'] == 'getProviderName'){
    Provider::getProviderNameAutoComplete($_POST['provider_name']);
}

if (isset($_POST['function']) && isset($_POST['invoiceNumber']) && $_POST['function'] == 'repeatedInvoiceNumber'){
    Invoice::checkRepeatedInvoiceNumber($_POST['invoiceNumber'], $_POST['provider']);
}

if (isset($_POST['invoiceRecallButton'])){
    $_SESSION['invoiceNumber']          = $_POST['invoiceNumberHidden'];
    $_SESSION['invoiceProvider']        = $_POST['providerHidden'];
    $_SESSION['invoiceDiscount']        = $_POST['discountHidden'];
    $_SESSION['invoicePercentDiscount'] = $_POST['percentdiscountHidden'];
    $_SESSION['invoiceCost']            = $_POST['costHidden'];
    $_SESSION['invoiceVAT']             = $_POST['vatHidden'];
    $_SESSION['invoiceTotal']           = $_POST['totalHidden'];
    
    $_SESSION['invoiceDetails'] = Invoice::getInvoiceByNumber($_POST['invoiceNumberHidden'], $_POST['providerHidden']);

    if ($_POST['pageNameHidden'] == 'New invoice')
        header("Location: newinvoice");
    
    if ($_POST['pageNameHidden'] =='Return an item')
        header("Location: returnitem");
    
}

?>