<?php
include 'config/database.php';
try {

    $productID = isset( $_GET['productID'] ) ? $_GET['productID'] :  die( 'ERROR: Product ID not found.' );
    $orderID = isset( $_GET['orderID'] ) ? $_GET['orderID'] :  die( 'ERROR: Order ID not found.' );
    $total = isset( $_GET['productTotal'] ) ? $_GET['productTotal'] :  die( 'ERROR: total not found.' );

    $checkQuery = 'SELECT * FROM order_detail WHERE orderID = :orderID';
    $checkStmt = $con->prepare( $checkQuery );
    $checkStmt->bindParam( ':orderID', $orderID );
    $checkStmt->execute();
    $num = $checkStmt->rowCount();
    if ( $num == 1 ) {
        header('Location: order_update.php?orderID='.$orderID.'&action=onlyOneProduct');
    }
    else {
        $query = 'DELETE FROM order_detail WHERE productID = :productID AND orderID = :orderID';
        $stmt = $con->prepare($query);
        $stmt->bindParam(':productID', $productID);
        $stmt->bindParam(':orderID', $orderID);
        if ($stmt->execute()) {
            //delete the selected product
            $getAmountQuery = 'SELECT total FROM orders WHERE orderID = :orderID';
            $getStmt = $con->prepare($getAmountQuery);
            $getStmt->bindParam(':orderID', $orderID);
            $getStmt->execute();
            if ($row = $getStmt->fetch(PDO::FETCH_ASSOC)) {
                $updateTotalQuery = 'UPDATE orders SET total=:total WHERE orderID=:orderID';
                $total = $row['total'] - $total ;
                $updateTotalStmt = $con->prepare($updateTotalQuery);
                $updateTotalStmt->bindParam(':orderID', $orderID);
                $updateTotalStmt->bindParam(':total', $total);
                $updateTotalStmt->execute();
            }
            header('Location: order_update.php?orderID='.$orderID.'&action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
    
} catch( PDOException $exception ) {
    die( 'ERROR: ' . $exception->getMessage() );
}