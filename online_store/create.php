<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        #leftrow {
            width: 30%;

        }

        .container {
            width: 50%;
        }

        .nav {
            padding-left: 30px;
            font-size: 18px;
            font-weight: normal;
            font-family: sans-serif;
        }

        span {
            font-weight: bolder;
            color: white;
        }
    </style>
</head>

<body>
    <?php
    include 'menu.php';
    ?>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
        </div>
        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            include 'config/database.php';
            try {
                if (
                    empty($_POST['name'])  ||  empty($_POST['nameMalay'])
                    || empty($_POST['description']) || empty($_POST['price'])
                    || empty($_POST['promotion_price'])  || empty($_POST['manufacture_date'])
                    || empty($_POST['expired_date'])
                ) {
                    throw new Exception("Please make sure all fields are not empty");
                }
                if (!is_numeric($_POST['price']) || !is_numeric($_POST['promotion_price'])) {
                    throw new Exception(" <div class='alert alert-danger'>Please make sure the price is a number.</div>");
                }
                if ($_POST['price'] < 0 && $_POST['promotion_price'] < 0) {
                    throw new Exception("<div class='alert alert-danger'>Please make sure the price must be not a negative value.</div>");
                }

                if ($_POST['price'] > 1000 && $_POST['promotion_price'] > 1000) {
                    throw new Exception("<div class='alert alert-danger'>Please make sure the price must be not bigger than RM 1000.</div>");
                }
                if ($_POST['price'] < $_POST['promotion_price']) {
                    throw new Exception("<div class='alert alert-danger'>Please make sure the promotion price must be not bigger than normal price.</div>");
                }

                if ($_POST['manufacture_date'] > $_POST['expired_date']) {
                    throw new Exception("<div class='alert alert-danger'>Please make sure the expired date is later than the manufacture date.</div>");
                }

                $name = $_POST['name'];
                $nameMalay = $_POST['nameMalay'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $promotion_price = $_POST['promotion_price'];
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = $_POST['expired_date'];
                // include database connection

                // insert query
                $query = "INSERT INTO products SET name=:name,nameMalay=:nameMalay,description=:description, price=:price, promotion_price=:promotion_price,manufacture_date=:manufacture_date,expired_date=:expired_date,
        created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query);
                // posted values

                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':nameMalay', $nameMalay);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotion_price', $promotion_price);
                $stmt->bindParam(':manufacture_date', $manufacture_date);
                $stmt->bindParam(':expired_date', $expired_date);
                // specify when this record was inserted to the database
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);
                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            }
            // show error
            //for database 'PDO'
            catch (PDOException $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <table class='table table-hover table-responsive table-bordered'>
                <tr>

                    <td id="leftrow">Name</td>
                    <td>
                        <input type='text' name='name' placeholder="Enter name" class='form-control' />
                    </td>
                </tr>
                <tr>
                    <td>Malay Name</td>
                    <td>
                        <input type='text' name='nameMalay' placeholder="Enter malay name " class='form-control' />
                    </td>
                </tr>
                <tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' id="uname" placeholder="Enter description" class='form-control' /></textarea></td>
                </tr>
                <td> Price</td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">RM</span>
                        <input type='text' name='price' id="uname" placeholder="xx.xx" class='form-control' />
                </td>
                </tr>
                </tr>
    </div>


    <td>Promotion Price</td>
    <td>
        <div class="input-group">
            <span class="input-group-text">RM</span>
            <input type='text' name='promotion_price' id="uname" placeholder="xx.xx" class='form-control' />

    </td>
    </tr>
    </tr>
    </div>

    <td>Manufacture Date</td>
    <td><input type='date' name='manufacture_date' class='form-control' /></td>
    </tr>
    </tr>

    <td>Expired Date</td>
    <td><input type='date' name='expired_date' class='form-control' /></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type='submit' value='Save' class='btn btn-primary' />
            <a href='product_read.php' class='btn btn-danger'>Views Product</a>
        </td>
    </tr>
    </table>
    </form>
    <?php
    include 'footer.php';
    ?>


    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>