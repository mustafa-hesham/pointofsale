<?php
    include 'init.php';
    include $tpl."header.php";
    include_once $classes.'myAutoloader.php';
    include $navbar;
?>
<title>New invoice</title>

<form action="invoiceaddition" method="POST" id="invoiceAddForm" class="form-row">
    <div class="container">
<div class="row">
<div class="col-lg-12 text-center">
    <button type="submit" class="btn btn-primary" name="invoiceSubmit" id="invoiceSubmit">Save invoice</button>
    <button type="button" class="btn btn-info" name="recallInvoice" id="recallInvoice">Recall invoice</button>
    </div>
    <div id="searchProductNameWarning">
    </div>
    </div>
    </div>
  <div class="form-group col-md-3">
        <label for="invoiceNumber" class="control-label">Invoice number</label>
        <input type="text" name='invoiceNumber' class="form-control" id="invoiceNumber" placeholder="Invoice number" value="<?= isset($_SESSION['invoiceNumber'])? $_SESSION['invoiceNumber'] : ""; ?>" <?= isset($_SESSION['invoiceNumber'])? "disabled " : "" ?> required>
  </div>

  <div class="form-group col-md-3" >
      <label for="provider" class="control-label">Provider</label>
      <input type="text" name='provider' class="form-control" id="provider" placeholder="Provider" value="<?= isset($_SESSION['invoiceProvider'])? $_SESSION['invoiceProvider'] : ""; ?>" <?= isset($_SESSION['invoiceProvider'])? "disabled " : "" ?> required>
  </div>

  <div class="form-group col-md-3" >
      <label for="providerbalance" class="control-label">Provider balance</label>
      <input type="number" name='providerbalance' class="form-control" id="providerbalance" placeholder="Provider balance" value="<?= isset($_SESSION['invoiceDetails'][0]['balance'])? $_SESSION['invoiceDetails'][0]['balance'] : ""; ?>" disabled>
  </div>

  <div class="form-group col-md-3" >
      <label for="discount" class="control-label">Discount</label>
      <input type="number" min="0"  step="0.01" name='discount' class="form-control" id="discount" placeholder="Discount" value="<?= isset($_SESSION['invoiceDiscount'])? $_SESSION['invoiceDiscount'] : "0.00"; ?>" <?= isset($_SESSION['invoiceDiscount'])? "disabled " : "" ?>>
  </div>

  <div class="form-group col-md-3" >
      <label for="discountpercent" class="control-label">Percentage discount</label>
      <input type="number"  min="0" max="100" step="0.01"  name='discountpercent' class="form-control" id="discountpercent" placeholder="Percentage discount" value="<?= isset($_SESSION['invoicePercentDiscount'])? $_SESSION['invoicePercentDiscount'] : "0.00"; ?>" <?= isset($_SESSION['invoicePercentDiscount'])? "disabled " : "" ?>>
  </div>

  <div class="form-group col-md-3" >
      <label for="subtotal" class="control-label">Subtotal</label>
      <input type="number" name='subtotal' min="0"  step="0.01" class="form-control" id="subtotal" placeholder="Subtotal" value="<?= isset($_SESSION['invoiceCost'])? $_SESSION['invoiceCost'] : "0.00"; ?>" disabled>
  </div>

  <div class="form-group col-md-3" >
      <label for="vat" class="control-label">VAT</label>
      <input type="number" name='vat' min="0"  step="0.01" class="form-control" id="vat" placeholder="Value-added tax" value="<?= isset($_SESSION['invoiceVAT'])? $_SESSION['invoiceVAT'] : "0.00"; ?>" disabled>
  </div>

  <div class="form-group col-md-3" >
      <label for="total" class="control-label">Total</label>
      <input type="number" name='total' min="0"  step="0.01" class="form-control" id="total" placeholder="Total" value="<?= isset($_SESSION['invoiceTotal'])? $_SESSION['invoiceTotal'] : "0.00"; ?>" readonly>
  </div>

  <div class="container">
  
  <input type="text" class="form-control" name="searchProductNameInvoice" id="searchProductNameInvoice" placeholder="Search products by name, id or barcode" <?= isset($_SESSION['invoiceDiscount'])? "disabled " : "" ?>>
  
  </div>

  <table id="invoiceTable" class="table table-striped">
  <thead>
    <tr>
      <th scope="col" class="col-1">ID</th>
      <th scope="col" class="col-3">Name</th>
      <th scope="col" class="col-1">Price</th>
      <th scope="col" class="col-1">Stock</th>
      <th scope="col" class="col-1">Amount</th>
      <th scope="col" class="col-1">Cost</th>
      <th scope="col" class="col-1">VAT</th>
      <th scope="col" class="col-1">VAT%</th>
      <th scope="col" class="col-1">Discount%</th>
      <th scope="col" class="col-1">Total</th>
    </tr>
  </thead>
  <tbody id="productsRows">
      <?php
        if (isset($_SESSION['invoiceDetails'])):
        for ($counter = 0; $counter < count($_SESSION['invoiceDetails']); $counter++):
            $productDiscount    = round((1 - (floatval($_SESSION['invoiceDetails'][$counter]['productCost'])/floatval($_SESSION['invoiceDetails'][$counter]['productSellPrice']))) * 100, 2);
            $productVatPercent  = round(floatval($_SESSION['invoiceDetails'][$counter]['productVAT'])/floatval($_SESSION['invoiceDetails'][$counter]['productCost']) *100, 2);
            $productTotal       = round(floatval($_SESSION['invoiceDetails'][$counter]['productCost']) * floatval($_SESSION['invoiceDetails'][$counter]['productAmount']) + floatval($_SESSION['invoiceDetails'][$counter]['productAmount']) * floatval($_SESSION['invoiceDetails'][$counter]['productVAT']), 2);
            $row  = '<tr id="product'.$_SESSION['invoiceDetails'][$counter]['product_id'].'">';
            $row .= '<th scope="row">'.$_SESSION['invoiceDetails'][$counter]['product_id'].'</th>';
            $row .= '<td>'.$_SESSION['invoiceDetails'][$counter]['name'].'</td>';
            $row .= '<td><input type="text" name="price'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" class="form-control" id="price'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" value="'.$_SESSION['invoiceDetails'][$counter]['productSellPrice'].'" readonly></td>';
            $row .= '<td>'.$_SESSION['invoiceDetails'][$counter]['quantity'].'</td>';
            $row .= '<td id="amountcell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" class="form-control" min = "1" name="amount'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id="amount'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" value ="'.$_SESSION['invoiceDetails'][$counter]['productAmount'].'" disabled></td>';
            $row .= '<td id="costcell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" max="'.$_SESSION['invoiceDetails'][$counter]['productSellPrice'].'" min = "1" step="0.01" class="form-control" name="cost'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id="'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" value ="'.$_SESSION['invoiceDetails'][$counter]['productCost'].'" readonly></td>';
            $row .= '<td id="VATcell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" class="form-control" min = "0" step="0.01" name="VAT'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id=VAT"'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" value = "'.$_SESSION['invoiceDetails'][$counter]['productVAT'].'" disabled></td>';
            $row .= '<td id="VATpercentcell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" max="100" min="0" step="0.01" class="form-control"  value="'.$productVatPercent.'"name="VATpercent'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id=VATpercent"'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" readonly></td>';
            $row .= '<td id="discountcell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" max="100" min="0" step="0.01" class="form-control"  value="'.$productDiscount.'"name="discount'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id=discount"'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" readonly></td>';
            $row .= '<td id="totalcell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" class="form-control"  value="'.$productTotal.'" name="total'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id="total'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" disabled></td>';
            $row .= '</tr>';
            echo $row;
        endfor;
       endif; 
       ?>

  </tbody>
  </table>
  <div id="addProviderID"></div>
