<?php
$mysqli = new mysqli("mysql", "admin", "password", "SREPS");
if($mysqli->connect_error) 
{
  exit('Could not connect');
}

$sql = "SELECT ProductName FROM Products";
$result = $mysqli->query($sql);
$productNames = array();

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) 
  {
    array_push($productNames, $row["ProductName"]);
  }
  $cookie_name = "productNames";
  $cookie_value = json_encode($productNames);
  setcookie($cookie_name, $cookie_value, time() + 60, "/"); // 86400 = 1 day
} 
else 
{
  echo "0 results";
}
$mysqli->close();
?> 