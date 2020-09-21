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

        public function find($recordNumber)
        {
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
                // return 0 if the salesRecord already exists
                // return 1 if the new input is successfully appended
                return $statement->rowCount();
            } 
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function update(Array $input)
        {
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
                // return 0 if the salesRecord does not update
                // return 1 if the new input is successfully updated
                return $statement->rowCount();
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function delete($salesRecordNumber)
        {
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

                // return 1 when the target record is successfully deleted.
                // return 0 when the target record is not deleted.
                return $statement->rowCount();
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }
    }