<?php
    include 'init.php';
    include_once $classes.'myAutoloader.php';
    $_SESSION['duplicateProviderName']  = false;
    $_SESSION['duplicateTelephone']     = false;
    $_SESSION['noError']                = false;

    if(isset($_POST['providerUpdateSubmit']) && Provider::getProviderByName($_SESSION['providerName']) != null){
        $providerObj    = new Provider($_POST['providerName'], $_POST['managerName'], $_POST['telephone']);
        $oldProviderID  = Provider::getProviderIdByName($_SESSION['providerName']);

        if ($providerObj->getProviderName() != $_SESSION['providerName'] && intval(Provider::getIdCountByName($providerObj->getProviderName()) != 0)){
            $_SESSION['duplicateProviderName']  = true;
            SetProviderSessionVariables();
            header("Location:editprovider");
        }

        if ($providerObj->getProviderTelephone() != $_SESSION['telephone'] && intval(Provider::getIdCountByTelephone($_POST['telephone']) != 0)){
            $_SESSION['duplicateTelephone']  = true;
            SetProviderSessionVariables();
            header("Location:editprovider");
        }

        if ($_SESSION['duplicateProviderName'] == false && $_SESSION['duplicateTelephone'] == false){
            $_SESSION['noError'] = !$providerObj->updateProvider($oldProviderID);
            UnsetProviderSessionVariables();
            header("Location:editprovider");

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
        $_SESSION['duplicateProviderName']  = false;
        $_SESSION['duplicateTelephone']     = false;
    }
   
?>