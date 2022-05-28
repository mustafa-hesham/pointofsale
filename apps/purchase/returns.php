<?php
    include 'init.php';
    include $tpl."header.php";
    include_once $classes.'myAutoloader.php';
    include $navbar;
?>
<title>Return an item</title>


<form action="returningitems" method="POST" id="returnItemForm" class="form-row">
<div class="container">
<div class="row">
<div class="col-lg-12 text-center">
    <button type="submit" class="btn btn-primary" name="invoiceUpdate" id="invoiceUpdate">Update invoice</button>
    <button type="button" class="btn btn-info" name="recallInvoice" id="recallInvoice">Search invoice</button>
    </div>
    <div id="searchProductNameWarning">

</div>
</div>
</div>

<div class="form-group col-md-4">
        <label for="invoiceNumberReturns" class="control-label">Invoice number</label>
        <input type="text" name='invoiceNumberReturns' class="form-control" id="invoiceNumberReturns" placeholder="Invoice number" value="<?= isset($_SESSION['invoiceNumber'])? $_SESSION['invoiceNumber'] : ""; ?>" <?php if(isset($_SESSION['invoiceNumber'])) echo 'readonly '; ?>required>
  </div>

  <div class="form-group col-md-4" >
      <label for="provider" class="control-label">Provider</label>
      <input type="text" name='provider' class="form-control" id="provider" placeholder="Provider" value="<?= isset($_SESSION['invoiceProvider'])? $_SESSION['invoiceProvider'] : ""; ?>" <?php if(isset($_SESSION['invoiceProvider'])) echo 'readonly '; ?> required>
  </div>



  <div class="form-group col-md-4" >
  <label for="returnsTotal" class="control-label">Returns total</label>
  <input type="text" name='returnsTotal' class="form-control" id="returnsTotal" placeholder="0.00" readonly>
  </div>

<table id="invoiceRetunsTable" class="table table-striped">
  <thead>
    <tr>
      <th scope="col" class="col-1">ID</th>
      <th scope="col" class="col-3">Name</th>
      <th scope="col" class="col-1">Price</th>
      <th scope="col" class="col-1">Stock</th>
      <th scope="col" class="col-1">Amount</th>
      <th scope="col" class="col-1">Returns</th>
      <th scope="col" class="col-1">Cost</th>
      <th scope="col" class="col-1">VAT</th>
      <th scope="col" class="col-1">Total</th>
    </tr>
  </thead>
  <tbody id="returnsRows">
  <?php
        if (isset($_SESSION['invoiceDetails'])):
        for ($counter = 0; $counter < count($_SESSION['invoiceDetails']); $counter++):
            $productTotal       = round(floatval($_SESSION['invoiceDetails'][$counter]['productCost']) * floatval($_SESSION['invoiceDetails'][$counter]['productAmount']) + floatval($_SESSION['invoiceDetails'][$counter]['productAmount']) * floatval($_SESSION['invoiceDetails'][$counter]['productVAT']), 2);
            $returnsDisabled    = floatval($_SESSION['invoiceDetails'][$counter]['returns']) > 0 || floatval($_SESSION['invoiceDetails'][$counter]['quantity']) == 0? 'disabled' : '';
            $returnsMax         = floatval($_SESSION['invoiceDetails'][$counter]['productAmount']) < floatval($_SESSION['invoiceDetails'][$counter]['quantity']) ? floatval($_SESSION['invoiceDetails'][$counter]['productAmount']) : floatval($_SESSION['invoiceDetails'][$counter]['quantity']);
            $row  = '<tr id="product'.$_SESSION['invoiceDetails'][$counter]['product_id'].'">';
            $row .= '<th scope="row">'.$_SESSION['invoiceDetails'][$counter]['product_id'].'</th>';
            $row .= '<td>'.$_SESSION['invoiceDetails'][$counter]['name'].'</td>';
            $row .= '<td><input type="text" name="price'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" class="form-control" id="price'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" value="'.$_SESSION['invoiceDetails'][$counter]['productSellPrice'].'" disabled></td>';
            $row .= '<td>'.$_SESSION['invoiceDetails'][$counter]['quantity'].'</td>';
            $row .= '<td id="amountcell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" class="form-control" min = "1" name="amount'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id="amount'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" value ="'.$_SESSION['invoiceDetails'][$counter]['productAmount'].'" readonly></td>';
            $row .= '<td id="returnscell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" min = "0" max="'.$returnsMax.'" class="form-control" name="returns'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id="returns'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" value ="'.$_SESSION['invoiceDetails'][$counter]['returns'].'" '.$returnsDisabled.' required></td>';
            $row .= '<td id="costcell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" class="form-control" min = "0" step="0.01" name="cost'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id="cost'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" value = "'.$_SESSION['invoiceDetails'][$counter]['productCost'].'" readonly></td>';
            $row .= '<td id="VATcell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" class="form-control"  value="'.$_SESSION['invoiceDetails'][$counter]['productVAT'].'" name="VAT'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id="VAT'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" readonly></td>';
            $row .= '<td id="totalcell'.$_SESSION['invoiceDetails'][$counter]['product_id'].'"><input type="number" class="form-control"  value="'.$productTotal.'" name="total'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" id="total'.$_SESSION['invoiceDetails'][$counter]['product_id'].'" disabled></td>';
            $row .= '</tr>';
            echo $row;
        endfor;
       endif; 
       ?>
  </tbody>
  </table>
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
