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
                        <a class="nav-link" href="create.php">Create Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="customer.php">Create Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </li>
                </ul>
            </div>
    </nav>
    </div>
    <div class="container">
        <div class="page-header">
            <h1>Create Customer</h1>
        </div>

        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            if (
                $_POST['name'] != "" &&  $_POST['password'] != ""
                && $_POST['firstname'] != "" && $_POST['lastname'] != ""
                && $_POST['gender'] != "" && $_POST['dateofbirth'] != ""
                && $_POST['registrationdatetime'] != "" && $_POST['accountstatus'] != ""
                && $_POST['confPass'] != ""
            ) {
                $namelength = strlen($_POST['name']);
                if ($namelength >= 6) {
                    if ($_POST['password'] == $_POST['confPass']) {
                        if (preg_match('/[A-Za-z]/', $_POST['password']) && preg_match('/[0-9]/', $_POST['password'])) {
                            if (strlen($_POST["password"]) >= 8) {
                                $date1 = "Y";
                                $diff = abs(strtotime($date1) - strtotime($_POST['dateofbirth']));
                                $years = floor($diff / (365 * 60 * 60 * 24));
                                if ($years >= 18) {

                                    // include database connection
                                    include 'config/database.php';
                                    try {
                                        // insert query
                                        $query = "INSERT INTO customer SET name=:name,password=:password,confPass=:confPass,firstname=:firstname,lastname=:lastname, gender=:gender,dateofbirth=:dateofbirth,registrationdatetime=:registrationdatetime,
                accountstatus=:accountstatus";
                                        // prepare query for execution
                                        $stmt = $con->prepare($query);
                                        // posted values
                                        $name = $_POST['name'];
                                        $password = $_POST['password'];
                                        $confPass = $_POST['confPass'];
                                        $firstname = $_POST['firstname'];
                                        $lastname = $_POST['lastname'];
                                        $gender = $_POST['gender'];
                                        $dateofbirth = $_POST['dateofbirth'];
                                        $registrationdatetime = $_POST['registrationdatetime'];
                                        $accountstatus = $_POST['accountstatus'];
                                        // bind the parameters
                                        $stmt->bindParam(':name', $name);
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
                                        die('ERROR: ' . $exception->getMessage());
                                    }
                                } else {
                                    echo "<div class='alert alert-danger'>Please make sure your ages are 18 years old and above</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Please make sure your password contains 8 characters</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Please make sure your password contains numbers and alphabets</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Please make sure your password same as confirm password</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Please make sure your name should be greater than 6 characters</div>";
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
                    <td id="leftrow">User Name</td>
                    <td>
                        <div class="input-container">
                            <i class="fa fa-user icon"></i>
                            <div class="input-group">
                                <input type='text' name='name' placeholder="Enter user name " class='form-control' />
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
            <label class="container">
                <input type="radio" checked="checked" name="gender">
                <span class="checkmark"></span>Male
            </label>
            <label class="container">
                <input type="radio" name="gender">
                <span class="checkmark"></span>Female
            </label>
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
        <td><label class="container">
                <input type="radio" checked="checked" name="accountstatus">
                <span class="checkmark"></span>Active
            </label>
            <label class="container">
                <input type="radio" name="accountstatus">
                <span class="checkmark"></span>Inactive
            </label>
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

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>