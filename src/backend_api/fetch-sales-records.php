<?php

const DEFAULT_PAGE_SIZE = 10;

include "../connector/database.php";
include "../connector/salesRecord.php";
$db = (new Database())->getConnection();
$salesRecordTable = new SalesRecord($db);

try {
    // Get page and page size. Default values if input does not fit
    $page = isset($_GET["page"]) && intval($_GET["page"]) > 0 ? intval($_GET["page"]) : 1;
    $page_size = isset($_GET["page_size"]) && intval($_GET["page_size"]) > 0 ? intval($_GET["page_size"]) : DEFAULT_PAGE_SIZE;

    // Get sort by column and validate input
    switch(strtolower($_GET["sort"])) {
        case "record":
            $order_by = SalesRecord::SALES_RECORD_NUMBER;
        break;
        case "items":
            $order_by = SalesRecord::TOTAL_ITEMS;
        break;
        case "price":
            $order_by = SalesRecord::TOTAL_PRICE;
        break;
        case "date":
        default:
            $order_by = SalesRecord::SALES_RECORD_DATE;
        break;
    }
    
    //Get order direction
    switch(strtolower($_GET["order"])) {
        case "asc":
            $order_direction = SalesRecord::ASC;
        break;
        case "desc":
        default:
            $order_direction = SalesRecord::DESC;
        break;
    }

    $offset = $page == 1 ? 0 : (($page-1)*$page_size);
    $records = $salesRecordTable->findAllOverview($page_size, $offset, $order_by, $order_direction);

    // Set return type as json
    header('Content-Type: application/json');
    echo json_encode($records);
}
catch(Exception $e)
{
    echo json_encode(["error" => $e->getMessage()]);
    // exit($e->getMessage());
}
