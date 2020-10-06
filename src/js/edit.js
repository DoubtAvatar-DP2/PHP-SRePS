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
            // console.log(salesData);

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
            alert("We can not retrieve your sales record now. Please try again later.");
        },
        success: (data) => {
            if (data==0)
            {
                alert(`Successfully delete record ${SalesRecordNumber}`);
                window.location.href = "index.php";
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
            // check if receiving successful code
            if (data == 0)
            {
                alert(`Successfully edit record ${SalesRecordNumber}, we will move you to the main page shortly.`);
            }
            else 
            {
                alert(`Failed to edit record ${SalesRecordNumber}`);
                console.log(data);
            }
        },
        error: () => {
            alert(`We can not edit your sales record now. Please try again later.`);
        }
    });
});

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

function fetchRecordDetails()
{
    /*
    * retrieve details from table into an array.
    */
    var productNumberInputs = document.getElementsByClassName("productNumber");
    var quantityInputs = document.getElementsByClassName("quantity");
    var quotedInputs = document.getElementsByClassName("price");

    var recordDetails = [];

    for (let i = 0; i < productNumberInputs.length - 1; ++i)
    {
        recordDetails.push({
            productNumber: productNumberInputs[i].value,
            quantityOrdered: quantityInputs[i].value,
            quotedPrice: quotedInputs[i].value
        });
    }
    return recordDetails;
}