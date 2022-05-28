<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tpl     = 'includes/templates/';
$css     = 'layout/css/';
$js      = 'layout/js/';
$classes = 'includes/classes/';
$navbar  = $tpl.'navbar.php';
$productImage = 'apps/product/images/';
$generalFunctions = 'includes/functions/';
?>