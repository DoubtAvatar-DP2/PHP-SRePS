document.getElementById("predict").addEventListener("click", (event) => {

    /*
    * fetch record data + details before send them to edit-record.php by POST
    */
    // prevent the form from submitting as the default action
    event.preventDefault();
    
    $("#pastSales .record").empty();
    $("#canvas").empty();
    $("#reportTitle").val();

    input_data = {
        recorddatestart: document.getElementById("recorddatestart").value,
        PERIOD: document.getElementById("week").checked ? "WEEK" : "MONTH",
        WHICHDATA: document.getElementById("item").checked ? "ITEM" : "CATEGORY",
        ITEMID: document.getElementById("ITEMID").value,
        CATEGORYID: document.getElementById("CATEGORYID").value 
    };

    // post to add-new-record.php
    $.get({
        url: "backend_api/sales-predictor.php",
        data: input_data,
        success: (data) => {
            data = JSON.parse(data);
            console.log(data);
            tableData = data[2];
            futureData = data[3];
            
            //
            // future sales, replaced with 
            //
            var futureDates = [];
            var futureSales = [];

            // draw table
            tableData.forEach((record) => addNewDateEntry($('#pastSales')[0], moment(record.SalesDate).format("YYYY-MM-DD"), record.AllQtyOrd, record.TotalPrice));
            for (let i = 0; i < futureDates.length; ++i)
            {
                addNewDateEntry($("#futureSales")[0], moment(futureDates[i]).format("YYYY-MM-DD"), futureSales[i]);
            }

            // draw chart
            var xLabels = tableData.map(record => moment(record.SalesDate).format("YYYY-MM-DD"));
            var itemsSold = tableData.map(record => record.AllQtyOrd);
            var totalPrice = tableData.map(record => record.TotalPrice);

            DrawChart([...xLabels, ...futureDates], totalPrice, futureSales);
        },
        error: (data) => {
            console.log(data);
            AddErrorMessage(`We can not edit your sales record now. Please try again later.`);
        }
    });
});

function addNewDateEntry(table, date, quantity, totalPrice) {
    let newRow = document.createElement('tr');
    let dateCell = document.createElement('td');
    let itemsSoldCell = document.createElement('td');
    let totalPriceCell = document.createElement('td');

    newRow.classList.add("record");
    
    dateCell.innerText = date;
    itemsSoldCell.innerText = quantity;
    totalPriceCell.innerText = totalPrice;

    newRow.appendChild(dateCell);
    newRow.appendChild(itemsSoldCell);
    newRow.appendChild(totalPriceCell);
   
    table.appendChild(newRow);
}