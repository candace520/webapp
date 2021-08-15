<?php
    session_start();
    if (!isset($_SESSION["cus_username"])) {
        header("Location: login.php?error=restrictedAccess");
    }
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>Create Product</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <style>
            td{
                font-size: 15px;
            }
            .instruction{
                font-size: 20px;
            }

            span {
                font-weight: bolder;
                color: white;
            }
            .container {
                width: 70%;
            }
        </style>
    </head>

    <body>
        

        <div class="container">
            <?php
                include 'menu.php';
            ?>
            <div class="page-header">
                <h1>Create Product <img src='picture/img/create.png' style='width: 8%;'></h1>
                <h6>**Please fill in all fields of relevant data!(except Optional)</h6>
            </div>
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
                        if ($_POST['price'] < 0 || $_POST['promotion_price'] < 0) {
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
                        
                            $target_dir = "picture/img/";
                            $fileToUpload = $_FILES['fileToUpload']['name']; 
                            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);//the name of target file u choose
                            $isUploadOK = TRUE;
                            
                            // Check if image file is a actual image or fake image
                            if($fileToUpload!=""){
                                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                                if($check == false) {
                                    echo "Please make sure the file uploaded is an image!";
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
                        $name = ucwords($_POST['name']);
                        $nameMalay = ucwords($_POST['nameMalay']);
                        $description = ucfirst($_POST['description']);
                        $price = $_POST['price'];
                        $promotion_price = $_POST['promotion_price'];
                        $manufacture_date = $_POST['manufacture_date'];
                        $expired_date = $_POST['expired_date'];

                        // insert query
                        $query = "INSERT INTO products SET name=:name,nameMalay=:nameMalay,description=:description, price=:price, promotion_price=:promotion_price,manufacture_date=:manufacture_date,expired_date=:expired_date,
                        created=:created";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':nameMalay', $nameMalay);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':promotion_price', $promotion_price);
                        $stmt->bindParam(':manufacture_date', $manufacture_date);
                        $stmt->bindParam(':expired_date', $expired_date);
                        $created = date('Y-m-d H:i:s');
                        $stmt->bindParam(':created', $created);
                       
                        // Execute the query
                        if ($stmt->execute()) {
                            $lastID = $con->lastInsertId();
                            $temp = explode(".", $_FILES["fileToUpload"]["name"]);
                            $newfilename = $lastID .'.' . end($temp);
                            $newtarget_file = $target_dir  . $newfilename;
                            $default = "picture/img/noPic.jpg";
                                    $insertcuQuery = "UPDATE products SET fileToUpload=:fileToUpload WHERE productID = :productID";
                                    $insertcuStmt = $con->prepare($insertcuQuery); 
                                    $insertcuStmt->bindParam(':productID', $lastID);
                                    if($fileToUpload!=""){
                                        $insertcuStmt->bindParam(':fileToUpload', $newtarget_file);
                                    }
                                    else{
                                    $insertcuStmt->bindParam(':fileToUpload', $default);
                                    }
                                    if($insertcuStmt->execute()){
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
                                    echo "<div class='alert alert-success'>Product had been created.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to create product.</div>";
                        }
                        }
                    }
                
                    catch (PDOException $exception) {
                        echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                    } catch (Exception $exception) {
                        echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                    }
                }
               
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">

                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Product Image(Optional)</td>
                        <td>
                            <input type="file" name="fileToUpload" id="fileToUpload" value="<?php echo (isset($_FILES['fileToUpload']['name']))?($_FILES['fileToUpload']['name']):'';?>" >
                        </td>
                    </tr>
                    <tr>

                        <td id="leftrow">Name</td>
                        <td>
                            <input type='text' name='name' placeholder="Enter name" class='form-control' id="name"
                            
                            value="<?php echo (isset($_POST['name']))?($_POST['name']):'';?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Malay Name</td>
                        <td>
                            <input type='text' name='nameMalay' placeholder="Enter malay name " class='form-control' id="Mname"value="<?php echo (isset($_POST['nameMalay']))?($_POST['nameMalay']):'';?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><textarea name='description' id="desc" placeholder="Enter description" class='form-control' ><?php echo (isset($_POST['description']))?($_POST['description']):'';?></textarea></td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type='text' name='price' id="price" placeholder="xx.xx" class='form-control' value="<?php echo (isset($_POST['price']))?($_POST['price']):'';?>"/>
                            </div>
                        </td>
                    </tr>
                    

                    <tr>
                        <td>Promotion Price</td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type='text' name='promotion_price' id="proPrice" placeholder="xx.xx" class='form-control' value="<?php echo (isset($_POST['promotion_price']))?($_POST['promotion_price']):'';?>" />
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Manufacture Date</td>
                            <td><input type='date' id="man_date" name='manufacture_date' class='form-control' value="<?php echo (isset($_POST['manufacture_date']))?($_POST['manufacture_date']):'';?>"/></td>
                    </tr>
                    <tr>   
                        <td>Expired Date</td>
                        <td><input type='date' name='expired_date' class='form-control' id="exp_date" value="<?php echo (isset($_POST['expired_date']))?($_POST['expired_date']):'';?>"/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' name="submit" value='Save' class='btn btn-primary'/>
                            <a href='product_read.php' class='btn btn-danger'>Back to Product List</a>
                        </td>
                    </tr>
                </table>
            </form>
                <?php
                include 'footer.php';
                ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        <script>
             function validateForm() {
                var name = document.getElementById("name").value;
                var Mname = document.getElementById("Mname").value;
                var price = document.getElementById("price").value;
                var desc = document.getElementById("desc").value;
                var proPrice = document.getElementById("proPrice").value;
                var man_date = document.getElementById("man_date").value;
                var exp_date = document.getElementById("exp_date").value;
                var flag = false;
                var msg = "";
                if (name == ""||Mname == "" ||desc==""||proPrice ==""|| price == ""||man_date == ""|| exp_date == "") {
                    flag = true;
                    msg = msg + "Please make sure all fields are not empty!\r\n";
                }
                else if(isNaN(price)||isNaN(proPrice)){
                    flag = true;
                    msg = msg  + " Please make sure the price is a number.\r\n";
                }
                else if (proPrice > 1000|| price > 1000) {
                    flag = true;
                    msg = msg + "Please make sure the price must be not bigger than RM 1000.\r\n";
                }
                else if (proPrice < 0 || price < 0) {
                    flag = true;
                    msg = msg + "Please make sure the price must be not a negative value.\r\n";
                }
                
                else if (parseFloat(price) < parseFloat(proPrice) ) {
                    flag = true;
                    
                    msg = msg + "Please make sure the promotion price must be not bigger than normal price.\r\n";
                }
                else if (exp_date < man_date ) {
                    flag = true;
                    msg = msg + "Please make sure the expired date is later than the manufacture date.\r\n";
                }
                if (flag == true) {
                    alert(msg);
                    return false;
                } else {
                    return true;
                }
            }
        </script>
    </body>

</html>