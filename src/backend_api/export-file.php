<?php

    include "../connector/database.php";
    include "../connector/salesRecord.php";
    include "../connector/recordDetail.php";

    include "../connector/export-csv.php";

    $db = (new Database())->getConnection();
    $sr = new SalesRecord($db);
    $srd = new SaleRecordDetails($db);

    $startDate = $_GET['start'] ?? null;
    $endDate = $_GET['end'] ?? null;

    if(!$_GET['file'])
        die();
    
    switch ($_GET['file']) {
        case 'records':
            $data = $sr->findAllOverview();
            exportAssocToCSV($data['results'], "records.csv");
            break;
        
        case 'salesReport':
            $data = $srd->findAllSalesData($startDate, $endDate);
            exportAssocToCSV($data, "salesReport.csv");
            break;
        
        case 'summary':
            $data = $sr->findAllDailySalesSummary($startDate, $endDate);
            exportAssocToCSV($data, "dailySummary.csv");
            break;

        default:
            # code...
            break;
    }