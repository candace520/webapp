<?php
session_start();
if ( !isset( $_SESSION['cus_username'] ) ) {
    header( 'Location: login.php?error=restrictedAccess' );
}
?>
<!DOCTYPE HTML>
<html>

<head>
<title>PDO - Create a Record - PHP CRUD Tutorial</title>
<!-- Latest compiled and minified Bootstrap CSS -->
<link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css' rel = 'stylesheet' integrity = 'sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x' crossorigin = 'anonymous'>
<link rel = 'stylesheet' href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>

<style>
.row {
    padding: 16px;
    border-style: inset;
    
}
.container-fluid {
    padding:20px;
}

.container {
    background-color: plum;
}
.container {
    border-style: groove;
    background-color: plum;
}
.nav {
    padding-left: 30px;
    font-size: 18px;
    font-weight: normal;
    font-family: sans-serif;
}

span {
    font-weight: bolder;
    color: black;
    padding: 16px;
    font-size: 20px;
    border-style: outset;
    background-color: pink;
}
.header {
    padding: 80px;
    text-align: center;
    background: plum;
    color: black;
            position: relative;
}
.content {
    border-style: groove;
}
.aboutUs {
    background-color:pink;
}
h1{
    position: absolute;
            left: 180px;
            bottom: 157px;
}
h3{
    position: absolute;
            left: 120px;
            bottom: 57px;
}
</style>
</head><?php
include 'menu.php';
?>
<body>

<div class = 'header'>
<?php
echo"<img src='img/shopping.jpg'>";
echo "<h1 class='text-center text-dark p-5'> Hi, $_SESSION[cus_username].</h1>
     <br><h3 class='text-center text-dark p-5'>Welcome to <span>Candace's Online Store.</span></h3>";
echo'</div>';
?>

</div>

<div class = 'container-fluid'>
<div class = 'aboutUs text-center'>
<h2 class = 'p-2'>About Us</h2>
</div><?php
echo"<div class = 'row'>";
echo'<span >TOTAL CALCULATION:</span>';

echo"<div class = 'col-sm-4'>";
echo"<div class='pic p-1'>";
echo"<img src='img/product.jpg' style='width: 70%;'>";
echo"</div>";
include 'config/database.php';

$productQuery = 'SELECT * FROM products';
$productStmt = $con->prepare( $productQuery );
$productStmt->execute();
$productTot = $productStmt->rowCount();
echo"<h2>Total number of products: $productTot</h2>";
echo '<a href=product_read.php> <h7>CLICK TO SEE THE LISTING OF products</h7> </a>';

echo '</div>';
echo"<div class = 'col-sm-4'>";
echo"<div class='pic p-1'>";
echo"<img src='img/customer.jpg' style='width: 40%;'>";
echo"</div>";
include 'config/database.php';

$custQuery = 'SELECT * FROM customer';
$custStmt = $con->prepare( $custQuery );
$custStmt->execute();
$custTot = $custStmt->rowCount();
echo"<h2>Total number of customer:$custTot</h2>";
echo '<a href=customer_read.php> <h7>CLICK TO SEE THE LISTING OF customer</h7> </a>';
echo '</div>';

echo"<div class = 'col-sm-4'>";
echo"<div class='pic p-1'>";
echo"<img src='img/order.png' style='width: 60%;'>";
echo"</div>";
include 'config/database.php';

$ordQuery = 'SELECT * FROM orders';
$ordStmt = $con->prepare( $ordQuery );
$ordStmt->execute();
$ordTot = $ordStmt->rowCount();
echo"<h2>Total number of orders: $ordTot</h2>";
echo '<a href=order_read.php> <h7>CLICK TO SEE THE LISTING OF order</h7> </a>';
echo '</div>';

include 'config/database.php';
echo'<span>LATEST ORDER:</span>';
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
        echo "<td> 
    $orderID
    </td>";
        echo "<td> 
        $cus_username
        </td>";
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
        echo "<th class = 'col-3'>Price( Per One )</th>";
        $total = 0;
        while ( $od_row = $od_stmt->fetch( PDO::FETCH_ASSOC ) ) {

            echo '<tr>';
            echo "<td>$od_row[name]</td>";
            echo "<td>$od_row[quantity]</td>";
            echo "<td>RM $od_row[price]</td>";
            $quantity = $od_row['quantity'];

        }
        echo '<tr>';

    }
    echo '</table>';
}

include 'config/database.php';
echo'<span>HIGHEST PURCHARSE ORDER:</span>';
$query = "SELECT p.productID, name, SUM( p.price )total, od.orderID FROM order_detail od INNER JOIN products p ON od.productID = p.productID GROUP BY od.orderID ORDER BY total DESC LIMIT 1;
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
        $od_query = "SELECT p.productID, name, quantity, price
            FROM order_detail od
            INNER JOIN products p ON od.productID = p.productID
            WHERE orderID = :orderID";
        $od_stmt = $con->prepare( $od_query );
        $od_stmt->bindParam( ':orderID', $orderID );
        $od_stmt->execute();
        echo "<th class = 'col-3'>Product</th>";
        echo "<th class = 'col-3'>Quantity</th>";
        $total = 0;
        while ( $od_row = $od_stmt->fetch( PDO::FETCH_ASSOC ) ) {
            echo '<tr>';
            echo "<td>$od_row[name]</td>";
            echo "<td>$od_row[quantity]</td>";
            $quantity = $od_row['quantity'];
            $price = $od_row['price'];

        }
        echo'</th></tr>';

    }
    echo '</table>';
} else {
    echo "<div class = 'alert alert-danger'>No records found.</div>";
}

include 'config/database.php';
echo'<span>TOP 5 SELLING PRODUCT:</span>';
$query = 'SELECT p.productID, name, quantity, price, SUM( quantity )quan FROM order_detail od INNER JOIN products p ON od.productID = p.productID GROUP BY productID ORDER BY quan DESC LIMIT 5';
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
        echo "<td> 
                $name
                </td>";
        echo "<td> 
                    $quan
                    </td>";
        echo '<td>';
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
<!-- Band Description -->
<div class = 'container'>
<section class = "w3-container w3-center w3-content' style='max-width:600px">
<h2 class = 'w3-wide'>ONLINE_STORE</h2>
<p class = 'w3-opacity'><i>We love shopping</i></p>

<p class = 'w3-justify' >We have created a fictional band website. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

<article class = 'w3-third'>
<p><span>Earphone</span></p>
<img src = 'img/earphone.jpg' alt = 'Random Name' style = 'width:50%'>
<p><span>Earphone is the smartest.</span></p>
</article>
<article class = 'w3-third'>
<br><p><span>Mouse</span></p>
<img src = 'img/mouse.jpg' alt = 'Random Name' style = 'width:50%'>
<p><span>Mouse is the prettiest.</span></p>
</article>
</section>
<script>
// Automatic Slideshow - change image every 3 seconds
var myIndex = 0;
carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName( 'mySlides' );
    for ( i = 0; i < x.length; i++ ) {
        x[i].style.display = 'none';
    }
    myIndex++;
    if ( myIndex > x.length ) {
        myIndex = 1}
        x[myIndex-1].style.display = 'block';
        setTimeout( carousel, 3000 );
    }
    </script>
    </div>
    </body>
    <?php
    include 'footer.php';
    ?>
    </html>