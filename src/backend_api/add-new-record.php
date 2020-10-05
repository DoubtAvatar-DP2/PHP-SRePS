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

    try {
        // collect sales date and comment data
        $newRecord = FetchRecordByPOST();
        // adding new sales record
        $newRecordID = AddNewSalesRecord($salesRecordTable, $newRecord);
        // retrieving record details
        $recordDetails = FetchRecordDetailsByPOST($newRecordID);
    }
    catch(Exception $e)
    {
        exit($e->getMessage());
    }


    for ($i = 0; $i < count($recordDetails); ++$i)
    {
        $rowInserted = $recordDetailTable->insert($recordDetails[$i]);
        
        if ($rowInserted == 0)
        {
            //
            // if any row fails to be added, addition has to be cancelled
            // Remove all details already appended.
            //
            for ($j = 0; $j <= $i; ++$j)
            {
                $recordDetailTable->delete($newRecordID, $recordDetails[$j]["ProductNumber"]);
            }
            
            // Lastly, safely remove the current sales record.
            $salesRecordTable->delete($newRecordID);
            die("Can not insert rows. Cancel adding. Remove previous added details.");
        }
    }

    echo SUCCESS_CODE;
?>
