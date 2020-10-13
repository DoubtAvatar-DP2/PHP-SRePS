<?php
    // require "../connector/database.php";
    // require "../connector/salesRecord.php";
    // require "../connector/recordDetail.php";
    require "connector/database.php";
    require "connector/product.php";
    require "connector/salesRecord.php";
    require "connector/recordDetail.php";

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
        // round covert must be 'floor' as floor will round down only, very important
        $i = 0;
        foreach($tableDataArrayX as $table)
        {
            $tableDataArrayX[$i]['SalesDate'] = strtotime($table["SalesDate"]);
            $tempDayValue = floor(($tableDataArrayX[$i]['SalesDate'] - $startX)/86400);
            $tableDataArrayX[$i]["SalesDate"] = $tempDayValue;
            $i++;

        }
        return $tableDataArrayX;
    }

    // gets the sum of x
    function GetXSum($tableDataArrayX)
    {
        $total = 0;

        foreach($tableDataArrayX as $table)
        {
            $total += $table["SalesDate"];
        }
        return $total;
    }

    // gets the sum of y
    function GetYSum($tableDataArrayY)
    {
        $total = 0;

        foreach($tableDataArrayY as $table)
        {
            $total += $table["QuantityOrdered"]; 
        }
        $total = $total;
        return $total;
    }
    
    // gets the sum of x^2
    function GetXSqrSum($tableDataArrayX)
    {
        $total = 0;

        foreach($tableDataArrayX as $table)
        {
            $total += $table->xSqr;
        }
        return $total;
    }

    // gets the sum of xy
    function GetXYSum($tableDataArrayXY)
    {
        $total = 0;

        foreach($tableDataArrayXY as $table)
        {
            $total += $table->xy;
        }
        return $total;
    }
    
    // find the intercept
    function GetIntercept($dataSize,  $slope, $YSum, $XSum)
    {   
        return ($YSum - ($slope * $XSum)) / $dataSize;
    }

    // find the slope
    function GetSlope($dataSize, $tableDataArray, $XYSum, $XSum, $YSum, $xSqrSum)
    {
        return (($dataSize * $XYSum) - ($XSum* $YSum)) / (($dataSize * $xSqrSum) - pow($XSum, 2));
    }

    // converts all quanities to a total for each sales record
    function SalesRecordQuantity($tableDataArrayY, $tableDataArrayX)
    {
        $sorted = array();
        $total = 0;
        $j = 0;

        // accumulates quatity sold 
        // else it pushes current sales record quantity into and array and moves to the next sales record
        foreach ($tableDataArrayY as $table)
        {
            if ($table["SalesRecordNumber"] == $tableDataArrayX[$j]["SalesRecordNumber"])
            {
                $total += $table["QuantityOrdered"];
            }
            else 
            {
                array_push($sorted, $total);
                $total = $table["QuantityOrdered"];
                $j++;
            }
        }
        array_push($sorted, $total);
        return array_reverse($sorted); // needs to be reversed
    }

    // find XY
    function GetSpecialValues($tableDataArrayY, $tableDataArrayX)
    {
        $dataArrayXY = array();

        //TODO: Delete echos below
        echo "-- XY --";
        echo "<br>";

        // converts quatitiy to a total
        $tableDataQuanityY = SalesRecordQuantity($tableDataArrayY, $tableDataArrayX);

        // find the appropriate QuantityOrdered and SalesDate, times them together into the array and return the array
        $it = new MultipleIterator();
        $it->attachIterator(new ArrayIterator($tableDataArrayX));
        $it->attachIterator(new ArrayIterator($tableDataArrayY));

        foreach ($it as $dataXY)
        {
            $predictedData = new PredictData($dataXY[0]["SalesDate"], $dataXY[1]['QuantityOrdered']);
            $predictedData->xy = $predictedData->date * $predictedData->QtyNum;
            $predictedData->xSqr = pow($predictedData->date, 2);
            array_push($dataArrayXY, $predictedData);

            //TODO: Delete echos below
            echo $predictedData->date . " : " . $predictedData->QtyNum . " : " . $predictedData->xy . " : " . $predictedData->xSqr;
            echo "<br>";
        }
        return $dataArrayXY;
    }

    function GetLeastSquareRegression($startDateX, $itemTableArray)
    {
        // setting up date to x axis
        $convertedXAxisArray = ConvertXAxisToInt($itemTableArray, $startDateX);

        // get special values 
        $predictDataArray = GetSpecialValues($itemTableArray, $convertedXAxisArray);

        // setting up sums 
        $XSum = GetXSum($convertedXAxisArray);
        $YSum = GetYSum($itemTableArray);
        $xSqrSum = GetXSqrSum($predictDataArray);
        $XYSum = GetXYSum($predictDataArray);

        echo $XSum . " : " . $YSum . " : " . $xSqrSum . " : " . $XYSum;
        echo "<br>";

        // get slope and intercept -- Sam: Again, reworked to work with the class
        $slope = GetSlope(count($predictDataArray), $predictDataArray, $XYSum, $XSum, $YSum, $xSqrSum);
        $intercept = GetIntercept(count($predictDataArray), $slope, $YSum, $XSum);

        echo "slope: " . $slope . " : Intercept " . $intercept;

        //$regressionLine = $slope * (X) + $intercept;
        $regressionLine = null;
        return $regressionLine;
    }

    function findRecordMinMax($tableDataArrayX)
    {
        $sorted = array();
        $numbers = array_column($tableDataArrayX, 'SalesRecordNumber');        
        $min = min($numbers);
        $max = max($numbers);
        array_push($sorted, $min, $max);
        return $sorted;
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

    // -- Just to show how to get the data we'll need from the display page --
    echo $_GET["recorddatestart"] . " ";
    echo $_GET["WHICHDATA"] . " ";
    echo $_GET["PERIOD"] . " <br/>";

    $strPeriod = "+1 " . $_GET["PERIOD"];

    // Just to show how it can be done
    $startDateX = strtotime($_GET["recorddatestart"]);
    $endDateX = strtotime($strPeriod, $startDateX);

    echo gmdate("Y-m-d", $startDateX) . " ";
    echo gmdate("Y-m-d", $endDateX) . " <br/>";

    // More realistically you'll want something like this if you want to keep the dates in DateTime before
    // passing them into ConvertXAxisToInt
    $strPeriod = "+1 " . $_GET["PERIOD"];
    $startDateX = $_GET["recorddatestart"];
    $endDateX = gmdate("Y-m-d", strtotime($strPeriod, strtotime($startDateX)));

    // -- --

    // This will be entered in from the user, for now this is just for the testing
    $startDateX = "2020-09-04";
    $endDateX = "2020-09-25";

    // This is needed for the x axis (date of sale)
    //$salesRecordTable = new SalesRecord($db);
    //$tableDataArrayX = $salesRecordTable->findpredictionData($startDateX, $endDateX);

    // finds the min and max for record number, needed for query of table
    //$RecordNumbers = findRecordMinMax($tableDataArrayX);

    // This is needed for the y axis (number of items sold)
    $recordDetailTable = new SaleRecordDetails($db);
    //$tableDataArrayY = $recordDetailTable->findpredictionData($RecordNumbers[0], $RecordNumbers[1]);
    $itemTableArray = $recordDetailTable->findPredictionDatas($startDateX, $endDateX);

    // -- Just to test my data --
    var_dump($recordDetailTable->findPredictionDatas($startDateX, $endDateX));
    var_dump($recordDetailTable->findPredictDataByDayProductNum($startDateX, $endDateX));
    // -- --

    //TODO: Add boolean logic to get leastsquare regression differently based on user input

    GetLeastSquareRegression($startDateX, $itemTableArray);
?>