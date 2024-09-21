<?php
header("Content-type: text/plain");
include "../../api/web/config.php";
$allowedUserAgent = 'Goldblox';

$userAgent = $_SERVER['HTTP_USER_AGENT'];

if ($userAgent === $allowedUserAgent) {
$pid = (int)$_GET['placeId'];
$action = htmlspecialchars((string)$_GET['action']);
$pcount = (int)$_GET['PlayerCount'];
if (empty($action)) {
  die("actionactionactionactionactionactionaction");
}
try {
    $con = new PDO("mysql:host=localhost;dbname=whale;charset=utf8", "whale", "726038cacc24c8");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  switch ($action) {
    case 'PlayerAdded':
      $upd = $con->prepare("UPDATE `catalog` SET players = :newplr WHERE id = $pid AND type='place'");
      $upd->execute([":newplr" => $pcount]);
      break;
      
    case 'PlayerRemoving':
      $upd = $con->prepare("UPDATE `catalog` SET players = :newplr WHERE id = $pid AND type='place'");
      $upd->execute([":newplr" => $pcount]);
      break;
    
    default:
      die('actionactionactionactionactionactionaction');
      break;
  }
   
} catch (PDOException $e) {
  die("Error: " . $e->getMessage());
}
 } else {
echo ('nah');
exit;
}
?>