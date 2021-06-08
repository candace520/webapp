Skip to content
Search or jump to…

Pull requests
Issues
Marketplace
Explore
 
@candace520 
CLaire1903
/
webapp
2
00
Code
Issues
Pull requests
Actions
Projects
Wiki
Security
Insights
webapp/online_store/product.php /
@CLaire1903
CLaire1903 product edited
Latest commit a6f3998 1 hour ago
 History
 1 contributor
150 lines (147 sloc)  8.09 KB
  
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
            <div class="container-fluid">
                <h3 class="text-light">Claire_Store</h3>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-around" id="navbarToggle">
                    <div>
                        <ul class="navbar-nav">
                            <li class="nav-item ">
                                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="product.php">Create Product</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="customer.php">Create Customer</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="contact.php">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
        </nav>
        <div class="page-header">
            <h4 class="p-1">Create Product</h4>
        </div>
        <?php
        if ($_POST) {
            if ($_POST['name'] != "" && $_POST['description'] != "" && $_POST['price'] != "" && $_POST['manufacture_date'] != "" && $_POST['expired_date'] != "") {
                if (is_numeric($_POST['price']) && is_numeric($_POST['promotion_price'])) {
                    if ($_POST['price'] > 0 && $_POST['promotion_price'] > 0) {
                        if ($_POST['price'] < 1000 && $_POST['promotion_price'] < 1000) {
                            if ($_POST['price'] > $_POST['promotion_price']) {
                                if ($_POST['manufacture_date'] < $_POST['expired_date']) {
                                    include 'config/database.php';
                                    try {
                                        $query = "INSERT INTO products SET name=:name, name_malay=:name_malay, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, created=:created";
                                        $stmt = $con->prepare($query);
                                        $name = $_POST['name'];
                                        $name_malay = $_POST['name_malay'];
                                        $description = $_POST['description'];
                                        $price = $_POST['price'];
                                        $promotion_price = $_POST['promotion_price'];
                                        $manufacture_date = $_POST['manufacture_date'];
                                        $expired_date = $_POST['expired_date'];
                                        $stmt->bindParam(':name', $name);
                                        $stmt->bindParam(':name_malay', $name_malay);
                                        $stmt->bindParam(':description', $description);
                                        $stmt->bindParam(':price', $price);
                                        $stmt->bindParam(':promotion_price', $promotion_price);
                                        $stmt->bindParam(':manufacture_date', $manufacture_date);
                                        $stmt->bindParam(':expired_date', $expired_date);
                                        $created = date('Y-m-d H:i:s');
                                        $stmt->bindParam(':created', $created);
                                        if ($stmt->execute()) {
                                            echo "<div class='alert alert-success'>Record was saved.</div>";
                                        } else {
                                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                                        }
                                    } catch (PDOException $exception) {
                                        die('ERROR: ' . $exception->getMessage());
                                    }
                                } else {
                                    echo "<div class='alert alert-danger'>Please make sure expired date is late than the manufacture date.</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Promotion price cannot bigger than normal price.</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Please make sure the price is not bigger than RM 1000.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Please make sure the price must be not a negative value.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Please make sure the price is a number.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Make sure all fields are not empty</div>";
            }
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Name_Malay</td>
                    <td><input type='text' name='name_malay' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea type='text' name='description' class='form-control' rows="3"></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' class='form-control datepicker' /></td>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>
© 2021 GitHub, Inc.
Terms
Privacy
Security
Status
Docs
Contact GitHub
Pricing
API
Training
Blog
About
