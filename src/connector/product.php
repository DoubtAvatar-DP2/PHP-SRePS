<?php
    class Product {
        private $db = null;
        private $product_table_name = "Products";
        private $category_table_name = "Categories";

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function findAll()
        {
            $statement = "
                SELECT 
                    product.ProductNumber,
                    product.productName, 
                    product.ProductDescription, 
                    product.Price, 
                    product.CategoryID, 
                    category.CategoryName
                FROM 
                    $this->product_table_name product 
                JOIN 
                    $this->category_table_name category 
                ON 
                    product.CategoryID = category.CategoryID;
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

        public function findByProductNumber($recordNumber)
        {
            $statement = "
                SELECT 
                    product.productNumber, 
                    product.productName, 
                    product.ProductDescription, 
                    product.Price, 
                    product.CategoryID, 
                    category.CategoryName
                FROM 
                    $product_table_name product 
                JOIN 
                    $category_table_name category 
                ON 
                    product.CategoryID = category.CategoryID
                WHERE
                    product.productNumber = ?;
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

        public function findByProductName($productName)
        {
            /*
            * Search for $productName appearance as a substring of the product name
            */
            $statement = "
                SELECT 
                    product.productNumber, 
                    product.productName, 
                    product.ProductDescription, 
                    product.Price, 
                    product.CategoryID, 
                    category.CategoryName
                FROM 
                    $product_table_name product 
                JOIN 
                    $category_table_name category 
                ON 
                    product.CategoryID = category.CategoryID
                WHERE
                    product.productName = '%?%';
            ";
            
            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array($productName));
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