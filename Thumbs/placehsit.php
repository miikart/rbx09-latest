<?php

$assetId = isset($_GET['assetId']) ? (int)$_GET['assetId'] : 0;
$isSmall = isset($_GET['isSmall']) && $_GET['isSmall'] == '';
$unavaidd = $_SERVER['DOCUMENT_ROOT'] . "/images/unavail.png";
 $unavail = imagecreatefrompng($unavaidd);
$thumbnailPath = $_SERVER['DOCUMENT_ROOT'] . "/Thumbs/CATALOG/" . $assetId . ".png";
if (file_exists($thumbnailPath)) {
    $image = imagecreatefrompng($thumbnailPath);
        $unavail = imagecreatefrompng($unavaidd);
    if ($image === false) {
        header('Location: /images/unavail-160x100.png');
        exit;
    }

    // Preserve alpha transparency
    imagealphablending($image, false);
    imagesavealpha($image, true);

    // Set content type
    header("Content-Type: image/png");

    if ($isSmall) {
     $targetWidth = isset($_GET['x']) ? intval($_GET['x']) : 160;
$targetHeight = isset($_GET['y']) ? intval($_GET['y']) : 100;

// Create a new image resource with transparent background
$resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);

// Preserve alpha transparency for resized image
imagealphablending($resizedImage, false);
imagesavealpha($resizedImage, true);
$transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
imagefill($resizedImage, 0, 0, $transparent);

// Assuming $unavail is your original image resource
$originalWidth = imagesx($image);
$originalHeight = imagesy($image);

// Resize and copy the original image onto the resized canvas
imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $originalWidth, $originalHeight);

// Output the resized image
imagepng($resizedImage, null, 9);

// Clean up resources
imagedestroy($resizedImage);

  exit;
    } else {
        // Output the original image without resizing
        imagepng($image);

        // Free up memory
        imagedestroy($image);
   exit;
    }
} else {
 header("Content-Type: image/png");
$targetWidth = isset($_GET['x']) ? intval($_GET['x']) : 160;
$targetHeight = isset($_GET['y']) ? intval($_GET['y']) : 100;

// Create a new image resource with transparent background
$resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);

// Preserve alpha transparency for resized image
imagealphablending($resizedImage, false);
imagesavealpha($resizedImage, true);
$transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
imagefill($resizedImage, 0, 0, $transparent);

// Assuming $unavail is your original image resource
$originalWidth = imagesx($unavail);
$originalHeight = imagesy($unavail);

// Resize and copy the original image onto the resized canvas
imagecopyresampled($resizedImage, $unavail, 0, 0, 0, 0, $targetWidth, $targetHeight, $originalWidth, $originalHeight);

// Output the resized image
imagepng($resizedImage, null, 9);

// Clean up resources
imagedestroy($resizedImage);

    exit;
}

?>
