<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        #leftrow {
            width: 30%;

        }

        .container {
            width: 50%;
        }

        .nav {
            padding-left: 30px;
            font-size: 18px;
            font-weight: normal;
            font-family: sans-serif;
        }

        span {
            font-weight: bolder;
            color: white;
        }
    </style>
</head>

<body>
    <?php
    include 'menu.php';
    ?>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Orders</h1>
        </div>
        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            include 'config/database.php';
            try {
                if (empty($_POST['cus_username'])) {
                    throw new Exception("Make sure all fields are not empty");
                }
                $con->beginTransaction();
                $query = "INSERT INTO orders SET cus_username=:cus_username";
                $stmt = $con->prepare($query);
                $cus_username = $_POST['cus_username'];
                $stmt->bindParam(':cus_username', $cus_username);
                if ($stmt->execute()) {
                    $lastID = $con->lastInsertId();
                    for ($i = 0; $i < count($_POST['productID']); $i++) {
                        $product = $_POST['productID'][$i];
                        $quant = $_POST['quantity'][$i];
                        if ($product != '' && $quant != '') {
                            $query = "INSERT INTO order_detail SET orderID=:orderID, productID=:productID, quantity=:quantity";
                            $stmt = $con->prepare($query);
                            $stmt->bindParam(':orderID', $lastID);
                            $stmt->bindParam(':productID', $product);
                            $stmt->bindParam(':quantity', $quant);
                            $stmt->execute();
                        } else {
                            throw new Exception("Please make sure the product and quantity is selected.");
                        }
                    }
                    echo "<div class='alert alert-success'>Record was saved. Order ID is $lastID.</div>";
                } else {
                    throw new Exception("Unable to save record.");
                }
                $con->commit();
            } catch (PDOException $exception) {
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
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                </tr>

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
        <?php
        include 'footer.php';
        ?>
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