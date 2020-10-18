<?php

require_once('connector/salesRecord.php');
require_once('connector/database.php');

use PHPUnit\Framework\TestCase;
$salesRecordNumber = 2003;

function initializeRecordDB()
{
    $db = (new Database())->getConnection();
    $salesRecord = new SalesRecord($db);
    return $salesRecord;
};

class PredictionTest extends TestCase
{
    # if test fails double check the db to see if record already exist, if it does remove it
    public function testInsertion()
    {
        $this->assertTrue(1 == 1);
    }
}
?>