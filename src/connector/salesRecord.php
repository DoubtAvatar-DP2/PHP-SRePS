<?php
    class SalesRecord {
        /** Constant values */
        // Sort Column constants
        const SALES_RECORD_NUMBER = "SalesRecords.SalesRecordNumber";
        const SALES_RECORD_DATE = "SalesRecords.SalesDate";
        const TOTAL_ITEMS = "TotalItems";
        const TOTAL_PRICE = "TotalPrice";

        // Sort order constants
        const ASC = "ASC";
        const DESC = "DESC";

        private $db = null;
        private $table_name = "SalesRecords";

        public function __construct($db)
        {
            $this->db = $db;
        }

        /**
         * @param int $page_size Default 0 If the page size is 0, else fetch count of all records and divide by page size
         */
        public function getPageCount(int $page_size = 0)
        {
            if($page_size == 0)
                return 1;
            // query total count of all sales records
            $query = $this->db->query("SELECT COUNT(SalesRecordNumber) as count FROM $this->table_name");
            $totalCount = $query->fetch(PDO::FETCH_ASSOC);
            // calculate how many pages there are
            $totalPages = ceil(intval($totalCount["count"])/$page_size);
            return $totalPages;
        }

        /**
         * @param int $limit Default 0 If $limit is 0, there is no limit
         * @param int $offset Default 0 The starting offset for the limit. If $limit is 0, there is no offset
         */
        public function findAll($limit = 0, $offset = 0)
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
            if($limit)
                $statement .= "LIMIT $offset, $limit";            

            try {
                $statement = $this->db->query($statement);
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
                //TODO: Update to return result and page count for pagination
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        /**
         * @param string $startDate nullable starting date for summary
         * @param string $endDate nullable ending date for summary
         * @return array (SalesDate, TotalSales, TotalItems, TotalPrice)
         */
        public function findAllDailySalesSummary(?string $startDate, ?string $endDate)
        {
            if(!$startDate && !$endDate) { // No start date or end date specified
                $statement = "
                SELECT SalesRecords.SalesDate, COUNT(DISTINCT SalesRecords.SalesRecordNumber) AS TotalNumSales, SUM(SaleRecordDetails.QuantityOrdered) AS TotalItems, SUM(SaleRecordDetails.QuantityOrdered * SaleRecordDetails.QuotedPrice) AS TotalSales FROM SalesRecords
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
                    SELECT SalesRecords.SalesDate, COUNT(DISTINCT SalesRecords.SalesRecordNumber) AS TotalNumSales, SUM(SaleRecordDetails.QuantityOrdered) AS TotalItems, SUM(SaleRecordDetails.QuantityOrdered * SaleRecordDetails.QuotedPrice) AS TotalSales FROM SalesRecords
                    JOIN SaleRecordDetails ON SaleRecordDetails.SalesRecordNumber = SalesRecords.SalesRecordNumber
                    WHERE SalesRecords.SalesDate >= :startDate
                    GROUP BY SalesRecords.SalesDate
                    ";
                    $statement = $this->db->prepare($statement);
                    $statement->execute(array("startDate" => $startDate));
                } elseif(!$startDate && $endDate) { // No start date specified, end date specified
                    $statement = "
                    SELECT SalesRecords.SalesDate, COUNT(DISTINCT SalesRecords.SalesRecordNumber) AS TotalNumSales, SUM(SaleRecordDetails.QuantityOrdered) AS TotalItems, SUM(SaleRecordDetails.QuantityOrdered * SaleRecordDetails.QuotedPrice) AS TotalSales FROM SalesRecords
                    JOIN SaleRecordDetails ON SaleRecordDetails.SalesRecordNumber = SalesRecords.SalesRecordNumber
                    WHERE SalesRecords.SalesDate <= :endDate
                    GROUP BY SalesRecords.SalesDate
                    ";
                    $statement = $this->db->prepare($statement);
                    $statement->execute(array("endDate" => $endDate));
                } else { // Both start date and end date specified
                    $statement = "
                    SELECT SalesRecords.SalesDate, COUNT(DISTINCT SalesRecords.SalesRecordNumber) AS TotalNumSales, SUM(SaleRecordDetails.QuantityOrdered) AS TotalItems, SUM(SaleRecordDetails.QuantityOrdered * SaleRecordDetails.QuotedPrice) AS TotalSales FROM SalesRecords
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

        /**
         * @param int $limit Default 0. If $limit is 0, there is no limit
         * @param int $offset Default 0 The starting offset for the limit. If $limit is 0, there is no offset
         * @param string $startDate Default null The start date to filter by
         * @param string $endDate Default null The end date to filter by
         * @param string $order_by Default SALES_RECORD_DATE The column to order by
         * @param string $order_direction Default DESC The direction to order in
         * @return Array Returns an array containing all sales records, with the total price and total items
         */
        public function findAllOverview(int $limit = 0, int $offset = 0, ?string $startDate = null, ?string $endDate = null, ?string $order_by = SalesRecord::SALES_RECORD_DATE, ?string $order_direction = SalesRecord::DESC)
        {
            $order_by = $order_by ?? SalesRecord::SALES_RECORD_DATE;
            $order_direction = $order_direction ?? SalesRecord::DESC;
            $statement = "
            SELECT SalesRecords.SalesRecordNumber, SalesRecords.SalesDate, SUM(SaleRecordDetails.QuantityOrdered) AS TotalItems, SUM(SaleRecordDetails.QuotedPrice * SaleRecordDetails.QuantityOrdered) AS TotalPrice
                FROM $this->table_name
                JOIN SaleRecordDetails ON SalesRecords.SalesRecordNumber = SaleRecordDetails.SalesRecordNumber
            ";
            if($startDate && !$endDate)
                $statement .= "WHERE SalesDate >= '$startDate'";
            elseif (!$startDate && $endDate)
                $statement .= "WHERE SalesDate <= '$endDate'";
            elseif ($startDate && $endDate)
                $statement .= "WHERE SalesDate BETWEEN '$startDate' and '$endDate'";

            $statement .= "
                GROUP BY SalesRecords.SalesRecordNumber
            ORDER BY $order_by $order_direction
            ";
            if($limit)
                $statement .= "LIMIT $offset, $limit";

            try {
                $statement = $this->db->query($statement);
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                return [
                    "results" => $result,
                    "total_pages" => $this->getPageCount($limit)
                ];
            } catch(PDOException $e)
            {
                exit($e->getMessage());
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