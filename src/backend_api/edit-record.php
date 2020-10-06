<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";
    
    class SalesRecordUpdateFailedException extends Exception {};
    class RecordDetailsUpdateFailedException extends Exception {};
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
        if ($rowAffected == 0) throw new SalesRecordUpdateFailedException("Can not update sales record");
        
        return $newRecord["SalesRecordNumber"];
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
        $edittedRecord = FetchRecordByPOST();
        //
        // backup record & details right before update
        //
        $backupRecord = $salesRecordTable->find($edittedRecord["SalesRecordNumber"]);
        $backupDetails = $recordDetailTable->findByRecordNumber($edittedRecord["SalesRecordNumber"]);
        //
        // edit sales record
        //
        $edittedRecordID = EditSalesRecord($salesRecordTable, $edittedRecord);
        // retrieving record details
        $recordDetails = FetchRecordDetailsByPOST($edittedRecordID);
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
        //
        for ($i = 0; $i < count($recordDetails); ++$i)
        {
            $rowInserted = $recordDetailTable->insert($recordDetails[$i]);
            
            if ($rowInserted == 0)
            {
                throw new RecordDetailsUpdateFailedException("Can not update details");
                // die("Can not insert rows. Cancel adding. Remove previous added details.");
            
                // echo SUCCESS_CODE;
            }
        }
    }
    catch(RecordDetailsUpdateFailedException $e)
    {
            //
            // if any row fails to be added, addition has to be cancelled
            // Remove all details already appended.
            //
            $recordDetailTable->deleteByRecordNumber($edittedRecordID);
            
            // Lastly, safely remove the current sales record.
            $salesRecordTable->delete($edittedRecordID);

            $salesRecordNumber->insert($backupRecord);
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
?>
