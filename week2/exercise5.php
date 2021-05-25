<!DOCTYPE html>
<html>
<head>
<title>exercise5</title>
</head>
<body>
<?php
$x = rand(1,100);
$y = rand(1,100);
$total =$x + $y ;
echo "$x * $y =" . $x * $y. "<br>";
echo "$x + $y = " .$total. "<br>";
if ($x > $y) { 
  echo "The larger random number is " . $x;
}
?>

</body>
</html>
