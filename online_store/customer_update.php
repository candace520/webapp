<?php
    session_start();
    if (!isset($_SESSION["cus_username"])) {
        header("Location: index.php?error=restrictedAccess");
    }
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Update Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <style>
    .container {
        width: 100%;
    }

    .img1 {
        display: flex;
    }

    .img2 {
        width: 25%;
    }

    .img3 {
        font-weight: bold;
    }

    radio {
        display: flex;
    }

    #delPicbtn {
        display: none;
    }

    #changeWindow {
        display: flex;
    }

    .cancelPic {
        width: 100px;
        height: 25px;
        background-color: red;
    }

    .changePic {
        width: 150px;
    }
    </style>
</head>


<body>
    <?php
                include 'menu.php';
            ?>
    <div class="container">

        <div class="page-header">
            <h1>Update Customer <img src='picture/product/edit.png' style='width: 4%;'></h1>
            <h5>**Please refresh this page if you did not see any changes!**</h5>
        </div>
        <?php

                // get passed parameter value, in this case, the record ID
                // isset() is a PHP function used to verify if a value is there or not
                $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

                //include database connection
                include 'config/database.php';

                // read current record's data
                try {

                    // prepare select query
                    $query = "SELECT * FROM customer WHERE id = ? LIMIT 0,1";
                    $stmt = $con->prepare($query);

                    // this is the first question mark
                    $stmt->bindParam(1, $id);

                    // execute our query
                    $stmt->execute();

                    // store retrieved row to a variable
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    // values to fill up our form
                    $cus_username = $row['cus_username'];
                    $gender = $row['gender'];
                    $accountstatus = $row['accountstatus'];
                    $password = $row['password'];
                    $confPass = $row['confPass'];
                    $firstname = $row['firstname'];
                    $lastname = $row['lastname'];
                    $dateofbirth = $row['dateofbirth'];
                    $registrationdatetime = $row['registrationdatetime'];
                    $photo = $row['fileToUpload'];
                }

                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }

                ?>

        <?php
                if ($_POST) {
                    try {
                        if (
                            empty($_POST['cus_username']) ||   empty($_POST['password'])
                            ||  empty($_POST['firstname'])  ||  empty($_POST['lastname'])
                            ||  empty($_POST['gender'])  || empty($_POST['dateofbirth'])
                            ||  empty($_POST['accountstatus'])
                            ||  empty($_POST['confPass'])
                        ) {
                            throw new Exception("<div class='alert alert-danger'>Please make sure all fields are not empty</div>");
                        }
                        if (15 < strlen($_POST['cus_username']) || strlen($_POST['cus_username']) < 6 || (strpos($_POST['cus_username'], ' ') !== false)) {
                            throw new Exception("Please make sure your name should be  6- 15 characters and no space included!)");
                        }
                        if ($_POST['password'] != $_POST['confPass']) {
                            throw new Exception("<div class='alert alert-danger'>Please make sure your password same as confirm password</div>");
                        }
                        if (!preg_match('/[A-Za-z]/', $_POST['password']) || !preg_match('/[0-9]/', $_POST['password'])) {
                            throw new Exception("<div class='alert alert-danger'>Please make sure your password contains numbers and alphabets</div>");
                        }
                        if (strlen($_POST["password"]) < 8) {
                            throw new Exception("<div class='alert alert-danger'>Please make sure your password contains 8 characters</div>");
                        }
                        $date1 = "Y";
                        $diff = abs(strtotime($date1) - strtotime($_POST['dateofbirth']));
                        $years = floor($diff / (365 * 60 * 60 * 24));
                        if ($years < 18) {
                            throw new Exception("<div class='alert alert-danger'>Please make sure your ages are 18 years old and above</div>");
                        }
                        $target_dir = "picture/customer/";
                        $fileToUpload = $_FILES['fileToUpload']['name']; 
                        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);//the name of target file u choose
                        $temp = explode(".", $_FILES['fileToUpload']['name']);
                        $newfilename = $id . '_' . round(microtime(true)) . '.' .  end($temp);
                        $newtarget_file = $target_dir . $newfilename;
                        $isUploadOK = TRUE;
                        
                        // Check if image file is a actual image or fake image
                        if($fileToUpload!=""){
                                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                            if($check == false) {
                                throw new Exception("Please make sure the file uploaded is an image!");
                                    $isUploadOK = 0;
                            }
                            
                            list($width, $height, $type, $attr) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                                if($width!=$height){
                                    $isUploadOK = false;
                                    throw new Exception("Please make sure the width and height of your image is same!"); 
                                }
                        
                        
                            if ($_FILES["fileToUpload"]["size"] > 512000) {
                                throw new Exception("Please make sure the image uploaded is not larger than 512kb!");
                                $isUploadOK = false;
                            }
                        
                        }

                        if(isset($_POST["delete_pic"])) {
                                if (unlink($photo)){
                                    $photo = "picture/customer/noPic.jpg";
                                }
                        }
                        else{
                            if($fileToUpload!=""){
                                $photo = $newtarget_file;
                            }
                            
                        }
                        if($newtarget_file!=""){
                            $profile = "fileToUpload=:fileToUpload";
                        }
                                
                            $query = "UPDATE customer
                            SET fileToUpload=:fileToUpload,cus_username=:cus_username,gender=:gender,accountstatus=:accountstatus,password=:password,
                            confPass=:confPass,lastname=:lastname,firstname=:firstname,dateofbirth=:dateofbirth WHERE id = :id";
                            $stmt = $con->prepare($query);
                            $cus_username = htmlspecialchars(strip_tags(ucwords($_POST['cus_username'])));
                            $gender = htmlspecialchars(strip_tags($_POST['gender']));
                            $accountstatus = htmlspecialchars(strip_tags($_POST['accountstatus']));
                            $password = htmlspecialchars(strip_tags($_POST['password']));
                            $confPass = htmlspecialchars(strip_tags($_POST['confPass']));
                            $firstname = htmlspecialchars(strip_tags(ucwords($_POST['firstname'])));
                            $lastname = htmlspecialchars(strip_tags(ucwords($_POST['lastname'])));
                            $dateofbirth = htmlspecialchars(strip_tags($_POST['dateofbirth']));
                            // bind the parameters
                            if($fileToUpload!=""&&$isUploadOK == true){
                                $stmt->bindParam(':fileToUpload', $newtarget_file);  
                            }
                            else{
                                $stmt->bindParam(':fileToUpload', $photo);  
                            }
                            $stmt->bindParam(':cus_username', $cus_username);
                            $stmt->bindParam(':gender', $gender);
                            $stmt->bindParam(':accountstatus', $accountstatus);
                            $stmt->bindParam(':password', $password);
                            $stmt->bindParam(':confPass', $confPass);
                            $stmt->bindParam(':firstname', $firstname);
                            $stmt->bindParam(':lastname', $lastname);
                            $stmt->bindParam(':dateofbirth', $dateofbirth);
                            $stmt->bindParam(':id', $id);
                            // Execute the query
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
                                echo "<div class='alert alert-success'>Customer $id had been updated.</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Unable to update customer $id. Please try again.</div>";
                            }
                    } catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    
                    } catch (Exception $exception) {
                        echo "<div class='alert alert-danger'>".$exception->getMessage()."</div>";
                    }
                } ?>


        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post"
            onsubmit="return validateForm()" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Profile Image(*Optional)</td>
                    <td>
                        <div class="image">
                            <div class="img3">
                                <h4>
                                    <div class='img-block m-2 d-flex'>
                                        <div>
                                            <img src=<?php echo htmlspecialchars($photo, ENT_QUOTES); ?> width='100'
                                                height='100' />
                                        </div>

                                        <div class="d-flex flex-column">
                                            <button type="submit" class="deleteBtn btn mx-2 p-2" name="delete_pic"
                                                <?php if ($photo == "picture/customer/noPic.jpg"){ echo("id = delPicbtn");} ?>>x</button>
                                        </div>
                                    </div>



                                </h4>
                            </div>
                            <div class="img1">
                                <div class="img2">
                                    <?php if ($photo == "picture/customer/noPic.jpg"){ 
                                echo '<button type="button" class="changePic btn-info text-light btn m-2 p-1" onclick="showDiv()">Add Picture</button>';echo (isset($_FILES['fileToUpload']['name']))?($_FILES['fileToUpload']['name']):"";
                                } else {
                                    echo '<button type="button" class="changePic btn-info text-light btn m-2 p-2" onclick="showDiv()">Change Picture</button>'; echo (isset($_FILES['fileToUpload']['name']))?($_FILES['fileToUpload']['name']):"";
                                }?>

                                    <div id="changePic" style="display:none">
                                        <div id="changeWindow">
                                            <div class="d-flex m-2">
                                                <input type="file"
                                                    value="<?php echo (isset($_FILES['fileToUpload']['name']))?($_FILES['fileToUpload']['name']):htmlentities($photo, ENT_QUOTES);?>"
                                                    name="fileToUpload"
                                                    id="fileToUpload"><?php echo (isset($_FILES['fileToUpload']['name']))?($_FILES['fileToUpload']['name']):"";?>
                                            </div>
                                            <div>
                                                <button type="button"
                                                    class="cancelPic btn btn-warning text-light btn mt-2 mx-2 p-1"
                                                    onclick="showCancel()">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Customer Name</td>
                    <td>
                        <div class="input-container">
                            <div class="input-group">
                                <input type='text' name='cus_username' placeholder="Enter user name "
                                    class='form-control'
                                    value="<?php echo (isset($_POST['cus_username']))?($_POST['cus_username']):htmlspecialchars($cus_username, ENT_QUOTES);?>"
                                    id="cName" />
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td>
                        <div class="input-container">
                            <div class="input-group">
                                <input type='password' name='password' placeholder="Enter password "
                                    class='form-control'
                                    value="<?php echo (isset($_POST['password']))?($_POST['password']):htmlspecialchars($password, ENT_QUOTES);?>"
                                    id="pass" />
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Confirm Password</td>
                    <td>
                        <div class="input-container">
                            <div class="input-group">
                                <input type='password' name='confPass' placeholder="Enter confirm password "
                                    value="<?php echo (isset($_POST['confPass']))?($_POST['confPass']):htmlspecialchars($confPass, ENT_QUOTES);?>"
                                    class='form-control' id="conPass" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>

                    <td>First Name</td>
                    <td>
                        <input type='text' name='firstname' id="fname" placeholder="Enter Firstname"
                            value="<?php echo (isset($_POST['firstname']))?($_POST['firstname']):htmlspecialchars($firstname, ENT_QUOTES);?>"
                            class='form-control' />
                    </td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td>
                        <div class="input-group">
                            <input type='text' name='lastname' id="lname" placeholder="Enter Lastname"
                                value="<?php echo (isset($_POST['lastname']))?($_POST['lastname']):htmlspecialchars($lastname, ENT_QUOTES);?>"
                                class='form-control' />

                    </td>
                </tr>
                <tr>
                    <td>Gender</td>

                    <td>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="gender" value="male" id="gen1" <?php
                                        if(isset($_POST['gender'])){
                                            echo $_POST['gender'] == "male" ? 'checked' : '';
                                        }
                                        else{
                                            echo ($gender=='male')?'checked':'';
                                        }
                                        ?>>
                                Male
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="gender" value="female" id="gen2" <?php
                                        if(isset($_POST['gender'])){
                                            echo $_POST['gender'] == "female" ? 'checked' : '';
                                        }
                                        else{
                                            echo ($gender=='female')?'checked':'';
                                        }
                                        ?>>
                                Female
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Date of birth</td>
                    <td>
                        <div class="input-container">
                            <input type='date' name='dateofbirth' class='form-control' id="datbir"
                                value="<?php echo (isset($_POST['dateofbirth']))?($_POST['dateofbirth']):htmlspecialchars($dateofbirth, ENT_QUOTES);?>" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Accounts Status</td>
                    <td>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="accountstatus" value="active" id="acc1" <?php
                                        if(isset($_POST['accountstatus'])){
                                            echo $_POST['accountstatus'] == "active" ? 'checked' : '';
                                        }
                                        else{
                                            echo ($accountstatus=='active')?'checked':'';
                                        }
                                        ?>>
                                Active
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="accountstatus" value="inactive" id="acc2" <?php
                                        if(isset($_POST['accountstatus'])){
                                            echo $_POST['accountstatus'] == "inactive" ? 'checked' : '';
                                        }
                                        else{
                                            echo ($accountstatus=='inactive')?'checked':'';
                                        }
                                        ?>>
                                Inactive
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to Customer List</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>
    <script>
    function validateForm() {
        var cName = document.getElementById("cName").value;
        var nameC = /^[a-zA-Z0-9.\-_$@*!]{6,}$/;
        var pass = document.getElementById("pass").value;
        var conPass = document.getElementById("conPass").value;
        var fname = document.getElementById("fname").value;
        var lname = document.getElementById("lname").value;
        var gen1 = document.getElementById("gen1").checked;
        var gen2 = document.getElementById("gen2").checked;
        var datbir = document.getElementById("datbir").value;
        var acc1 = document.getElementById("acc1").checked;
        var acc2 = document.getElementById("acc2").checked;
        var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/;
        var date1 = new Date().getFullYear();
        var date2 = new Date(datbir);
        var yearsDiff = date1 - date2.getFullYear();
        var flag = false;
        var msg = "";
        if (cName == "" || pass == "" || conPass == "" || fname == "" || lname == "" || datbir == "" || (gen1 ==
                false && gen2 == false) || (acc1 == false && acc2 == false)) {
            flag = true;
            msg = msg + "Please make sure all fields are not empty!\r\n";
        }if (cName.length < 6 || cName.length > 15 || cName.indexOf(' ') >= 0) {
            flag = true;
            msg = msg + "Please make sure your name should be  6- 15 characters and no space included!\r\n";
        }if (pass != conPass) {
            flag = true;
            msg = msg + "Please make sure your password same as confirm password!\r\n";
        } if (!pass.match(passw)) {
            flag = true;
            msg = msg +
                "Please make sure your password which contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character in at least 8 characters!\r\n";
        } if (yearsDiff < 18) {
            flag = true;
            msg = msg + "Please make sure your ages are 18 years old and above!\r\n";
        }

        if (flag == true) {
            alert(msg);
            return false;
        } else {
            return true;
        }
    }

    function showDiv() {
        document.getElementById('changePic').style.display = "block";
    }

    function showCancel() {
        document.getElementById('changePic').style.display = "none";
    }
    </script>
</body>
<?php
            include 'footer.php';
        ?>

</html>