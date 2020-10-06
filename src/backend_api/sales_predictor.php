<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";

    // functions that will be used 
    function GetIntercept($tableDataArray);
    function GetSlope($tableDataArray);
    function GetXY($tableDataArray);
    function GetXSquared($tableDataArray);
    function GetLeastSquareRegression($tableDataArray);

    /*
    * program starts here
    */

    // establish the connection
    $db = (new Database())->getConnection();

    if (!$db)
    {
        die("Can not connect to database. Please try again later");
    }

    // retrieve the salesRecord table (from salesrecord.php)
    // This is needed for the x axis (date of sale)
    $salesRecordTable = new SalesRecord($db);

    // retrieve the recordDetails table (from recordDetail.php)
    // This is needed for the y axis (number of items sold)
    $recordDetailTable = new SaleRecordDetails($db);

    $tableDataArray = $recordDetailTable->FindAll();

    // the foreach loop will just be for testing, will show quantity of items sold from table
    foreach($tableDataArray as $table)
    {
        echo $table['QuantityOrdered'];
    }

    GetLeastSquareRegression($tableDataArray);

?>