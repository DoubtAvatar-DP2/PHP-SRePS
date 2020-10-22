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
            alert("We are unable to retrieve your record.");
        },
        success: (data) => {
            salesData = JSON.parse(data);

            if (!salesData.hasOwnProperty("SalesRecord")) 
            {
                alert("We are unable to retrieve your record.");
                window.location.href = "index.php";
            }
            FillSalesTable(salesData);
            addNewProductField();
        }
    });
};

document.getElementById("delete").addEventListener("click", (event) => {
    event.preventDefault();
    let SalesRecordNumber = GetRecordIDByGET();
    let confirmation = window.confirm(`Are you sure you want to permanently delete record ${SalesRecordNumber}?`);
    if (confirmation == false) return;  

    $.post({
        url: "backend_api/delete-record.php",
        data: {
            SalesRecordNumber 
        },
        error: () => {
            AddErrorMessage(`Unable to delete record ${SalesRecordNumber}.`);
        },
        success: (data) => {
            if (data==0)
            {
                alert(`Successfully delete record ${SalesRecordNumber}`);
                window.location.href = `index.php`;
            }
        }
    });
});

document.getElementById("update").addEventListener("click", (event) => {

    /*
    * fetch record data + details before send them to edit-record.php by POST
    */
    // prevent the form from submitting as the default action
    event.preventDefault();
    let SalesRecordNumber = GetRecordIDByGET();
    let confirmation = window.confirm(`Are you sure you want to edit record ${SalesRecordNumber}?`);
    if (confirmation == false) return;  

    sales_record_data = {
        SalesRecordNumber,
        SalesDate: document.getElementById("recorddate").value,
        Comment: document.getElementById("note").value,
        RecordDetails: fetchRecordDetails() 
    };

    // post to add-new-record.php
    $.post({
        url: "backend_api/edit-record.php",
        data: sales_record_data,
        success: (data) => {
            data = JSON.parse(data);
            if (data.exitCode == 0)
            {
                let edittedRecordID = data.edittedRecordID;
                // check if receiving successful code        
                alert("Successfully editted a record, we will move you to the edit page shortly.");
                window.location.href = `view.php?recordID=${edittedRecordID}`;
                document.cookie = "productNamesAndNumbers=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            }
            else {
                ClearErrorMessage();
                AddErrorMessage(data.errorMessage);
            }
        },
        error: () => {
            AddErrorMessage(`We can not edit your sales record now. Please try again later.`);
        }
    });
});

function FillSalesTable(data)
{
    var recordNumber = data.SalesRecord[0].SalesRecordNumber;
    var recordDate = data.SalesRecord[0].SalesDate;
    var comment = data.SalesRecord[0].Comment;
    var recordDetails = data.RecordDetails;
    
    document.getElementById("recorddate").value = moment(recordDate).format("YYYY-MM-DD");
    document.getElementById("note").value = comment;

    // fill in data into the correct outputs
    recordDetails.forEach((recordDetail, i) => { 
        addNewProductField(recordDetail.ProductNumber, recordDetail.QuantityOrdered, recordDetail.QuotedPrice) 
        calculateProductTotal(i+1);
    });
}

function GetRecordIDByGET()
{
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);
    return params.get("recordID");
}