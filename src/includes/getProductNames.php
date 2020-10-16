<?php
$mysqli = new mysqli("mysql", "admin", "password", "SREPS");
if($mysqli->connect_error) 
{
  exit('Could not connect');
}

$sql = "SELECT ProductName, ProductNumber FROM Products";
$result = $mysqli->query($sql);
$productNames = array();
$productNamesAndNumbers = array();

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) 
  {
    $productNamesAndNumbers[$row['ProductName']] = $row['ProductNumber'];
    array_push($productNames, $row["ProductName"]);
  }
  $cookie_name = "productNames";
  $cookie_value = json_encode($productNames);
  setcookie($cookie_name, $cookie_value, time() + 60, "/");

  $cookie_name = "productNamesAndNumbers";
  $cookie_value = json_encode($productNamesAndNumbers);
  setcookie($cookie_name, $cookie_value, time() + 60, "/");
} 
else 
{
  echo "0 results";
}
$mysqli->close();
?> 