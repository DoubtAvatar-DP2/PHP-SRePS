<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";

    function FetchRecordByPOST()
    {
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

    $db = (new Database())->getConnection();

    if (!$db)
    {
        die("Can not connect to database. Please try again later");
    }
    $salesRecordTable = new SalesRecord($db);
    $newRecord = FetchRecordByPOST();
    $last_id = AddNewSalesRecord($salesRecordTable, $newRecord);

    echo $last_id;
?>
