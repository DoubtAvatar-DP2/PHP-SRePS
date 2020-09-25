window.onload = () => {
    //
    // currently, record number is hardcoded.
    // replace line below with code that get sales record number, possibly via GET methods 
    // (sales record appear on url, preferred)
    // or from session storage. 
    //
    SalesRecordNumber = 1002;

    recordData = {
        SalesRecordNumber 
    };

    $.post({
        url: "backend_api/retrieve-record.php",
        data: recordData,
        success: (data) => {
            salesData = JSON.parse(data);
            FillSalesTable(salesData);
        },
        error: () => {
            alert("We can not save your sales record now. Please try again later.");
        }
    });

};

function FillSalesTable(data)
{
    var recordNumber = data.SalesRecord[0].SalesRecordNumber;
    var recordDate = data.SalesRecord[0].SalesDate;
    var comment = data.SalesRecord[0].Comment;
    var recordDetails = data.RecordDetails;
 
    //
    // replace below with functions that fill in data into the correct outputs
    //
    console.log(recordNumber);
    console.log(recordDate);
    console.log(recordDetails);
}