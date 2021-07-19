<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Order list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

<body> <?php
        include 'menu.php';
        ?>
    <div class="container">
       
        <div class="page-header">
            <h1> Read Order</h1>
        </div>

        <?php
        include 'config/database.php';
        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }
        $query = "SELECT * FROM orders ORDER BY orderId DESC";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        echo "<a href='orders.php' class='btn btn-primary mb-2'>Create New Order</a>";
        if ($num > 0) {
            echo "<table class='table table-hover table-responsive table-bordered'>";

            echo "<tr>";
            echo "<th>Order ID</th>";
            echo "<th>Customer Username</th>";
            echo "<th>Total Amount You Need To Paid:</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$orderID}</td>";
                echo "<td>{$cus_username}</td>";
                echo "<td>{$total}</td>";
                echo "<td>";
                echo "<a href='order_read_one.php?orderID={$orderID}' class='btn btn-info me-2'>Read</a>";
                echo "<a href='order_update.php?orderID={$orderID}' class='btn btn-primary me-2'>Edit</a>";
                echo "<a href='#' onclick='delete_order({$orderID});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type='text/javascript'>
        function delete_order(orderID) {

            if (confirm('Are you sure?')) {
                window.location = 'order_delete.php?orderID=' + orderID;
            }
        }
    </script>
</body>

</html>