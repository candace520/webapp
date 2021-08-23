<?php
    session_start();
    if ( !isset( $_SESSION['cus_username'] ) ) {
        header( 'Location: index.php?error=restrictedAccess' );
    }
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>Home Page</title>
        <!-- Latest compiled and minified Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css' rel='stylesheet'
            integrity='sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x' crossorigin='anonymous'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <style>
        h4 {
            position: relative;
            font-weight: bold;
            font-size: 25px;
        }

        .heading {
            font-weight: bolder;
            color: #F05454;
            font-size: 40px;
        }

        .title {
            font-weight: bolder;
            color: black;
            padding: 16px;
            font-size: 20px;
            border: 2px solid black;
            border-radius: 12px;
        }

        .itemsub1 {
            padding-left: 150px;
            padding-top: 100px;
            text-shadow: 2px 2px #000;
            line-height: 40px;
        }

        .item_active1 {
            padding-left: 40px;
            padding-top: 100px;
            font-size: 30px;
            width: 45%;
            text-shadow: 2px 1px #A0C1B8;
            line-height: 40px;

        }



        .ite1 {
            display: flex;
            padding-left: 160px;

        }

        .ite3 {
            display: flex;
            padding-left: 143px;
        }

        .itesub2 {
            font-size: 30px;
            padding-left: 20px;
            padding-top: 70px;
            border-style: dotted;
            border-radius: 8px;
            border-width: 20%;
            border-left: 20px;
            font-weight: bold;
        }

        .itesub4 {
            font-size: 30px;
            padding-top: 80px;
            border-style: dotted;
            border-radius: 8px;
            border-width: 20%;
            border-left: 20px;
            font-weight: bold;
        }
        .container-fluid_home{
            padding: 30px;
        }
        .main {
            font-size: 40px;
            border: 2px solid black;
            border-radius: 12px;
        }
        .fullrow{
            padding: 20px;
        }
        .welcome {
            position: relative;
            color: black;
            font-weight: bold;
            font-size: 40px;
        }
        .sessionName {
            position: relative;
            font-weight: bold;
            font-size: 25px;
        }
        .total{
            text-decoration: none;
        }
        .total:hover{
            font-weight: bold;
            text-decoration: underline;
        }
        </style>
    </head>

    <body>
        <?php
            include 'menu.php';
        ?>
        <div class="header">
                <div class="item active">
                    <div class="bg-image" style="
                        background-image: url('picture/product/shopping.jpeg');
                        height: 100vh;background-size: cover;
                        ">
                                
                        <div class="item1">
                            <div class="itemsub1">
                                <?php
                                    echo"<div class='sessionName'>HI, $_SESSION[cus_username].</div>"
                                ?>
                                <br>
                                <div class="welcome">Welcome to<br><br>
                                    <div class="heading">CANDACE'S <br>ONLINE<br>
                                                STORE.
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>  
        </div>


            <div class='container-fluid_home'>
                <div class='aboutUs text-center'>
                    <div class="main p-2 fw-bold rounded-pill">Summary</div>
                </div>
                <?php
                    echo"<div class = 'fullrow'>";
                        echo"<div class = 'row'>";
                            echo"<div class = 'title'>TOTAL CALCULATION:</div>";

                            echo"<div class = 'col-sm-4'>";
                                echo"<div class='pic p-1'>";
                                    echo"<a href=product_read.php><img src='picture/product/product.png' style='width: 30%;'>";
                                echo'</div>';
                                include 'config/database.php';

                                $productQuery = 'SELECT * FROM products';
                                $productStmt = $con->prepare( $productQuery );
                                $productStmt->execute();
                                $productTot = $productStmt->rowCount();
                                echo "<a class='total' href=product_read.php> <h6 class='p-2 text-dark'>$productTot products</h6> </a>";

                            echo '</div>';
                                echo"<div class = 'col-sm-4'>";
                                    echo"<div class='pic p-1'>";
                                        echo"<a href=customer_read.php><img src='picture/product/customer.png' style='width: 30%;'>";
                                    echo'</div>';
                                        include 'config/database.php';

                                    $custQuery = 'SELECT * FROM customer';
                                    $custStmt = $con->prepare( $custQuery );
                                    $custStmt->execute();
                                    $custTot = $custStmt->rowCount();
                                    echo "<a class='total' href=customer_read.php> <h6 class='p-2 text-dark'>$custTot customers</h6> </a>";
                                echo '</div>';

                            echo"<div class = 'col-sm-4'>";
                                echo"<div class='pic p-1'>";
                                    echo"<a href=order_read.php><img src='picture/product/box.png' style='width: 30%; a href='order_read.php'>";
                                echo'</div>';
                                include 'config/database.php';

                                $ordQuery = 'SELECT * FROM orders';
                                $ordStmt = $con->prepare( $ordQuery );
                                $ordStmt->execute();
                                $ordTot = $ordStmt->rowCount();
                                echo "<a class='total' href=order_read.php> <h6 class='p-2 text-dark'>$ordTot orders</h6> </a>";
                            echo '</div>';

                            include 'config/database.php';
                            echo"<div class = 'title'>LATEST ORDER:</div>";
                            $query = 'SELECT * FROM orders ORDER BY orderId DESC LIMIT 1';
                            $stmt = $con->prepare( $query );
                            $stmt->execute();
                            $num = $stmt->rowCount();
                            if ( $num > 0 ) {
                                echo "<table class = 'table table-hover table-responsive table-bordered'>";

                                    echo '<tr>';
                                        echo "<th class = 'col-sm-3'>Order ID</th>";
                                        echo "<th class = 'col-sm-3'>Customer Username</th>";
                                    echo '</tr>';

                                    while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
                                        extract( $row );
                                        echo '<tr>';
                                            echo "<td>$orderID</td>";
                                            echo "<td>$cus_username</td>";
                                        echo '</tr>';
                                        $od_query = "SELECT p.productID, name, quantity, price
                                        FROM order_detail od
                                        INNER JOIN products p ON od.productID = p.productID
                                        WHERE orderID = :orderID";
                                        $od_stmt = $con->prepare( $od_query );
                                        $od_stmt->bindParam( ':orderID', $orderID );
                                        $od_stmt->execute();
                                        echo "<th class = 'col-3'>Product</th>";
                                        echo "<th class = 'col-3'>Quantity</th>";
                                        echo "<th class = 'col-3'>Price( Per Piece )</th>";
                                        $total = 0;

                                        while ( $od_row = $od_stmt->fetch( PDO::FETCH_ASSOC ) ) {
                                            $price = sprintf('%.2f', $od_row['price']);
                                            echo '<tr>';
                                                echo "<td>$od_row[name]</td>";
                                                echo "<td>$od_row[quantity]</td>";
                                                echo "<td>RM $price</td>";
                                                $quantity = $od_row['quantity'];
                                            echo '<tr>';
                                        }
                                            

                                    }
                                echo '</table>';
                            }

                            include 'config/database.php';
                            echo"<div class = 'title'>HIGHEST PURCHARSE ORDER:</div>";
                            $query = "SELECT p.productID, name, total, od.orderID FROM order_detail od INNER JOIN products p ON od.productID = p.productID GROUP BY od.orderID ORDER BY total DESC LIMIT 1;
                                    ";
                            $stmt = $con->prepare( $query );
                            $stmt->execute();
                            $num = $stmt->rowCount();
                            if ( $num > 0 ) {
                                echo "<table class = 'table table-hover table-responsive table-bordered'>";

                                    echo '<tr>';
                                    echo '<th>Order ID</th>';
                                    echo '</tr>';

                                    while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
                                        extract( $row );
                                        echo '<tr>';
                                        echo "<td> 
                                            $orderID
                                            </td>";
                                        echo '</td>';
                                        echo '</tr>';
                                        $od_query = "SELECT p.productID, name, quantity, sum(total)amount
                                            FROM order_detail od
                                            INNER JOIN products p ON od.productID = p.productID
                                            WHERE orderID = :orderID";
                                        $od_stmt = $con->prepare( $od_query );
                                        $od_stmt->bindParam( ':orderID', $orderID );
                                        $od_stmt->execute();
                                        echo "<th class = 'col-4'>Product</th>";
                                        echo "<th class = 'col-4'>Quantity</th>";
                                        echo "<th class = 'col-4'>Total amount spend:</th>";
                                        $total = 0;
                                        while ( $od_row = $od_stmt->fetch( PDO::FETCH_ASSOC ) ) {
                                            $amount = sprintf('%.2f', $od_row['amount']);
                                            echo '<tr>';
                                                echo "<td>$od_row[name]</td>";
                                                echo "<td>$od_row[quantity]</td>";
                                                echo "<td>RM $amount</td>";
                                            echo'</tr>';
                                        }
                                        

                                    }
                                echo '</table>';
                            } else {
                                    echo "<div class = 'alert alert-danger'>No records found.</div>";
                                }

                                include 'config/database.php';
                                echo"<div class = 'title'>TOP 5 SELLING PRODUCTS:</div>";
                                $query = 'SELECT p.productID, name, quantity, SUM(  od.quantity )quan FROM order_detail od INNER JOIN products p ON od.productID = p.productID GROUP BY productID ORDER BY quan DESC LIMIT 5';
                                $stmt = $con->prepare( $query );
                                $stmt->execute();
                                $num = $stmt->rowCount();
                                if ( $num > 0 ) {
                                    echo "<table class = 'table table-hover table-responsive table-bordered'>";

                                        echo '<tr>';
                                            echo '<th>NAME</th>';
                                            echo '<th>Total quantity</th>';
                                        echo '</tr>';

                                        while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
                                            extract( $row );
                                            echo '<tr>';
                                                echo "<td>$name</td>";
                                                
                                                echo "<td>$quan</td>";
                                                echo '</td>';
                                            echo '</tr>';
                                        }
                                    echo '</table>';
                                } else {
                                    echo "<div class = 'alert alert-danger'>No records found.</div>";
                                }
                        echo '</div>';
                    echo '</div>';    

                ?>
                        <br>
                        
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
                <script>
                    // Automatic Slideshow - change image every 3 seconds
                    var myIndex = 0;
                    carousel();

                    function carousel() {
                        var i;
                        var x = document.getElementsByClassName('mySlides');
                        for (i = 0; i < x.length; i++) {
                            x[i].style.display = 'none';
                        }
                        myIndex++;
                        if (myIndex > x.length) {
                            myIndex = 1
                        }
                        x[myIndex - 1].style.display = 'block';
                        setTimeout(carousel, 3000);
                    }
                </script>
            </div>
    </body>
        <?php
            include 'footer.php';
        ?>
</html>