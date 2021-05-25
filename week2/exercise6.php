<!DOCTYPE html>
<html>
<head>
<title>exercise6</title>
<style>
.bigdiv{
    color: red;
    font-weight: bold;
}
.smalldiv{
    color: blue;
}

</style>
</head>
<body>
<?php
$x = rand(1,100);
$y = rand(1,100);
echo "The first random number is " . $x . "<br>";
echo "The second random number is " . $y . "<br>";
if ($x > $y) { 
  echo "The larger random number is " . "<div class=bigdiv>".$x."</div>"."<br>";
  echo "The smaller random number is " . "<div class=smalldiv>".$y."</div>";
}
?>

</body>
</html>
