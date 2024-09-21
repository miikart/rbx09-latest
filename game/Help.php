<?php
header('Content-Type:text/plain');
require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/config.php");

if (!$auth) {
    header('Location: /Login/Studio.aspx');
    exit; 
}

echo 'Logged in.';
?>
