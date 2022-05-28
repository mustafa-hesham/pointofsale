<?php

spl_autoload_register('myAutoloader');
    
    function myAutoloader($className){
        $path = 'includes/classes/';
        $extension = '.php';
        $fullPath = $path. $className. $extension;

        if (!file_exists($fullPath)){
            $path = '../../includes/classes/';
            $fullPath = $path. $className. $extension;
        }

        elseif (!file_exists($fullPath)){
            return false;
        }
        

        include_once $fullPath;
    }

    ?>