<?php
    include 'init.php';
    include_once $classes.'myAutoloader.php';

    Invoice::getInvoice($_POST['invoiceNumberRecallInvoice'], $_POST['providerRecallInvoice'], $_POST['fromDate'], $_POST['toDate']);
    

    ?>