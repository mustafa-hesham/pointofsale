<?php
// Product session variables
if (isset($GLOBALS['target_file']))
    unset($GLOBALS['target_file']);

foreach($_SESSION as $key=>$value){
    if ($key != 'username')
    unset($_SESSION[$key]);
}
?>
