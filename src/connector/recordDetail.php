<?php
    class SaleRecordDetails 
    {
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

        /**
         * Find all sales data
         *
         * Finds all sales data for reporting
         * Includes Sales record number, sales date, product number, product name, product description, category id, category name, category description, price, quoted price, quantity ordered, total price, discount
         *
         * @param string $startDate nullable starting date for summary
         * @param string $endDate nullable ending date for summary
         * @return array associative array (SalesRecordNumber, SalesDate, ProductNumber, ProductName, ProductDescription, CategoryID, CategoryName, CategoryDescription, Price, QuotedPrice, QuantityOrdered, TotalPrice, Discount)
         **/
        public function findAllSalesData(?string $startDate, ?string $endDate)
        {
            $statement = "
            SELECT sr.SalesRecordNumber, SalesDate, srd.ProductNumber, p.ProductName, ProductDescription, c.CategoryID, CategoryName, CategoryDescription, Price, QuotedPrice, QuantityOrdered, (QuotedPrice * QuantityOrdered) AS TotalPrice, (Price - QuotedPrice) AS Discount FROM SaleRecordDetails srd
            JOIN SalesRecords sr ON sr.SalesRecordNumber = srd.SalesRecordNumber
            JOIN Products p ON p.ProductNumber = srd.ProductNumber
            JOIN Categories c ON c.CategoryID = p.CategoryID
            ";
            if(!$startDate && !$endDate) { // No start date or end date specified
                try {
                    $statement = $this->db->query($statement);
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    return $result;
                }
                catch(PDOException $e)
                {
                    exit($e->getMessage());
                }
            } else {
                if($startDate && !$endDate) { // Start date specified, no end date specified
                    $statement .= "
                    WHERE sr.SalesDate >= :startDate
                    ";
                    $statement = $this->db->prepare($statement);
                    $statement->execute(array("startDate" => $startDate));
                } elseif(!$startDate && $endDate) { // No start date specified, end date specified
                    $statement .= "
                    WHERE sr.SalesDate <= :endDate
                    ";
                    $statement = $this->db->prepare($statement);
                    $statement->execute(array("endDate" => $endDate));
                } else { // Both start date and end date specified
                    $statement .= "
                    WHERE sr.SalesDate BETWEEN :startDate AND :endDate
                    ";
                    $statement = $this->db->prepare($statement);
                    $statement->execute(array("startDate" => $startDate, "endDate" => $endDate));
                }
                try {
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    return $result;
                }
                catch(PDOException $e)
                {
                    exit($e->getMessage());
                }
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

        public function productNameToId($productName)
        {
            /*
            * return the id of the product with name that matches productname
            */
            $statement = "
                SELECT 
                    ProductNumber 
                FROM 
                    $this->product_table_name 
                WHERE 
                    ProductName LIKE :ProductName
            ";            

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'ProductName' => $productName
                ));
                $result = $statement->fetch();
                return $result[0];
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

        public function deleteByRecordNumber($salesRecordNumber)
        {
            /* 
            * Delete all record details that have the sales record number.
            * return 1 when successfully deleted.
            * return 0 when records failed to be deleted.
            */
            $statement = "
                DELETE FROM $this->table_name
                WHERE 
                    SalesRecordNumber = :SalesRecordNumber
            ";
            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(Array(
                    "SalesRecordNumber" => $salesRecordNumber
                ));
                return $statement->rowCount() > 0 ? 1 : 0;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }
        
        public function findPredictDataItemOrCategory($startDateX, $endDateX, $itemOrCategory, $ID)
        {
            $groupBy = "category.CategoryID";
            $conditions = "";

            if ($itemOrCategory == "ITEM")
            {
                $groupBy = "record.ProductNumber";

                if ($ID != -1)
                {
                    $conditions .= "
                    record.ProductNumber = '$ID'
                    AND
                    ";
                }
            }
            else
            {
                if ($ID != -1)
                {
                    $conditions .= "
                    category.CategoryID = '$ID'
                    AND
                    ";
                }
            }

            $conditions .= "
                 sales.SalesDate
            BETWEEN 
            '$startDateX'
            AND 
            '$endDateX'
            ";

            $statement = "
            SELECT 
            sales.SalesDate,
            record.SalesRecordNumber,
            record.ProductNumber, 
            record.QuotedPrice, 
            SUM(record.QuantityOrdered) as AllQtyOrd, 
            SUM(record.QuotedPrice * record.QuantityOrdered) as TotalPrice,
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
            $conditions
            GROUP BY sales.SalesDate, $groupBy
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
    }
?>