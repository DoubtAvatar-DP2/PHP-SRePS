var inputid = 0;

var products = [];

window.onload = () => {
    addNewProductField();
    readProductNames();
};

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
    calculateSalesTotal();
}

function addNewProductField(productName = null, quantity = null, price = null)
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

    productNameCell.classList.add("autocomplete");

    var quantityCell = productEntryRow.insertCell(2);
    var priceCell = productEntryRow.insertCell(3);
    var totalCell = productEntryRow.insertCell(4);
    var deleteButtonCell = productEntryRow.insertCell(5);
    
    entrynumbercell.innerHTML = productEntryRow.rowIndex;
    productNameCell.innerHTML = "<input type=\"text\" id=\""+productNameId+"\" name=\"productname\" placeholder=\"Enter product name or ID here\" class='productNumber'></td>"; 
    quantityCell.innerHTML = "<input type=\"number\" id=\""+quantityId+"\" name=\"quantity\" class='quantity'>";
    priceCell.innerHTML = "<input type=\"number\" step=\"0.01\" id=\""+priceId+"\" name=\"price\" class='price'>";
    totalCell.innerHTML = "<input type=\"number\" id=\""+totalpriceId+"\" name=\"totalprice\" readonly=\"true\" value=\"0\">";
    deleteButtonCell.innerHTML = "<img id=\"delete"+inputid+"\" src=\"images/bin.png\" height=\"20\" width=\"20\">";

    var i = inputid;

    document.getElementById(productNameId).value = productName;
    document.getElementById(quantityId).value = quantity;
    document.getElementById(priceId).value = price;

    document.getElementById(quantityId).addEventListener("change", function() {calculateProductTotal(i)}); 
    document.getElementById(priceId).addEventListener("change", function() {calculateProductTotal(i)}); 

    document.getElementById(quantityId).addEventListener("change", checkForNewProductField); 
    document.getElementById(productNameId).addEventListener("change", checkForNewProductField); 

    document.getElementById(priceId).addEventListener("change", checkForNewProductField);
    document.getElementById("delete"+inputid).addEventListener("click", function() {deleteRow(productEntryRow.rowIndex)});
    autocomplete(document.getElementById(productNameId), products);

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
            productNumber: productToID(productNumberInputs[i].value),
            quantityOrdered: quantityInputs[i].value,
            quotedPrice: quotedInputs[i].value
        });
    }
    return recordDetails;
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
            data = JSON.parse(data);
            if (data.exitCode == 0)
            {
                let newRecordID = data.newRecordID;
                // check if receiving successful code        
                alert("Successfully added a record, we will move you to the main page shortly.");
                window.location.href = `view.php?recordID=${newRecordID}`;
            }
            else {
                ClearErrorMessage();
                AddErrorMessage(data.errorMessage);
            }
        },
        error: () => {
            alert("We can not save your sales record now. Please try again later.");
        }
    });
});

//from https://www.w3schools.com/howto/howto_js_autocomplete.asp 
function autocomplete(inp, arr) 
{
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function(e) 
        {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) 
            {
                return false;
            }
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) 
            {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) 
                {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) 
                    {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) 
        {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) 
            {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } 
            else if (e.keyCode == 38) 
            { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } 
            else if (e.keyCode == 13) 
            {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });
        function addActive(x) 
        {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) 
        {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) 
        {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) 
            {
                if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) 
    {
    closeAllLists(e.target);
    });
}

function readProductNames()
{
    //set the value of productNames to the products in the database
    var productsAsJSON = JSON.parse(getCookie("productNames"));
    for (i in productsAsJSON) 
    {
        products.push(productsAsJSON[i]);
    }
    document.cookie = "productNames=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

function productToID(productName)
{
    var productID = 0;
    var productsAndNumbers = JSON.parse(getCookie("productNamesAndNumbers"));
    productID = productsAndNumbers[productName];
    document.cookie = "productNamesAndNumbers=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    return productID;
}

function getCookie(cname) 
{
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) 
    {
        var c = ca[i];
        while (c.charAt(0) == ' ') 
        {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) 
        {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}