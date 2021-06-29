<?php
// Start the session
session_start();


?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
    }

    form {
      border: 3px solid #f1f1f1;
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
      width: 40%;
      border-radius: 50%;
    }

    .container {
      padding: 16px;
    }

    span.psw {
      float: right;
      padding-top: 16px;
    }

    /* Change styles for span and cancel button on extra small screens */
    @media screen and (max-width: 300px) {
      span.psw {
        display: block;
        float: none;
      }

      .cancelbtn {
        width: 100%;
      }
    }
  </style>
</head>

<body>

  <h2>Login Form</h2>
  <?php
  if ($_POST) {
      
    
    include 'config/database.php';
   
    $cus_username = $_POST['cus_username'];
    $password = $_POST['password'];
    try {
      if (
        empty($_POST['cus_username'])  ||  empty($_POST['password'])
      ) {
        throw new Exception("<div class='alert alert-danger'>Please make sure all fields are not empty!</div>");
      }
      if (isset($_POST['cus_username']) ) {
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
       $query = "SELECT * from  customer WHERE  cus_username=:cus_username AND accountstatus='active'";

       $stmt = $con->prepare($query);
       $stmt->bindParam(':cus_username', $cus_username);
       // posted values
       // bind the parameters
       // Execute the query
       if ($stmt->execute()) {
         $count = $stmt->rowCount();
         $row   = $stmt->fetch(PDO::FETCH_ASSOC);
         if ($count == 1 && !empty($row)) {
 
         }
         else{
           echo"<div class='alert alert-danger'>Your Account NOT ACTIVE!!</div>";
         }
       }

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
        if($count == 1 && !empty($row)) {
        } 
        else{
          echo "<div class='alert alert-danger'>User Not found !</div>";
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
        if($count == 1 && !empty($row)) {
            
        } 
        else{
          echo "<div class='alert alert-danger'>INVALID password!</div>";
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
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" method="post">
    <div class="imgcontainer">
      <img src="img/img_avatar2.png" alt="Avatar" class="avatar">
    </div>

    <div class="container">
      <label for="uname"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="cus_username">

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password">

      <input type='submit' value='Login' class='btn btn-primary' href='index.php' />
    </div>

  </form>

</body>

</html>