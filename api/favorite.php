<?php
header("Content-type: text/plain");
include "../api/web/config.php";
 if($auth == false){
exit('fag');
 }
   if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("no thx.");
    }

try {
    $uid = $_USER['id'];
    $id = htmlspecialchars((int)$_GET['id']);
    if ($id < 1) {
        die("Invalid ID.");
    }

    $stmt = $con->prepare("SELECT type FROM catalog WHERE id=:id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        die("Database did not return SHIT!!!!!.");
    }
    $type = $result['type'];

    $stmt = $con->prepare("SELECT * FROM favorites WHERE uid = :uid AND itemid = :id");
    $stmt->bindParam(':uid', $uid);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $rows = $stmt->rowCount();
    if ($rows != 0) {
        header("Location: /Item.aspx?ID=" . $uid);
    } else {
        $stmt = $con->prepare("INSERT INTO favorites (uid, itemid, type) VALUES (:uid, :id, :type)");
        $stmt->bindParam(':uid', $uid);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
    }
   context::favoritelog("{$_USER['username']} favorited item http://www.rccs.lol/Item.aspx?ID={$id} ");
    header("Location: /Item.aspx?ID=" . $id);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
