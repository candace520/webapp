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
    <style>
    .img1{
        display: flex;
    }
    .img2{
        width: 25%;
    }
    .img3,h6{
        font-weight: bold;
    }
    h5{
        font-weight: bold;
    }
    </style>
    <?php
        include 'menu.php';
    ?>
    <body>
        <div class="container">
            
            <div class="page-header">
                <h1>Update Product <img src='img/edit.png' style='width: 4%;'></h1>
                <h5>**Please refresh this page if you did not see any changes!**</h5>
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
                $photo = $row['fileToUpload'];
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            
            if ($_POST) {
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
                    if ($_POST['price'] < 0 ||$_POST['promotion_price'] < 0) {
                        throw new Exception("<div class='alert alert-danger'>Please make sure the price must be not a negative value.</div>");
                    }

                    if ($_POST['price'] > 1000 || $_POST['promotion_price'] > 1000) {
                        throw new Exception("<div class='alert alert-danger'>Please make sure the price must be not bigger than RM 1000.</div>");
                    }
                    if ($_POST['price'] < $_POST['promotion_price']) {
                        throw new Exception("<div class='alert alert-danger'>Please make sure the promotion price must be not bigger than normal price.</div>");
                    }

                    if ($_POST['manufacture_date'] > $_POST['expired_date']) {
                        throw new Exception("<div class='alert alert-danger'>Please make sure the expired date is later than the manufacture date.</div>");
                    }
                            $target_dir = "img/";
                            $fileToUpload = $_FILES['fileToUpload']['name']; 
                            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);//the name of target file u choose
                            $temp = explode(".", $_FILES['fileToUpload']['name']);
                            $newfilename = $productID . '.' . end($temp);
                            $newtarget_file = $target_dir . $newfilename;
                            $isUploadOK = TRUE;
                            
                            // Check if image file is a actual image or fake image
                            if($fileToUpload!=""){
                                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                                if($check == false) {
                                    echo "Please make sure file uploaded is an image!";
                                        $isUploadOK = 0;
                                }
                                
                                list($width, $height, $type, $attr) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                                    if($width!=$height){
                                        $isUploadOK = false;
                                        echo"<div class='alert alert-danger'>Please make sure the width and height of your image is same!</div>";  
                                    }
                            
                            
                                if ($_FILES["fileToUpload"]["size"] > 5120000) {
                                    echo"<div class='alert alert-danger'>Please make sure the image uploaded is not larger than 512kb!</div>";
                                    $isUploadOK = false;
                                }
                            
                            }
                        
                            if(isset($_POST["delete_pic"])) {
                                $preFile = "img/".$photo;
                                if($photo!="noPic.jpg"){
                                    if (unlink($preFile)) {   
                                        $photo = "noPic.jpg";
                                    } 
                                    else{
                                        echo"<div class='alert alert-danger'>Photo cant been deleted!</div>";
                                    }
                                }
                                else{
                                    echo"<div class='alert alert-danger'>Photo cant been deleted due to no photo uploaded!</div>";
                                }
                            }
                        
                            
                                $query = "UPDATE products SET fileToUpload=:fileToUpload,name=:name, nameMalay=:nameMalay, description=:description,
                                price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date WHERE productID = :productID";
                                $stmt = $con->prepare($query);
                                $name = htmlspecialchars(strip_tags($_POST['name']));
                                $nameMalay = htmlspecialchars(strip_tags($_POST['nameMalay']));
                                $description = htmlspecialchars(strip_tags($_POST['description']));
                                $price = htmlspecialchars(strip_tags($_POST['price']));
                                $promotion_price = htmlspecialchars(strip_tags($_POST['promotion_price']));
                                $manufacture_date = htmlspecialchars(strip_tags($_POST['manufacture_date']));
                                $expired_date = htmlspecialchars(strip_tags($_POST['expired_date']));
                                if($fileToUpload!=""){
                                    $stmt->bindParam(':fileToUpload', $newfilename);
                                }
                                else{
                                    $stmt->bindParam(':fileToUpload', $photo);
                                }
                                $stmt->bindParam(':productID', $productID);
                                $stmt->bindParam(':name', $name);
                                $stmt->bindParam(':nameMalay', $nameMalay);
                                $stmt->bindParam(':description', $description);
                                $stmt->bindParam(':price', $price);
                                $stmt->bindParam(':promotion_price', $promotion_price);
                                $stmt->bindParam(':manufacture_date', $manufacture_date);
                                $stmt->bindParam(':expired_date', $expired_date);
                                
                                if ($stmt->execute()) {
                                    if($fileToUpload!=""){
                                        if ($isUploadOK == false) {
                                            echo"<div class='alert alert-danger'>Sorry, your file was not uploaded!</div>";
                                        } 
                                        else {
                                            if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newtarget_file)) {
                                                echo "Sorry, there was an error uploading your file.";
                                            } 
                                        } 
                                    }
                                    
                                    echo "<div class='alert alert-success'>Product had been updated.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Unable to update pruduct. Please try again.</div>";
                                }
                        
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                
                } catch (Exception $exception) {
                    echo "<div class='alert alert-danger'>".$exception->getMessage()."</div>";
                }
            } ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?productID={$productID}"); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Product ID</td>
                        <td><?php echo htmlspecialchars($productID, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                            <td>Product Image(Optional)</td>
                            <td>
                            <div class="image">
                                <div class="img3">
                                    <h4>
                                        <?php 
                                            if (isset($photo) && !empty($photo)) {
                                                echo "<img src='img/$photo' width='100' height='100'>";
                                            } 
                                            else{
                                                echo "<img src='img/noPic.jpg' width='100' height='100'>";
                                            }
                                                echo"<button type='submit' name='delete_pic'>Delete Picture</button>";
                                        ?>
                                    </h4>
                                </div>
                                <div class="img1">
                                    <div class="img2">
                                        <div class="img3"><?php echo  $photo;?></div>
                                        <input type="file" value=" <?php echo htmlentities($photo, ENT_QUOTES);  ?>"  name="fileToUpload" id="fileToUpload">
                                    </div>
                                </div>
                                
                            </div>
                        </td>
                        </tr>
                    <tr>
                        <td>Name </td>
                        <td><input type='text' name='name' id="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Malay Name </td>
                        <td><input type='text' name='nameMalay' id="Mname" value="<?php echo htmlspecialchars($nameMalay, ENT_QUOTES); ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Description </span></td>
                        <td><textarea name='description' id="desc" class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES); ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Price </td>
                        <td><input type='text' name='price' id="price" value="<?php echo htmlspecialchars($price, ENT_QUOTES); ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Promotion Price </td>
                        <td><input type='text' name='promotion_price' id="proPrice" value="<?php echo htmlspecialchars($promotion_price, ENT_QUOTES); ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Manufacture Date </td>
                        <td><input type='date' name='manufacture_date' id="man_date" value="<?php echo htmlspecialchars($manufacture_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Expired Date </td>
                        <td><input type='date' name='expired_date' id="exp_date" value="<?php echo htmlspecialchars($expired_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                </table>
                <div class="d-flex justify-content-center">
                    <input type='submit' value='Save Changes' class='btn btn-primary m-2' />
                    <a href='product_read.php' class='btn btn-danger m-2'>Back to Product List</a>
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        <script>
            function validation() {
                var name = document.getElementById("name").value;
                var Mname = document.getElementById("Mname").value;
                var desc = document.getElementById("desc").value;
                var price = document.getElementById("price").value;
                var proPrice = document.getElementById("proPrice").value;
                var priceValidation = /^[0-9]*[.]?[0-9]*$/;
                var man_date = document.getElementById("man_date").value;
                var exp_date = document.getElementById("exp_date").value;
                var flag = false;
                var msg = "";
                if (name == "" || Mname == "" || desc == "" || price == "" || proPrice == "" || man_date == "" || exp_date == "") {
                    flag = true;
                    msg = msg + "Please make sure all fields are not empty!\r\n";
                }
                if(isNaN(price)||isNaN(proPrice)){
                    flag = true;
                    msg = msg  + " Please make sure the price is a number.\r\n";
                }
                if (price <= 0 || proPrice <= 0) {
                    flag = true;
                    msg = msg + "Please make sure the price must not be a negative value or zero!\r\n";
                }
                if (price > 1000 || proPrice > 1000) {
                    flag = true;
                    msg = msg + "Please make sure the price is not bigger than RM 1000!\r\n";
                }
                if (parseFloat(price) < parseFloat(proPrice) ) {
                    flag = true;
                    msg = msg + "Please make sure the promotion price must be not bigger than normal price!\r\n";
                }
                if (man_date > exp_date) {
                    flag = true;
                    msg = msg + "Please make sure expired date is later than the manufacture date!\r\n";
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

</html>