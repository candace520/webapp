<?php
    session_start();
    if (!isset($_SESSION["cus_username"])) {
        header("Location: login.php?error=restrictedAccess");
    }
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>Update Order</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    </head>
        
    <body>
        <div class="container">
            <?php
                include 'menu.php';
            ?>
            <div class="page-header">
                <h1>Update Order  <img src='picture/img/edit.png' style='width: 4%;'></h1>
            </div>
            <?php 
                    
                include 'config/database.php';
                try {
                    $query = "SELECT * FROM orders WHERE orderID = :orderID ";
                    $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Order record not found.');
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(":orderID", $orderID);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $cus_username = $row['cus_username'];
                    $total = $row['total'];
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
                    $action = isset($_GET['action']) ? $_GET['action'] : "";
                    if ($action == 'onlyOneProduct') {
                        echo "<div class='alert alert-danger'>Product could not be deleted due to only one product placed in order</div>";
                    }
                    if ($action == 'deleted') {
                        echo "<div class='alert alert-success'>Product was deleted.</div>";
                    }
                
                if ($_POST) {
                    try {
                        $con->beginTransaction();//avoid duplicate
                        $updateTotalAmountQuery = "UPDATE orders SET total=:total WHERE orderID=:orderID";
                        $updateTotalAmountStmt = $con->prepare($updateTotalAmountQuery);
                        $total = 0;
                        for ($i = 0; $i < count($_POST['productID']); $i++) {
                            $productPrice = htmlspecialchars(strip_tags($_POST['productID'][$i]));
                            $selectPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                            $selectPriceStmt = $con->prepare($selectPriceQuery);
                            $selectPriceStmt->bindParam(':productID', $productPrice);
                            $selectPriceStmt->execute();
                            while ($selectPriceRow = $selectPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                                $productPrice = $selectPriceRow['price'];
                                $quant = htmlspecialchars(strip_tags($_POST['quantity'][$i]));
                                $productTotal = $productPrice * $quant;
                                $total += $productTotal;
                            }
                        }
                        $updateTotalAmountStmt->bindParam(':orderID', $orderID);
                        $updateTotalAmountStmt->bindParam(':total', $total);
                        $updateTotalAmountStmt->execute();

                        

                        $delete_query = "DELETE FROM order_detail WHERE orderID = :orderID";
                        $delete_stmt = $con->prepare($delete_query);
                        $delete_stmt->bindParam(':orderID', $orderID);
                        if ($delete_stmt->execute()) {
                            for ($i = 0; $i < count($_POST['productID']); $i++) {
                                $getPrice = htmlspecialchars(strip_tags($_POST['productID'][$i]));
                                $getPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                                $getPriceStmt = $con->prepare($getPriceQuery);
                                $getPriceStmt->bindParam(':productID', $getPrice);
                                $getPriceStmt->execute();
                                while ($getPriceRow = $getPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                                    $productPrice = $getPriceRow['price'];
                                    $quant = htmlspecialchars(strip_tags($_POST['quantity'][$i]));
                                    $total = $productPrice * $quant;
                                }
                                $product = htmlspecialchars(strip_tags($_POST['productID'][$i]));
                                $quant = htmlspecialchars(strip_tags($_POST['quantity'][$i]));
                                if ($product != '' && $quant != '') {
                                    $insertodQuery = "INSERT INTO order_detail SET orderID=:orderID, productID=:productID, quantity=:quantity, total=:total";
                                    $insertodStmt = $con->prepare($insertodQuery);
                                    $insertodStmt->bindParam(':orderID', $orderID);
                                    $insertodStmt->bindParam(':productID', $product);
                                    $insertodStmt->bindParam(':quantity', $quant);
                                    $insertodStmt->bindParam(':total', $total);
                                    $insertodStmt->execute();

                                    if ($quant == 0) {
                                        $delete_productQuery = "DELETE FROM order_detail WHERE quantity = :quantity";
                                        $delete_productStmt = $con->prepare($delete_productQuery);
                                        $delete_productStmt->bindParam(':quantity', $quant);
                                        $delete_productStmt->execute();
                                    }
                                } else {
                                    throw new Exception("Please make sure the product and quantity is selected.");
                                }
                            }
                            echo "<div class='alert alert-success'>Order had been updated.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to update order. Please try again.</div>";
                        }
                        $con->commit();
                    } catch (PDOException $exception) {
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
                </table>
                <table class='table table-hover table-responsive table-bordered'>
                    <th class='col-4 text-center'>Product</th>
                    <th class='col-2 text-center'>Quantity</th>
                    <th class='col-2 text-center'>Price per Piece</th>
                    <th class='col-2 text-center'>Total</th>
                    <th class='col-2 text-center'>Action</th>
                    <?php
                        $od_query = "SELECT orderID, p.productID, name, quantity, price, total
                                FROM order_detail od
                                INNER JOIN products p ON od.productID = p.productID
                                WHERE orderID = :orderID";
                        $od_stmt = $con->prepare($od_query);
                        $od_stmt->bindParam(":orderID", $orderID);
                        $od_stmt->execute();

                        while ($od_row = $od_stmt->fetch(PDO::FETCH_ASSOC)) {
                            
                            $productID = $od_row['productID'];
                            $name = $od_row['name'];
                            $quantity = $od_row['quantity'];
                            $price = $od_row['price'];
                            $productID = htmlspecialchars($productID, ENT_QUOTES);
                            $productName = htmlspecialchars($name, ENT_QUOTES);
                            $productQuantity = htmlspecialchars($quantity, ENT_QUOTES);
                            echo "<tr id='mySelect'>";
                            echo "<td>";
                            echo "<select class='form-select' id='autoSizingSelect' name='productID[]'> ";
                            echo "<option value='' disabled selected>-- Select Product --</option> ";
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
                            echo "<option value='' disabled selected> -- Select Quantity -- </option>";
                            for ($i = 1; $i <= 20; $i++) {
                                $result = $productQuantity == $i ? 'selected' : '';
                                echo "<option value='$i' $result> $i </option>";
                            }
                            echo "</select>";
                            echo "</td>";
                            $productPrice = sprintf('%.2f', $od_row['price']);
                            echo "<td class= 'text-end'>RM $productPrice</td>";
                            $productTotal = sprintf('%.2f', $od_row['total']);
                            echo "<td class= 'text-end'>RM $productTotal</td>";
                            echo "<td class= 'text-center'>";
                            echo "<a href='#' onclick='delete_product({$productID},{$orderID});'  class='btn btn-danger'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }  
                        
                    
                    ?>
                            
                                
                    
                    <tr class='productQuantity'>
                        
                        <td>Optional choice: (Add more product)
                            <select class='form-select' id='autoSizingSelect' name='productID[]'>
                                <option value='' disabled selected>-- Select Product --</option>
                                <?php
                                include 'config/database.php';
                                $select_product_query = "SELECT productID, name FROM products";
                                $select_product_stmt = $con->prepare($select_product_query);
                                $select_product_stmt->execute();
                                while ($productID = $select_product_stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value = '$productID[productID]'> $productID[name] </option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            If have 
                            <select class='form-select' id='autoSizingSelect' name='quantity[]'>
                                <option value='' disabled selected>-- Select Quantity --</option>
                                <?php
                                for ($i = 1; $i <= 20; $i++) {
                                    echo "<option value='$i'> $i </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class= 'text-end'>You need to pay:</td>
                        <?php 
                        $query = "SELECT * FROM orders WHERE orderID = :orderID ";
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(":orderID", $orderID);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $total = sprintf('%.2f', $row['total']);
                        echo "<td class= 'text-end'>RM $total</td>"; ?>
                    </tr>
                

                </table>
                <table class='table table-hover table-responsive table-bordered'>
                    
                </table>

                <div class="d-flex justify-content-center">
                    <button type="button" class="add_one btn btn-info text-light m-2">Add More Product</button>
                    <button type="button" class="delete_one btn btn-warning text-light m-2">Delete Last Product</button>
                    <input type='submit' value='Save Changes' class='btn btn-primary m-2' />
                    <a href='order_read.php' class='btn btn-danger m-2'>Back to Order List</a>
                </div>
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

        function delete_product(productID,orderID) {
            if (confirm('Are you sure?')) {
                window.location = "order_detail_delete.php?productID=" + productID + "&orderID="  + orderID;
            }
        }

        function validation() {
            var product = document.querySelectorAll('.product').length;
            var quantity = document.querySelector('.quantity').value;
            var flag = false;
            var msg = "";
            if (product == 1) {
                if(quantity == 0){
                    flag = true;
                    msg = msg + "Product cannot be deleted!\r\n";
                    msg = msg + "An order must buy at least one product!\r\n";
                    msg = msg + "Please re-enter the quantity other then zero!\r\n";
                }
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