<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";

    function GetXAxis()
    {

    }
    
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
        $startDateX = "2020-09-04";
        $endDateX = "2020-09-26";
        $startX = strtotime($startDateX);
        $endX = strtotime($endDateX);

        // pushed both sales number and sales date, as you will need a link for sale number later
        // should make more sense later, still thinking of ideas to do this 
        foreach($tableDataArrayX as $table)
        {
            //array_push($sqrDataArrayX, pow($table["SalesDate"], 2));
            array_push($sqrDataArrayX, $table["SalesRecordNumber"]);
            array_push($sqrDataArrayX, strtotime($table["SalesDate"]));
        }
        
        // checks to see if  anything in the table is within the date time
        // we will need to fix the search query to the database, as this will take a long time if there are 100+ sales records
        $length = count($sqrDataArrayX);
        for ($i = 1; $i <= $length; $i++)
        {
            if ($sqrDataArrayX[$i] > $startX && $sqrDataArrayX[$i] < $endX)
            {
                echo $sqrDataArrayX[$i];
                echo "<br>";
                echo $endX;
                echo "<br>";
            }
            $i++;
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
    $tableDataArrayX = $salesRecordTable->FindAll();

    // retrieve the recordDetails table (from recordDetail.php)
    // This is needed for the y axis (number of items sold)
    $recordDetailTable = new SaleRecordDetails($db);

    $tableDataArray = $recordDetailTable->FindAll();

    // the foreach loop will just be for testing, will show quantity of items sold from table
    foreach($tableDataArray as $table)
    {
        echo $table['QuantityOrdered'];
        echo " ";
    }
    echo "<br>";

    GetLeastSquareRegression($tableDataArray);
    GetXSquared($tableDataArrayX);

?>