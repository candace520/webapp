<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <?php
    include 'menu.php';
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Update Orders</h1>
        </div>


        <?php
        //PHP read record by ID will be here 

        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            $o_query = "SELECT * FROM orders WHERE orderID = ? LIMIT 0,1";
            $o_stmt = $con->prepare($o_query);
            $o_stmt->bindParam(1, $orderID);
            $o_stmt->execute();
            $o_row = $o_stmt->fetch(PDO::FETCH_ASSOC);

            $orderID = $o_row['orderID'];
            $cus_username = $o_row['cus_username'];

            $od_query = "SELECT p.productID, name, quantity
                        FROM order_detail od
                        INNER JOIN products p ON od.productID = p.productID
                        WHERE orderID = ? LIMIT 0,1";
            $od_stmt = $con->prepare($od_query);
            $od_stmt->bindParam(1, $orderID);
            $od_stmt->execute();
            while ($od_row = $od_stmt->fetch(PDO::FETCH_ASSOC)) {
                $productID = $od_row['name'];
                $quantity = $od_row['quantity'];
            }
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        ?>

        <?php

        //PHP post to update record will be here
        // check if form was submitted
        if ($_POST) {
            try {
                $con->beginTransaction();
                $query = "UPDATE orders SET cus_username=:cus_username WHERE orderID = :orderID";
                $stmt = $con->prepare($query);
                $cus_username = htmlspecialchars(strip_tags($_POST['cus_username']));
                $stmt->bindParam(':orderID', $orderID);
                $stmt->bindParam(':cus_username', $cus_username);

                         if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to updated record.</div>";
                }
                    $con->commit();
            }
            catch (PDOException $exception) {
                //for databae 'PDO'
                if ($con->inTransaction()) {
                    $con->rollback();
                    echo "<div class='alert alert-danger'>Please make sure no duplicate product chosen!</div>";
                } else {
                    echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                }
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        } ?>


        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?orderID={$orderID}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
            <tr>
                    <td>Order ID</td>
                    <td><?php echo htmlspecialchars($orderID, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Customer Username</td>
                    <td><?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?></td>
                </tr>
                <th class='col-4'>Product</th>
                <th class='col-4'>Quantity</th>
                <th class='col-4'>Price</th>
                <?php
                $od_query = "SELECT orderID, p.productID, name, quantity, price
                        FROM order_detail od
                        INNER JOIN products p ON od.productID = p.productID
                        WHERE orderID = :orderID";
                $od_stmt = $con->prepare($od_query);
                $od_stmt->bindParam(":orderID", $orderID);
                $od_stmt->execute();

                while ($od_row = $od_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $ori_productID = $od_row['productID'];
                    $productID = $od_row['productID'];
                    $name = $od_row['name'];
                    $quantity = $od_row['quantity'];
                    $price = $od_row['price'];
                    $productID = htmlspecialchars($productID, ENT_QUOTES);
                    $productName = htmlspecialchars($name, ENT_QUOTES);
                    $productQuantity = htmlspecialchars($quantity, ENT_QUOTES);
                    echo "<tr>";
                    echo "<td>";
                    echo "<select class='form-select' id='autoSizingSelect' name='productID[]'> ";
                    echo "<option ='' >-- Please Select --</option> ";
                    $select_product_query = "SELECT productID, name FROM products";
                    $select_product_stmt = $con->prepare($select_product_query);
                    $select_product_stmt->execute();
                    while ($get_product = $select_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $result = $productID == $get_product['productID'] ? 'selected' : '';
                        echo "<option value = '$get_product[productID]' $result> $get_product[name] </option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td>";
                    echo "<select class='form-select' id='autoSizingSelect' name='quantity[]'>";
                    echo "<option value=''>-- Please Select --</option>";
                    
                    for ($i = 0; $i < 20; $i++) {
                        $result = $productQuantity == $i? 'selected' :'';
                        echo "<option value='$i' $result> $i </option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td>$price</td>";
                    echo "</tr>";
                } ?>

                <tr>
                    <td></td>
                    <td>
                        <button type="button" class="add_one btn btn-info text-light">Add More Product</button>
                        <button type="button" class="delete_one btn btn-warning text-light">Delete Last Product</button>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='order_read.php' class='btn btn-danger'>View Order</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.productQuantity');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.delete_one')) {
                var total = document.querySelectorAll('.productQuantity').length;
                if (total > 1) {
                    var element = document.querySelector('.productQuantity');
                    element.remove(element);
                }
            }
        }, false);
    </script>



    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>