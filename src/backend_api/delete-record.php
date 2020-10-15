<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";
    
    const SUCCESS_CODE = 0;

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

    
    $recordDetailTable->deleteByRecordNumber($_POST["SalesRecordNumber"]);
    $salesRecordTable->delete($_POST["SalesRecordNumber"]);

    echo SUCCESS_CODE;
?>
