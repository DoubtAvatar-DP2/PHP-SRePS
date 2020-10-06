// When document is loaded and ready

async function fetchPage() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const response = await fetch("backend_api/fetch-sales-records.php"+queryString, {
        method: "GET"
    })
    const body = await response.json();
    console.log(body);

    // Create pagination
    let pageURL = window.location.pathname;
    let paginationHolder = $('ul.pagination')[0];
    for(let i = 0; i <= body.total_pages + 1; i++) {
        let pageItem = document.createElement("li");
        pageItem.classList.add("page-item");
        if(i == urlParams.get("page"))
            pageItem.classList.add('active');
        let link = document.createElement("a");
        link.classList.add("page-link");
        let newUrlParams = new URLSearchParams(urlParams);

        //if i == 0 we are creating the prev link
        if(i == 0) {
            link.setAttribute("aria-label","Previous");
            if(urlParams.get('page') > 1) {
                newUrlParams.set('page', +urlParams.get('page')-1);
                link.href = pageURL + "?" + newUrlParams.toString();
            }
            else {
                pageItem.classList.add("disabled");
                link.href = "#";
            }
            let linkText = document.createElement("span");
            linkText.setAttribute("aria-hidden", "true");
            linkText.innerHTML = "&laquo;";
            link.appendChild(linkText);
        } else if(i > body.total_pages) { //if i > total_pages we are creating the next link
            link.setAttribute("aria-label","Previous");
            if(urlParams.get('page') < body.total_pages) {
                newUrlParams.set('page', +urlParams.get('page')+1);
                link.href = pageURL + "?" + newUrlParams.toString();
            }
            else {
                pageItem.classList.add("disabled");
                link.href = "#";
            }
            let linkText = document.createElement("span");
            linkText.setAttribute("aria-hidden", "true");
            linkText.innerHTML = "&raquo;";
            link.appendChild(linkText);
        } else { //we are creating a normal page link
            newUrlParams.set('page', i);
            link.innerText = i;
        }
        link.href = pageURL + "?" + newUrlParams.toString();
        pageItem.appendChild(link);
        paginationHolder.appendChild(pageItem);
    }

    //create each record
    let recordTable = $('tbody')[0];
    for(const record of body.results) {
        let row = document.createElement("tr");
        let recordLink = document.createElement("a");
        recordLink.href = "/view.php?id=" + record.SalesRecordNumber;
        let recordNum = document.createElement("td");
        let recordDate = document.createElement("td");
        let recordTotal = document.createElement("td");

        recordLink.innerHTML = record.SalesRecordNumber;
        recordNum.appendChild(recordLink.cloneNode(true));
        recordLink.innerHTML = record.SalesDate; //TODO: Adjust to a better date
        recordDate.appendChild(recordLink.cloneNode(true));
        recordLink.innerHTML = "$"+ record.TotalPrice;
        recordTotal.appendChild(recordLink.cloneNode(true));

        row.appendChild(recordNum);
        row.appendChild(recordDate);
        row.appendChild(recordTotal);
        recordTable.appendChild(row);
    }

}

$(() => {
    fetchPage();
    //get page size and page number get params
    //query for current page. update pagination
});