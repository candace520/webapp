<!DOCTYPE html>
<html>

<head>
    <title>Read File Exercise</title>
</head>

<body>

    <?php
    $myfile = fopen("text.txt", "r") ;
    // Output one line until end-of-file
    while(!feof($myfile)) {
        $line = fgets($myfile);
      echo ($line) . "<br>";
    }
    fclose($myfile);
    ?>

</body>

</html>