<?php
// 100 percent gpt real
require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/config.php");
$allowedUserAgent = 'Goldblox';

$userAgent = $_SERVER['HTTP_USER_AGENT'];

if ($userAgent === $allowedUserAgent) {
// Check if the apikey and userId are provided in the URL
if(isset($_GET['apikey']) && isset($_GET['userId'])) {
    // Sanitize and store the provided parameters
    $apikey = mysqli_real_escape_string($link, $_GET['apikey']);
    $userId = mysqli_real_escape_string($link, $_GET['userId']);

    // Query to fetch the gameId corresponding to the provided apikey
    $gameQuery = mysqli_query($link, "SELECT id FROM catalog WHERE apikey='$apikey' AND type ='place'");

    // Check if a game with the provided apikey exists
    if(mysqli_num_rows($gameQuery) > 0) {
        // Fetch the gameId
        $gameRow = mysqli_fetch_assoc($gameQuery);
        $gameId = $gameRow['id'];

        // Update the playing field and expiretime in the users table
        $updateQuery = mysqli_query($link, "UPDATE users SET playing='$gameId', expiretime='99999999999' WHERE id='$userId'");
 
}}
 } else {
echo ('nah');
exit;
}
?>
