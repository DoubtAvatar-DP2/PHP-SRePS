<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";
    
    const SUCCESS_CODE = 0;

    function FetchRecordByPOST()
    {
        /*
        * Return the associate array containing SalesDate and Comment collected from POST data
        * return null if salesDate is missing.
        */

        // sales date is required.
        if (!$_POST["SalesDate"]) throw new Exception("SalesDate is missing");
        return Array(    
            "SalesDate" => $_POST["SalesDate"],
            "Comment" => $_POST["Comment"],
            "SalesRecordNumber" => $_POST["SalesRecordNumber"] ?? null
        );
    };

    function AddNewSalesRecord($table, $newRecord)
    {
        $last_id = $table->insert($newRecord);
        return $last_id;
    }
    
    function FetchRecordDetailsByPOST($salesRecordNumber)
    {
        /*
        * returning an array containing all record details (productNumber, quotedPrice, quantityOrdered)
        * if any of the record detail is missing, return null.
        */
        $recordDetails = $_POST["RecordDetails"];

        if (!$salesRecordNumber) throw new Exception("SalesRecordNumber is missing");
        if (!$recordDetails) throw new Exception("RecordDetails is missing");
        
        $details = Array();

        for ($index = 0; $index < count($recordDetails); ++$index)
        {
            $newRecordDetails = $recordDetails[$index];

            $productNumber = $newRecordDetails['productNumber'];
            $quotedPrice = $newRecordDetails['quotedPrice'];
            $quantityOrdered = $newRecordDetails['quantityOrdered'];
            
            // check if details are missing
            if (!($productNumber && $quotedPrice && $quantityOrdered)) throw new Exception("Details are missing");
            
            $newRecordDetails = Array(
                'SalesRecordNumber' => $salesRecordNumber,
                'ProductNumber'     => $productNumber,
                'QuotedPrice'       => $quotedPrice,
                'QuantityOrdered'   => $quantityOrdered
            );

            $details[] = $newRecordDetails;    
        }
        return $details;
    }

    /*
    * program starts here
    */

    // establish the connection
    $db = (new Database())->getConnection();

    if (!$db)
    {
        die("Can not connect to database. Please try again later");
    }

    // retrieve the salesRecord table
    $salesRecordTable = new SalesRecord($db);
    
    // retrieve the recordDetails table
    $recordDetailTable = new SaleRecordDetails($db);

    echo SUCCESS_CODE;
?>
