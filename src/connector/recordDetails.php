<?php
    class SaleRecordDetails {
        private $db = null;
        private $table_name = "SaleRecordDetails";

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function findAll()
        {
            $statement = "
                SELECT 
                    SalesRecordNumber, ProductNumber, QuotedPrice, QuantityOrdered
                FROM 
                    $this->table_name
            ";

            try {
                $statement = $this->db->query($statement);
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function find($recordNumber, $productNumber)
        {
            $statement = "
                SELECT 
                    SalesRecordNumber, ProductNumber, QuotedPrice, QuantityOrdered
                FROM 
                    $this->table_name
                WHERE 
                    SalesRecordNumber = :SalesRecordNumber and ProductNumber = :ProductNumber;
            ";            

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'SalesRecordNumber' => $recordNumber,
                    'ProductNumber' => $productNumber
                ));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function findByRecordNumber($recordNumber)
        {
            $statement = "
                SELECT 
                    SalesRecordNumber, ProductNumber, QuotedPrice, QuantityOrdered
                FROM 
                    $this->table_name
                WHERE 
                    SalesRecordNumber = :SalesRecordNumber
            ";            

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'SalesRecordNumber' => $recordNumber
                ));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function findByProductNumber($productNumber)
        {
            $statement = "
                SELECT 
                    SalesRecordNumber, ProductNumber, QuotedPrice, QuantityOrdered
                FROM 
                    $this->table_name
                WHERE 
                    ProductNumber = :ProductNumber;
            ";            

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'ProductNumber' => $productNumber
                ));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function insert(Array $newRecordDetails)
        {
            $statement = "
                INSERT INTO $this->table_name
                    (SalesRecordNumber, ProductNumber, QuotedPrice, QuantityOrdered)
                VALUES
                    (:SalesRecordNumber, :ProductNumber, :QuotedPrice, :QuantityOrdered);
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'SalesRecordNumber' => $newRecordDetails['SalesRecordNumber'],
                    'ProductNumber'     => $newRecordDetails['ProductNumber'],
                    'QuotedPrice'       => $newRecordDetails['QuotedPrice'],
                    'QuantityOrdered'   => $newRecordDetails['QuantityOrdered']
                ));

                return $statement->rowCount();
            } 
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function update($existingRecordNumber, $existingProductNumber, Array $recordDetails)
        {
            $statement = "
                UPDATE $this->table_name
                SET
                    SalesRecordNumber = :newSalesRecordNumber,
                    ProductNumber = :newProductNumber,
                    QuotedPrice = :newQuotedPrice,
                    QuantityOrdered = :newQuantityOrdered

                WHERE SalesRecordNumber = :existingRecordNumber and ProductNumber = :existingProductNumber;
            ";
            
            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'existingRecordNumber' =>   $existingRecordNumber,
                    'existingProductNumber' =>  $existingProductNumber,

                    'newSalesRecordNumber' =>   $recordDetails['SalesRecordNumber'],
                    'newProductNumber' =>       $recordDetails['ProductNumber'],
                    'newQuotedPrice' =>         $recordDetails['QuotedPrice'],
                    'newQuantityOrdered' =>     $recordDetails['QuantityOrdered'],
                ));
                return $statement->rowCount();
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function delete($salesRecordNumber, $productNumber)
        {
            $statement = "
                DELETE FROM $this->table_name
                WHERE 
                    SalesRecordNumber = :SalesRecordNumber and ProductNumber = :ProductNumber
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    "SalesRecordNumber" => $salesRecordNumber,
                    "ProductNumber" => $productNumber
                ));
                return $statement->rowCount();
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }
    }