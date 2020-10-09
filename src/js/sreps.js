// Generic JavaScript functions file

function GetRecordIDByGET()
{
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);
    return params.get("recordID");
}

async function getRecordDetailsByID(SalesRecordNumber) {
    try {
        let result = await $.post({
            url: "backend_api/retrieve-record.php",
            data: {
                SalesRecordNumber 
            }
        });
        salesData = JSON.parse(result);

        if (!salesData.hasOwnProperty("SalesRecord")) 
        {
            alert("We are unable to retrieve your data");
            window.location.href = "index.php";
        }

        return salesData;

    } catch (error) {
        alert("We can not retrieve your sales record now. Please try again later.");
    }
}