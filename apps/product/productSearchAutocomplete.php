<?php
include 'init.php';
include_once $classes.'myAutoloader.php';

if (isset($_GET['term'])) Product::getProductsByNameAutocomplete($_GET['term']);

?>