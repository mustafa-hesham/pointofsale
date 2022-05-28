<?php

function uploadImage($target_dir, $imageFile, &$targetFile, $itemName, $itemPrice){
    $_SESSION['imageErrors'] = array();
    $imageFileType = strtolower(pathinfo($imageFile['name'],PATHINFO_EXTENSION));
    $targetFile = $target_dir . $itemName . '_' . $itemPrice.'.' .$imageFileType;
    $targetFile = str_replace(' ', '_', $targetFile);
    $uploadOk = 1;
    

    // Check if image file is a actual image or fake image

    $check = getimagesize($imageFile["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        array_push($_SESSION['imageErrors'], 'File is not an image.');
        $uploadOk = 0;
        }
 

    // Check if file already exists
    if (file_exists($targetFile)) {
        array_push($_SESSION['imageErrors'], 'Sorry, file already exists.');
        $uploadOk = 0;
    }

    // Check file size
    if ($imageFile["size"] > 500000) {
        array_push($_SESSION['imageErrors'], 'Sorry, your file is too large.');
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            array_push($_SESSION['imageErrors'], 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
            $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        array_push($_SESSION['imageErrors'], 'Sorry, your file was not uploaded.');
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($imageFile["tmp_name"], $targetFile)) {
          //  echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        } else {
        array_push($_SESSION['imageErrors'], 'Sorry, there was an error uploading your file.');
            }
        }
}


?>
