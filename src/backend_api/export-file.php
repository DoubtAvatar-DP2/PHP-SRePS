<?php

    include "../connector/database.php";
    include "../connector/salesRecord.php";
    include "../connector/recordDetail.php";

    include "../connector/export-csv.php";

    $db = (new Database())->getConnection();
    $sr = new SalesRecord($db);
    $srd = new SaleRecordDetails($db);

    if(!$_GET['file'])
        die();
    
    switch ($_GET['file']) {
        case 'records':
            $data = $sr->findAllOverview();
            exportAssocToCSV($data['results'], "records.csv");
            break;
        
        case 'salesReport':
            $data = $srd->findAllSalesData(null,null);
            exportAssocToCSV($data, "salesReport.csv");
            break;
        
        case 'summary':
            $data = $sr->findAllDailySalesSummary(null, null);
            exportAssocToCSV($data, "dailySummary.csv");
            break;

        default:
            # code...
            break;
    }