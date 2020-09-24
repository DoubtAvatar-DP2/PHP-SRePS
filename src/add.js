var inputid = 0;

addNewProductField();

function checkForNewProductField()
{

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
    
    entrynumbercell.innerHTML = inputid;
    productNameCell.innerHTML = "<input type=\"text\" id=\""+productNameId+"\" name=\"productname\" placeholder=\"Enter product name or ID here\"></td>"; 
    quantityCell.innerHTML = "<input type=\"number\" id=\""+quantityId+"\" name=\"quantity\">";
    priceCell.innerHTML = "<input type=\"number\" id=\""+priceId+"\" name=\"price\">";
    totalCell.innerHTML = "<input type=\"number\" id=\""+totalpriceId+"\" name=\"totalprice\" readonly=\"true\">";

    var i = inputid;
    document.getElementById(quantityId).addEventListener("click", function() {calculateProductTotal(i)}); 
    document.getElementById(priceId).addEventListener("click", function() {calculateProductTotal(i)}); 
}

function calculateProductTotal(idNumber)
{
    //var idNumber = inputid;
    var quantityId = "quantity" + idNumber;
    var priceId = "price" + idNumber;
    var productNameId = "productname" + idNumber;
    var totalpriceId = "totalprice" + idNumber;

    document.getElementById(totalpriceId).value = "";
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
        totals += parseInt(itemtotal);
    }

    if (!isNaN(totals))
    {
        document.getElementById("total").innerHTML = "Total $" + totals;
    }
}