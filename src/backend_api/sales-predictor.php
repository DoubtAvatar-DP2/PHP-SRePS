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
            $total += $table["AllQtyOrd"]; 
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
    function GetSlope($dataSize, $XYSum, $XSum, $YSum, $xSqrSum)
    {
        return (($dataSize * $XYSum) - ($XSum* $YSum)) / (($dataSize * $xSqrSum) - pow($XSum, 2));
    }

    // find XY
    function GetSpecialValues($tableDataArrayY, $tableDataArrayX)
    {
        $dataArrayXY = array();

        // find the appropriate QuantityOrdered and SalesDate, times them together into the array and return the array
        $it = new MultipleIterator();
        $it->attachIterator(new ArrayIterator($tableDataArrayX));
        $it->attachIterator(new ArrayIterator($tableDataArrayY));

        foreach ($it as $dataXY)
        {
            $predictedData = new PredictData($dataXY[0]["SalesDate"], $dataXY[1]['AllQtyOrd']);
            $predictedData->xy = $predictedData->date * $predictedData->QtyNum;
            $predictedData->xSqr = pow($predictedData->date, 2);
            array_push($dataArrayXY, $predictedData);
        }
        return $dataArrayXY;
    }

    function returnArray($slope, $intercept, $itemTableArray)
    {
        $regressionLine = array();
        array_push($regressionLine, $slope, $intercept, $itemTableArray);
        return $regressionLine;
    }

    function GetLeastSquareRegression($startDateX, $itemTableArray)
    {
        // setting up date to x axis
        $convertedXAxisArray = ConvertXAxisToInt($itemTableArray, $startDateX);

        // get special values 
        $predictDataArray = GetSpecialValues($itemTableArray, $convertedXAxisArray);

        // needs to be greater than 1 due do the way the regression line is calculated, if it is two it will end up dividing by 0.
        // must be two serpate days
        if(count($predictDataArray) > 1)
        {
            // setting up sums 
            $XSum = GetXSum($convertedXAxisArray);
            $YSum = GetYSum($itemTableArray);
            $xSqrSum = GetXSqrSum($predictDataArray); 
            $XYSum = GetXYSum($predictDataArray); 

            // get slope and intercept -- Sam: Again, reworked to work with the class
            $slope = GetSlope(count($predictDataArray), $XYSum, $XSum, $YSum, $xSqrSum);
            $intercept = GetIntercept(count($predictDataArray), $slope, $YSum, $XSum);

            $regressionLine = returnArray($slope, $intercept, $itemTableArray);
            return $regressionLine;
        }
        else
        {
            return "Error: No data found to form a prediction from.";
        }
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

    // Gets selections from the display page
    $strPeriod = "+1 " . $_GET["PERIOD"];
    $startDateX = $_GET["recorddatestart"]; //2020-09-04
    $endDateX = gmdate("Y-m-d", strtotime($strPeriod, strtotime($startDateX))); //~2020-09-2
    $groupBy = $_GET["WHICHDATA"];
    $groupID = ($_GET["ITEMID"] != "") ? $_GET["ITEMID"] : $_GET["CATEGORYID"]; // Assuming the two ID boxes

    // This is needed for the y axis (number of items sold)
    $recordDetailTable = new SaleRecordDetails($db);
    $itemTableArray = $recordDetailTable->findPredictDataItemOrCategory($startDateX, $endDateX, $groupBy, $groupID); 

    // returns an array ie. [slope, intercept, array of data]
    exit(json_encode(GetLeastSquareRegression($startDateX, $itemTableArray)));
?>