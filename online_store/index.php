<?php
    session_start();
    if ( !isset( $_SESSION['cus_username'] ) ) {
        header( 'Location: login.php?error=restrictedAccess' );
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

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <style>
        .row {
            padding: 16px;
            border: 2px solid black;
            border-radius: 12px;

        }

        .container-fluid {
            padding: 20px;

        }

        .container {
            background-color: plum;
        }

        .container {
            border-style: dotted;
            background-color: plum;
        }

        .nav {
            padding-left: 30px;
            font-size: 18px;
            font-weight: normal;
            font-family: sans-serif;
        }



        .header {
            position: relative;
        }

        h1 {
            position: relative;
            bottom: 127px;
            color: black;
        }

        h2 {
            padding-left: 130px;
            color: #F05454;
            font-weight: bold;
            font-size: 50px;
        }

        h5 {
            text-align: center;
            font-size: 50px;
            color: #1597BB;
        }



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

        .ite2 {
            padding-top: 160px;
            padding-left: 160px;
            position: relative;
            bottom: 69px;
            color: black;
            font-size: 40px;
            font-weight: bold;
        }

        p.one {
            border-style: dotted;
            border-radius: 8px;
            border-width: 20%;
            border-left: 20px;
        }

        p {
            color: #726A95;
            font-weight: bold;
            font-size: 30px;
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

        .itesub3 {
            padding-top: 10px;
        }

        .content {
            border-style: dotted;
        }

        .aboutUs {
            background-color: pink;
        }

        h1 {
            position: absolute;
            left: 180px;
            bottom: 157px;
        }


        h3 {
            position: absolute;
            left: 120px;
            bottom: 57px;
        }

        .welcome {
            position: relative;
            color: black;
            font-weight: bold;
            font-size: 40px;
        }

        h6 {
            font-size: 20px;
        }

        .main {
            font-size: 40px;
            text-shadow: 2px 1px white;
        }
        </style>
    </head>

    <body>
        <?php
                include 'menu.php';
                ?><div class="header">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner">

                    <div class="item active">
                        <div class="bg-image" div
                            style="background-image: url('img/shopping.jpeg'); background-repeat: no-repeat;height: 100vh;background-size: cover;">
                            <div class="item1">
                                <div class="itemsub1">
                                    <?php
                                        echo"<h4>HI, $_SESSION[cus_username].</h4>"?>
                                    <br>
                                    <div class="welcome">Welcome to<br><br>
                                        <div class="heading">CANDACE'S <br>ONLINE<br>
                                            STORE.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="bg-image" div
                            style="background-image: url('img/shopping2.jpeg'); background-repeat: no-repeat;height: 100vh;background-size: cover;">
                            <div class="item_active1">
                                <h5><img src="img/team.png" alt="New York" style="width:10%;"> About Us</h5><br>
                                <div class="welcome"><img src="img/story.png" alt="New York" style="width:5%;">OUR
                                    STORY<img src="img/story.png" alt="New York" style="width:5%;"></h3><br>
                                    <br>
                                    <p class="one"><img src="img/happy.png" alt="New York" style="width:5%;"> We love
                                        shopping
                                        <img src="img/happy.png" alt="New York" style="width:5%;"><br><br>
                                        -- We have created a fictional band website. Lorem ipsum dolor sit amet, consectetur
                                        adipiscing elit.-
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="bg-image" div
                            style="background-image: url('img/shopping2.jpeg'); background-repeat: no-repeat;height: 100vh;background-size: cover;">
                            <div class="ite2">
                                <img src="img/new.png" width="8%"> NEW PRODUCTS:
                            </div>
                            <div class="ite1">

                                <div class="itesub1">
                                    <br><img src="img/headphones.png" alt="New York" width="100%">
                                </div>
                                <div class="itesub2">
                                    Earphone is the smartest.
                                </div>
                            </div><br><br><br>
                            <div class="ite3">


                                <div class="itesub4">
                                    Mouse is the sensitiveness.
                                </div>
                                <div class="itesub3">
                                    <br><img src="img/mouse.png" width="30%">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>


            <div class='container-fluid'>
                <div class='aboutUs text-center'>
                    <div class="main">Summary</div>
                </div>
                <?php
                        echo"<div class = 'row'>";
                            echo"<div class = 'title'>TOTAL CALCULATION:</div>";

                            echo"<div class = 'col-sm-4'>";
                                echo"<div class='pic p-1'>";
                                    echo"<img src='img/product.png' style='width: 30%;'>";
                                echo'</div>';
                                include 'config/database.php';

                                $productQuery = 'SELECT * FROM products';
                                $productStmt = $con->prepare( $productQuery );
                                $productStmt->execute();
                                $productTot = $productStmt->rowCount();
                                echo "<a href=product_read.php> <h6 class='p-2 text-dark'>$productTot products</h6> </a>";

                            echo '</div>';
                                echo"<div class = 'col-sm-4'>";
                                    echo"<div class='pic p-1'>";
                                        echo"<img src='img/customer.png' style='width: 30%;'>";
                                    echo'</div>';
                                        include 'config/database.php';

                                    $custQuery = 'SELECT * FROM customer';
                                    $custStmt = $con->prepare( $custQuery );
                                    $custStmt->execute();
                                    $custTot = $custStmt->rowCount();
                                    echo "<a href=customer_read.php> <h6 class='p-2 text-dark'>$custTot customers</h6> </a>";
                                echo '</div>';

                            echo"<div class = 'col-sm-4'>";
                                echo"<div class='pic p-1'>";
                                    echo"<img src='img/box.png' style='width: 30%;'>";
                                echo'</div>';
                                include 'config/database.php';

                                $ordQuery = 'SELECT * FROM orders';
                                $ordStmt = $con->prepare( $ordQuery );
                                $ordStmt->execute();
                                $ordTot = $ordStmt->rowCount();
                                echo "<a href=order_read.php> <h6 class='p-2 text-dark'>$ordTot orders</h6> </a>";
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

                                            echo '<tr>';
                                                echo "<td>$od_row[name]</td>";
                                                echo "<td>$od_row[quantity]</td>";
                                                echo "<td>RM $od_row[price]</td>";
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
                                            echo '<tr>';
                                                echo "<td>$od_row[name]</td>";
                                                echo "<td>$od_row[quantity]</td>";
                                                echo "<td>RM $od_row[amount]</td>";
                                            echo'</tr>';
                                        }
                                        

                                    }
                                echo '</table>';
                            } else {
                                    echo "<div class = 'alert alert-danger'>No records found.</div>";
                                }

                                include 'config/database.php';
                                echo"<div class = 'title'>TOP 5 SELLING PRODUCT:</div>";
                                $query = 'SELECT p.productID, name, quantity, SUM( quantity )quan FROM order_detail od INNER JOIN products p ON od.productID = p.productID GROUP BY productID ORDER BY quan DESC LIMIT 5';
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
                                                echo '<td>';
                                                echo '</td>';
                                            echo '</tr>';
                                        }
                                    echo '</table>';
                                } else {
                                    echo "<div class = 'alert alert-danger'>No records found.</div>";
                                }
                        echo '</div>';

                    ?>
                <br>
                <?php
                        include 'footer.php';
                    ?>

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
    </body>

</html>