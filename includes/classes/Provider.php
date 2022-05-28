<?php

    class Provider{
        private $provider_id;
        private $provider_name;
        private $provider_manager;
        private $provider_telephone;
        private connect $connection;


        //  ## Methods ## //

        // ## Setters ## //

        public function setProviderID($id){
            $this->provider_id = $id;
        }

        public function setProviderName($name){
            $this->provider_name = $name;
        }

        public function setProviderManager($manager){
            $this->provider_manager = $manager;
        }

        public function setProviderTelephone($telephone){
            $this->provider_telephone = $telephone;
        }

        // ## Getters ## //

        public function getProviderID(){
            return $this->provider_id;
        }

        public function getProviderName(){
            return $this->provider_name;
        }

        public function getProviderManager(){
            return $this->provider_manager;
        }

        public function getProviderTelephone(){
            return $this->provider_telephone;
        }

        function __construct($name, $manager, $telephone){
            $this->provider_name        = $name;
            $this->provider_manager     = $manager;
            $this->provider_telephone   = $telephone;
            $this->connection           = new connect();
        }
        // Insert data methods

        public function checkDuplicateProviderName($providerName){
            $duplicateProviderNameQuery        = "SELECT provider_name FROM provider WHERE provider_name='$providerName'";
            $duplicateProviderNameQueryResult  = $this->connection->connectDB()->query($duplicateProviderNameQuery);
            $this->connection->connectDB()->close();
            return $duplicateProviderNameQueryResult->num_rows > 0;
        }

        public function checkDuplicateProviderTelephone($providerTelephone){
            $duplicateProviderTelephoneQuery        = "SELECT telephone FROM provider WHERE telephone='$providerTelephone'";
            $duplicateProviderTelephoneQueryResult  = $this->connection->connectDB()->query($duplicateProviderTelephoneQuery);
            $this->connection->connectDB()->close();
            return $duplicateProviderTelephoneQueryResult->num_rows > 0;
        }
        
        public function saveProvider(){
            $successfulInsertion = false;
            if (!$this->checkDuplicateProviderName($this->provider_name) && !$this->checkDuplicateProviderTelephone($this->provider_telephone)){
                $providerInsertQuery        = "INSERT INTO provider(provider_name, manager_name, telephone) 
                        VALUES('$this->provider_name', '$this->provider_manager', '$this->provider_telephone')";
                $this->connection->connectDB()->query($providerInsertQuery);
                $successfulInsertion        = $this->connection->showLastID()? true : false;
                $checkInsertionQuery        = "SELECT provider_name from provider WHERE provider_name='$this->provider_name' AND telephone='$this->provider_telephone' AND manager_name='$this->provider_manager'";
                $checkInsertionQueryresult  = $this->connection->connectDB()->query($checkInsertionQuery);
                $successfulInsertion        = ($checkInsertionQueryresult->num_rows > 0)? true : false;
                $this->connection->connectDB()->close();
                return $successfulInsertion;
            }
        }

        public function updateProvider($providerID){
            $affectedRows = false;
            $providerUpdateQuery = "UPDATE provider SET provider_name='{$this->provider_name}', manager_name='{$this->provider_manager}', telephone='{$this->provider_telephone}' WHERE provider.ID = '$providerID'";
            $this->connection->connectDB()->query($providerUpdateQuery);
            $affectedRows = $this->connection->connectDB()->affected_rows;
            $this->connection->connectDB()->close();
            return $affectedRows;
        }


        // Get data methods

        public static function getProviderNameAutoComplete($providerName){
            $providersList = array();
            $conn = new connect();
            $providerNameQuery       = "SELECT provider_name, ID, balance FROM provider WHERE provider_name LIKE '$providerName%' LIMIT 5";
            $providerNameQueryResult = $conn->connectDB()->query($providerNameQuery);
            if ($providerNameQueryResult->num_rows > 0){
                while ($row  = $providerNameQueryResult->fetch_assoc()){
                    $providersList[] = $row;
                    }
            }
            $conn->connectDB()->close();
            echo json_encode($providersList);
        }


        public static function getProviderByName($providerName){
            $conn = new connect();
            $providerNameQuery = "SELECT provider_name, manager_name, telephone FROM provider WHERE provider_name = '$providerName'";
            $providerNameQueryResult = $conn->connectDB()->query($providerNameQuery);
            $conn->connectDB()->close();
            if ($providerNameQueryResult->num_rows == 1){
                while ($row  = $providerNameQueryResult->fetch_assoc()){
                    $providerObj = new Provider($row['provider_name'], $row['manager_name'], $row['telephone']);
                    }
                    return $providerObj;
            }
            return null;
        }

        public static function getProviderIdByName($providerName){
            $row['ID']              = '';
            $conn                   = new connect();
            $ProviderIdQuery        = "SELECT ID from provider WHERE provider_name = '$providerName'";
            $ProviderIdQueryResult  = $conn->connectDB()->query($ProviderIdQuery);
            if ($ProviderIdQueryResult->num_rows > 0)
                $row = $ProviderIdQueryResult->fetch_assoc();
            $conn->connectDB()->close();
            return $row['ID'];
        }

        public static function getIdCountByName($providername){
            $conn           = new connect();
            $IDQuery        = "SELECT COUNT(ID) FROM provider WHERE provider_name = '$providername'";
            $IDQueryResult  = $conn->connectDB()->query($IDQuery);
            $row = $IDQueryResult->fetch_assoc();
            $conn->connectDB()->close();
            return $row['COUNT(ID)'];
        }

        public static function getIdCountByTelephone($providerTelephone){
            $conn           = new connect();
            $IDQuery        = "SELECT COUNT(ID) FROM provider WHERE telephone = '$providerTelephone'";
            $IDQueryResult  = $conn->connectDB()->query($IDQuery);
            $row = $IDQueryResult->fetch_assoc();
            $conn->connectDB()->close();
            return $row['COUNT(ID)'];
        }


    }



?>