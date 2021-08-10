<?php
    session_start();
    if (!isset($_SESSION["cus_username"])) {
        header("Location: login.php?error=restrictedAccess");
    }
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>PDO - Product Read One Record</title>
        <!-- Latest compiled and minified Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
        <?php
        include 'menu.php';
        ?>
        <!-- container -->
        <div class="container">
            <div class="page-header">
                <h1>Product Details <img src='img/detail.png' style='width: 3%;'></h1>
            </div>

            <!-- PHP read one record will be here -->
            <?php
                // get passed parameter value, in this case, the record ID
                // isset() is a PHP function used to verify if a value is there or not
                $productID = isset($_GET['productID']) ? $_GET['productID'] : die('ERROR: Record ID not found.');
                /*if (isset($_GET['id'])){
                $id = $_GET['id'];
                } else {
                    die('ERROR: Record ID not found.');
                }*/


                //include database connection
                include 'config/database.php';

                // read current record's data
                try {
                    // prepare select query
                    $query = "SELECT * FROM products WHERE productID = :productID ";
                    $stmt = $con->prepare($query);

                    // Bind the parameter
                    $stmt->bindParam(":productID", $productID);

                    // execute our query
                    $stmt->execute();

                    // store retrieved row to a variable
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    // values to fill up our form
                    $name = $row['name'];
                    $description = $row['description'];
                    $nameMalay = $row['nameMalay'];
                    $price = $row['price'];
                    $promotion_price = $row['promotion_price'];
                    $manufacture_date = $row['manufacture_date'];
                    $expired_date = $row['expired_date'];
                    // shorter way to do that is extract($row)
                }

                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            ?>


            <!-- HTML read one record table will be here -->
            <!--we have our html table here where the record will be displayed-->
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><?php echo htmlspecialchars($price, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Malay name</td>
                    <td><?php echo htmlspecialchars($nameMalay, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><?php echo htmlspecialchars($promotion_price, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><?php echo htmlspecialchars($manufacture_date, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><?php echo htmlspecialchars($expired_date, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <a href='product_read.php' class='btn btn-danger'>Back to Product List</a>
                    </td>
                </tr>
            </table>


        </div> <!-- end .container -->

    </body>

</html>