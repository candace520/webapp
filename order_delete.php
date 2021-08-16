<?php
    // include database connection
    include 'config/database.php';
    try {
        $con->beginTransaction();
        // get record ID
        // isset() is a PHP function used to verify if a value is there or not
        $orderID = isset( $_GET['orderID'] ) ? $_GET['orderID'] :  die( 'ERROR: Record ID not found.' );

        // delete query
        $query = 'DELETE FROM orders WHERE orderID = ?';
        $stmt = $con->prepare( $query );
        $stmt->bindParam( 1, $orderID );
        if ( $stmt->execute() ) {
            header( 'Location: order_read.php?action=deleted' );
        } else {
            die( 'Unable to delete record.' );
        }

        $con->commit();

        // show error
    } catch ( PDOException $exception ) {
        die( 'ERROR: ' . $exception->getMessage() );
    }
?>
