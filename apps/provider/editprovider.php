<?php
    include 'init.php';
    include $tpl."header.php";
    include_once $classes.'myAutoloader.php';
    include $navbar;
?>
<title>Edit provider</title>
<div class="center">
    
<form action="searchprovidersubmit" method="POST" id="searchProviderAutoComplete">
<div class="form-group">


<input type="text" class="form-control" name="searchProviderName" id="searchProviderName" placeholder="Search providers by name" value="<?= isset($_SESSION['SearchProvider'])? $_SESSION['SearchProvider'] : ""; ?>">

</div>

<div id="AdditionSuccess">
  <?php if (isset($_SESSION['noError']) && $_SESSION['noError']): ?>
    <small class="text-success" >Provider updated successfully.</small>
    <?php endif; ?>
</div>
</form>

<form action="providerediting" method="POST"  id="providerEditForm">
<div class="form-group">
    <label for="providerName">Provider name</label>
    <input type="text" class="form-control" id="providerName" name ="providerName"
        aria-describedby="providerNameHelp" placeholder="Provider name" required value="<?= isset($_SESSION['providerName'])? $_SESSION['providerName'] : ""; ?>">
        <?php if(isset($_SESSION['duplicateProviderName']) && $_SESSION['duplicateProviderName']): ?>
    <small id="providerNameHelp" class="text-danger">Name already exists in the database.</small>
    <?php else: ?>
      <small id="providerNameHelp" class="form-text text-muted">Please enter a valid provider name.</small>
          <?php endif; ?>
  </div>

  <div class="form-group">
    <label for="managerName">Manager name</label>
    <input type="text" class="form-control" id="managerName" name ="managerName"
        aria-describedby="managerNameHelp" placeholder="Manager name" required value="<?= isset($_SESSION['managerName'])? $_SESSION['managerName'] : ""; ?>">
  </div>

  <div class="form-group">
  <label for="telephone">Telephone</label>
  <input type="number" class="form-control" name="telephone" id="telephone"
           pattern="^[0-9]$"
            title="Enter a valid telephone number." placeholder="Telephone" required value="<?= isset($_SESSION['telephone'])? $_SESSION['telephone'] : ""; ?>">
            <?php if(isset($_SESSION['duplicateTelephone']) && $_SESSION['duplicateTelephone']): ?>
    <small id="telephoneError" class="text-danger">This telephone number already exists in the database.</small>
    <?php endif; ?>
  </div>

  <button type="submit" class="btn btn-primary" name="providerUpdateSubmit">Submit</button>
</form>
</div>

<?php
include $tpl."footer.php";
?>