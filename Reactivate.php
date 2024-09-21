<?php
require_once($_SERVER['DOCUMENT_ROOT']."/api/web/config.php");
if ($_USER['bantype'] == 'Ban') {
    header('location: /Membership/NotApproved.aspx');
    die();
}

try {
    $stmt = $con->prepare("UPDATE `users` SET `bantype` = :bantype WHERE `id` = :id");
    $stmt->bindValue(':bantype', 'None', PDO::PARAM_STR);
    $stmt->bindValue(':id', $_USER['id'], PDO::PARAM_STR);
    $stmt->execute();

    header('location: /');
} catch (PDOException $e) {
 
    error_log("db is a femboy and died" . $e->getMessage());
    header('location: /error.php');
    die();
}
?>
