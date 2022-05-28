<?php
    include 'init.php';
    include $tpl."header.php";
    include_once $classes.'myAutoloader.php';
    include $navbar;
    
?>
<title>Edit product</title>
<div class = center>
<form action="searchProductsubmit" method="POST" id="searchProductAutoComplete">

<div class="form-group">
<input type="text" class="form-control" name="searchProductName" id="searchProductName" placeholder="Search products by name" value="<?= isset($_SESSION['Searchterm'])? $_SESSION['Searchterm'] : ""; ?>">
</div>

<div id="searchProductNameWarning">
<?php if(isset($_SESSION['DoesNotExist']) && $_SESSION['DoesNotExist']): ?>
  <small class="text-danger" >Name does not exist.</small>
  <?php endif; ?>
  <?php if (isset($_SESSION['successfullUpdate']) && $_SESSION['successfullUpdate']): ?>
    <small class="text-success" >Product updated successfully.</small>
    <?php endif; 
    $_SESSION['successfullUpdate'] = false;
    ?>
</div>

</form>
<img src="<?= isset($_SESSION['productImage']) && strlen($_SESSION['productImage']) > 0? $_SESSION['productImage'] : 'apps/product/images/placeholder-product-image.png' ?>" class="float-right img-responsive" id="productImage">
<form action="productupdate" method="POST" enctype="multipart/form-data" id="UpdateProduct">
  <div class="form-group">
    <label for="productName">Product name</label>
    <input type="text" class="form-control" id="productName" name ="productName"
        aria-describedby="productNameHelp" placeholder="Enter product name" required value="<?= isset($_SESSION['productName'])? $_SESSION['productName'] : ""; ?>">
        <?php if(isset($_SESSION['duplicateProductName']) && $_SESSION['duplicateProductName']): ?>
    <small id="productNameHelp" class="text-danger"><?= $_SESSION['duplicateName'] ?> already exists in the database.</small>
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
  <label class="custom-file-label" for="imageSelector" id ="imageSelectorLabel"><?= isset($_SESSION['imageName']) && $_SESSION['imageName']? $_SESSION['imageName'] : "Choose image"?></label>
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
    <small id="barcodeError" class="text-danger"><?= $_SESSION['duplicateProductBarcode'] ?> already exists in the database.</small>
    <?php endif; ?>
  </div>

  <button type="submit" class="btn btn-primary" name="editproductSubmit">Submit</button>
</form>
</div>

<?php
include $tpl."footer.php";
?>