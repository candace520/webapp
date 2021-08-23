<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: index.php?error=restrictedAccess");
    
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Create Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
    td {
        font-size: 15px;
    }

    span {
        font-weight: bolder;
        color: white;
    }

    .input-container {
        display: -ms-flexbox;
        /* IE10 */
        display: flex;
        width: 100%;
        margin-bottom: 5px;
    }

    .icon {
        background: dodgerblue;
        color: white;
        min-width: 50px;
        text-align: center;
    }

    #leftrow {
        width: 30%;
    }

    .leftrow {
        padding-left: 60px;
    }
    </style>
</head>

    <body>
        <?php
                    include 'menu.php';
                ?>

        <div class="container">

            <div class="page-header">
                <h1>Create Customer <img src='picture/product/create.png' style='width: 4%;'></h1>
                <h6>**Please fill in all fields of relevant data!(except Optional)</h6>
            </div>

            <!-- PHP insert code will be here -->
            <?php
                    if ($_POST) {
                        include 'config/database.php';
                        try {
                            if (
                                empty($_POST['cus_username']) ||   empty($_POST['password'])
                                ||  empty($_POST['firstname'])  ||  empty($_POST['lastname'])
                                ||  empty($_POST['gender'])  || empty($_POST['dateofbirth'])
                                ||  empty($_POST['registrationdatetime']) ||  empty($_POST['accountstatus'])
                                ||  empty($_POST['confPass'])
                            ) {
                                throw new Exception("<div class='alert alert-danger'>Please make sure all fields are not empty!</div>");
                            }
                            $namelength = strlen($_POST['cus_username']);
                            if (15 < $namelength ||$namelength < 6|| (strpos($_POST['cus_username'], ' ') !== false)) {
                                throw new Exception("<div class='alert alert-danger'>Please make sure your name should be  6- 15 characters and no space included!</div>");
                            }
                            if ($_POST['password'] != $_POST['confPass']) {
                                throw new Exception("<div class='alert alert-danger'>Please make sure your password same as confirm password!</div>");
                            }
                            if (!preg_match('/[A-Za-z]/', $_POST['password']) || !preg_match('/[0-9]/', $_POST['password'])||strlen($_POST["password"]) < 8) {
                                throw new Exception("<div class='alert alert-danger'>Please make sure your password which contain at least one lowercase letter, uppercase letter, numeric digit, and special character in at least 8 characters!</div>");
                            }
                            $date1 = "Y";
                            $diff = abs(strtotime($date1) - strtotime($_POST['dateofbirth']));
                            $years = floor($diff / (365 * 60 * 60 * 24));
                            if ($years < 18) {
                                throw new Exception("<div class='alert alert-danger'>Please make sure your ages are 18 years old and above!</div>");
                            }
                            $checkQuery = "SELECT * FROM customer WHERE cus_username= :cus_username";
                            $checkStmt = $con->prepare($checkQuery);
                            $check_username = ucwords($_POST['cus_username']);
                            $checkStmt->bindParam(':cus_username', $check_username);
                            $checkStmt->execute();
                            $num = $checkStmt->rowCount();
                            if($num >= 1){
                                throw new Exception("Please make sure that username is NOT EXIST!");
                            }
                                $target_dir = "picture/customer/";
                                $fileToUpload = $_FILES['fileToUpload']['name']; 
                                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);//the name of target file u choose
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

                            // insert query
                            $query = "INSERT INTO customer SET cus_username=:cus_username,password=:password,confPass=:confPass,firstname=:firstname,lastname=:lastname, gender=:gender,dateofbirth=:dateofbirth,registrationdatetime=:registrationdatetime,
                            accountstatus=:accountstatus";
                            // prepare query for execution
                            $stmt = $con->prepare($query);
                            // posted values
                            
                            $cus_username = ucwords($_POST['cus_username']);
                            $password = $_POST['password'];
                            $confPass = $_POST['confPass'];
                            $firstname = ucwords($_POST['firstname']);
                            $lastname = ucwords($_POST['lastname']);
                            $gender = $_POST['gender'];
                            $dateofbirth = $_POST['dateofbirth'];
                            $registrationdatetime = $_POST['registrationdatetime'];
                            $accountstatus = $_POST['accountstatus'];
                            // bind the parameters
                            
                            $stmt->bindParam(':cus_username', $cus_username);
                            $stmt->bindParam(':password', $password);
                            $stmt->bindParam(':confPass', $confPass);
                            $stmt->bindParam(':firstname', $firstname);
                            $stmt->bindParam(':lastname', $lastname);
                            $stmt->bindParam(':gender', $gender);
                            $stmt->bindParam(':dateofbirth', $dateofbirth);
                            $stmt->bindParam(':registrationdatetime', $registrationdatetime);
                            $stmt->bindParam(':accountstatus', $accountstatus);
                            // Execute the query
                            if ($stmt->execute()) {
                                $lastID = $con->lastInsertId();
                                $temp = explode(".", $_FILES["fileToUpload"]["name"]);
                                $newfilename = $lastID . '_' . round(microtime(true)) . '.' .  end($temp);
                                $newtarget_file = $target_dir  . $newfilename;
                                $default = "picture/customer/noPic.jpg";
                                if ($target_file != "") {
                                        $insertcuQuery = "UPDATE customer SET fileToUpload=:fileToUpload WHERE id = :id";
                                        $insertcuStmt = $con->prepare($insertcuQuery); 
                                        $insertcuStmt->bindParam(':id', $lastID);
                                        if($fileToUpload!=""&&$isUploadOK == true){
                                            $insertcuStmt->bindParam(':fileToUpload', $newtarget_file);
                                        }
                                        else{
                                        $insertcuStmt->bindParam(':fileToUpload', $default);
                                        }
                                    
                                        if ($insertcuStmt->execute()) {
                                            if ($fileToUpload!="") {
                                                if ($isUploadOK == false) {
                                                    echo"<div class='alert alert-danger'>Sorry, your file was not uploaded!</div>";
                                                } else {
                                                    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newtarget_file)) {
                                                        echo "Sorry, there was an error uploading your file.";
                                                    }
                                                }
                                            }
                                
                                            echo "<div class='alert alert-success'>Customer $lastID had been created.</div>";
                                        } else {
                                            echo "<div class='alert alert-danger'>Unable to create customer $lastID.</div>";
                                        }
                                }
                            }
                        }
                        // show error
                        catch (PDOException $exception) {
                            echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                        } catch (Exception $exception) {
                            echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
                        }
                    }
                ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                onsubmit="return validateForm()" enctype="multipart/form-data">

                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td class="leftrow">Profile Image(*Optional)</td>
                        <td>
                            <input type="file" name="fileToUpload" id="fileToUpload"
                                value="<?php echo (isset($_FILES["fileToUpload"]["name"]))?($_FILES["fileToUpload"]["name"]):'';?>"><?php echo (isset($_FILES["fileToUpload"]["name"]))?($_FILES["fileToUpload"]["name"]):'';?>
                        </td>
                    </tr>
                    <tr>
                        <td id="leftrow">User Name</td>
                        <td>
                            <div class="input-group">
                                <input type='text' name='cus_username' placeholder="Enter user name " class='form-control'
                                    id="cName"
                                    value="<?php echo (isset($_POST['cus_username']))?($_POST['cus_username']):'';?>" />

                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td id="leftrow">Password</td>
                        <td>
                            <div class="input-container">

                                <div class="input-group">
                                    <input type='password' name='password' placeholder="Enter password " class='form-control' id="pass"
                                        value="<?php echo (isset($_POST['password']))?($_POST['password']):'';?>" />

                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td id="leftrow">Confirm Password</td>
                        <td>
                            <div class="input-container">

                                <div class="input-group">
                                    <input type='password' name='confPass' placeholder="Enter confirm password " class='form-control'
                                        id="conPass" value="<?php echo (isset($_POST['confPass']))?($_POST['confPass']):'';?>" />
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>

                        <td id="leftrow">First Name</td>
                        <td>
                            <input type='text' name='firstname' placeholder="Enter Firstname" class='form-control' id="fname"
                                older="Enter confirm password " class='form-control' id="conPass"
                                value="<?php echo (isset($_POST['firstname']))?($_POST['firstname']):'';?>" />
                        </td>
                        </div>
                    </tr>
                    <tr>
                        <td id="leftrow">Last Name</td>
                        <td>
                            <div class="input">
                                <input type='text' name='lastname' id="lname" placeholder="Enter Lastname" class='form-control'
                                    value="<?php echo (isset($_POST['lastname']))?($_POST['lastname']):'';?>" />
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td id="leftrow">Gender</td>
                        <td>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="gender" value="male" id="gen1" <?php
                                                        if(isset($_POST['gender'])){
                                                            echo $_POST['gender'] == "male" ? 'checked' : '';
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
                                                        ?>>
                                    Female
                                </label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td id="leftrow">Date of birth</td>
                        <td>
                            <div class="input-group">

                                <input type='date' name='dateofbirth' class='form-control' id="datbir"
                                    value="<?php echo (isset($_POST['dateofbirth']))?($_POST['dateofbirth']):'';?>" />
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td id="leftrow">Registration Date And Time</td>
                        <td><input type='datetime-local' name='registrationdatetime' class='form-control' id="reDate"
                                value="<?php echo (isset($_POST['registrationdatetime']))?($_POST['registrationdatetime']):'';?>" />
                        </td>
                    </tr>

                    <tr>
                        <td id="leftrow">Accounts Status</td>
                        <td>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="accountstatus" value="active" id="acc1" <?php
                                                        if(isset($_POST['accountstatus'])){
                                                            echo $_POST['accountstatus'] == "active" ? 'checked' : '';
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
                                                        ?>>
                                    Inactive
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' name="submit" value='Save' class='btn btn-primary' />
                            <a href='customer_read.php' class='btn btn-danger'>Back to Customer List</a>

                        </td>
                    </tr>
                </table>
            </form>

        </div>
        <?php
                    include 'footer.php';
                ?>
        <!-- end .container -->
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
            var reDate = document.getElementById("reDate").value;
            var acc1 = document.getElementById("acc1").checked;
            var acc2 = document.getElementById("acc2").checked;
            var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/;
            var date1 = new Date().getFullYear();
            var date2 = new Date(datbir);
            var yearsDiff = date1 - date2.getFullYear();
            var flag = false;
            var msg = "";
            if (cName == "" || pass == "" || conPass == "" || fname == "" || lname == "" || datbir == "" || reDate == "" ||
                (gen1 == false && gen2 == false) || (acc1 == false && acc2 == false)) {
                flag = true;
                msg = msg + "Please make sure all fields are not empty!\r\n";
            } else if (cName.length < 6 || cName.length > 15 || cName.indexOf(' ') >= 0) {
                flag = true;
                msg = msg + "Please make sure your name should be  6- 15 characters and no space included!\r\n";
            } else if (pass != conPass) {
                flag = true;
                msg = msg + "Please make sure your password same as confirm password!\r\n";
            } else if (!pass.match(passw)) {
                flag = true;
                msg = msg +
                    "Please make sure your password which contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character in at least 8 characters!\r\n";
            } else if (yearsDiff < 18) {
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
        </script>
    </body>

</html>