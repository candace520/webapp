<?php
    session_start();
    if (!isset($_SESSION["cus_username"])) {
        header("Location: login.php?error=restrictedAccess");
    }
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>Customer Read</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <style>
        .page-header{
            display: flex;
        }
        .title{
            width: 30%;
        }
        .title2{
           margin-top: 8px;
        }
    </style>

    <body>
        
        <!-- container -->
        <div class="container">
            <?php
                include 'menu.php';
            ?>
        <div class = 'page-header'>
                            <div class = 'title'><h1>Customer List <img src='picture/img/read.png' style='width: 12%;'></div>
                            <div class = 'title2'><a href = 'customer.php' class = 'btn btn-primary'>Create         New           Customer</h1></a>
                            </div>
                    </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <table class = 'table table-hover table-responsive table-bordered' >
                    <tr style = 'border:none;'>
                        <td class = 'col-10' style = 'border:none;'>
                            <div class = 'input-group rounded'>
                                <input type = 'text'  class = 'form-control rounded' placeholder = 'Search by customer ID OR names...' aria-label = 'Search'
                                aria-describedby = 'search-addon' id = 'myInput' onkeyup = 'myFunction()' name="sear"/>
                                <button type = 'button' class = 'btn btn-primary' >
                                <i class = 'fa fa-search' style = 'font-size:20px;color:white'></i>
                                </button>
                            </div>
                        </td>
                        
                    </tr>
                </table>
            </form>

            <!-- PHP code to read records will be here -->
            <?php
            // include database connection
            include 'config/database.php';

            // delete message prompt will be here
            $action = isset($_GET['action']) ? $_GET['action'] : "";
            // if it was redirected from delete.php
            if ($action == 'customerInOrder') {
                echo "<div class='alert alert-success'>Record could not deleted as this customer in the order.</div>";
            }
            
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }
            // select all data
            $query = "SELECT * FROM customer ORDER BY id DESC";
            $stmt = $con->prepare($query);

            if ($_POST) {
                $sear = $_POST['sear'];
                $query = 'SELECT * FROM customer  WHERE cus_username LIKE :sear OR id LIKE :sear ORDER BY id DESC';
                $stmt = $con->prepare( $query );
                $stmt->bindParam(':sear', $sear);
            }    
            
            $stmt->execute();

            // this is how to get number of rows returned
            //this is how to get how many product found
            $num = $stmt->rowCount();

            //check if more than 0 record found
            if ($num > 0) {

                // data from database will be here
                echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

                //creating our table heading
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Profile Image</th>";
                echo "<th>User Name</th>";
                echo "</tr>";

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<tr>";
                    echo "<td>{$id}</td>";
                    echo "<td><img src='$row[fileToUpload]' width='100' height='100'></td>";
                    echo "<td>{$cus_username}</td>";
                    echo "<td>";
                    echo "<a href='customer_read_one.php?id={$id}' class='btn btn-info m-r-1em'>Details</a>";

                    echo "<a href='customer_update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";

                    echo "<a href='#' onclick='delete_customer(&#39;$cus_username&#39;)'  class='btn btn-danger'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }


                echo "</table>";
            }

            // if no records found
            else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }
            ?>

            <?php
                include 'footer.php';
            ?>
        </div> 

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        <script type='text/javascript'>
            function delete_customer(cus_username) {

                if (confirm('Are you sure?')) {
                    window.location = 'customer_delete.php?cus_username=' + cus_username;
                }
            }
        </script>
</html>