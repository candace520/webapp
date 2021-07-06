<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Update Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
<?php
        include 'menu.php';
        ?>
    <div class="container">
        
        <div class="page-header">
            <h1>Update Product</h1>
        </div>
        <?php
        $productID = isset($_GET['productID']) ? $_GET['productID'] : die('ERROR: Product record not found.');

        include 'config/database.php';
        try {
            $query = "SELECT * FROM products WHERE productID = :productID ";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":productID", $productID);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $productID = $row['productID'];
            $name = $row['name'];
            $nameMalay = $row['nameMalay'];
            $description = $row['description'];
            $price = $row['price'];
            $promotion_price = $row['promotion_price'];
            $manufacture_date = $row['manufacture_date'];
            $expired_date = $row['expired_date'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        if ($_POST) {
            try {
                if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price']) || empty($_POST['manufacture_date']) || empty($_POST['expired_date'])) {
                    throw new Exception("Make sure all fields are not empty");
                }
                if (!is_numeric($_POST['price']) || !is_numeric($_POST['promotion_price'])) {
                    throw new Exception("Please make sure the price is a number");
                }
                if ($_POST['price'] < 0 || $_POST['promotion_price'] < 0) {
                    throw new Exception("Please make sure the price must be not a negative value");
                }
                if ($_POST['price'] > 1000 || $_POST['promotion_price'] > 1000) {
                    throw new Exception("Please make sure the price is not bigger than RM 1000.");
                }
                if ($_POST['price'] < $_POST['promotion_price']) {
                    throw new Exception("Promotion price cannot bigger than normal price.");
                }
                if ($_POST['manufacture_date'] > $_POST['expired_date']) {
                    throw new Exception("Please make sure expired date is late than the manufacture date.");
                }
                $query = "UPDATE products SET name=:name, nameMalay=:nameMalay, description=:description,
                         price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date WHERE productID = :productID";
                $stmt = $con->prepare($query);
                $name = htmlspecialchars(strip_tags($_POST['name']));
                $nameMalay = htmlspecialchars(strip_tags($_POST['nameMalay']));
                $description = htmlspecialchars(strip_tags($_POST['description']));
                $price = htmlspecialchars(strip_tags($_POST['price']));
                $promotion_price = htmlspecialchars(strip_tags($_POST['promotion_price']));
                $manufacture_date = htmlspecialchars(strip_tags($_POST['manufacture_date']));
                $expired_date = htmlspecialchars(strip_tags($_POST['expired_date']));
                $stmt->bindParam(':productID', $productID);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':name_malay', $name_malay);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotion_price', $promotion_price);
                $stmt->bindParam(':manufacture_date', $manufacture_date);
                $stmt->bindParam(':expired_date', $expired_date);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>".$exception->getMessage()."</div>";
            }
        } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?productID={$productID}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Product ID</td>
                    <td><?php echo htmlspecialchars($productID, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Malay Name</td>
                    <td><input type='text' name='nameMalay' value="<?php echo htmlspecialchars($nameMalay, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES); ?></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' value="<?php echo htmlspecialchars($promotion_price, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' value="<?php echo htmlspecialchars($manufacture_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' value="<?php echo htmlspecialchars($expired_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read product</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>