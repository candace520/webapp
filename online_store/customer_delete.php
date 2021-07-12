<?php
// include database connection
include 'config/database.php';
try {
    $con->beginTransaction();
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $cus_username = isset($_GET['cus_username']) ? $_GET['cus_username'] :  die('ERROR: Record ID not found.');

     // delete query
     $ordquery = "SELECT * FROM orders WHERE cus_username = ?";
     $ordstmt = $con->prepare($ordquery);
     $ordstmt->bindParam(1, $cus_username);
     $ordstmt->execute();
     $num = $ordstmt->rowCount();
    if($num != 0){
        header('Location: customer_read.php?action=customerInOrder');
    } else {
        // delete query
        $query = "DELETE FROM customer WHERE cus_username = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $cus_username);
        if ($stmt->execute()) {
            header('Location: customer_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
    $con->commit();

    // show error
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
