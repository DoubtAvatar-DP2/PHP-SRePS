<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";

    // functions that will be used 
    function GetIntercept($tableDataArray)
    {

    }

    function GetSlope($tableDataArray)
    {

    }

    function GetXY($tableDataArray, $tableDataArrayX)
    {
        $dataArrayXY = array();

        // find the appropriate QuantityOrdered and SalesDate, times them together into the array 
        // and return the array
    }

    // squares the value of x that's stored in the data array
    function GetXSquared($tableDataArrayX)
    {
        $sqrDataArrayX = array();

        foreach($tableDataArrayX as $table)
        {
            array_push($sqrDataArrayX, pow($table["SalesDate"], 2));
        }

        return $sqrDataArrayX;
    }

    function GetLeastSquareRegression($tableDataArray)
    {

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

    // retrieve the salesRecord table (from salesrecord.php)
    // This is needed for the x axis (date of sale)
    $salesRecordTable = new SalesRecord($db);

    // I'm not totally familar with PHP, not sure if this is needed
    $tableDataArrayX = $recordDetailTabletable->FindAll();

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