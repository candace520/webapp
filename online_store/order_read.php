<?php
    session_start();
    if ( !isset( $_SESSION['cus_username'] ) ) {
        header( 'Location: login.php?error=restrictedAccess' );
    }
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>Order Read</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css' rel='stylesheet'
            integrity='sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x' crossorigin='anonymous'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    </head>
    <style>
        .page-header{
            display: flex;
        }
        .title{
            width: 25%;
        }
        .title2{
           margin-top: 8px;
        }
    </style>

    <body> 
        <?php
        include 'menu.php';
        ?>
        <div class='container'>

            <div class = 'page-header'>
                <div class = 'title'><h1>Read Order</div>
                <div class = 'title2'><a href = 'create.php' class = 'btn btn-primary'>Create         New           Order</h1></a>
                </div>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                    <table class = 'table table-hover table-responsive table-bordered' >
                        <tr style = 'border:none;'>
                            <td class = 'col-10' style = 'border:none;'>
                                <div class = 'input-group rounded'>
                                    <input type = 'text'  class = 'form-control rounded' placeholder = 'Search by order ID OR names...' aria-label = 'Search'
                                    aria-describedby = 'search-addon' id = 'myInput' onkeyup = 'myFunction()' name="sear"/>
                                    <button type = 'button' class = 'btn btn-primary' >
                                    <i class = 'fa fa-search' style = 'font-size:20px;color:white'></i>
                                    </button>
                                </div>
                            </td>
                            
                        </tr>
                    </table>
                </form>
            <?php
                include 'config/database.php';
                $action = isset( $_GET['action'] ) ? $_GET['action'] : '';
                if ( $action == 'deleted' ) {
                    echo "<div class='alert alert-success'>Record was deleted.</div>";
                }
                $query = 'SELECT * FROM orders ORDER BY orderID DESC';
                $stmt = $con->prepare( $query );
                if ($_POST) {
                    $sear = $_POST['sear'];
                    $query = 'SELECT * FROM orders  WHERE orderID LIKE :sear OR cus_username LIKE :sear ORDER BY orderId DESC';
                    $stmt = $con->prepare( $query );
                    $stmt->bindParam(':sear', $sear);
                }   
                
                
                $stmt->execute();
                $num = $stmt->rowCount();
                if ( $num > 0 ) {
                    echo "<table class='table table-hover table-responsive table-bordered' id='myTable'>";

                    echo '<tr>';
                    echo '<th>Order ID</th>';
                    echo '<th>Customer Username</th>';
                    echo '</tr>';

                    while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
                        extract( $row );
                        echo '<tr>';
                        echo "<td>{$orderID}</td>";
                        echo "<td>{$cus_username}</td>";
                        echo '<td>';
                        echo "<a href='order_read_one.php?orderID={$orderID}' class='btn btn-info me-2'>Read</a>";
                        echo "<a href='order_update.php?orderID={$orderID}' class='btn btn-primary me-2'>Edit</a>";
                        echo "<a href='#' onclick='delete_order({$orderID});'  class='btn btn-danger'>Delete</a>";
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo "<div class='alert alert-danger'>No records found.</div>";
                }

            ?>

        </div>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js'
            integrity='sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4' crossorigin='anonymous'>
        </script>
        <script type='text/javascript'>
        function delete_order(orderID) {

            if (confirm('Are you sure?')) {
                window.location = 'order_delete.php?orderID=' + orderID;
            }
        }
        </script>
        
    </body>

</html>