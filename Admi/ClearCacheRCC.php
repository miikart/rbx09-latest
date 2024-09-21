<?php
include $_SERVER["DOCUMENT_ROOT"]."/api/web/header.php";

if($auth == false) {
     header("Location: /Error/Default.aspx");
    exit();
}

if($_USER['USER_PERMISSIONS'] !== "Administrator") {
    header("Location: /Error/Default.aspx");
    exit();
}

$RCCAPI->clearCache();
header('location: /Admi/Default.aspx');