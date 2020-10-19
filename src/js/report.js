document.getElementById("item").addEventListener("change", (e) => {
    if (document.getElementById("item").checked)
    {
        document.getElementById("ITEMID").disabled = false;
        document.getElementById("CATEGORYID").value = null;
        document.getElementById("CATEGORYID").disabled = true;
    }
});

document.getElementById("category").addEventListener("change", (e) => {
    if (document.getElementById("category").checked)
    {
        document.getElementById("CATEGORYID").disabled = false;    
        document.getElementById("ITEMID").disabled = true;
        document.getElementById("ITEMID").value = null;
    }
});


document.getElementById("report").addEventListener("click", (event) => {

    /*
    * fetch record data + details before send them to edit-record.php by POST
    */
    // prevent the form from submitting as the default action
    event.preventDefault();

    // clear message
    ClearErrorMessage();
    $("#reportTable .record").empty();
    $("#canvas").empty();
    $("#reportTitle").val();


    if (!validateInput()) return;
    
    input_data = {
        recorddatestart: document.getElementById("recorddatestart").value,
        recorddateend: document.getElementById("recorddateend").value,
        WHICHDATA: document.getElementById("item").checked ? "ITEM" : "CATEGORY",
        ITEMID: document.getElementById("ITEMID").value,
        CATEGORYID: document.getElementById("CATEGORYID").value 
    };

    // get to report.php
    $.get({
        url: "backend_api/report.php",
        data: input_data,
        success: (data) => {
            data = JSON.parse(data);
            console.log(data);

            if (data.length == 0) {
                AddErrorMessage(`No record found from ${moment(input_data.recorddatestart).format("YYYY-MM-DD")} to ${moment(input_data.recorddateend).format("YYYY-MM-DD")}`)
                return;
            }

            // write title
            document.getElementById("reportTitle").innerHTML = `${input_data.WHICHDATA == "ITEM" ? data[0].ProductName: data[0].CategoryName} Sales Report`;
            // draw table
            data.forEach((record) => addNewDateEntry(record.SalesDate, record.AllQtyOrd, record.TotalPrice));
            
            // draw chart
            var xLabels = data.map(record => moment(record.SalesDate).format("YYYY-MM-DD"));
            var itemsSold = data.map(record => record.AllQtyOrd);
            var totalPrice = data.map(record => record.TotalPrice);
            DrawChart(xLabels, itemsSold, totalPrice);
        },
        error: () => {
           AddErrorMessage(`We can not generate your sales record now. Please try again later.`);
        }
    });
});

function addNewDateEntry(date, quantity, totalPrice) {
    let table = $('#reportTable')[0];
    let newRow = document.createElement('tr');
    newRow.classList.add("record");
    let dateCell = document.createElement('td');
    let itemsSoldCell = document.createElement('td');
    let totalPriceCell = document.createElement('td');
    
    dateCell.innerText = moment(date).format("YYYY-MM-DD");
    itemsSoldCell.innerText = quantity;
    totalPriceCell.innerText = totalPrice;
    newRow.appendChild(dateCell);
    newRow.appendChild(itemsSoldCell);
    newRow.appendChild(totalPriceCell);
   
    table.appendChild(newRow);
}

function validateInput()
{
    if (document.getElementById("recorddatestart").value == null)
    {
        AddErrorMessage("Please fill in the start date.");
        return false;
    }
    
    if (document.getElementById("recorddateend").value == null)
    {
        AddErrorMessage("Please fill in the end date.");
        return false;
    }

    if (document.getElementById("category").value == "" && document.getElementById("item").value == "")
    {
        AddErrorMessage("Please fill either category or item.");
        return false;
    }
    return true;
}

function AddErrorMessage(newMessage)
{
    $("#error").append(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
    ${newMessage}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>`);
}

function ClearErrorMessage()
{
    $("#error").empty();
}