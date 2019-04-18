<?php

function uploadimg($filename, $width, $get_height, $path, $order) {
    if (trim($_FILES["image"]["tmp_name"][$order]) != "") {
        $tmp_images = $_FILES["image"]["tmp_name"][$order];
        // type select
        if ($_FILES['image']['type'][$order] == 'image/jpeg' OR $_FILES['image']['type'][$order] == 'image/jpg' OR $_FILES['image']['type'][$order] == 'image/pjpeg') {
            $images = $filename . ".jpg";
            //upload source image
            $size = getimagesize($tmp_images);
            //check radio widht and height
            $height = round($width * $size[1] / $size[0]);
            if ($height > $get_height) {
                $width = round($get_height * $size[0] / $size[1]);
                $height = $get_height;
            }
            $images_orig = ImageCreateFromJPEG($tmp_images);
            $photoX = ImagesX($images_orig);
            $photoY = ImagesY($images_orig);
            $images_fin = ImageCreateTrueColor($width, $height);
            ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width + 1, $height + 1, $photoX, $photoY);
            ImageJPEG($images_fin, $path . $images);
            ImageDestroy($images_orig);
            ImageDestroy($images_fin);
            return $images;
        } elseif ($_FILES['image']['type'][$order] == 'image/x-png' OR $_FILES['image']['type'][$order] == 'image/png') {
            $images = $filename . ".png";
            $size = getimagesize($tmp_images);
            //check radio widht and height
            $height = round($width * $size[1] / $size[0]);
            if ($height > $get_height) {
                $width = round($get_height * $size[0] / $size[1]);
                $height = $get_height;
            }
            $images_orig = ImageCreateFromPNG($tmp_images);
            $photoX = ImagesX($images_orig);
            $photoY = ImagesY($images_orig);
            $images_fin = ImageCreateTrueColor($width, $height);
            ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width + 1, $height + 1, $photoX, $photoY);
            Imagepng($images_fin, $path . $images);
            ImageDestroy($images_orig);
            ImageDestroy($images_fin);
            return $images;
        } elseif ($_FILES['image']['type'][$order] == 'image/gif') {
            $images = $filename . ".gif";
            $size = getimagesize($tmp_images);
            //check radio widht and height
            $height = round($width * $size[1] / $size[0]);
            if ($height > $get_height) {
                $width = round($get_height * $size[0] / $size[1]);
                $height = $get_height;
            }
            $images_orig = ImageCreateFromgif($tmp_images);
            $photoX = ImagesX($images_orig);
            $photoY = ImagesY($images_orig);
            $images_fin = ImageCreateTrueColor($width, $height);
            ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width + 1, $height + 1, $photoX, $photoY);
            Imagegif($images_fin, $path . $images);
            ImageDestroy($images_orig);
            ImageDestroy($images_fin);
            return $images;
        } else {
            return FALSE;
        }
    }
}

function uploadimg2($target_path) {
    $j = 0;     // Variable for indexing uploaded image.
    for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
        // Loop to get individual element from the array
        $validextensions = array("jpeg", "jpg", "png");      // Extensions which are allowed.
        $ext = explode('.', basename($_FILES['file']['name'][$i]));   // Explode file name from dot(.)
        $file_extension = end($ext); // Store extensions in the variable.
        $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];     // Set the target path with a new name of image.
        $j = $j + 1;      // Increment the number of uploaded images according to the files in array.
        if (($_FILES["file"]["size"][$i] < 100000) && in_array($file_extension, $validextensions)) {
            if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {
                // If file moved to uploads folder.
                echo $j. ').<span id="noerror">Image uploaded successfully!.</span><br/><br/>';
            } else {     //  If File Was Not Moved.
                echo $j. ').<span id="error">please try again!.</span><br/><br/>';
            }
        } else {     //   If File Size And File Type Was Incorrect.
            echo $j. ').<span id="error">***Invalid file Size or Type***</span><br/><br/>';
        }
    }
}

function checkimg() {
    if ($_FILES['image']['type'] == 'image/jpeg' OR $_FILES['image']['type'] == 'image/jpg' OR $_FILES['image']['type'] == 'image/pjpeg' OR $_FILES['image']['type'] == 'image/x-png' OR $_FILES['image']['type'] == 'image/png' OR $_FILES['image']['type'] == 'image/gif') {
        return TRUE;
    } else {
        return FALSE;
    }
}
