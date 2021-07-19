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
    <!-- Add icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #leftrow {
            width: 25%;

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

        .input-container {
            display: -ms-flexbox;
            /* IE10 */
            display: flex;
            width: 100%;
            margin-bottom: 15px;
        }

        .icon {
            padding: 10px;
            background: dodgerblue;
            color: white;
            min-width: 50px;
            text-align: center;
        }
    </style>
</head>
<?php
        include 'menu.php';
        ?>
<body>
    <div class="container">
        
    <div class="page-header">
            <h1>Create Order</h1>
        </div>
        <?php
        if ($_POST) {
            include 'config/database.php';
            try {
                if (empty($_POST['cus_username'])||empty($_POST['productID'])||empty($_POST['quantity'])) {
                    throw new Exception("Please make sure all fields are not empty!");
                }
                $con->beginTransaction();
                $query = "INSERT INTO orders SET cus_username=:cus_username, total=:total";
                $stmt = $con->prepare($query);
                $cus_username = $_POST['cus_username'];
                $total = 0;

                for ($i = 0; $i < count($_POST['productID']); $i++) {
                    $productPrice = $_POST['productID'][$i];
                    $selectPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                    $selectPriceStmt = $con->prepare($selectPriceQuery);
                    $selectPriceStmt->bindParam(':productID', $productPrice);
                    $selectPriceStmt->execute();
                    while ($row = $selectPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                        $productPrice = $row['price'];
                        $quant = $_POST['quantity'][$i];
                        $productTotal = $productPrice * $quant;
                        $total += $productTotal;
                    }
                }
                $stmt->bindParam(':cus_username', $cus_username);
                $stmt->bindParam(':total', $total);

                if ($stmt->execute()) {
                    $lastID = $con->lastInsertId();
                    for ($i = 0; $i < count($_POST['productID']); $i++) {
                        $getPrice = $_POST['productID'][$i];
                        $getPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                        $getPriceStmt = $con->prepare($getPriceQuery);
                        $getPriceStmt->bindParam(':productID', $getPrice);
                        $getPriceStmt->execute();
                        while ($row = $getPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                            $productPrice = $row['price'];
                            $quant = $_POST['quantity'][$i];
                            $total = $productPrice * $quant;
                        }
                        $product = $_POST['productID'][$i];
                        $quant = $_POST['quantity'][$i];
                        if ($product != '' && $quant != '') {
                            $insertodQuery = "INSERT INTO order_detail SET orderID=:orderID, productID=:productID, quantity=:quantity, total=:total";
                            $insertodStmt = $con->prepare($insertodQuery);
                            $insertodStmt->bindParam(':orderID', $lastID);
                            $insertodStmt->bindParam(':productID', $product);
                            $insertodStmt->bindParam(':quantity', $quant);
                            $insertodStmt->bindParam(':total', $total);
                            $insertodStmt->execute();
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer Username</td>
                    <td>
                        <div>
                            <select class="form-select" name="cus_username" id="cus_username">
                                <option value='' disabled selected>-- Select User --</option>
                                <?php
                                include 'config/database.php';
                                $select_user_query = "SELECT cus_username FROM customer WHERE accountStatus = 'active'";
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
                echo "<select class='productID form-select' name='productID[]'>";
                echo "<option value='' disabled selected>-- Select Product --</option> ";
                include 'config/database.php';
                $select_product_query = "SELECT productID, name, price FROM products";
                $select_product_stmt = $con->prepare($select_product_query);
                $select_product_stmt->execute();
                while ($productID = $select_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value = '$productID[productID]'> $productID[name] </option>";
                }
                echo "</select>";
                echo "<select class='quantity form-select' name='quantity[]'>";
                echo "<option value='' disabled selected>-- Select Quantity --</option>";
                for ($i = 1; $i <= 20; $i++) {
                    echo "<option value='$i'> $i </option>";
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

        function validation() {
            var cus_username = document.getElementById("cus_username").value;
            var productID = document.querySelector('.productID').value;
            var quantity = document.querySelector('.quantity').value;
            var flag = false;
            var msg = "";
            if (cus_username == ""||productID == ""||quantity == "") {
                flag = true;
                msg = msg + "Please make sure all fields are not empty!";
            }
            if (flag == true) {
                alert(msg);
                return false;
            }else{
                return true;
            }
        }
    </script>

</body>

</html>