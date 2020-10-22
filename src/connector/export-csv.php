<?php

/**
 * Sets the headers for downloading a file
 *
 * Sets the Content-Description, type, disposition, etc.
 *
 * @param string $fileName The file name to be used
 * @param int $contentLength the size of the content
 **/
function setDownloadHeaders($fileName, $contentLength) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.$fileName.'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $contentLength);
}

/**
 * exportToCSV exports data to CSV
 *
 * Intakes an array of data and an optional array of column headings. Generates a CSV and downloads a CSV file
 *
 * @param array $data An array of arrays, containing the data to export
 * @param array $headings default null An optional array containing the column headings
 * @param string $fileName The file name to be used
 **/
function exportToCSV(array $data, ?array $headings = null, string $fileName="export.csv") {
    $output = "";

    if($headings) {
        foreach ($headings as $heading) {
            $output .= '"'.$heading.'",';
        }
        $output .= "\r\n";
    }

    foreach ($data as $row) {
        foreach ($row as $cell) {
            $output .= '"'.$cell.'",';
        }
        $output .= "\r\n";
    }

    setDownloadHeaders($fileName, strlen($output));

    flush(); // Flush system output buffer
    echo $output;
}

/**
 * exportAssocToCSV exports data to CSV
 *
 * Intakes an associative array of data. Generates a CSV and downloads a CSV file
 *
 * @param array $data An array of arrays, containing the data to export
 * @param string $fileName The file name to be used
  **/
function exportAssocToCSV(array $data, string $fileName="export.csv") {
    $output = "";

    //Loop through the first array and use its associative keys as column headings
    foreach($data[0] as $heading => $cellData) {
        $output .= '"'.$heading.'",';
    }
    $output .= "\r\n";
    
    foreach ($data as $key => $row) {
        foreach ($row as $cell) {
            $output .= '"'.$cell.'",';
        }
        $output .= "\r\n";
    }
    setDownloadHeaders($fileName, strlen($output));

    flush(); // Flush system output buffer

    echo $output;
}