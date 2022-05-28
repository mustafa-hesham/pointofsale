<?php
    include 'init.php';
    include $tpl."header.php";
    include_once $classes.'myAutoloader.php';
    $_SESSION['duplicateProviderName']  = false;
    $_SESSION['duplicateTelephone']     = false;
    $_SESSION['noError']                = false;

    
    if(isset($_POST['providerSubmit'])){
        $providerObj = new Provider($_POST['providerName'], $_POST['managerName'], $_POST['telephone']);

        if ($providerObj->checkDuplicateProviderName($providerObj->getProviderName())){
            $_SESSION['duplicateProviderName']  = true;
            setProviderSessionVariables();
        }
        
        if ($providerObj->checkDuplicateProviderTelephone($providerObj->getProviderTelephone())){
            $_SESSION['duplicateTelephone']  = true;
            setProviderSessionVariables();
        }

        if ($_SESSION['duplicateTelephone'] || $_SESSION['duplicateProviderName']) header("Location:newprovider");

        if (!$_SESSION['duplicateProviderName'] && !$_SESSION['duplicateTelephone']){
            
                $_SESSION['noError'] = $providerObj->saveProvider();
                
                if ($_SESSION['noError']){
                    
                    unsetProviderSessionVariables();
                    header("Location:newprovider");
                }
                else{
                    setProviderSessionVariables();
                    header("Location:newprovider");
                }
                
        }
    }

    function setProviderSessionVariables(){
        $_SESSION['providerName']   = $_POST['providerName'];
        $_SESSION['managerName']    = $_POST['managerName'];
        $_SESSION['telephone']      = $_POST['telephone'];
        $_SESSION['noError']        = false;
    }

    function unsetProviderSessionVariables(){
        $_SESSION['providerName']           = '';
        $_SESSION['managerName']            = '';
        $_SESSION['telephone']              = '';
        $_SESSION['noError']                = true;
        $_SESSION['duplicateProviderName']  = false;
        $_SESSION['duplicateTelephone']     = false;
    }

?>