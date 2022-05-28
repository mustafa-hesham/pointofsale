<?php
    include 'init.php';
    include $tpl."header.php";
    include_once $classes.'myAutoloader.php';
    include $navbar;
?>
<title>New product</title>

<img src="apps/product/images/placeholder-product-image.png" class="float-right img-responsive" id="productImage">

<div class = center>
<div id="AdditionSuccess">
  <?php if (isset($_SESSION['successfullAddition']) && $_SESSION['successfullAddition']): ?>
    <small class="text-success" >Product added successfully.</small>
    <?php endif; ?>
    <?php $_SESSION['successfullAddition'] = false; ?>
</div>
<form action="productaddition" method="POST" enctype="multipart/form-data" id="productAddForm"> 
  <div class="form-group">
    <label for="productName">Product name</label>
    <input type="text" class="form-control" id="productName" name ="productName"
        aria-describedby="productNameHelp" placeholder="Enter product name" required value="<?= isset($_SESSION['productName'])? $_SESSION['productName'] : ""; ?>">
        <?php if(isset($_SESSION['duplicateProductName']) && $_SESSION['duplicateProductName']): ?>
    <small id="productNameHelp" class="text-danger">Name already exists in the database.</small>
    <?php else: ?>
      <small id="productNameHelp" class="form-text text-muted">Please enter a unique valid product name.</small>
          <?php endif; ?>
  </div>

  <div class="form-group">
  <label for="price">Price</label>

      <input type="number" min="1" class="form-control" name="price" id="price"
           pattern="[0-9]+([\.][0-9]{2})?" step="0.01"
            title="This should be a number with up to 2 decimal places." placeholder="Price" required value="<?= isset($_SESSION['productPrice'])? $_SESSION['productPrice'] : ""; ?>">
  </div>

  <div class="form-group">
  <label for="price">Image</label>
  <div class="custom-file">
  <label class="custom-file-label" for="imageSelector" id ="imageSelectorLabel">Choose image</label>
  <input type="file" class="custom-file-input" id="imageSelector" accept="image/*" name="productImage">
  </div>
  <?php for ($error = 0; isset($_SESSION['imageErrors']) && $error < count($_SESSION['imageErrors']); $error++): ?>
    <small id="imageError" class="text-danger"><?= $_SESSION['imageErrors'][$error].'<br>'; ?></small>
    <?php endfor; ?>
  </div>

  <div class="form-group">
  <label for="barcode">Barcode</label>
  <input type="number" class="form-control" name="barcode" id="barcode"
           pattern="^[1-9]{1}[0-9]{11}$"
            title="Enter a 12 digits valid barcode." placeholder="Barcode" required value="<?= isset($_SESSION['productBarcode'])? $_SESSION['productBarcode'] : ""; ?>">
            <?php if(isset($_SESSION['duplicatebarcode']) && $_SESSION['duplicatebarcode']): ?>
    <small id="barcodeError" class="text-danger">Barcode already exists in the database.</small>
    <?php endif; ?>
  </div>

  <button type="submit" class="btn btn-primary" name="productSubmit">Submit</button>
</form>
</div>

<?php
include $tpl."footer.php";
?>
