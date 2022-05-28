<?php
include 'init.php';
include_once $classes.'myAutoloader.php';


if (isset($_POST['searchProviderName']) && strlen($_POST['searchProviderName']) > 0) 
    $providerObj = Provider::getProviderByName($_POST['searchProviderName']);
    if (isset($providerObj)){
        setSessionVariables($providerObj);
    }
    else
        unsetSessionVariables();


        if (isset($_POST['searchProviderName']) && strlen($_POST['searchProviderName']) == 0){
            $_SESSION['Searchterm']         = '';
            $_SESSION['noError']            = false;
            $_SESSION['successfullUpdate']  = false;
    }

    unset($providerObj);
    header("Location: /training/editprovider");


    function setSessionVariables($providerObj){
        $_SESSION['Searchterm']         = '';
        $_SESSION['providerName']       = $providerObj->getProviderName();
        $_SESSION['managerName']        = $providerObj->getProviderManager();
        $_SESSION['telephone']          = $providerObj->getProviderTelephone();
        $_SESSION['noError']            = false;
    }

    function unsetSessionVariables(){
        $_SESSION['Searchterm']         = $_POST['searchProviderName'];
        $_SESSION['providerName']       = '';
        $_SESSION['managerName']        = '';
        $_SESSION['telephone']          = '';
        $_SESSION['noError']            = false;

    }



?>