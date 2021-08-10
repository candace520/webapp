<?php
include 'config/database.php';
try {  
    $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Order record not found.');
    $productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Product ID not found.');
    echo$orderID;
    echo $productID;

    $checkQuery = "SELECT * FROM order_detail WHERE orderID = :orderID";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bindParam(':orderID', $orderID);
    $checkStmt->execute();
    $num = $checkStmt->rowCount();
    echo$num;
    if($num == 1){
        header('Location: order_update.php?orderID='.$orderID.'&action=onlyOneProduct');
    }
    else{
    $query = "DELETE FROM order_detail WHERE productID = :productID";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':productID', $productID);
    if($stmt->execute()){
        header('Location: order_update.php?orderID='.$orderID.'&action=deleted');
    }else{
        die('Unable to delete record.');
    }


    }
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}