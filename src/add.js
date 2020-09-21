document.getElementById("quantity").addEventListener("click", calculateProductTotal); 
document.getElementById("price").addEventListener("click", calculateProductTotal); 

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
    if (totals != "")
    {
        document.getElementById("total").innerHTML = "Total $" + totals;
    }
}