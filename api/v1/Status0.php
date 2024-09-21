<?php
// 100 percent gpt real
require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/config.php");
$allowedUserAgent = 'Goldblox';

$userAgent = $_SERVER['HTTP_USER_AGENT'];

if ($userAgent === $allowedUserAgent) {

if(isset($_GET['apikey']) && isset($_GET['userId'])) {
   
    $apikey = mysqli_real_escape_string($link, $_GET['apikey']);
    $userId = mysqli_real_escape_string($link, $_GET['userId']);


 
        $updateQuery = mysqli_query($link, "UPDATE users SET playing='0', expiretime='0' WHERE id='$userId'");
 
}
 } else {
echo ('nah');
exit;
}
?>