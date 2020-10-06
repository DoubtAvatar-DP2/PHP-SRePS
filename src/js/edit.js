window.onload = () => {
    //
    // retrieve record ID from GET parameter
    // https://stackoverflow.com/questions/5448545/how-to-retrieve-get-parameters-from-javascript
    //

    var SalesRecordNumber = GetRecordIDByGET();

    // check if the sales record number is valid input
    if (SalesRecordNumber === null || isNaN(SalesRecordNumber))
    {
        alert("We are unable to retrieve your record.");
        window.location.href = "index.php";
        return;
    }

    $.post({
        url: "backend_api/retrieve-record.php",
        data: {
            SalesRecordNumber 
        },
        error: () => {
            alert("We can not retrieve your sales record now. Please try again later.");
        },
        success: (data) => {
            salesData = JSON.parse(data);

            if (!salesData.hasOwnProperty("SalesRecord")) 
            {
                alert("We are unable to retrieve your data");
                window.location.href = "index.php";
            }
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
    
    document.getElementById("recorddate").value = recordDate;
    document.getElementById("note").value = comment;

    //  that fill in data into the correct outputs
    recordDetails.forEach((recordDetail) => 
        addNewProductField(recordDetail.ProductNumber, recordDetail.QuantityOrdered, recordDetail.QuotedPrice) 
    );
}

function GetRecordIDByGET()
{
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);
    return params.get("recordID");
}