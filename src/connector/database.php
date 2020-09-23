<?php
    class Database {
        private $dbConnection = null;

        public function __construct()
        {
            $host   = "mysql";
            $db     = "SREPS";
            $user   = "admin";
            $password = "password";
            $charset = "utf8mb4";

            try {
                $this->dbConnection = new PDO(
                    "mysql:host=$host;charset=$charset;dbname=$db",
                    $user,
                    $password
                );
            }
            catch(PDOException $e)
            {
                exit($e->getMessage());
            }
        }

        public function getConnection()
        {
            return $this->dbConnection;
        }
    }
?>