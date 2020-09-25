window.onload=function()
{
    document.getElementById("newRecBut").addEventListener("click", newRecord);
}

function newRecord()
{
    window.location.href = "/add.html";
}