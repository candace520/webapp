<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        #leftrow {
            width: 30%;

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
    </style>
</head>

<body>
    <?php
    include 'menu.php';
    ?>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Orders</h1>
        </div>
        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            include 'config/database.php';
            try {
                if (
                    empty($_POST['orderdatetime']) || empty($_POST['names'])
                    || empty($_POST['pro_names']) || empty($_POST['quantity'])
                ) {
                    throw new Exception("Please make sure all fields are not empty");
                }

                $names = $_POST['names'];
                $orderdatetime = $_POST['orderdatetime'];
                $pro_names = $_POST['pro_names'];
                $quantity = $_POST['quantity'];
                // include database connection

                // insert query
                $query = "INSERT INTO orders SET orderdatetime=:orderdatetime,names=:names,pro_names=:pro_names,
                quantity=:quantity";
                // prepare query for execution
                $stmt = $con->prepare($query);
                // posted values

                // bind the parameters
                $stmt->bindParam(':names', $names);
                $stmt->bindParam(':orderdatetime', $orderdatetime);
                $stmt->bindParam(':pro_names', $pro_names);
                $stmt->bindParam(':quantity', $quantity);
                // specify when this record was inserted to the database
                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            }
            // show error
            //for database 'PDO'
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

                    <td id="leftrow">Order Date Time</td>
                    <td><input type='datetime-local' name='orderdatetime' class='form-control' /></td>
                </tr>

                <tr>

                    <td id="leftrow">User Name</td>
                    <td>
                        <?php
                        // include database connection
                        include 'config/database.php';

                        // delete message prompt will be here

                        // select all data
                        $query = "SELECT * FROM customer ORDER BY id ";
                        $stmt = $con->prepare($query);
                        $stmt->execute();

                        // this is how to get number of rows returned
                        //this is how to get how many product found
                        $num = $stmt->rowCount();

                        //check if more than 0 record found
                        if ($num > 0) {


                            echo "<select name='names' class='form-select' aria-label='Default select example'>";
                            echo "<option selected>Please select the user name</option>";
                            // table body will be here
                            // retrieve our table contents
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                // extract row
                                extract($row);
                                // this will make $row['firstname'] to just $firstname only


                                // creating new table row per record

                                echo "<option value='$name'>$id. $name</option>";

                                // read one record


                            }
                            echo "</select>";



                            // end table
                        }

                        // if no records found
                        else {
                            echo "<div class='alert alert-danger'>No records found.</div>";
                        }
                        ?>
                    </td>
                </tr>

                <tr>

                    <td id="leftrow">Product Name</td>
                    <td>
                        <?php
                        // include database connection
                        include 'config/database.php';

                        // delete message prompt will be here

                        // select all data
                        $query = "SELECT * FROM products ORDER BY id ";
                        $stmt = $con->prepare($query);
                        $stmt->execute();

                        // this is how to get number of rows returned
                        //this is how to get how many product found
                        $num = $stmt->rowCount();

                        //check if more than 0 record found
                        if ($num > 0) {


                            echo "<select name='pro_names' class='form-select' aria-label='Default select example'>";
                            echo "<option selected>Please select the product name</option>";
                            // table body will be here
                            // retrieve our table contents
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                // extract row
                                extract($row);
                                // this will make $row['firstname'] to just $firstname only


                                // creating new table row per record

                                echo "<option value='$name'>$id. $name</option>";

                                // read one record


                            }
                            echo "</select>";



                            // end table
                        }

                        // if no records found
                        else {
                            echo "<div class='alert alert-danger'>No records found.</div>";
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Quantity</td>
                    <td>
                        <div class="input-group">
                            <select name="quantity" class="form-select" aria-label="Default select example">
                                <?php
                                echo "<option selected>Please select the quantity</option>";
                                for ($i = 1; $i <= 100; $i++) {

                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>

                    </td>
                </tr>
    </div>

    <tr>
        <td></td>
        <td>
            <input type='submit' value='Save' class='btn btn-primary' />
            <a href='index.php' class='btn btn-danger'>Back to read products</a>
        </td>
    </tr>
    </table>
    </form>
    <?php
    include 'footer.php';
    ?>


    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>