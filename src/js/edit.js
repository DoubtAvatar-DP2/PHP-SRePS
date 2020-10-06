window.onload = () => {
    //
    // retrieve record ID from GET parameter
    // https://stackoverflow.com/questions/5448545/how-to-retrieve-get-parameters-from-javascript
    //
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);
    let SalesRecordNumber = params.get("recordID");

    // check if the sales record number is valid input
    if (isNaN(SalesRecordNumber))
    {
        alert("We are unable to retrieve your record");

        window.location.href = "index.php";
        return;
    }

    // console.log("Hello");
    $.post({
        url: "backend_api/retrieve-record.php",
        data: {
            SalesRecordNumber 
        },
        error: () => {
            alert("We can not save your sales record now. Please try again later.");
        },
        success: (data) => {
            salesData = JSON.parse(data);
            console.log(salesData);
            FillSalesTable(salesData);
            addNewProductField();
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
    recordDetails.forEach((recordDetail) => 
        addNewProductField(recordDetail.ProductNumber, recordDetail.QuantityOrdered, recordDetail.QuotedPrice) 
    );
}