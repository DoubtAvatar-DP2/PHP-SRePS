<?php
    // require "../connector/database.php";
    // require "../connector/salesRecord.php";
    // require "../connector/recordDetail.php";
    require "connector/database.php";
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
        $i = 0;
        foreach($tableDataArrayX as $table)
        {
            $tableDataArrayX[$i]['SalesDate'] = strtotime($table["SalesDate"]);
            $i++;
        }

        // converts unix time to an single integer (i.e 6)
        // round covert must be 'floor' as floor will round down only, very important
        $j = 0;
        foreach($tableDataArrayX as $table)
        {  
            $tempDayValue = floor(($table["SalesDate"] - $startX)/86400);
            $tableDataArrayX[$j]["SalesDate"] = $tempDayValue;
            $j++;
        }

        return $tableDataArrayX;
    }

    // gets the sum of x
    function GetXSum($tableDataArrayX)
    {
        $total = 0;
        $i = 0;

        foreach($tableDataArrayX as $table)
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
        $total = $total - 24;
        return $total;
    }
    
    // gets the sum of x^2
    function GetXSqrSum($tableDataArrayX)
    {
        $total = 0;
        $i = 0;

        foreach($tableDataArrayX as $table)
        {
            $total += $table->xSqr;
            $i++;
        }
        return $total;
    }

    // gets the sum of xy
    function GetXYSum($tableDataArrayXY)
    {
        $total = 0;
        $i = 0;

        foreach($tableDataArrayXY as $table)
        {
            $total += $table->xy;
            $i++;
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

        $tableDataQuanityY = SalesRecordQuantity($tableDataArrayY, $tableDataArrayX);

        // find the appropriate QuantityOrdered and SalesDate, times them together into the array and return the array
        $it = new MultipleIterator();
        $it->attachIterator(new ArrayIterator($tableDataArrayX));
        $it->attachIterator(new ArrayIterator($tableDataQuanityY));

        foreach ($it as $dataXY)
        {
            $predictedData = new PredictData($dataXY[0]["SalesDate"], $dataXY[1]);
            $predictedData->xy = $predictedData->date * $predictedData->QtyNum;
            $predictedData->xSqr = pow($predictedData->date, 2);
            array_push($dataArrayXY, $predictedData);

            //TODO: Delete echos below
            echo $predictedData->date . " : " . $predictedData->QtyNum . " : " . $predictedData->xy . " : " . $predictedData->xSqr;
            echo "<br>";
        }
        return $dataArrayXY;
    }

    function GetLeastSquareRegression($tableDataArrayY, $tableDataArrayX, $startDateX)
    {
        // setting up date to x axis
        $convertedXAxisArray = ConvertXAxisToInt($tableDataArrayX, $startDateX);

        // get special values 
        $predictDataArray = GetSpecialValues($tableDataArrayY, $convertedXAxisArray);

        // setting up sums 
        $XSum = GetXSum($convertedXAxisArray);
        $YSum = GetYSum($tableDataArrayY);
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

    // This will be entered in from the user, for now this is just for the testing
    $startDateX = "2020-09-04";
    $endDateX = "2020-09-26";

    // This is needed for the x axis (date of sale)
    $salesRecordTable = new SalesRecord($db);
    $tableDataArrayX = $salesRecordTable->findpredictionData($startDateX, $endDateX);

    // finds the min and max for record number, needed for query of table
    $RecordNumbers = findRecordMinMax($tableDataArrayX);

    // This is needed for the y axis (number of items sold)
    $recordDetailTable = new SaleRecordDetails($db);
    $tableDataArrayY = $recordDetailTable->findpredictionData($RecordNumbers[0], $RecordNumbers[1]);

    GetLeastSquareRegression($tableDataArrayY, $tableDataArrayX, $startDateX);
?>