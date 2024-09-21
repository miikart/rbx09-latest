<?php
include $_SERVER["DOCUMENT_ROOT"] . "/api/web/config.php";

if (!$auth) {
    die("You are not logged in!");
}


$id = (int)$_POST['item'];
$comment = trim($_POST['content']);
    $item = $con->prepare("SELECT * FROM catalog WHERE id = :id");
    $item->execute(['id' => $id]);

$item = $item->fetch(PDO::FETCH_ASSOC);
if ($item && $item['commentsenabled'] == 0) {
    echo "Commentary for this item has been disabled.";
    die();
}
 

if (strlen($comment) < 1 || strlen($comment) > 500) {
    echo("Your comment is too long or too tiny. <a href='javascript:return false;' onclick='getComments(1, $id)'>Refresh Comments</a>");
    die();
}

$bypasscheck = str_replace(" ", "", $comment);

if (strlen($bypasscheck) < 1) {
    echo("Your comment is too long or too tiny. <a href='javascript:return false;' onclick='getComments(1, $id)'>Refresh Comments</a>");
    die();
}




    $checkStmt = $con->prepare("SELECT * FROM comments WHERE userid = :userid  ORDER BY id DESC LIMIT 1");
    $checkStmt->execute(['userid' => $_USER['id']]);

    $ratelimited = false;
    if ($checkStmt->rowCount() == 1) {
        $ratelimit = $checkStmt->fetch(PDO::FETCH_ASSOC);
        if (time() <= $ratelimit['timeout']) {
            $ratelimited = true;
        }
    }

    if ($ratelimited) {
        echo("You are being rate limited. <a href='javascript:return false;' onclick='getComments(1, $id)'>Refresh Comments</a>");
        die();
    }

  
      $timeout = time() + 15;
    $time = time();
    $insertStmt = $con->prepare("INSERT INTO comments (userid, assetid, content, time_posted, timeout) VALUES (:userid, :assetid, :content, :time, :timeout)");
    $insertStmt->execute([
        'userid' => $_USER['id'],
        'assetid' => $id,
        'content' => htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'),
        'time' => $time,
    'timeout' => $timeout,
    ]);
context::commentlog("{$_USER['username']} (id {$_USER['id']}) commented \"{$comment}\" on http://www.rccs.lol/Item.aspx?ID={$id}");
    exit("Success");

?>
