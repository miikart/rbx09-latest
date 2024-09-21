<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/api/web/dbpdo.php");
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
header("Content-Type: image/png");
$id = (int)$_REQUEST['assetId'];
if ($id === false || $id <= 0) {
    $unavail = $_SERVER['DOCUMENT_ROOT'] . '/images/unavail.png';
            echo file_get_contents($unavail);
        exit;
}
   
$isSmall = isset($_GET['isSmall']);
$size = $isSmall ? 120 : 250;

try {
 
    $stmt = $con->prepare("SELECT * FROM catalog WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
 if (!$result) {
                header("Content-Type: image/png");
             $unavail = $_SERVER['DOCUMENT_ROOT'] . '/images/unavail.png';
           echo file_get_contents($unavail);
        exit;
    }
    if ($result) {
        $type = "";

        $filename = (int)($result['id']) -1;
        $moderation = $result['moderation'];

        switch ($result['type']) {
            case "head": $type = "Head"; break;
            case "face": $type = "Face"; break;
            case "hat": $type = "Hat"; break;
            case "tshirt": $type = "T-Shirt"; break;
            case "shirt": $type = "Shirt"; break;
            case "pants": $type = "Pants"; break;
            case "decal": $type = "Decal"; break;
            case "model": $type = "Model"; break;
            case "gear": $type = "Gear"; break;
            case "place": $type = "Place"; break;
            case "audio": $type = "Audio"; break;
        }
        if ($type == "Audio") {
            header('Location: http://cataas.com/cat/says/no%20image%20found?width=250&height=250&fontColor=white'); 
            exit; 
        }
        if ($type == "Place") {
            include('placehsit.php'); 
            exit; 
        }
        if ($moderation === "pending") {
           header("Content-Type: image/png");
           $unavail = $_SERVER['DOCUMENT_ROOT'] . '/images/pending.png';
         echo file_get_contents($unavail);
        exit;
        }

        if ($moderation === "declined") {
            header("Content-Type: image/png");
            $unavail = $_SERVER['DOCUMENT_ROOT'] . '/images/unapproved.png';
          echo file_get_contents($unavail);
        exit;
        }

        if ($type === "Decal") {
            $decal1 = $_SERVER['DOCUMENT_ROOT'] . '/asset/realassetfrfr/' . $filename;
            $decal = @file_get_contents($decal1);

            if ($decal === false) {
                header("Content-Type: image/png");
                  $unavail = $_SERVER['DOCUMENT_ROOT'] . '/images/unavail.png';
            echo file_get_contents($unavail);
        exit;
            }

            header("Content-Type: image/png");
            echo $decal;
            exit;
        }

        if ($type === "T-Shirt") {
          
            $dest = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] .  '/images/tshirt_temple.png');
       $atj = $_SERVER['DOCUMENT_ROOT'] . '/Thumbs/CATALOG/' . $id . '.png';



$srcdis = "http://www.w.rccs.lol/asset/?id=" . ($id - 1);
    $srcdata = file_get_contents($srcdis);
            $src = imagecreatefromstring($srcdata);
            if (!$src) {
                   header("Content-Type: image/png");
             $unavail = $_SERVER['DOCUMENT_ROOT'] . '/images/unavail.png';
             echo file_get_contents($unavail);
        exit;
            }

            imagealphablending($dest, true);
            imagesavealpha($dest, true);
            $src = imagescale($src, 158, 148);
            imagecopy($dest, $src, 48, 45, 0, 0, 158, 148);
 imagepng($dest, $atj);
            header("Content-Type: image/png");
            imagepng($dest);

            imagedestroy($dest);
            imagedestroy($src);

            exit;
        }
if(!$isSmall) {
            $thumbnailPath = $_SERVER['DOCUMENT_ROOT'] . "/Thumbs/CATALOG/" . $id . ".png";
} else {
   $thumbnailPath = $_SERVER['DOCUMENT_ROOT'] . "/Thumbs/CATALOG/" . $id . "-small.png";   
}

    if (file_get_contents($thumbnailPath)) {
        echo file_get_contents($thumbnailPath);
        exit;
        } else {

             $unavail = $_SERVER['DOCUMENT_ROOT'] . '/images/unavail.png';
             echo file_get_contents($unavail);
        exit;
        }
    }
} catch (Exception $e) {
             $unavail = $_SERVER['DOCUMENT_ROOT'] . '/images/unavail.png';
             echo file_get_contents($unavail);
        exit;

}
ob_end_flush();
?>
