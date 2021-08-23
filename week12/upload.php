<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);//the name of target file u choose
$fileToUpload = $_FILES['fileToUpload']['name']; 
$isUploadOK = TRUE;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["upload"])&&!empty($_POST["upload"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
 if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
        $isUploadOK = 1;
  } else {
      echo "File is not an image.";
      $isUploadOK = 0;
 }
 list($width, $height, $type, $attr) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                        if($width!=$height){
                            $isUploadOK = false;
                            echo"<div class='alert alert-danger'>Please make sure File is in square!</div>";  
                        }
}


// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $isUploadOK = false;
}
// Check if $uploadOk is set to 0 by an error
if ($isUploadOK == false) {
    echo ("Sorry, your file was not uploaded.");// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>