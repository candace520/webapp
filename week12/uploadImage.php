<!DOCTYPE html>
<html>

<head>
    <title>Upload Image Exercise</title>
</head>

<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="upload">
    </form>
</body>

</html>