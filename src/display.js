var input = document.getElementById("searchBar");
input.addEventListener("keyup", function(event)
{
    // 13 is the 'Enter' key
    if (event.keyCode === 13)
    {
        event.preventDefault();

        document.getElementById("searchBut").click();
    }
});

function SearchButFilter()
{
    alert("Now searching...");
}