</form>

<div class="invoiceRecallPopup">
    <div class="closeBtn">&times;</div>
    <form action="invoicesearching" method="POST" id="invoiceSearchForm" class="form-row">
<div class="form-group col-md-3">
        <label for="invoiceNumber" class="control-label">Invoice number</label>
        <input type="text" name='invoiceNumberRecallInvoice' class="form-control" id="invoiceNumber" placeholder="Invoice number">
  </div>

  <div class="form-group col-md-3" >
      <label for="provider" class="control-label">Provider</label>
      <input type="text" name='providerRecallInvoice' class="form-control" id="provider" placeholder="Provider">
  </div>
  <div class="form-group col-md-3" >
      <label for="fromDate" class="control-label">From</label>
      <input type="datetime-local" name='fromDate' class="form-control" id="fromDate">
  </div>

  <div class="form-group col-md-3" >
      <label for="toDate" class="control-label">To</label>
      <input type="datetime-local" name='toDate' class="form-control" id="toDate">
  </div>
  <div class="container">
<div class="row">
<div class="col-lg-12 text-center">
    <button type="submit" class="btn btn-primary" name="invoiceRecallSubmit" id="invoiceRecallSubmit">Search</button>
</div>
</div>
  </div>
  </form>
  <table id="invoiceRecallTable" class="table table-striped">
  <thead>
    <tr>
      <th scope="col" class="col-1">Provider</th>
      <th scope="col" class="col-1">Date</th>
      <th scope="col" class="col-1">Discount</th>
      <th scope="col" class="col-1">Discount %</th>
      <th scope="col" class="col-1">Number</th>
      <th scope="col" class="col-1">Cost</th>
      <th scope="col" class="col-1">VAT</th>
      <th scope="col" class="col-1">Products</th>
      <th scope="col" class="col-1">Total</th>
      <th scope="col" class="col-1">Recall</th>
    </tr>
  </thead>
  <tbody id="invoicesRows">

  </tbody>

  </table>

  
</div>


<?php
include $tpl."footer.php";
?>