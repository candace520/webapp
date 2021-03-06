<?php
    session_start();
    if (!isset($_SESSION["cus_username"])) {
        header("Location: index.php?error=restrictedAccess");
    }
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>Update Order</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        
    </head>
        
    <body>
                <?php
                    include 'menu.php';
                ?>
            <div class="container">
                
                <div class="page-header">
                    <h1>Update Order  <img src='picture/product/edit.png' style='width: 4%;'></h1>
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
                            if(isset($_POST['newproductID'])||isset($_POST['newquantity'])){
                                if (empty($_POST['newproductID'])) {
                                    throw new Exception("Please select at least one product!");
                                }
                                if (empty($_POST['newquantity'])) {
                                    throw new Exception("Please select the quantity of the product you chose!");
                                }
                            }
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
                            if (isset($_POST['newproductID'])||isset($_POST['newquantity'])) {
                                for ($i = 0; $i < count($_POST['newproductID']); $i++) {
                                    $newproductPrice = htmlspecialchars(strip_tags($_POST['newproductID'][$i]));
                                    $selectPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                                    $selectPriceStmt = $con->prepare($selectPriceQuery);
                                
                                    $selectPriceStmt->bindParam(':productID', $newproductPrice);
                                    
                                    $selectPriceStmt->execute();
                                    while ($selectPriceRow = $selectPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                                        $productPrice = $selectPriceRow['price'];
                                        $newquant = htmlspecialchars(strip_tags($_POST['newquantity'][$i]));
                                        $newproductTotal = $productPrice * $newquant;
                                        $total += $newproductTotal;
                                    }
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
                                    } else {
                                        throw new Exception("Please make sure the product and quantity is selected.");
                                    }
                                }
                                if (isset($_POST['newproductID'])||isset($_POST['newquantity'])) {
                                    for ($i = 0; $i < count($_POST['newproductID']); $i++) {
                                        $getPrice = htmlspecialchars(strip_tags($_POST['newproductID'][$i]));
                                        $getPriceQuery = "SELECT price FROM products WHERE productID=:productID";
                                        $getPriceStmt = $con->prepare($getPriceQuery);
                                        $getPriceStmt->bindParam(':productID', $getPrice);
                                        $getPriceStmt->execute();
                                        while ($getPriceRow = $getPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                                            $productPrice = $getPriceRow['price'];
                                            $newquant = htmlspecialchars(strip_tags($_POST['newquantity'][$i]));
                                            $newtotal = $productPrice * $newquant;
                                        }

                                        $newproduct = htmlspecialchars(strip_tags($_POST['newproductID'][$i]));
                                        $newquant = htmlspecialchars(strip_tags($_POST['newquantity'][$i]));
                                        if ($newproduct != '' && $newquant != '') {
                                            $insertodQuery = "INSERT INTO order_detail SET orderID=:orderID, productID=:productID, quantity=:quantity, total=:total";
                                            $insertodStmt = $con->prepare($insertodQuery);
                                            $insertodStmt->bindParam(':orderID', $orderID);
                                            $insertodStmt->bindParam(':productID', $newproduct);
                                            $insertodStmt->bindParam(':quantity', $newquant);
                                            $insertodStmt->bindParam(':total', $newtotal);
                                            $insertodStmt->execute();
                                        } else {
                                            throw new Exception("Please make sure the product and quantity is selected.");
                                        }
                                    }
                                }
                    
                                echo "<div class='alert alert-success'>Order ID $orderID had been updated.</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Unable to update order ID $orderID. Please try again.</div>";
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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?orderID={$orderID}"); ?>" method="post" onsubmit="return validation()">
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
                        <th class='col-1 text-center'>Action</th>
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
                                echo "<tr class='product'>";
                                echo "<td>";
                                echo "<select class='productID form-select' id='autoSizingSelect' name='productID[]'>";
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
                                echo "<select class='quantity form-select' id='autoSizingSelect' name='quantity[]'>";
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
                                echo "<a href='#' onclick='delete_product({$productID},{$orderID},{$productTotal});'  class='btn btn-danger'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }  
                            
                        
                        ?>
                                
                                    
                        
                        <tr class='productQuantity'>
                            
                            <td>Optional choice: (Add more product)
                                <select class='newproductID form-select' id='autoSizingSelect' name='newproductID[]'>
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
                                <select class='newquantity form-select' id='autoSizingSelect' name='newquantity[]'>
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
                
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
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
                    else{
                        alert("You could not delete the last product due to only one optional product lefted! ");
                    }
                }
            }, false);

            function delete_product(productID,orderID,$productTotal) {
                if (confirm('Are you sure?')) {
                    window.location = "order_detail_delete.php?productID=" + productID + "&productTotal="  + $productTotal+ "&orderID="  + orderID;
                }
            }
            function validation() {
                var productID = document.querySelector('.newproductID').value;
                var quantity = document.querySelector('.newquantity').value;
                var flag = false;
                var msg = "";
                if (productID == "" && quantity != "") {
                    flag = true;
                    msg = msg + "Please select at least one product!";
                }
                if (quantity == "" && quantity != "") {
                    flag = true;
                    msg = msg + "Please select the quantity of the product you chose!";
                }
                if (flag == true) {
                    alert(msg);
                    return false;
                }else{
                    return true;
                }
            }
        </script>
    </body><br>
            <?php
                include 'footer.php';
            ?>
</html>