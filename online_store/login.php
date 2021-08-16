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
      </style>
  </head>

  <body>
      <form class="modal-content" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
          method="post">
          <div class="container">
              <h2>Login</h2>
            <?php
              include 'config/database.php';
              if (isset($_GET['error']) && $_GET['error'] == "restrictedAccess") {
                $errorMessage = "Please login for further proceed!";
              }
              if ($_POST) {
                  try {
                      $query = "SELECT * FROM customer WHERE cus_username= :cus_username";
                      $stmt = $con->prepare($query);
                      $cus_username = $_POST['cus_username'];
                      $password = $_POST['password'];
                      $stmt->bindParam(':cus_username', $cus_username);
                      $stmt->execute();
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);
                      if (empty($_POST['cus_username']) || empty($_POST['password'])) {
                          throw new Exception("Please make sure that all fields are not empty!");
                      }
                      if (isset($row['cus_username']) != $cus_username) {
                          throw new Exception("Please make sure that Username is EXIST!");
                      }
                      if ($row['password'] != $password) {
                          throw new exception("Please make sure that your Password is CORRECT!");
                      }
                      if ($row['accountstatus'] != 'active') {
                          throw new Exception("Please make sure that your Account is ACTIVE!");
                      }
                      $_SESSION['cus_username'] = $row['cus_username'];
                      $_SESSION['password'] = $row['password'];
                      header("Location: index.php");
                  }
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
                }
              ?>

              <div class="imgcontainer">
                  <img src="picture/login_picture/user.png" alt="Avatar" class="avatar">
              </div>


              <label for="uname"><b>Username</b></label>
              <input type="text" placeholder="Enter Username" name="cus_username" value="<?php echo (isset($_POST['cus_username']))?($_POST['cus_username']):'';?>">

              <label for="psw"><b>Password</b></label>
              <input type="password" placeholder="Enter Password" name="password" value="<?php echo (isset($_POST['password']))?($_POST['password']):'';?>">

              <input type='submit' value='Login' class='btn btn-primary' />
          </div>

      </form>

  </body>

</html>