document.getElementById("predict").addEventListener("click", (event) => {

    /*
    * fetch record data + details before send them to edit-record.php by POST
    */
    // prevent the form from submitting as the default action
    event.preventDefault();

    input_data = {
        productNumber: document.getElementById("ItemName").value,
        recorddatestart: document.getElementById("recorddatestart").value,
        PERIOD: document.getElementById("week").checked ? "WEEK" : "MONTH",
        WHICHDATA: document.getElementById("item").checked ? "ITEM" : "CATEGORY" 
    };

    console.log(input_data);
    // post to add-new-record.php
    $.get({
        url: "backend_api/sales-predictor.php",
        data: input_data,
        success: (data) => {
            data = JSON.parse(data);
            console.log(data);
            tableData = data[2];
            tableData.forEach((record) => addNewDateEntry(record.SalesDate, record.AllQtyOrd, record.QuotedPrice))
        },
        error: (data) => {
            console.log(data);
            
            // AddErrorMessage(`We can not edit your sales record now. Please try again later.`);
        }
    });
});

function addNewDateEntry(date, quantity, price) {
    let table = $('#pastSales')[0];
    let newRow = document.createElement('tr');
    let dateCell = document.createElement('td');
    let itemsSoldCell = document.createElement('td');
    let totalPriceCell = document.createElement('td');
    
    dateCell.innerText = date;
    itemsSoldCell.innerText = quantity;
    totalPriceCell.innerText = `$${(price* quantity).toFixed(2)}`;
    newRow.appendChild(dateCell);
    newRow.appendChild(itemsSoldCell);
    newRow.appendChild(totalPriceCell);
   
    table.appendChild(newRow);
}