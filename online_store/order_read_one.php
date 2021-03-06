<?php
    session_start();
    if (!isset($_SESSION["cus_username"])) {
        header("Location: index.php?error=restrictedAccess");
    }
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>Order Detail</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    </head>
    <style>
        </style>
    <body>
        <?php
                include 'menu.php';
            ?>
        <div class="container">
            
            <div class="page-header">
                <h1>Order Details <img src='picture/product/detail.png' style='width: 3%;'></h1>
            </div>

            <?php
            $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Order record not found.');

            include 'config/database.php';

            try {
                $o_query = "SELECT * FROM orders WHERE orderID = :orderID";
                $o_stmt = $con->prepare($o_query);
                $o_stmt->bindParam(":orderID", $orderID);
                $o_stmt->execute();
                $o_row = $o_stmt->fetch(PDO::FETCH_ASSOC);
                $orderID = $o_row['orderID'];
                $cus_username = $o_row['cus_username'];
                $total = sprintf('%.2f', $o_row['total']);
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td class="col-4">Order ID</td>
                    <td><?php echo htmlspecialchars($orderID, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Customer Username</td>
                    <td><?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?></td>
                </tr>
            </table>
            <table class='table table-hover table-responsive table-bordered'>
                <?php
                $od_query = "SELECT p.productID, name, quantity, price, total
                            FROM order_detail od
                            INNER JOIN products p ON od.productID = p.productID
                            WHERE orderID = :orderID";
                $od_stmt = $con->prepare($od_query);
                $od_stmt->bindParam(":orderID", $orderID);
                $od_stmt->execute();
                echo "<th class='col-4 text-center'>Product</th>";
                echo "<th class='col-2 text-center'>Quantity</th>";
                echo "<th class='col-3 text-center'>Price per piece</th>";
                echo "<th class='col-3 text-center'>Total Price</th>";
                while ($od_row = $od_stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td class='text-center'>$od_row[name]</td>";
                    echo "<td class='text-center'>$od_row[quantity]</td>";
                    $productPrice = sprintf('%.2f', $od_row['price']);
                    echo "<td class='text-end'>RM $productPrice</td>";
                    $productTotal = sprintf('%.2f', $od_row['total']);
                    echo "<td class='text-end'>RM $productTotal</td>";
                    echo "</tr>";
                }
                echo "<tr>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<th class='text-end'>Total amount you need to pay:</th>";
                echo "<td class='text-end'>RM $total</td>";
                echo "</tr>";
                ?>   
            </table>
            <div class="d-flex justify-content-center">
                <a href='order_read.php' class='btn btn-danger'>Back to Order List</a>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
            
        </div>
    </body>  
             <?php
                include 'footer.php';
            ?>    
</html>