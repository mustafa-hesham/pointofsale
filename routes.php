<?php
$folderName = '/training/';
require_once("{$_SERVER['DOCUMENT_ROOT']}{$folderName}router.php");

// ##################################################
// ##################################################
// ##################################################

// Static GET
// In the URL -> http://localhost
// The output -> Index

//### GET ###//

//Product routes
get('/training', 'index.php');
get($folderName.'addproduct', 'apps/product/addProduct.php');
get($folderName.'productaddition', 'apps/product/productAddition.php');
get($folderName.'editproduct', 'apps/product/editProduct.php');
get($folderName.'productsearchautocomplete', 'apps/product/productSearchAutocomplete.php');
get($folderName.'searchProductsubmit', 'apps/product/searchProductsubmit.php');
get($folderName.'unsetproductsession', 'apps/product/unsetProductSessionVars.php');
get($folderName.'productupdate', 'apps/product/productUpdate.php');

//Purchase routes
get($folderName.'newinvoice', 'apps/purchase/newInvoice.php');
get($folderName.'invoiceInfosearch', 'apps/purchase/invoiceInfosearch.php');
get($folderName.'invoiceaddition', 'apps/purchase/invoiceAddition.php');
get($folderName.'invoicesearching', 'apps/purchase/invoiceSearching.php');
get($folderName.'returnitem', 'apps/purchase/returns.php');
get($folderName.'returningitems', 'apps/purchase/returningitems.php');


//Provider routes
get($folderName.'newprovider', 'apps/provider/addprovider.php');
get($folderName.'provideraddition', 'apps/provider/providerAddition.php');
get($folderName.'editprovider', 'apps/provider/editprovider.php');
get($folderName.'providerediting', 'apps/provider/providerEditing.php');
get($folderName.'searchprovidersubmit', 'apps/provider/searchprovidersubmit.php');

//### POST ###//

//Product routes
post($folderName, 'index.php');
post($folderName.'addproduct', 'apps/product/addProduct.php');
post($folderName.'productaddition', 'apps/product/productAddition.php');
post($folderName.'editproduct', 'apps/product/editProduct.php');
post($folderName.'productsearchautocomplete', 'apps/product/productSearchAutocomplete.php');
post($folderName.'searchProductsubmit', 'apps/product/searchProductsubmit.php');
post($folderName.'unsetproductsession', 'apps/product/unsetProductSessionVars.php');
post($folderName.'productupdate', 'apps/product/productUpdate.php');

//Purchase routes
post($folderName.'newinvoice', 'apps/purchase/newInvoice.php');
post($folderName.'invoiceInfosearch', 'apps/purchase/invoiceInfosearch.php');
post($folderName.'invoiceaddition', 'apps/purchase/invoiceAddition.php');
post($folderName.'invoicesearching', 'apps/purchase/invoiceSearching.php');
post($folderName.'returnitem', 'apps/purchase/returns.php');
post($folderName.'returningitems', 'apps/purchase/returningitems.php');

//Provider routes
post($folderName.'newprovider', 'apps/provider/addprovider.php');
post($folderName.'provideraddition', 'apps/provider/providerAddition.php');
post($folderName.'editprovider', 'apps/provider/editprovider.php');
post($folderName.'providerediting', 'apps/provider/providerEditing.php');
post($folderName.'searchprovidersubmit', 'apps/provider/searchprovidersubmit.php');


// For GET or POST
// The 404.php which is inside the views folder will be called
// The 404.php has access to $_GET and $_POST
//any('/404','views/404.php');
