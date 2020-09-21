document.getElementById("quantity").addEventListener("click", calculateProductTotal); 
document.getElementById("price").addEventListener("click", calculateProductTotal); 

var inputid = 1;

//addNewProductField();
//inputid++
//addNewProductField();

function calculateProductTotal()
{
    document.getElementById("totalprice").value = "";
    document.getElementById("total").innerHTML = "Total $0"
    var quantity = document.getElementById("quantity").value;
    var price = document.getElementById("price").value;
    if (!isNaN(price) && !isNaN(quantity) && price > 0 && quantity > 0)
    {
        document.getElementById("totalprice").value = quantity*price;
        calculateSalesTotal();
    }
}

function calculateSalesTotal()
{
    var totals = document.getElementById("totalprice").value;
    if (!isNaN(totals))
    {
        document.getElementById("total").innerHTML = "Total $" + totals;
    }
}

function addNewProductField()
{
    var quantityId = "quantity" + inputid;
    var priceId = "price" + inputid;
    var productNameId = "productname" + inputid;
    var totalpriceId = "totalprice" + inputid;

    //var tablerow = '<tr><td>'+inputid+'</td><td><input type=\"text\" id=\"'+productNameId+'\" name=\"productname\" placeholder=\"Enter product name or ID here\"></td><td><input type=\"number\" id=\"'+quantityId+'\" name=\"quantity\"></td><td><input type=\"number\" id=\"'+priceId+'\" name=\"price\"></td><td><input type=\"number\" id=\"'+totalpriceId+'\" name=\"totalprice\" readonly=\"true\"></td></tr>';
    
    //document.getElementById("productEntries").appendChild(tablerow);
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
    totalCell.innerHTML = "<input type=\"number\" id=\""+totalpriceId+"\" name=\"totalprice\" readonly=\"true\">"
    //write(tablerow);
    //var row1 = document.createElement('tr');
    //row1 = '<h1>jjjjjjjjjjjjjjjj</h1>';
    //row1 = document.getElementById("productEntries").append(row1);
}