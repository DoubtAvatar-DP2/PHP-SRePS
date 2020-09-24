var inputid = 0;

addNewProductField();

function checkForNewProductField()
{
    var allFieldsFull = false;

    for (i = 1; i <= inputid; i++)
    {
        totalprice = document.getElementById("totalprice"+i).value;
        productName = document.getElementById("productname"+i).value;
        price = document.getElementById("price"+i).value;
        quantity = document.getElementById("quantity"+i).value;

        if (totalprice != 0 && productName != "" && price != "" && quantity != "")
        {
            allFieldsFull = true;
        }
        else
        {
            alert("One of these rows has empty cells.");
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
    var table = document.getElementById("productEntries").deleteRow(idNumber);
    if (document.getElementById("productEntries").rows.length > 1)
    {
        table.deleteRow(idNumber);
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

    document.getElementById("delete"+inputid).addEventListener("click", function() {deleteRow(i)});
}

function calculateProductTotal(idNumber)
{
    //var idNumber = inputid;
    var quantityId = "quantity" + idNumber;
    var priceId = "price" + idNumber;
    var productNameId = "productname" + idNumber;
    var totalpriceId = "totalprice" + idNumber;

    document.getElementById(totalpriceId).value = 0;
    document.getElementById("total").innerHTML = "Total $0"
    var quantity = document.getElementById(quantityId).value;
    var price = document.getElementById(priceId).value;
    //alert("Checking values: Quantity: "+quantity+", Price: "+price);
    if (!isNaN(price) && !isNaN(quantity) && price > 0 && quantity > 0)
    {
        document.getElementById(totalpriceId).value = quantity*price;
        calculateSalesTotal();
    }
}

function calculateSalesTotal()
{
    var totals = 0; 

    for (i = 1; i <= inputid; i++)
    {
        itemtotal = document.getElementById("totalprice"+i).value
        totals += parseFloat(itemtotal);
    }

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