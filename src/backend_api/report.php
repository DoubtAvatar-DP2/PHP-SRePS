<?php
    require "../connector/database.php";
    require "../connector/salesRecord.php";
    require "../connector/recordDetail.php";
    require "../connector/product.php";

    // establish the connection
    $db = (new Database())->getConnection();

    if (!$db)
    {
        die("Can not connect to database. Please try again later");
    }

    // Gets selections from the display page
    $strPeriod = "+1 " . $_GET["PERIOD"];
    $startDateX = $_GET["recorddatestart"]; //2020-09-04
    // $endDateX = gmdate("Y-m-d", strtotime($strPeriod, strtotime($startDateX))); //~2020-09-2
    $endDateX = $_GET["recorddateend"];
    $groupBy = $_GET["WHICHDATA"];
    $groupID = ($_GET["ITEMID"] != "") ? $_GET["ITEMID"] : $_GET["CATEGORYID"]; // Assuming the two ID boxes

    // This is needed for the y axis (number of items sold)
    $recordDetailTable = new SaleRecordDetails($db);
    $itemTableArray = $recordDetailTable->findPredictDataItemOrCategory($startDateX, $endDateX, $groupBy, $groupID); 

    // returns an array ie. [slope, intercept, array of data]
    exit(json_encode($itemTableArray));
?>