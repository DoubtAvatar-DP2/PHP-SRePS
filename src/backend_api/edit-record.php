<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";
    require "./exception.php";
    
    const SUCCESS_CODE = 0;

    function FetchRecordByPOST()
    {
        /*
        * Return the associate array containing SalesDate and Comment collected from POST data
        * return null if salesDate is missing.
        */
        // sales date is required.
        if (!$_POST["SalesDate"]) throw new MissingRecordDataException("SalesDate is missing");
        return Array(    
            "SalesDate" => $_POST["SalesDate"],
            "Comment" => $_POST["Comment"],
            "SalesRecordNumber" => $_POST["SalesRecordNumber"]
        );
    }

    function EditSalesRecord($table, $newRecord)
    {
        /*
        * update the sales record
        * return the successful record number
        * else throw error when unable to edit the database
        */
        $rowAffected = $table->update($newRecord);
        //
        // retrieve new record back to double check if the modification is successful
        //
        $double_check = $table->find($newRecord["SalesRecordNumber"]);
        $double_check = $double_check[0];
        
        if ($double_check["SalesRecordNumber"] != $newRecord["SalesRecordNumber"] 
            || $double_check["Comment"] != $newRecord["Comment"]
            || date_parse($double_check["SalesDate"]) != date_parse($newRecord["SalesDate"]) ) 
            
            throw new SalesRecordUpdateFailedException("Updated record in the database is not the same as the expected record.");

        return $newRecord["SalesRecordNumber"];
    }
    
    function FetchRecordDetailsByPOST($salesRecordNumber)
    {
        /*
        * returning an array containing all record details (productNumber, quotedPrice, quantityOrdered)
        * if any of the record detail is missing, return null.
        */
        $recordDetails = $_POST["RecordDetails"];

        if (!$salesRecordNumber) throw new MissingRecordDataException("Record ID is missing");
        if (!$recordDetails) throw new MissingDetailDataException("Details are missing.");
        
        $details = Array();

        for ($index = 0; $index < count($recordDetails); ++$index)
        {
            $newRecordDetails = $recordDetails[$index];

            $productNumber = $newRecordDetails['productNumber'];
            $quotedPrice = $newRecordDetails['quotedPrice'];
            $quantityOrdered = $newRecordDetails['quantityOrdered'];
            
            //
            // check if product number is missing
            //
            if (!$productNumber)
            {
                throw new MissingDetailDataException(sprintf("Row %d is missing the product number", $index + 1));
            }
            //
            // check if price and quantity are missing
            // 
            if (!($quotedPrice && $quantityOrdered)) 
                throw new MissingDetailDataException(sprintf("Row %d are missing details", $index + 1));
            
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
    try {
        // establish the connection
        $db = (new Database())->getConnection();
        if (!$db)
        {
            throw new Exception("Can not connect to database. Please try again later");
        }
        // retrieve the salesRecord table
        $salesRecordTable = new SalesRecord($db);
        
        // retrieve the recordDetails table
        $recordDetailTable = new SaleRecordDetails($db);
        // collect sales date and comment data
        $edittedRecord = FetchRecordByPOST();
        // retrieving record details
        $recordDetails = FetchRecordDetailsByPOST($edittedRecord["SalesRecordNumber"]);
        //
        // backup record & details right before update
        //
        $backupRecord = $salesRecordTable->find($edittedRecord["SalesRecordNumber"]);
        $backupDetails = $recordDetailTable->findByRecordNumber($edittedRecord["SalesRecordNumber"]);
    }
    catch(Exception $e)
    {
        // when missing any data (record or detail), stop doing anything.
        exit(json_encode(Array(
            "exitCode" => 1,
            "errorMessage" => $e->getMessage()
        )));
    }
    try {
        //
        // edit sales record
        //
        $edittedRecordID = EditSalesRecord($salesRecordTable, $edittedRecord);
        // 
        // delete old record details that have the record number being updated.
        //
        $recordDetailTable->deleteByRecordNumber($edittedRecordID);
        //
        // add details into table.
        // this should not cause conflicts because old table has been deleted.
        //
        for ($i = 0; $i < count($recordDetails); ++$i)
        {
            $rowInserted = $recordDetailTable->insert($recordDetails[$i]);
            
            if ($rowInserted == 0)
            {
                throw new RecordDetailsUpdateFailedException("Details update failed. Check your product number, quantity and price inputs.");
            }
        }
        exit(json_encode(Array(
            "exitCode" => SUCCESS_CODE,
            "edittedRecordID" => $edittedRecordID,
            "errorMessage" => "",
        )));
    }
    catch(SalesRecordUpdateFailedException $e)
    {
        // put backup data back
        EditSalesRecord($salesRecordTable, $backupRecord);
        exit(json_encode(Array(
            "exitCode" => 1,
            "errorMessage" => $e->getMessage()
        )));
    }
    catch(RecordDetailsUpdateFailedException $e)
    {           
            //
            // if any row fails to be added, addition has to be cancelled
            // Remove all details already appended.
            //
            $recordDetailTable->deleteByRecordNumber($edittedRecordID);
            
            // Lastly, safely update the record table back to the original one.
            EditSalesRecord($salesRecordTable, $backupRecord);
            
            for ($i = 0; $i < count($backupDetails); ++$i)
            {
                $rowInserted = $recordDetailTable->insert($backupDetails[$i]);
            }    
            exit(json_encode(Array(
                "exitCode" => 1,
                "errorMessage" => $e->getMessage()
            )));

    }
    catch(Exception $e)
    {
        exit(json_encode(Array(
            "exitCode" => 1,
            "errorMessage" => $e->getMessage()
        )));
    }
?>
