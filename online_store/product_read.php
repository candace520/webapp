<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Read Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

<body>
    <?php
    include 'menu.php';
    ?>
    <div class="container">

        <div class="page-header">
            <h1>Read Product</h1>
        </div>

        <?php
        include 'config/database.php';

        $action = isset($_GET['action']) ? $_GET['action'] : "";
        // if it was redirected from delete.php
        if ($action == 'productInStock') {
            echo "<div class='alert alert-danger'>Product could not be deleted.</div>";
        }
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }

        $query = "SELECT productID, name, description, price FROM products ORDER BY productID DESC";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        echo "<a href='create.php' class='btn btn-primary mb-2'>Create New Product</a>";
        if ($num > 0) {

            echo "<table class='table table-hover table-responsive table-bordered'>";

            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Description</th>";
            echo "<th>Price</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$productID}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$description}</td>";
                echo "<td>{$price}</td>";
                echo "<td>";
                echo "<a href='product_read_one.php?productID={$productID}' class='btn btn-info me-2'>Read</a>";
                echo "<a href='product_update.php?productID={$productID}' class='btn btn-primary me-2'>Edit</a>";
                echo "<a href='#' onclick='delete_product({$productID});'  class='btn btn-danger'>Delete</a>";
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
        // confirm record deletion
        function delete_product(productID) {

            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'product_delete.php?productID=' + productID;
            }
        }
    </script>

</body>

</html>