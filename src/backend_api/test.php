<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";
    require "./exception.php";

    // establish the connection
    $db = (new Database())->getConnection();

    if (!$db)
    {
        die("Can not connect to database. Please try again later");
    }

    // retrieve the salesRecord table
    $salesRecordTable = new SalesRecord($db);

    echo $salesRecordTable->delete(1028);
?>