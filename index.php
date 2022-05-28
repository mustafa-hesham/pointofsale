<?php

    include 'init.php';
    include $tpl."header.php";
    include_once $classes.'myAutoloader.php';
    include $navbar;
    $productsInfo = Product::getAllProducts();
?>
    <div class="container mt-5 mb-5">
    <div class="row mt-2 justify-content-center">
    <?php  for($index = 0; $index < count($productsInfo); $index++):?>
        <div class="card text-center">
            <div class="card-body">
            <img class="card-img-top" src="<?= $productsInfo[$index]->getImagePath(); ?>" alt="<?= $productsInfo[$index]->getName(); ?>" id="productImageCard">
            <h5 class="card-title"><?= $productsInfo[$index]->getName(); ?></h5>
            <p class="card-text"><?= '$'.$productsInfo[$index]->getPrice() ?></p>
            </div>
        </div>
    <?php endfor; ?>
    </div>
</div>
<?php
include $tpl."footer.php";
?>