window.onload = async () => {
    //
    // retrieve record ID from GET parameter
    // https://stackoverflow.com/questions/5448545/how-to-retrieve-get-parameters-from-javascript
    //
    var SalesRecordNumber = GetRecordIDByGET();

    // check if the sales record number is valid input
    if (SalesRecordNumber === null || isNaN(SalesRecordNumber))
    {
        alert("We are unable to retrieve your record.");
        // window.location.href = "index.php";
        return;
    }

    $("#editLink")[0].href=`/edit.php?recordID=${SalesRecordNumber}`;
    salesData = await getRecordDetailsByID(SalesRecordNumber);
    fillSalesTable(salesData);
};

function addNewProductEntry(productNumber, productName, quantityOrdered, quotedPrice) {
    let table = $('#productEntries')[0];
    let newRow = document.createElement('tr');
    let productNumberCell = document.createElement('td');
    let productNameCell = document.createElement('td');
    let quantityOrderedCell = document.createElement('td');
    let quotedPriceCell = document.createElement('td');
    let totalPriceCell = document.createElement('td');
    productNumberCell.innerText = productNumber;
    productNameCell.innerText = productName;
    quantityOrderedCell.innerText = quantityOrdered;
    quotedPriceCell.innerText = `$${quotedPrice}`;
    totalPriceCell.innerText = `$${quotedPrice* quantityOrdered}`;
    newRow.appendChild(productNumberCell);
    newRow.appendChild(productNameCell);
    newRow.appendChild(quantityOrderedCell);
    newRow.appendChild(quotedPriceCell);
    newRow.appendChild(totalPriceCell);
    table.appendChild(newRow);
}

function fillSalesTable(data)
{
    var recordDate = data.SalesRecord[0].SalesDate;
    var comment = data.SalesRecord[0].Comment;
    var recordDetails = data.RecordDetails;
    
    document.getElementById("recorddate").innerText = "Record Date: " + moment(recordDate).format("DD/MM/YYYY");
    document.getElementById("note").innerText = comment;

    let orderTotal = 0;
    // fill in data into the correct outputs
    recordDetails.forEach((recordDetail, i) => {
        addNewProductEntry(i+1, recordDetail.ProductName, recordDetail.QuantityOrdered, recordDetail.QuotedPrice);
        orderTotal += recordDetail.QuantityOrdered * recordDetail.QuotedPrice;
    });

    $("#total")[0].innerText = `Total: $${orderTotal}`;
}