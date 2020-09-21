document.getElementById("quantity").addEventListener("click", calculateProductTotal); 
document.getElementById("price").addEventListener("click", calculateProductTotal); 

function calculateProductTotal()
{
    var quantity = document.getElementById("quantity").value;
    var price = document.getElementById("price").value;
    if (price != "" && quantity != "")
    {
        document.getElementById("totalprice").value = quantity*price;
    }
}

function calculateSalesTotal()
{

}