<?php
$mysqli = new mysqli("mysql", "admin", "password", "SREPS");
if($mysqli->connect_error) {
    exit('Could not connect');
}

$sql = "SELECT ProductName FROM Products";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
  echo "<table id=\"productNames\">";
  while($row = $result->fetch_assoc()) 
  {
    //echo "<tr><td>" . $row["ProductName"] . "</td></tr>";
    echo $row["ProductName"] . "<br>";
  }
  echo "</table>";
} 
else 
{
    echo "0 results";
}
$mysqli->close();
?> 