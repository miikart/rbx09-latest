<?php
header('Content-Type:text/plain'); 
  require_once($_SERVER['DOCUMENT_ROOT']."/api/web/config.php");

  $directory = realpath(__DIR__);

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$ticket = isset($_GET["ticket"]) ? $_GET["ticket"] : "";

$rbxlFilePath = $directory . "/9yI7Js2rTpU5gWxLqHhFGaA1vlKwDcXBN6RfC0bnZPxSYjMi3e/" . $id . ".rbxl";

$sql = "SELECT id FROM users WHERE accountcode = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("s", $ticket);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
    

    $sql_game = "SELECT creatorid FROM catalog WHERE id = ?";
    $stmt_game = $link->prepare($sql_game);
    $stmt_game->bind_param("i", $id);
    $stmt_game->execute();
    $result_game = $stmt_game->get_result();


    if ($result_game->num_rows > 0) {
        $row_game = $result_game->fetch_assoc();
        $creator_id = $row_game['creatorid'];


        if ($user_id === $creator_id && file_exists($rbxlFilePath)) {

            $fileContent = file_get_contents($rbxlFilePath);
            echo $fileContent;
        } else {

            echo 'OhioFanumTaxRizzler';
        }

}
}
$stmt->close();
$stmt_game->close();
$link->close();
?>
