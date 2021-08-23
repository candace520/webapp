<?php
    session_start();
    if ( !isset( $_SESSION['cus_username'] ) ) {
        header( 'Location: index.php?error=restrictedAccess' );
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
        <?php
                include 'menu.php';
            ?>
        <div class = 'container'>
            
                    <div class = 'page-header'>
                            <div class = 'title'><h1>Product List <img src='picture/product/read.png' style='width: 15%;'></div>
                            <div class = 'title2'><a href = 'create.php' class = 'btn btn-primary'>Create         New           Product</h1></a>
                            </div>
                    </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
                    <table class = 'table table-hover table-responsive table-bordered' >
                        <tr style = 'border:none;'>
                            <td class = 'col-10' style = 'border:none;'>
                                <div class = 'input-group rounded'>
                                    <input type = 'text'  class = 'form-control rounded' placeholder = 'Search by product ID OR names...' aria-label = 'Search'
                                    aria-describedby = 'search-addon' id = 'sear' onkeyup = 'myFunction()' name="sear" value="<?php echo (isset($_POST['sear']))?($_POST['sear']):'';?>"/>
                                    <input type='submit' value='Search' id="searchBtn" class='btn' />
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
                    try {
                        if (
                            empty($_POST['sear'])
                        ) {
                            throw new Exception("Please make sure your name or id not empty before searching!");
                        }
                        $sear = "%" . $_POST['sear'] . "%";
                        $query = 'SELECT * FROM products  WHERE name LIKE :sear OR productID LIKE :sear ORDER BY productID DESC';
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(':sear', $sear);
                    }
                        catch (PDOException $exception) {
                            echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                        } catch (Exception $exception) {
                            echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                        }
                
                }    

                  $stmt->execute();  
                    
                    
                    $num = $stmt->rowCount();
                    if ( $num > 0 ) {

                        echo "<table class='table table-hover table-responsive table-bordered' id='myTable'>";
                        echo "<div class ='rowMain'>";
                        echo '<tr>';
                        echo "<th class='col-1 text-center'>ID</th>";
                        echo "<th class='col-1 text-center'>Picture</th>";
                        echo "<th class='col-2 col-lg-1 text-center'>Name</th>";
                        echo "<th class='col-lg-2 text-center'>Action</th>";
                        echo '</tr>';
                        echo '</div>';
                        while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
                            extract( $row );
                            echo '<tr>';
                            echo "<td class ='text-center'>{$productID}</td>";
                            echo "<td class ='text-center'><img src='$row[fileToUpload]' width='100' height='100'></td>";
                            echo "<td class ='text-center'>{$name}</td>";
                            echo '<td class ="text-center">';
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

        function validation() {
                var sear = document.getElementById("sear").value;
                var flag = false;
                var msg = "";
                if (sear == "") {
                    flag = true;
                    msg = msg + "Please make sure your name or id not empty before searching!\r\n";
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
        
        <?php
            include 'footer.php';
        ?>
</html>