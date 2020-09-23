window.onload=function()
{
    document.getElementById("newRecBut").addEventListener("click", newRecord);
    loadRecords();
}

function newRecord()
{
    window.location.href = "http://localhost:8082/add.html";
}

function loadRecords()
{
    // Change the .php to the appropriate filename
    getRequest('index.php', addRecords, alert);

    /* -- For testing --
    var aJSON = {"records": {0: {"SalesRecordNumber": 1, "SalesDate": "1/1/2000", "name": "Larry", "TotalPrice": 1000}, 1: {"SalesRecordNumber": 2, "SalesDate": "1/1/2000", "name": "Larry", "TotalPrice": 2000}, 2: {"SalesRecordNumber": 3, "SalesDate": "1/1/2000", "name": "Larry", "TotalPrice": 3000}}};

    addRecords(aJSON);
       -- For testing -- */
}

function addRecords(jsonRecord)
{
    var productDisplayTable = document.getElementById("recordTable");

    if (jsonRecord)
    {
        for (var i in jsonRecord.records)
        {
            var j = jsonRecord.records[i];

            var productDisplayRow = productDisplayTable.insertRow(-1);
            var recordNumbercell = productDisplayRow.insertCell(0);
            var dateCell = productDisplayRow.insertCell(1);
            var customerCell = productDisplayRow.insertCell(2);
            var totalPriceCell = productDisplayRow.insertCell(3);

            recordNumbercell.innerText = j.SalesRecordNumber;
            dateCell.innerText = j.SalesDate;
            customerCell.innerText = j.name;
            totalPriceCell.innerText = "$" + j.TotalPrice;
        }
    }
}

// https://stackoverflow.com/questions/7165395/call-php-function-from-javascript
function getRequest(url, success, error)
{
    var req = false;
    try
    {
        // most browsers
        req = new XMLHttpRequest();
    } 
    catch (e)
    {
        // IE
        try
        {
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } 
        catch(e) 
        {
            // try an older version
            try
            {
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } 
            catch(e) 
            {
                return false;
            }
        }
    }

    if (!req) return false;
    if (typeof success != 'function') success = function () {};
    if (typeof error!= 'function') error = function () {};

    req.onreadystatechange = function()
    {
        if(req.readyState == 4)
        {
            return req.status === 200 ? success(req.responseText) : error(req.status);
        }
    }
    req.open("GET", url, true);
    req.send(null);

    return req;
}