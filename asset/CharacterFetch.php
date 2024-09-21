<?php
header('Content-Type: text/plain');
require_once($_SERVER['DOCUMENT_ROOT'] . "/api/web/config.php");
$id = htmlspecialchars((int)$_GET["userId"]);
$sql = "SELECT * FROM wearing WHERE userid = :userid";
$stmt = $con->prepare($sql);
$stmt->bindParam(':userid', $id, PDO::PARAM_INT);
$stmt->execute();
echo "http://'. $domain . '/asset/BodyColors.ashx?userId=".$id.";";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $itemq = $con->prepare("SELECT * FROM catalog WHERE id = :id");
    $itemq->bindParam(':id', $row['itemid'], PDO::PARAM_INT);
    $itemq->execute();
    $item = $itemq->fetch(PDO::FETCH_ASSOC);
    if (in_array($item['type'], ['tshirt', 'shirt', 'hat', 'pants', 'face', 'gear', 'head'])) {
        $echothing = "http://'. $domain . '/asset/?id=" . $item['id'] . ";";
        echo $echothing;
    }
}
?>
