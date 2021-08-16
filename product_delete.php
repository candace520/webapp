<?php
// include database connection
    include 'config/database.php';
    try {
        $con->beginTransaction();
        // get record ID
        // isset() is a PHP function used to verify if a value is there or not
        $productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Record ID not found.');

        // delete query
        $proquery = "SELECT * FROM order_detail WHERE productID = ?";
        $prostmt = $con->prepare($proquery);
        $prostmt->bindParam(1, $productID);
        $prostmt->execute();
        $num = $prostmt->rowCount();
        if($num != 0){
            header('Location: product_read.php?action=productInStock');
        } else {
            // delete query
            $query = "DELETE FROM products WHERE productID = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $productID);
            if ($stmt->execute()) {
                header('Location: product_read.php?action=deleted');
            } else {
                die('Unable to delete record.');
            }
        }
        $con->commit();

        // show error
    } catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
?>