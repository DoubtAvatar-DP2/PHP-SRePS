var inputid = 0;

addNewProductField();

function checkForNewProductField()
{
    var allFieldsFull = false;

    var table = document.getElementById("productEntries");

    for(i=1; i < table.rows.length; i++)
    {   
        var totalprice = table.rows[i].cells[4].firstChild.value;
        var productName = table.rows[i].cells[1].firstChild.value;
        var price = table.rows[i].cells[3].firstChild.value;
        var quantity = table.rows[i].cells[2].firstChild.value;

        if (totalprice != 0 && productName != "" && price != "" && quantity != "")
        {
            allFieldsFull = true;
        }
        else
        {
            allFieldsFull = false;
        }
    }

    if(allFieldsFull)
    {
        addNewProductField();
    }
}

function deleteRow(idNumber)
{
    var table = document.getElementById("productEntries");
    if (table.rows.length > 2)
    {
        table.deleteRow(idNumber);
    }

    for(j=1; j < table.rows.length; j++)
    {   
        table.rows[j].cells[0].innerHTML = j;
    }
}

function addNewProductField()
{
    inputid++;

    var quantityId = "quantity" + inputid;
    var priceId = "price" + inputid;
    var productNameId = "productname" + inputid;
    var totalpriceId = "totalprice" + inputid;

    var productEntryTable = document.getElementById("productEntries");
    var productEntryRow = productEntryTable.insertRow(-1);
    var entrynumbercell = productEntryRow.insertCell(0);
    var productNameCell = productEntryRow.insertCell(1);
    var quantityCell = productEntryRow.insertCell(2);
    var priceCell = productEntryRow.insertCell(3);
    var totalCell = productEntryRow.insertCell(4);
    var deleteButtonCell = productEntryRow.insertCell(5);
    
    entrynumbercell.innerHTML = inputid;
    productNameCell.innerHTML = "<input type=\"text\" id=\""+productNameId+"\" name=\"productname\" placeholder=\"Enter product name or ID here\" class='productNumber'></td>"; 
    quantityCell.innerHTML = "<input type=\"number\" id=\""+quantityId+"\" name=\"quantity\" class='quantity'>";
    priceCell.innerHTML = "<input type=\"number\" step=\"0.01\" id=\""+priceId+"\" name=\"price\" class='price'>";
    totalCell.innerHTML = "<input type=\"number\" id=\""+totalpriceId+"\" name=\"totalprice\" readonly=\"true\" value=\"0\">";
    deleteButtonCell.innerHTML = "<img id=\"delete"+inputid+"\" src=\"bin.png\" height=\"20\" width=\"20\">";

    var i = inputid;
    document.getElementById(quantityId).addEventListener("change", function() {calculateProductTotal(i)}); 
    document.getElementById(priceId).addEventListener("change", function() {calculateProductTotal(i)}); 

    document.getElementById(quantityId).addEventListener("change", checkForNewProductField); 
    document.getElementById(productNameId).addEventListener("change", checkForNewProductField); 
    document.getElementById(priceId).addEventListener("change", checkForNewProductField); 

    document.getElementById("delete"+inputid).addEventListener("click", function() {deleteRow(productEntryRow.rowIndex)});

    for(j=1; j < productEntryTable.rows.length; j++)
    {   
        productEntryTable.rows[j].cells[0].innerHTML = j;
    }
}

function calculateProductTotal(idNumber)
{
    var quantityId = "quantity" + idNumber;
    var priceId = "price" + idNumber;
    var productNameId = "productname" + idNumber;
    var totalpriceId = "totalprice" + idNumber;

    document.getElementById(totalpriceId).value = 0;
    document.getElementById("total").innerHTML = "Total $0"
    var quantity = document.getElementById(quantityId).value;
    var price = document.getElementById(priceId).value;
    if (!isNaN(price) && !isNaN(quantity) && price > 0 && quantity > 0)
    {
        var total = quantity*price;
        total = total.toFixed(2);
        document.getElementById(totalpriceId).value = total;
        calculateSalesTotal();
    }
}

function calculateSalesTotal()
{
    var totals = 0; 

    var table = document.getElementById("productEntries");

    for (i = 1; i < table.rows.length; i++)
    {
        var itemtotal = table.rows[i].cells[4].firstChild.value;
        totals += parseFloat(itemtotal);
    }

    totals = totals.toFixed(2);

    if (!isNaN(totals))
    {
        document.getElementById("total").innerHTML = "Total $" + totals;
    }
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

document.getElementById("add-record-button").addEventListener("click", (event) => {

    /*
    * fetch record data + details before send them to add-new-record.php by POST
    */

    // prevent the form from submitting as the default action
    event.preventDefault();
    sales_record_data = {
        SalesDate: document.getElementById("recorddate").value,
        Comment: document.getElementById("note").value,
        RecordDetails: fetchRecordDetails() 
    };

    // post to add-new-record.php
    $.post({
        url: "backend_api/add-new-record.php",
        data: sales_record_data,
        success: (data) => {
            // check if receiving successful code
            if (data == 0)
            {
                alert("Successfully added a record, we will move you to the main page shortly.");
            }
            else 
            {
                alert("Failed to add a record");
                console.log(data);
            }
        },
        error: () => {
            alert("We can not save your sales record now. Please try again later.");
        }
    });
});