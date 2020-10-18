<?php

require_once('connector/salesRecord.php');
require_once('connector/database.php');

use PHPUnit\Framework\TestCase;
$salesRecordNumber = 2003;

function FetchRecord()
{
    global $salesRecordNumber;
    return Array(    
        "SalesDate" => date("Y-m-d H:i:sP"),
        "Comment" => "Test",
        "SalesRecordNumber" => $salesRecordNumber ?? null
    );
};

function FetchRecordEdited()
{
    global $salesRecordNumber;
    return Array(    
        "SalesDate" => date("Y-m-d H:i:sP"),
        "Comment" => "TestEdited",
        "SalesRecordNumber" => $salesRecordNumber ?? null
    );
};

// function initializeRecordDB()
// {
//     $db = (new Database())->getConnection();
//     $salesRecord = new SalesRecord($db);
//     return $salesRecord;
// };

class SalesTest extends TestCase
{
    # if test fails double check the db to see if record already exist, if it does remove it
    public function testInsertion()
    {
        global $salesRecordNumber;
        $salesRecord = initializeRecordDB();
        $recordDetailsArray = FetchRecord();
        $this->assertEquals($salesRecordNumber, $salesRecord->insert($recordDetailsArray));
    }

    # update returns 1 if succesful
    /**
    * @depends testInsertion
    */
    public function testRecordEdit()
    {
        $salesRecord = initializeRecordDB();
        $recordDetailsArray = FetchRecordEdited();
        $this->assertEquals(1, $salesRecord->update($recordDetailsArray)); 
    }

    # delete returns 1 if succesful
    /**
    * @depends testRecordEdit
    */
    public function testRemoval()
    {
        global $salesRecordNumber;
        $salesRecord = initializeRecordDB();
        $this->assertEquals(1, $salesRecord->delete($salesRecordNumber));    
    }
}
?>