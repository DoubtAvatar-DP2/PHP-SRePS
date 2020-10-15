<?php
    class SaleRecordDetails {
        private $db = null;
        private $table_name = "SaleRecordDetails";
        private $product_table_name = "Products";
        private $category_table_name = "Categories";
        private $sales_table_name = "SalesRecords";

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function findAll()
        {
            /*
            * return an associate array containing all record details
            */
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
            /*
            * return an associate array containing all record details that matches record number and product number
            */
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
            /*
            * return an associate array containing all record details that matches record number
            */
            $statement = "
                SELECT 
                    SalesRecordNumber, SaleRecordDetails.ProductNumber, ProductName, QuotedPrice, QuantityOrdered
                FROM 
                    $this->table_name
                JOIN Products ON Products.ProductNumber = SaleRecordDetails.ProductNumber
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
            /*
            * return an associate array containing all record details that matches product number
            */
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
            /*
            * insert the record details into the table.
            * when successful, return the key of the inserted detail including product number and record number.
            * otherwise, return null.
            */
            $statement = "
                INSERT INTO $this->table_name
                    (SalesRecordNumber, ProductNumber, QuotedPrice, QuantityOrdered)
                VALUES
                    (:SalesRecordNumber, :ProductNumber, :QuotedPrice, :QuantityOrdered);
            ";

            try {
                $statement = $this->db->prepare($statement);
                $isSuccesful = $statement->execute(array(
                    'SalesRecordNumber' => $newRecordDetails['SalesRecordNumber'],
                    'ProductNumber'     => $newRecordDetails['ProductNumber'],
                    'QuotedPrice'       => $newRecordDetails['QuotedPrice'],
                    'QuantityOrdered'   => $newRecordDetails['QuantityOrdered']
                ));

                if ($isSuccesful)
                {
                    return array('SalesRecordNumber' => $newRecordDetails['SalesRecordNumber'],
                                'ProductNumber'      => $newRecordDetails['ProductNumber']);
                }
                return null;
            } 
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function update($existingRecordNumber, $existingProductNumber, Array $recordDetails)
        {
            /*
            * update the existing detail.
            * return 1 when successful.
            * if not, return 0.
            */
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
                return $statement->rowCount() > 0 ? 1 : 0;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function delete($salesRecordNumber, $productNumber)
        {
            /*
            * return 1 when the target detail is successfully deleted.
            * return 0 when the target detail is not deleted.
            */
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
                return $statement->rowCount() > 0 ? 1 : 0;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function findpredictionData($salesNumberStart, $salesNumberEnd)
        {
            /*
            * return an associate array containing all sales for the dates requirements 
            */
            $statement = "
                SELECT 
                    SalesRecordNumber, ProductNumber, QuotedPrice, QuantityOrdered
                FROM 
                    $this->table_name
                WHERE
                    SalesRecordNumber
                BETWEEN 
                    '$salesNumberStart'
                AND 
                    '$salesNumberEnd'
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function findPredictionDatas($startDateX, $endDateX)
        {
            $statement = "
                SELECT 
                    sales.SalesDate,
                    record.SalesRecordNumber,
                    record.ProductNumber, 
                    record.QuotedPrice, 
                    record.QuantityOrdered, 
                    product.ProductName,
                    category.CategoryName
                FROM 
                    $this->sales_table_name sales
                JOIN 
                    $this->table_name record
                ON
                    sales.SalesRecordNumber = record.SalesRecordNumber
                JOIN 
                    $this->product_table_name product
                ON 
                    record.ProductNumber = product.ProductNumber
                JOIN 
                    $this->category_table_name category
                ON
                    product.CategoryID = category.CategoryID
                WHERE
                    sales.SalesDate
                BETWEEN 
                    '$startDateX'
                AND 
                    '$endDateX';
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function findPredictDataByDayProductNum($startDateX, $endDateX)
        {
            $statement = "
            SELECT 
            sales.SalesDate,
            record.SalesRecordNumber,
            record.ProductNumber, 
            record.QuotedPrice, 
            SUM(record.QuantityOrdered) as AllQtyOrd, 
            product.ProductName,
            category.CategoryName
            FROM 
                $this->sales_table_name sales
            JOIN 
                $this->table_name record
            ON
                sales.SalesRecordNumber = record.SalesRecordNumber
            JOIN 
                $this->product_table_name product
            ON 
                record.ProductNumber = product.ProductNumber
            JOIN 
                $this->category_table_name category
            ON
                product.CategoryID = category.CategoryID
            WHERE
                sales.SalesDate
            BETWEEN 
                '$startDateX'
            AND 
                '$endDateX'
            GROUP BY sales.SalesDate, record.ProductNumber
            ORDER BY 1;
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        // public function findPredictionDatas($startDateX, $endDateX)
        // {
        //     $statement = "
        //         SELECT 
        //             record.SalesRecordNumber,
        //             record.ProductNumber, 
        //             record.QuotedPrice, 
        //             record.QuantityOrdered, 
        //             product.ProductName,
        //             category.CategoryName,
        //             sales.SalesDate
        //         FROM 
        //             $this->table_name record 
        //         JOIN 
        //             $this->product_table_name product
        //         ON 
        //             record.ProductNumber = product.ProductNumber
        //         JOIN 
        //             $this->category_table_name category
        //         ON
        //             product.CategoryID = category.CategoryID
        //         JOIN 
        //             $this->sales_table_name sales
        //         ON
        //             record.SalesRecordNumber = sales.SalesRecordNumber
        //         WHERE
        //             sales.SalesDate
        //         BETWEEN 
        //             '$startDateX'
        //         AND 
        //             '$endDateX';
        //     ";

        //     try {
        //         $statement = $this->db->prepare($statement);
        //         $statement->execute();
        //         $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        //         return $result;
        //     }
        //     catch(PDOException $e)
        //     {
        //         exit($e->getMessage());
        //     }
        // }
    }