<?php
// include database connection
include 'config/database.php';
try {
    $con->beginTransaction();
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

     
        // delete query
        $query = "DELETE FROM customer WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            header('Location: customer_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    
    $con->commit();


    // show error
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
