<?php
    class SalesRecord {
        private $db = null;
        private $table_name = "SalesRecords";

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function findAll()
        {
            /*
            * return an associate array containing all sales records
            */
            $statement = "
                SELECT 
                    SalesRecordNumber, SalesDate, Comment
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
         * @param string $startDate nullable starting date for summary
         * @param string $endDate nullable ending date for summary
         * @return assoc array (SalesDate, TotalSales, TotalItems, TotalPrice)
         */
        public function findAllDailySalesSummary(?string $startDate, ?string $endDate)
        {
            if(!$startDate && !$endDate) { // No start date or end date specified
                $statement = "
                SELECT SalesRecords.SalesDate, COUNT(DISTINCT SalesRecords.SalesRecordNumber) AS TotalSales, SUM(SaleRecordDetails.QuantityOrdered) AS TotalItems, SUM(SaleRecordDetails.QuantityOrdered * SaleRecordDetails.QuotedPrice) AS TotalSales FROM SalesRecords
                JOIN SaleRecordDetails ON SaleRecordDetails.SalesRecordNumber = SalesRecords.SalesRecordNumber
                GROUP BY SalesRecords.SalesDate
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
            } else {
                if($startDate && !$endDate) { // Start date specified, no end date specified
                    $statement = "
                    SELECT SalesRecords.SalesDate, COUNT(DISTINCT SalesRecords.SalesRecordNumber) AS TotalSales, SUM(SaleRecordDetails.QuantityOrdered) AS TotalItems, SUM(SaleRecordDetails.QuantityOrdered * SaleRecordDetails.QuotedPrice) AS TotalSales FROM SalesRecords
                    JOIN SaleRecordDetails ON SaleRecordDetails.SalesRecordNumber = SalesRecords.SalesRecordNumber
                    WHERE SalesRecords.SalesDate >= :startDate
                    GROUP BY SalesRecords.SalesDate
                    ";
                    $statement = $this->db->prepare($statement);
                    $statement->execute(array("startDate" => $startDate));
                } elseif(!$startDate && $endDate) { // No start date specified, end date specified
                    $statement = "
                    SELECT SalesRecords.SalesDate, COUNT(DISTINCT SalesRecords.SalesRecordNumber) AS TotalSales, SUM(SaleRecordDetails.QuantityOrdered) AS TotalItems, SUM(SaleRecordDetails.QuantityOrdered * SaleRecordDetails.QuotedPrice) AS TotalSales FROM SalesRecords
                    JOIN SaleRecordDetails ON SaleRecordDetails.SalesRecordNumber = SalesRecords.SalesRecordNumber
                    WHERE SalesRecords.SalesDate <= :endDate
                    GROUP BY SalesRecords.SalesDate
                    ";
                    $statement = $this->db->prepare($statement);
                    $statement->execute(array("endDate" => $endDate));
                } else { // Both start date and end date specified
                    $statement = "
                    SELECT SalesRecords.SalesDate, COUNT(DISTINCT SalesRecords.SalesRecordNumber) AS TotalSales, SUM(SaleRecordDetails.QuantityOrdered) AS TotalItems, SUM(SaleRecordDetails.QuantityOrdered * SaleRecordDetails.QuotedPrice) AS TotalSales FROM SalesRecords
                    JOIN SaleRecordDetails ON SaleRecordDetails.SalesRecordNumber = SalesRecords.SalesRecordNumber
                    WHERE SalesRecords.SalesDate BETWEEN :startDate AND :endDate
                    GROUP BY SalesRecords.SalesDate
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

        public function find($recordNumber)
        {
            /* 
            * return the array containing the record that has the requested record number.
            */
            $statement = "
                SELECT 
                    SalesRecordNumber, SalesDate, Comment
                FROM $this->table_name
                WHERE 
                    SalesRecordNumber = ?;
            ";            

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array($recordNumber));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function insert(Array $input)
        {
            /*
            * insert the new record into the table.
            * return the id of the inserted record when successful.
            * otherwise, return null.
            */
            $statement = "
                INSERT INTO $this->table_name
                    (SalesRecordNumber, SalesDate, Comment)
                VALUES
                    (:SalesRecordNumber, :SalesDate, :Comment);
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'SalesRecordNumber' => $input['SalesRecordNumber'] ?? null,
                    'SalesDate' => $input['SalesDate'],
                    'Comment' => $input['Comment'] ?? null,
                ));
                
                if ($statement->rowCount() != 0)
                {
                    return $this->db->lastInsertId();
                }
                return null;
            } 
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function update(Array $input)
        {
            /*
            * update the existing record in table.
            * return 1 when successful.
            * if not, return 0.
            */
            $statement = "
                UPDATE 
                    $this->table_name
                SET
                    SalesDate = :SalesDate,
                    Comment = :Comment
                WHERE 
                    SalesRecordNumber = :SalesRecordNumber;
            ";
            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'SalesRecordNumber' => $input['SalesRecordNumber'],
                    'SalesDate' => $input['SalesDate'],
                    'Comment' => $input['Comment'] ?? null,
                ));

                return $statement->rowCount() > 0 ? 1 : 0;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function delete($salesRecordNumber)
        {
            /*
            * return 1 when the target record is successfully deleted.
            * return 0 when the target record is not deleted.
            */
            $statement = "
                DELETE FROM 
                    $this->table_name
                WHERE 
                    SalesRecordNumber = :SalesRecordNumber
            ";

            try {
                $statement = $this->db->prepare($statement);

                $result = $statement->execute(array(
                    'SalesRecordNumber' => $salesRecordNumber
                ));
                return $statement->rowCount() > 0 ? 1 : 0;
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }
    }
?>