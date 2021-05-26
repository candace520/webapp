<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<style>

</style>

<body>

  <div class="container">
    <div class="btn-group">
      <button class="btn btn-info btn-lg dropdown-toggle" type="button" data-toggle="dropdown">Day
        <span class="caret"></span></button>

      <ul class="dropdown-menu">
        <?php
        for ($i = 1; $i <= 31; $i++) {
          echo $i . "<br>";
        }
        ?>

      </ul>
    </div>
    <div class="btn-group">
      <button class="btn btn-warning btn-lg dropdown-toggle" type="button" data-toggle="dropdown">Month
        <span class="caret"></span></button>

      <ul class="dropdown-menu">
        <?php
        for ($i = 1; $i <= 12; $i++) {
          echo $i . "<br>";
        }
        ?>

      </ul>
    </div>
    <div class="btn-group">
      <button class="btn btn-danger btn-lg dropdown-toggle" type="button" data-toggle="dropdown">Year
        <span class="caret"></span></button>

      <ul class="dropdown-menu">
        <?php
        for ($i = 1900; $i <= 2021; $i++) {
          echo $i . "<br>";
        }
        ?>

      </ul>
    </div>
  </div>

</body>

</html>