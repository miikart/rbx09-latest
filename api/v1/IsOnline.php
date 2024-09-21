<?php
header("Content-type: text/plain");
include "../../api/web/config.php";

$pid = (int)$_GET['placeid'];
$action = htmlspecialchars((string)$_GET['action']);

if (empty($action)) {
    die("Action not specified.");
}

switch ($action) {
    case 'Online':
        $sql = "UPDATE `games` SET isoffline = 0 WHERE id = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("i", $pid); // Use "i" for integer type
        $stmt->execute();
        $stmt->close();
        break;

    case 'Offline':
        $sql = "UPDATE `games` SET isoffline = 1 WHERE id = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("i", $pid); // Use "i" for integer type
        $stmt->execute();
        $stmt->close();
        break;

    default:
        die('Invalid action.');
}
?>
