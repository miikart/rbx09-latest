<?php
// no gpt -copy
$i = (int)($_REQUEST['assetId']);
$s = isset($_REQUEST['IsSmall']);
$square = isset($_REQUEST['square']);
$u = $_SERVER['DOCUMENT_ROOT'] . '/images/unavail-352x352.Png';
header("Content-Type: image/png");
if($s) {
$a = $_SERVER['DOCUMENT_ROOT'] . '/Thumbs/USERS/'. $i . '-small.png'; 
} elseif($square) {
$a = $_SERVER['DOCUMENT_ROOT'] . '/Thumbs/USERS/'. $i . '-square.png';
} else { 
$a = $_SERVER['DOCUMENT_ROOT'] . '/Thumbs/USERS/'. $i . '.png';
}
if (!file_exists($a)) {
echo file_get_contents($u);
exit;
} else {
echo file_get_contents($a);
exit;
}
?>
