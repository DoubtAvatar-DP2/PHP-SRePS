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
        if (!$_POST["SalesDate"]) throw new MissingDataException("SalesDate is missing");
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

        if (!$salesRecordNumber) throw new MissingDataException("Can is missing");
        if (!$recordDetails) throw new MissingDataException("No details found.");
        
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
                throw new MissingDataException(sprintf("Row %d is missing the product number", $index + 1));
            }
            //
            // check if price and quantity are missing
            // 
            if (!($quotedPrice && $quantityOrdered)) 
                throw new MissingDataException(sprintf("Row %d are missing details", $index + 1));
            
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
        $edittedRecord = FetchRecordByPOST();
        // retrieving record details
        $recordDetails = FetchRecordDetailsByPOST($edittedRecord["SalesRecordNumber"]);
        //
        // backup record & details right before update
        //
        $backupRecord = $salesRecordTable->find($edittedRecord["SalesRecordNumber"]);
        $backupDetails = $recordDetailTable->findByRecordNumber($edittedRecord["SalesRecordNumber"]);
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
        // BUG: IN CASE adding fails -> old details have to be backed-up. 
        //
        for ($i = 0; $i < count($recordDetails); ++$i)
        {
            $rowInserted = $recordDetailTable->insert($recordDetails[$i]);
            
            if ($rowInserted == 0)
            {
                throw new RecordDetailsUpdateFailedException("Details update failed. Check your product number, quantity and price inputs.");
            }
        }
    }
    catch(RecordDetailsUpdateFailedException $e)
    {
            print_r($salesRecordTable);
            //
            // if any row fails to be added, addition has to be cancelled
            // Remove all details already appended.
            //
            $recordDetailTable->deleteByRecordNumber($edittedRecordID);
            
            // Lastly, safely update the record table back to the original one.
            EditSalesRecord($salesRecordTable, $backupRecord);
            
            //print_r($backupRecord);

            // print_r($edittedRecord);
            for ($i = 0; $i < count($backupDetails); ++$i)
            {
                $rowInserted = $recordDetailTable->insert($backupDetails[$i]);
            }    
            exit($e->getMessage());
    }
    catch(SalesRecordUpdateFailedException $e)
    {
        // should not do anything before database automatically rejects the UPDATE request

        exit($e->getMessage());
    }
    catch(MissingDataException $e)
    {
        // should not do anything before database automatically rejects the UPDATE request

        exit($e->getMessage());
    }
?>