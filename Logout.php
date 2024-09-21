<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/api/web/config.php');
if($auth == false){
    header('location: /Login/Default.aspx');
    exit;
}



if(!isset($_REQUEST["all"])) {
    $session = $_COOKIE["GOLDBLOSECURITY"];
    $stmt = $con->prepare("DELETE FROM sessions WHERE sessKey = :sessKey");
    $stmt->bindParam(':sessKey', $session);
} else {
    $stmt = $con->prepare("DELETE FROM sessions WHERE userId = :id");
    $stmt->bindParam(':id', $_USER['id']);
}

if($stmt->execute()) {
    setcookie("GOLDBLOSECURITY", null, -1, '/');
    session_destroy();
    header('location: /');
    exit;
} else {
    echo "Failed to log out. report to copy.aspx";
exit;
    
}
?>