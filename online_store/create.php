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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="nav">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item ">
                        <a class="nav-link" href="index.php">Home </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="create.php">Create Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customer.php">Create Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </li>
                </ul>
            </div>
    </nav>
    </div>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
        </div>
        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {

            if (
                $_POST['name'] != "" &&  $_POST['nameMalay'] != ""
                && $_POST['description'] != "" && $_POST['price'] != ""
                && $_POST['promotion_price'] != "" && $_POST['manufacture_date'] != ""
                && $_POST['expired_date'] != ""
            ) {
                if (is_numeric($_POST['price']) && is_numeric($_POST['promotion_price'])) {
                    if ($_POST['price'] > 0 && $_POST['promotion_price'] > 0) {
                        if ($_POST['price'] < 1000 && $_POST['promotion_price'] < 1000) {
                            if ($_POST['price'] > $_POST['promotion_price']) {
                                if ($_POST['manufacture_date'] < $_POST['expired_date']) {
                                    $name = $_POST['name'];
                                    $nameMalay = $_POST['nameMalay'];
                                    $description = $_POST['description'];
                                    $price = $_POST['price'];
                                    $promotion_price = $_POST['promotion_price'];
                                    $manufacture_date = $_POST['manufacture_date'];
                                    $expired_date = $_POST['expired_date'];
                                    // include database connection
                                    include 'config/database.php';
                                    try {
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
                                    catch (PDOException $exception) {
                                        die('ERROR: ' . $exception->getMessage());
                                    }
                                } else {
                                    echo "<div class='alert alert-danger'>Please make sure the expired date is later than the manufacture date.</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Please make sure the promotion price must be not bigger than normal price.</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Please make sure the price must be not bigger than RM 1000.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Please make sure the price must be not a negative value.</div>";
                    }
                } else {
                    echo " <div class='alert alert-danger'>Please make sure the price is a number.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Please make sure all fields are not empty</div>";
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
            <a href='index.php' class='btn btn-danger'>Back to read products</a>
        </td>
    </tr>
    </table>
    </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>