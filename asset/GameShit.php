<?php
//tysm - dimpersbumpers on discord for giving this public
	
include $_SERVER["DOCUMENT_ROOT"]."/api/web/config.php";

$pid = $_GET['pid'];

$selectQuery = $con->prepare("SELECT * FROM catalog WHERE creatorid = :creatorid AND apikey = :pid AND type = 'place'");
$selectQuery->execute(array(':creatorid' => $_USER['id'], ':pid' => $pid));
$newplace = $selectQuery->fetch(PDO::FETCH_ASSOC);

if (!$newplace) {
    echo 'FRAKING STUPID IOITSd';
    exit();
}
$updateQuery = $con->prepare("UPDATE catalog SET creation_date = NOW() WHERE creatorid = :creatorid AND apikey = :pid AND type = 'place'");
$updateQuery->execute(array(':creatorid' => $_USER['id'], ':pid' => $pid));

$robloxContents = file_get_contents('php://input');
$fix = zlib_decode($robloxContents);
$fp = fopen("{$_SERVER['DOCUMENT_ROOT']}/PlaceAsset/9yI7Js2rTpU5gWxLqHhFGaA1vlKwDcXBN6RfC0bnZPxSYjMi3e/{$newplace['id']}.rbxl", 'w');
fwrite($fp, $fix);
fclose($fp);

header("Location: /api/renderplacetest.ashx?id={$newplace['id']}");
exit();
?>