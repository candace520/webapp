<?php
// Start the session
  session_start();
  if (isset($_GET['error']) && $_GET['error'] == "restrictedAccess") {
    $errorMessage = "Please login for further proceed!";
  }
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #FAEBE0;
    }

    form {
        border: 10px solid #f1f1f1;
    }

    input[type=text],
    input[type=password] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    button {
        background-color: #04AA6D;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
    }

    button:hover {
        opacity: 0.8;
    }

    .cancelbtn {
        width: auto;
        padding: 10px 18px;
        background-color: #f44336;
    }

    .imgcontainer {
        text-align: center;
        margin: 24px 0 12px 0;
    }

    img.avatar {
        width: 30%;
        border-radius: 20%;
    }

    .container {
        padding: 16px;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
        padding-top: 60px;
    }

    h2 {
        font-weight: bold;
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #FFFAFA;
        margin: 5% auto 15% auto;
        /* 5% from the top, 15% from the bottom and centered */
        border: 5px solid #888;
        width: 80%;
        /* Could be more or less, depending on screen size */
    }

    @-webkit-keyframes animatezoom {
        from {
            -webkit-transform: scale(0)
        }

        to {
            -webkit-transform: scale(1)
        }
    }

    @keyframes animatezoom {
        from {
            transform: scale(0)
        }

        to {
            transform: scale(1)
        }
    }
    </style>
</head>

<body>
    <form class="modal-content" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
        method="post">
        <div class="container">
            <h2>Login</h2>
            <?php
    if ($_POST) {


      include 'config/database.php';
      if (isset($_GET['error']) && $_GET['error'] == "restrictedAccess") {
        $errorMessage = "Please login for further proceed!";
    }
      $cus_username = $_POST['cus_username'];
      $password = $_POST['password'];
      try {
        if (
          empty($_POST['cus_username'])  ||  empty($_POST['password'])
        ) {
          throw new Exception("<div class='alert alert-danger'>Please make sure all fields are not empty!</div>");
        }
        if (isset($_POST['cus_username'])) {
          // insert query
          $query = "SELECT * from  customer WHERE cus_username=:cus_username AND password=:password AND accountstatus='active'";

          $stmt = $con->prepare($query);
          // posted values
          // bind the parameters
          $stmt->bindParam(':cus_username', $cus_username);
          $stmt->bindParam(':password', $password);
          // Execute the query
          if ($stmt->execute()) {
            $count = $stmt->rowCount();
            $row   = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($count == 1 && !empty($row)) {
              //header("Location: profile.php");
              $_SESSION['cus_username'] = $row['cus_username'];
              $_SESSION['password'] = $row['password'];
              header("Location: index.php");
            }
          }

          // insert query
          

          $query = "SELECT * from  customer WHERE cus_username=:cus_username ";
          // prepare query for execution
          $stmt = $con->prepare($query);

          // posted values
          // bind the parameters
          $stmt->bindParam(':cus_username', $cus_username);
          // Execute the query
          if ($stmt->execute()) {
            $count = $stmt->rowCount();
            $row   = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($count !=0 && !empty($row)) {
              $query = "SELECT * from  customer WHERE  cus_username=:cus_username AND accountstatus='active'";

          $stmt = $con->prepare($query);
          $stmt->bindParam(':cus_username', $cus_username);
          // posted values
          // bind the parameters
          // Execute the query
          if ($stmt->execute()) {
            $count = $stmt->rowCount();
            $row   = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($count ==1 && !empty($row)) {
            } else {
              echo "<div class='alert alert-danger'>Please make sure your Username is ACTIVE!</div>";
            }
          }
            } else {
              echo "<div class='alert alert-danger'>Please make sure your Username is EXIST!</div>";
            }
          }


          $query = "SELECT * from  customer WHERE cus_username=:cus_username AND password=:password ";
          // prepare query for execution
          $stmt = $con->prepare($query);

          // posted values
          // bind the parameters
          $stmt->bindParam(':cus_username', $cus_username);
          $stmt->bindParam(':password', $password);
          // Execute the query
          if ($stmt->execute()) {
            $count = $stmt->rowCount();
            $row   = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($count !=0 && !empty($row)) {
            } else {
              echo "<div class='alert alert-danger'>Please make sure your Password is VALID!</div>";
            }
          }
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

            <?php
              if (isset($errorMessage)) { 
                  echo"<div class='alert alert-danger m-2'>$errorMessage</div>";
              }?>


            <div class="imgcontainer">
                <img src="login_picture/user.png" alt="Avatar" class="avatar">
            </div>


            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="cus_username">

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password">

            <input type='submit' value='Login' class='btn btn-primary' />
        </div>

    </form>

</body>

</html>