<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>


<body>
    <?php
    include 'menu.php';
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>
        <?php
        //PHP read record by ID will be here 

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
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        ?>

        <?php

        //PHP post to update record will be here
        // check if form was submitted
        if ($_POST) {
            try {
                $namelength = strlen($_POST['cus_username']);
                if ($namelength <= 6) {
                    throw new Exception("<div class='alert alert-danger'>Please make sure your name should be greater than 6 characters</div>");
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
                // write update query
                // in this case, it seemed like we have so many fields to pass and
                // it is better to label them and not use question marks
                $query = "UPDATE customer
                  SET cus_username=:cus_username,gender=:gender,accountstatus=:accountstatus,password=:password, 
                  confPass=:confPass,lastname=:lastname,firstname=:firstname,dateofbirth=:dateofbirth WHERE id = :id";
                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $cus_username = htmlspecialchars(strip_tags($_POST['cus_username']));
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $accountstatus = htmlspecialchars(strip_tags($_POST['accountstatus']));
                $password = htmlspecialchars(strip_tags($_POST['password']));
                $confPass = htmlspecialchars(strip_tags($_POST['confPass']));
                $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                $dateofbirth = htmlspecialchars(strip_tags($_POST['dateofbirth']));
                // bind the parameters
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
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
            }
        } ?>


        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post"onsubmit="return validateForm()">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer Name</td>
                    <td>
                        <div class="input-container">
                            <i class="fa fa-user icon"></i>
                            <div class="input-group">
                                <input type='text' name='cus_username' placeholder="Enter user name " class='form-control' value="<?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?>"id="cName"/>
                    </td>
                </tr>
                <tr>
                <td>Gender</td>
                <td><input type="radio" name="gender"id="gen1" value="male"<?php echo ($gender=='male')?'checked':'' ?>>
                          <label for="male">Male</label><br>
                          <input type="radio" name="gender" id="gen2" value="female"<?php echo ($gender=='female')?'checked':'' ?>>
                          <label for="female">Female</label>
                    </td>
                </tr>
                <tr>
                    <td>Accounts Status</td>
                    <td><input type="radio" name="accountstatus" id="acc1" value="active"<?php echo ($accountstatus=='active')?'checked':'' ?>>
                          <label for="active">Active</label><br>
                          <input type="radio" name="accountstatus" id="acc2" value="inactive"<?php echo ($accountstatus=='inactive')?'checked':'' ?>>
                          <label for="inactive">Inactive</label>
                    </td>
                </tr>

                <tr>
                    <td>Password</td>
                    <td>
            <div class="input-container">
                <i class="fa fa-key icon"></i>
                <div class="input-group">
                    <input type='password' name='password' placeholder="Enter password " class='form-control' value="<?php echo htmlspecialchars($password, ENT_QUOTES);  ?>"id="pass"/>
        </td>
                </tr>
    </div>
    </div>
    <tr>
        <td>Confirm Password</td>
        <td>
            <div class="input-container">
                <i class="fa fa-key icon"></i>
                <div class="input-group">
                    <input type='password' name='confPass' placeholder="Enter confirm password " value="<?php echo htmlspecialchars($confPass, ENT_QUOTES);  ?>"class='form-control' id="conPass"/>
        </td>
    </tr>
    </div>
    </div>
    <tr>

        <td>First Name</td>
        <td>
            <input type='text' name='firstname'id="fname" placeholder="Enter Firstname" 
            value="<?php echo htmlspecialchars($firstname, ENT_QUOTES);  ?>" class='form-control' />
        </td>
        </div>
    </tr>
    <tr>
        <td>Last Name</td>
        <td>
            <div class="input-group">
                <input type='text' name='lastname'  id="lname" placeholder="Enter Lastname" 
                value="<?php echo htmlspecialchars($lastname, ENT_QUOTES);  ?>" class='form-control' />

        </td>
    </tr>
    <tr>
        <td>Date of birth</td>
        <td>
            <div class="input-container">
                <i class="fa fa-birthday-cake icon"></i>
                <input type='date' name='dateofbirth' class='form-control' id="datbir"value="<?php echo htmlspecialchars($dateofbirth, ENT_QUOTES);  ?>"/>
        </td>
    </tr>
    </div>
    </div>
    <tr>
        <td></td>
        <td>
            <input type='submit' value='Save Changes' class='btn btn-primary' />
            <a href='customer_read.php' class='btn btn-danger'>Back to read customer</a>
        </td>
    </tr>
    </table>
    </form>

    </div>
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
        var yearsDiff =  date1 - date2.getFullYear();
        var flag = false;
        var msg = "";
        if (cName == ""||pass == "" ||conPass == ""|| fname == ""||lname == "" ||datbir ==""||(gen1 == false && gen2 == false)||(acc1 == false && acc2 == false)){ 
            flag = true;
          msg = msg + "Please make sure all fields are not empty!\r\n";
        }
        else if(cName.length <= 6){
            flag = true;
          msg = msg + "Please make sure your name should be greater than 6 characters!\r\n";
        }
        else if(pass != conPass){
            flag = true;
          msg = msg + "Please make sure your password same as confirm password!\r\n";
        }
        else if(!pass.match(passw)){ 
            flag = true;
          msg = msg + "Please make sure your password which contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character in at least 8 characters!\r\n";
        }
        else if(yearsDiff < 18){
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