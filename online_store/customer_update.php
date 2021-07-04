<?php
session_start();
if (isset($_SESSION['cus_username'])) {
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
                  confPass=:confPass,lastname=:lastname,firstname=:firstname WHERE id = :id";
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
                // bind the parameters
                $stmt->bindParam(':cus_username', $cus_username);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':accountstatus', $accountstatus);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':confPass', $confPass);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer Name</td>
                    <td><input type='text' name='cus_username' value="<?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" value="male">
                          <label for="html">Male</label><br>
                          <input type="radio" name="gender" value="female">
                          <label for="css">Female</label>
                    </td>
                </tr>
                <tr>
                    <td>Accounts Status</td>
                    <td><input type="radio" name="accountstatus" value="active">
                          <label for="html">Active</label><br>
                          <input type="radio" name="accountstatus" value="inactive">
                          <label for="css">Inactive</label>
                    </td>
                </tr>

                <tr>
                    <td>Password</td>
                    <td>
                        <div class="input-container">
                            <i class="fa fa-key icon"></i>
                            <div class="input-group">
                                <input type='password' name='password' placeholder="Enter password " class='form-control' />
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
                    <input type='password' name='confPass' placeholder="Enter confirm password " class='form-control' />
        </td>
    </tr>
    </div>
    </div>
    <tr>

        <td>First Name</td>
        <td>
            <input type='text' name='firstname' placeholder="Enter Firstname" class='form-control' />
        </td>
        </div>
    </tr>
    <tr>
        <td>Last Name</td>
        <td>
            <div class="input-group">
                <input type='text' name='lastname' id="uname" placeholder="Enter Lastname" class='form-control' />

        </td>
    </tr>
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
    <!-- end .container -->
</body>

</html>