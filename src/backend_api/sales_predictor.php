<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";

    class PredictData
    {
        public $date;
        public $QtyNum;
        public $xy;
        public $xSqr;

        public function __construct($date, $QtyNum)
        {
            $this->date = $date;
            $this->QtyNum = $QtyNum;
        }
    }

    // this function will convert the datetimes to integers (eg. 2020-09-05 00:00:00 --> 1)
    function ConvertXAxisToInt($tableDataArrayX, $startDateX)
    {
        $startX = strtotime($startDateX);

        // converts dates to a integer (unix time) 
        $i = 0;
        foreach($tableDataArrayX as $table)
        {
            echo "<br>";
            $tableDataArrayX[$i]['SalesDate'] = strtotime($table["SalesDate"]);
            echo $tableDataArrayX[$i]['SalesDate']; // this line is just for viewing on page will delete later
            $i++;
        }

        echo "<br>";

        // converts unix time to an single integer (i.e 6)
        // round covert must be 'floor' as floor will round down only, very important
        $j = 0;
        foreach($tableDataArrayX as $table)
        {  
            $tempDayValue = floor(($table["SalesDate"] - $startX)/86400);
            $tableDataArrayX[$j]["SalesDate"] = $tempDayValue;
            echo $tableDataArrayX[$j]["SalesDate"]; // this line is just for viewing on page will delete later
            echo "<br>";
            $j++;
        }

        return $tableDataArrayX;
    }

    // gets the sum of x
    function GetXSum($convertedXAxis)
    {
        $total = 0;
        $i = 0;

        foreach($convertedXAxis as $table)
        {
            $total += $table["SalesDate"];
            $i++;
        }
        
        return $total;
    }

    // gets the sum of y
    function GetYSum($tableDataArrayY)
    {
        $total = 0;
        $i = 0;

        foreach($tableDataArrayY as $table)
        {
            $total += $table["QuantityOrdered"];
            $i++;     
        }

        return $total;
    }
    
    // find the intercept
    function GetIntercept($tableDataArray)
    {

    }

    // find the slope
    function GetSlope($tableDataArray)
    {
        
    }

    // find XY
    function GetXY($tableDataArrayY, $tableDataArrayX)
    {
        $dataArrayXY = array();

        //TODO: Delete echos below
        echo "-- XY --";
        echo "<br>";

        // find the appropriate QuantityOrdered and SalesDate, times them together into the array 
        // and return the array
        $it = new MultipleIterator();
        $it->attachIterator(new ArrayIterator($tableDataArrayX));
        $it->attachIterator(new ArrayIterator($tableDataArrayY));

        foreach ($it as $dataXY)
        {
            $predictedData = new PredictData($dataXY[0]["SalesDate"], (int)$dataXY[1]["QuantityOrdered"]);
            $predictedData->xy = $predictedData->date * $predictedData->QtyNum;
            $predictedData->xSqr = pow($predictedData->QtyNum, 2);
            array_push($dataArrayXY, $predictedData);

            //TODO: Delete echos below
            echo $predictedData->date . " : " . $predictedData->QtyNum . " : " . $predictedData->xy;
            echo "<br>";
        }
        
        return $dataArrayXY;
    }

    // squares the value of x that's stored in the data array
    function GetXSquared($predDataArray)
    {
        //TODO: Delete echos below
        echo "-- xSqr --";
        echo "<br>";

        foreach ($predDataArray as $xData)
        {
            $xData->xSqr = pow((int)$xData->QuantityOrdered, 2);

            //TODO: Delete echos below
            echo $xData->date . " : " . $xData->QtyNum . " : " . $xData->xy . " : " . $xData->xSqr;
            echo "<br>";
        }
        
        return $predDataArray;
    }

    function GetLeastSquareRegression($tableDataArrayY, $tableDataArrayX, $startDateX)
    {
        // setting up values 
        $convertedXAxisArray = ConvertXAxisToInt($tableDataArrayX, $startDateX);
        $sumOfX = GetXSum($convertedXAxisArray);
        $SumOfY = GetYSum($tableDataArrayY);

        // get special values -- Sam: I changed this to work with the class we created, we only need the one array as the object holds all t he values we need
        $predictDataArray = GetXY($tableDataArrayY, $convertedXAxisArray);
        //$predictDataArray = GetXSquared($predictDataArray); -- Sam: Don't think we need this, probably rename GetXY to something else if you want        

        /*
        $XSquared = GetXSquared($convertedXAxisArray);
        $XY = GetXY($tableDataArrayY, $convertedXAxisArray);
        */

        // get slope and intercept -- Sam: Again, reworked to work with the class
        $slope = GetSlope($predictDataArray);
        $intercept = GetIntercept($predictDataArray);

        /*
        $slope = GetSlope($tableDataArrayY, $convertedXAxisArray);
        $intercept = GetIntercept($tableDataArrayY, $convertedXAxisArray);
        */

        // will have to figure out what to do with X
        //$regressionLine = $slope * (X) + $intercept;
        $regressionLine = null;
        return $regressionLine;
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

    // This will be entered in from the user, for now this is just for the testing
    $startDateX = "2020-09-04";
    $endDateX = "2020-09-24";

    // This is needed for the x axis (date of sale)
    $salesRecordTable = new SalesRecord($db);
    $tableDataArrayX = $salesRecordTable->findpredictionData($startDateX, $endDateX);

    // This is needed for the y axis (number of items sold)
    $recordDetailTable = new SaleRecordDetails($db);
    $tableDataArrayY = $recordDetailTable->FindAll();

    // the foreach loop will just be for testing, will show quantity of items sold from table
    foreach($tableDataArrayY as $table)
    {
        echo $table['QuantityOrdered'];
        echo " ";
    }
    echo "<br>";

    // the foreach loop will just be for testing, will show the dates sold
    foreach($tableDataArrayX as $table)
    {
        echo $table['SalesDate'];
        echo " ";
    }

    GetLeastSquareRegression($tableDataArrayY, $tableDataArrayX, $startDateX);
?>