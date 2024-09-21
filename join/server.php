<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.php");
header('Content-Type:text/plain');
$id = (int)$_GET["game"];
$stmt = $conn->prepare("SELECT * FROM games WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$game = $stmt->fetch(PDO::FETCH_ASSOC);
?>
dofile('http://miimak.xyz/join/HostServer.php?port=<?= $game['port']; ?>&game=<?= $id; ?>') 
