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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.css" rel="stylesheet"/>
    <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #FAEBE0;
    }

    form {
        border: 10px solid #f1f1f1;
    }

    .content1 {
        width: 70%;
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


    /* Modal Content/Box */
    .modal-content {
        background-color: #FFFAFA;
        margin: 5% auto 15% auto;
        /* 5% from the top, 15% from the bottom and centered */
        border: 5px solid #888;
        width: 80%;
        /* Could be more or less, depending on screen size */
    }

    #storeLogo img {
        width: 70px;
        height: 70px;
    }
    </style>
</head>


<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="https://mdbootstrap.com/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                        class="img-fluid" alt="Phone image">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <div class="">
                        <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
                        <span class="h1 fw-bold">
                            <div id="storeLogo">
                                <img src="picture/logo/loginpic.png">
                                Candace_Store
                        </span>
                    </div>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" method="post">
                    <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Log in</h3>
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
                      header("Location: home.php");
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

                    <!-- Email input -->
                    <label for="uname"><b>Username</b></label>
                    <input type="text" placeholder="Enter Username" name="cus_username"
                        value="<?php echo (isset($_POST['cus_username']))?($_POST['cus_username']):'';?>">

                    <label for="psw"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password"
                        value="<?php echo (isset($_POST['password']))?($_POST['password']):'';?>">

                    <input type='submit' value='Login' class='btn btn-primary' />

                </form>
            </div>
        </div>
        </div>
    </section>
</body>

</html>