<?php
    session_start();
    if (!isset($_SESSION["cus_username"])) {
        header("Location: index.php?error=restrictedAccess");
    }
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>Customer Details</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>

    <body>
        <?php
                include 'menu.php';
            ?>
        <div class="container">
            
            <div class="page-header">
                <h1>Customer Details  <img src='picture/product/detail.png' style='width: 3%;'></h1>
            </div>

            <?php
            // isset() is a PHP function used to verify if a value is there or not
            $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
            /*if (isset($_GET['id'])){
            $id = $_GET['id'];
            } else {
                die('ERROR: Record ID not found.');
            }*/


            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT * FROM customer WHERE id = :id ";
                $stmt = $con->prepare($query);

                // Bind the parameter
                $stmt->bindParam(":id", $id);

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
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Profile Image</td>
                    <td> <img src=<?php echo htmlspecialchars($photo, ENT_QUOTES); ?> width='100'
                                                height='100' /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><?php echo htmlspecialchars($password, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><?php echo htmlspecialchars($confPass, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><?php echo htmlspecialchars($firstname, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?php echo htmlspecialchars($lastname, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
                </tr>
                
                <tr>
                    <td>Date Of birth</td>
                    <td><?php echo htmlspecialchars($dateofbirth, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Registration date and time</td>
                    <td><?php echo htmlspecialchars($registrationdatetime, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td><?php echo htmlspecialchars($accountstatus, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <a href='customer_read.php' class='btn btn-danger'>Back to Customer List</a>
                    </td>
                </tr>
            </table>


        </div> 
            <?php
                include 'footer.php';
            ?>
    </body>

</html>