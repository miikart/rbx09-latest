<?php
header("content-type:text/plain");
$ip = $_GET['ip'];
$port = $_GET['port'];
$id = rand();
$user = $_GET['user'];
$app = $_GET['app'];
$f1 = str_replace("%user%",$user,file_get_contents("./launchcode.txt"));
$f2 = str_replace("%ip%",$ip,$f1);
$f3 = str_replace("%port%",$port,$f2);
$f4 = str_replace("%id%",$id,$f3);
$f5 = str_replace("%app%",$app,$f4);
echo($f5);
?>
