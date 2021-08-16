<?php
    session_start();
    if ( !isset( $_SESSION['cus_username'] ) ) {
        header( 'Location: login.php?error=restrictedAccess' );
    }
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>Read Product </title>
        <link href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css' rel = 'stylesheet' integrity = 'sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x' crossorigin = 'anonymous'>
        <!-- Add icon library -->
        <link rel = 'stylesheet' href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
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
        
        <div class = 'container'>
            <?php
                include 'menu.php';
            ?>
                    <div class = 'page-header'>
                            <div class = 'title'><h1>Product List <img src='picture/img/read.png' style='width: 15%;'></div>
                            <div class = 'title2'><a href = 'create.php' class = 'btn btn-primary'>Create         New           Product</h1></a>
                            </div>
                    </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <table class = 'table table-hover table-responsive table-bordered' >
                    <tr style = 'border:none;'>
                        <td class = 'col-10' style = 'border:none;'>
                            <div class = 'input-group rounded'>
                                <input type = 'text'  class = 'form-control rounded' placeholder = 'Search by product ID OR names...' aria-label = 'Search'
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
                $action = isset( $_GET['action'] ) ? $_GET['action'] : '';
                // if it was redirected from delete.php
                if ( $action == 'productInStock' ) {
                    echo "<div class='alert alert-success'>Product record could not deleted as this product in the order.</div>";
                }

                if ( $action == 'deleted' ) {
                    echo "<div class='alert alert-success'>Product record was deleted.</div>";
                }
                include 'config/database.php';
                $query = 'SELECT * FROM products ORDER BY productID DESC';
                
                $stmt = $con->prepare( $query );
                
                if ($_POST) {
                    $sear = $_POST['sear'];
                    $query = 'SELECT * FROM products  WHERE name LIKE :sear OR productID LIKE :sear ORDER BY productID DESC';
                    $stmt = $con->prepare( $query );
                    $stmt->bindParam(':sear', $sear);
                }    
                
                    

                  $stmt->execute();  
                    
                    
                    $num = $stmt->rowCount();
                    if ( $num > 0 ) {

                        echo "<table class='table table-hover table-responsive table-bordered' id='myTable'>";
                        echo "<div class ='rowMain'>";
                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Product Image</th>';
                        echo '<th>Name</th>';
                        echo '<th>Action</th>';
                        echo '</tr>';
                        echo '</div>';
                        while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
                            extract( $row );
                            echo '<tr>';
                            echo "<td>{$productID}</td>";
                            echo "<td><img src='$row[fileToUpload]' width='100' height='100'></td>";
                            echo "<td>{$name}</td>";
                            echo '<td>';
                            echo "<a href='product_read_one.php?productID={$productID}' class='btn btn-info me-2'>Details</a>";
                            echo "<a href='product_update.php?productID={$productID}' class='btn btn-primary me-2'>Edit</a>";
                            echo "<a href='#' onclick='delete_product({$productID});'  class='btn btn-danger'>Delete</a>";
                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        echo "<div class='alert alert-danger'>No records found.</div>";
                    }
                
            ?>
            <?php
                include 'footer.php';
            ?>
        </div>
        
        <script src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js' integrity = 'sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4' crossorigin = 'anonymous'></script>
        <script type = 'text/javascript'>

        function delete_product( productID ) {

            if ( confirm( 'Are you sure?' ) ) {
                window.location = 'product_delete.php?productID=' + productID;
            }
        }
        </script>
        <script>

        /*function myFunction() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById( 'myInput' );
            filter = input.value.toUpperCase();
            table = document.getElementById( 'myTable' );
            tr = table.getElementsByTagName( 'tr' );
            for ( i = 0; i < tr.length; i++ ) {
                td = tr[i].getElementsByTagName( 'td' )[1];
                if ( td ) {
                    txtValue = td.textContent || td.innerText;
                    if ( txtValue.toUpperCase().indexOf( filter ) > -1 ) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }

            }
        }*/
        </script>
    </body>

</html>