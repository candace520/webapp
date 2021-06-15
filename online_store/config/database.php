<?php
// used to connect to the database
$host = "localhost";
$db_name = "candace_store";
$username = "candace_store";
$password = "07240206xuen";
  
try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    
}  
// show error
catch(PDOException $exception){
    echo "Connection error: ".$exception->getMessage();
}
?>
