<?php
session_start();
if (isset($_SESSION['cus_username'])) {
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- Add icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #leftrow {
            width: 25%;

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

        .input-container {
            display: -ms-flexbox;
            /* IE10 */
            display: flex;
            width: 100%;
            margin-bottom: 15px;
        }

        .icon {
            padding: 10px;
            background: dodgerblue;
            color: white;
            min-width: 50px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    include 'menu.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Create Customer</h1>
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
                    throw new Exception("<div class='alert alert-danger'>Please make sure all fields are not empty</div>");
                }
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
                $date1 = "Y";
                $diff = abs(strtotime($date1) - strtotime($_POST['dateofbirth']));
                $years = floor($diff / (365 * 60 * 60 * 24));
                if ($years < 18) {
                    throw new Exception("<div class='alert alert-danger'>Please make sure your ages are 18 years old and above</div>");
                }
                // include database connection

                // insert query
                $query = "INSERT INTO customer SET cus_username=:cus_username,password=:password,confPass=:confPass,firstname=:firstname,lastname=:lastname, gender=:gender,dateofbirth=:dateofbirth,registrationdatetime=:registrationdatetime,
                accountstatus=:accountstatus";
                // prepare query for execution
                $stmt = $con->prepare($query);
                // posted values
                $cus_username = $_POST['cus_username'];
                $password = $_POST['password'];
                $confPass = $_POST['confPass'];
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
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
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
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
        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td id="leftrow">User Name</td>
                    <td>
                        <div class="input-container">
                            <i class="fa fa-user icon"></i>
                            <div class="input-group">
                                <input type='text' name='cus_username' placeholder="Enter user name " class='form-control' />
                    </td>
                </tr>
    </div>
    </div>

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
        <td>Gender</td>
        <td>
            <input type="radio" name="gender" value="male">
              <label for="html">Male</label><br>
              <input type="radio" name="gender" value="female">
              <label for="css">Female</label>
        </td>
    </tr>

    <tr>
        <td>Date of birth</td>
        <td>
            <div class="input-container">
                <i class="fa fa-birthday-cake icon"></i>
                <input type='date' name='dateofbirth' class='form-control' />
        </td>
    </tr>
    </div>
    <tr>
        <td>Registration Date And Time</td>
        <td><input type='datetime-local' name='registrationdatetime' class='form-control' /></td>
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
        <td></td>
        <td>
            <input type='submit' value='Save' class='btn btn-primary' />
            <a href='index.php' class='btn btn-danger'>Back to read customers</a>
        </td>
    </tr>
    </table>
    </form>
    <?php
    include 'footer.php';
    ?>
    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>