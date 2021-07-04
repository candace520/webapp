
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
                $productID = $od_row['productID'];
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
                  SET cus_username=:cus_username  WHERE orderID = :orderID";
                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $cus_username = htmlspecialchars(strip_tags($_POST['cus_username']));
                // bind the parameters
                $stmt->bindParam(':cus_username', $cus_username);
                $stmt->bindParam(':orderID', $orderID);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Order Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                }
                for ($i = 0; $i < count($_POST['productID']); $i++) {
                    $query = "UPDATE order_detail SET productID=:productID, quantity=:quantity  WHERE orderID = :orderID";
                    $stmt = $con->prepare($query);
                    $productID = htmlspecialchars(strip_tags($_POST['productID'][$i]));
                    $quantity = htmlspecialchars(strip_tags($_POST['quantity'][$i]));
                    $stmt->bindParam(':orderID', $orderID);
                    $stmt->bindParam(':productID', $productID);
                    $stmt->bindParam(':quantity', $quantity);
                }

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
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
                    <td>Customer Username</td>
                    <td>
                        <div>
                            <select class="form-select" id="autoSizingSelect" name="cus_username">
                                <option selected>-- Select User --</option>
                                <?php
                                include 'config/database.php';
                                $select_user_query = "SELECT cus_username FROM customer";
                                $select_user_stmt = $con->prepare($select_user_query);
                                $select_user_stmt->execute();
                                while ($cus_username = $select_user_stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value = '$cus_username[cus_username]'> $cus_username[cus_username] </option>";
                                }
                                ?>

                            </select>
                        </div>
                    </td>
                </tr>
                <?php
                echo "<tr class='productQuantity'>";
                echo "<td>Product</td>";
                echo "<td>";
                echo "<div>";
                echo "<select class='form-select' id='autoSizingSelect' name='productID[]'> ";
                echo "<option value=''>-- Select Product --</option> ";
                include 'config/database.php';
                $select_product_query = "SELECT productID, name FROM products";
                $select_product_stmt = $con->prepare($select_product_query);
                $select_product_stmt->execute();
                while ($productID = $select_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value = '$productID[productID]'> $productID[name] </option>";
                }
                echo "</select>";
                echo "<select class='form-select' id='autoSizingSelect' name='quantity[]'>";
                echo "<option value=''>-- Select Quantity --</option>";
                $number = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20);
                for ($i = 0; $i < count($number); $i++) {
                    echo "<option value='$number[$i]'> $number[$i] </option>";
                }
                echo "</select>";
                echo "</div>";
                echo "</td>";

                ?>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='order_read.php' class='btn btn-danger'>Back to read Order</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
</body>

</html>