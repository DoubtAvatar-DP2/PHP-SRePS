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
                SELECT SalesRecordNumber, SalesDate, Comment
                FROM $this->table_name
            ";
            echo $statement;

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
                FROM $table_name
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
                INSERT INTO $table_name
                    (SalesRecordNumber, SalesDate, Comment)
                VALUES
                    (:SalesRecordNumber, :SalesDate, :Comment);
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'SalesRecordNumber' => $input['SalesRecordNumber'],
                    'SalesDate' => $input['SalesDate'],
                    'Comment' => $input['Comment'] ?? null,
                ));

                return $statement->rowCount();
            } 
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function update($salesRecordNumber, Array $input)
        {
            $statement = "
                UPDATE $table_name
                SET
                    SalesDate = :SalesDate,
                    Comment = :Comment
                WHERE SalesRecordNumber = :SalesRecordNumber;
            ";
            
            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'SalesRecordNumber' => $salesRecordNumber,
                    'SalesDate' => $input['SalesDate'],
                    'Comment' => $input['Comment'] ?? null,
                ));
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
                DELETE FROM $table_name
                WHERE SalesRecordNumber = ?
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array($salesRecordNumber));
                return $statement->rowCount();
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }
    }