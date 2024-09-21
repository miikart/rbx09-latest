<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/config.php';
// rewritten by copy 2024 (also no gpt)
$id = (int)$_REQUEST["id"];
if ($auth == false) {
    exit(header("Location: /Login/Default.aspx"));
}
$robucks = isset($_REQUEST['buybux']);
$q = $con->prepare("SELECT * FROM catalog WHERE id = :id AND type !='place' AND type !='asset'");
$q->bindParam(':id', $id, PDO::PARAM_INT);
$q->execute();
$item = $q->fetch(PDO::FETCH_ASSOC);
if (!$item || $item['isoffsale'] == '1') {
    die('no');
}
$q = $con->prepare("SELECT * FROM owned_items WHERE itemid = :itemid AND ownerid = :ownerid");
$q->bindParam(':itemid', $id, PDO::PARAM_INT);
$q->bindParam(':ownerid', $_USER['id'], PDO::PARAM_INT);
$q->execute();
$owned = $q->fetch(PDO::FETCH_ASSOC);
if ($owned) {
    exit('ItemAlreadyOwned');
}
$q = $con->prepare("SELECT * FROM users WHERE id = :id");
$q->bindParam(':id', $item['creatorid'], PDO::PARAM_INT);
$q->execute();
$ownah = $q->fetch(PDO::FETCH_ASSOC);
if (!$ownah) {
    exit('no');
}
if ($robucks) {
    if ($item['buywith2'] != 'GOLDBUX') {
        exit('no');
    }
    $currency = 'GOLDBUX';
    $price = $item['price2'];
} else {
    if ($item['buywith'] != 'tix') {
        exit('no');
    }
    $currency = 'tix';
    $price = $item['price'];
}
    if ($currency == 'tix') {
        if ($_USER['tix'] >= $price) {
        $tixafter = $_USER['tix'] - $price;
        $tixafterowner = $ownah['tix'] + $price;
        $q = $con->prepare("UPDATE users SET tix = :tix WHERE id = :id");
        $q->bindParam(":tix", $tixafter, PDO::PARAM_INT);  
        $q->bindParam(":id", $_USER['id'], PDO::PARAM_INT);  
        $q->execute();
        $q = $con->prepare("UPDATE users SET tix = :tix WHERE id = :id");
        $q->bindParam(":tix", $tixafterowner, PDO::PARAM_INT);  
        $q->bindParam(":id", $ownah['id'], PDO::PARAM_INT);  
        $q->execute();
    } else {
        exit('nuh uh mate');
    }  
} elseif ($currency == 'GOLDBUX') {
        if ($_USER['GOLDBUX'] >= $price) {
        $buxafter = $_USER['GOLDBUX'] - $price;
        $buxafterowner = $ownah['GOLDBUX'] + $price;
        $q = $con->prepare("UPDATE users SET GOLDBUX = :GOLDBUX WHERE id = :id");
        $q->bindParam(":GOLDBUX", $buxafter, PDO::PARAM_INT);  
        $q->bindParam(":id", $_USER['id'], PDO::PARAM_INT);  
        $q->execute();
        $q = $con->prepare("UPDATE users SET GOLDBUX = :GOLDBUX WHERE id = :id");
        $q->bindParam(":GOLDBUX", $buxafterowner, PDO::PARAM_INT);  
        $q->bindParam(":id", $ownah['id'], PDO::PARAM_INT);  
        $q->execute();
    } else {
        exit('nuh uh mate');
    }
}
$q = $con->prepare("INSERT INTO owned_items (itemid, ownerid, type) VALUES (:itemid, :ownerid, :type)");
$q->bindParam(":itemid", $item['id'], PDO::PARAM_INT);  
$q->bindParam(":ownerid", $_USER['id'], PDO::PARAM_INT);  
$q->bindParam(":type", $item['type'], PDO::PARAM_STR);  
$q->execute();
context::boughtitem("# " . $_USER['username'] . " (id " . $_USER['id'] . ") bought  # \n**Name:** " . $item['name'] . "\n**Price:** " . $price . " " . $currency . " \n**Link:** http://" . $domain . "/Item.aspx?ID=" . $item['id'], 'http://' . $domain . '/Thumbs/Asset.php?assetId=' . $item['id']);
exit('Success');
?>
