<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";
    require "./exception.php";


    const SUCCESS_CODE = 0;
    const FAILED_CODE = 1; 
    
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

        $recordNumber = $_POST["SalesRecordNumber"];
        if (!$recordNumber) throw new MissingRecordDataException("Can not receive sales record number via POST request.");

        $salesRecord = $salesRecordTable->find($recordNumber);
        if (count($salesRecord) == 0) throw new MissingDetailDataException("Sales record does not exist in the database.");

        $salesRecordDetails = $recordDetailTable->findByRecordNumber($recordNumber);

        $salesData = Array(
            "SalesRecord" => $salesRecord,
            "RecordDetails" => $salesRecordDetails
        );
        
        echo json_encode($salesData);
    }
    catch(Exception $e)
    {
        exit(json_encode(Array()));
    }
?>