<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";

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

    $recordNumber = $_POST["SalesRecordNumber"];

    $salesRecord = $salesRecordTable->find($recordNumber);
    $salesRecordDetails = $recordDetailTable->findByRecordNumber($recordNumber);

    $salesData = Array(
        "SalesRecord" => $salesRecord,
        "RecordDetails" => $salesRecordDetails
    );

    echo json_encode($salesData);
?>