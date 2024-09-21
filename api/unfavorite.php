<?php
header("Content-type: text/plain");
include "../api/web/config.php";


if ($auth== false) {

    header("Location: /Login/Default.aspx");
    exit;
}
   if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("no thx.");
    }
$uid = $_USER['id']; 
   $id = htmlspecialchars((int)$_GET['id']);

if ($id < 1) {
    die("Invalid ID.");
}

try {
   
  

    $stmt = $con->prepare("SELECT COUNT(*) FROM favorites WHERE uid = :uid AND itemid = :itemid");
    $stmt->execute(['uid' => $uid, 'itemid' => $id]);
    $_1 = $stmt->fetchColumn();

    if ($_1 == 0) {
        echo("Success");
        exit;
    } else {
 
        $stmt = $con->prepare("SELECT COUNT(*) FROM catalog WHERE id = :id");
        $stmt->execute(['id' => $id]);
        if ($stmt->fetchColumn() == 0) {
            die("Invalid ID.");
        }

        
        $stmt = $con->prepare("DELETE FROM favorites WHERE uid = :uid AND itemid = :itemid");
        $stmt->execute(['uid' => $uid, 'itemid' => $id]);
    }
   context::favoritelog("{$_USER['username']} unfavorited item http://www.rccs.lol/Item.aspx?ID={$id} ");

    echo("Success");
    exit;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
