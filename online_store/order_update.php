<?php
session_start();
if (isset($_SESSION['cus_username'])) {
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
                // write update query
                // in this case, it seemed like we have so many fields to pass and
                // it is better to label them and not use question marks
                $query = "UPDATE orders
                  SET cus_username=:cus_username WHERE orderID=:orderID";
                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $cus_username = htmlspecialchars(strip_tags($_POST['cus_username']));
                // bind the parameters
                $stmt->bindParam(':cus_username', $cus_username);
                $stmt->bindParam(':orderID', $orderID);
                if ($stmt->execute()) {
                    $delete_query = "DELETE FROM order_detail WHERE orderID=:orderID";
                    $stmt = $con->prepare($delete_query);
                    $stmt->bindParam(':orderID', $orderID);
                    $stmt->execute();
                    for ($i = 0; $i < count($_POST['productID']); $i++) {
                        $product = $_POST['productID'][$i];
                        $quant = $_POST['quantity'][$i];
                        if ($product != '' && $quant != '') {
                            $query = "INSERT INTO order_detail SET orderID=:orderID, productID=:productID, quantity=:quantity";
                            $stmt = $con->prepare($query);
                            $stmt->bindParam(':orderID', $orderID);
                            $stmt->bindParam(':productID', $product);
                            $stmt->bindParam(':quantity', $quant);
                            $stmt->execute();
                        } else {
                            throw new Exception("Please make sure the product and quantity is selected.");
                        }
                    }
                    echo "<div class='alert alert-success'>Record was updated.";
                } else {
                    throw new Exception("Unable to updated record.");
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        } ?>


        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?orderID={$orderID}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>

                    <td id="leftrow">Order ID</td>
                    <td>
                        <input type='text' placeholder="Enter order ID" class='form-control' value="<?php echo htmlspecialchars($orderID, ENT_QUOTES);  ?>" />
                    </td>
                </tr>
                <tr>
                    <td>Customer Username</td>
                    <td>
                        <div>
                            <select class="form-select" id="autoSizingSelect" name="cus_username">
                                <option value="<?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?>" selected><?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?></option>
                                <?php
                                include 'config/database.php';
                                $select_user_query = "SELECT cus_username FROM customer";
                                $select_user_stmt = $con->prepare($select_user_query);
                                $select_user_stmt->execute();
                                while ($get_cus_username = $select_user_stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value = '$get_cus_username[cus_username]'> $get_cus_username[cus_username] </option>";
                                }
                                ?>
                            </select>
                        </div>
                    </td>
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
                    echo "<option value='$productID' selected>$productName</option> ";
                    $select_product_query = "SELECT productID, name FROM products";
                    $select_product_stmt = $con->prepare($select_product_query);
                    $select_product_stmt->execute();
                    while ($get_product = $select_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value = '$get_product[productID]'> $get_product[name] </option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td>";
                    echo "<select class='form-select' id='autoSizingSelect' name='quantity[]'>";
                    echo "<option value='$quantity' selected> $quantity </option>";
                    $number = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20);
                    for ($i = 0; $i < count($number); $i++) {
                        echo "<option value='$number[$i]'> $number[$i] </option>";
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