<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
<?php
    include 'menu.php';
    ?>
    <div class="container">
        <div class="page-header">
            <h4 class="p-1">Create Order</h4>
        </div>
        <?php
        if ($_POST) {
            include 'config/database.php';
            try {
                if (empty($_POST['cus_username'])) {
                    throw new Exception("Make sure all fields are not empty");
                }
                var_dump($_POST);
                $query = "INSERT INTO orders SET name=:name";
                $stmt = $con->prepare($query);
                $name = $_POST['name'];
                $stmt->bindParam(':name', $name);
                /*foreach($_POST['productID'] as $singleProduct) {
                    echo $singleProduct;
                }*/
                
                if ($stmt->execute()) {
                    $lastID=$con->lastInsertId();
                    echo "<div class='alert alert-success'>Record was saved. Order ID is $lastID.</div>";
                    for($i=0; $i<count($_POST['productID']); $i++){
                        $product=$_POST['productID'][$i];
                        $quant= $_POST['quantity'][$i];
                        $query = "INSERT INTO order_detail SET orderID=$lastID, productID=$product, quantity=$quant";
                        echo $query;
                        $stmt = $con->prepare($query);
                        $stmt->execute();
                    }
                } else {
                    throw new Exception("Unable to save record.");
                }
            } catch (PDOException $exception) {
                //for databae 'PDO'
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
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
                            <select class="form-select" id="autoSizingSelect" name="name">
                                <option selected>-- Select User --</option>
                                <?php
                                include 'config/database.php';
                                $select_user_query = "SELECT name FROM customer";
                                $select_user_stmt = $con->prepare($select_user_query);
                                $select_user_stmt->execute();
                                while ($name = $select_user_stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value = '$name[rname]'> $name[rname] </option>";
                                }
                                ?>

                            </select>
                        </div>
                    </td>
                </tr>
                <?php
                for ($x = 0; $x < 3; $x++) {
                    echo "<tr>";
                    echo "<td>Product ID</td>";
                    echo "<td>";
                    echo "<div>";
                    echo "<select class='form-select' id='autoSizingSelect' name='productID[]'> ";
                    echo "<option selected>-- Select Product --</option> ";
                    include 'config/database.php';
                    $select_product_query = "SELECT id, name FROM products";
                    $select_product_stmt = $con->prepare($select_product_query);
                    $select_product_stmt->execute();
                    while ($productID = $select_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value = '$productID[id]'> $productID[id] </option>";
                    }
                    echo "<td><input type='text' name='quantity[]' class='form-control' /></td>";
                    echo "</select>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                } ?>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='order_list.php' class='btn btn-danger'>View Order</a>
                    </td>
                </tr>
            </table>
        </form>
        <?php
    include 'footer.php';
    ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>