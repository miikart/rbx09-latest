<?php if($_GET['accountcode'] == 'Guest') {include('guestplay.php'); die();} ?>
dofile("http://miimak.xyz/join/JoinServer.php?<?= $_SERVER["QUERY_STRING"]; ?>&")
dofile("http://miimak.xyz/join/character.php?<?= $_SERVER["QUERY_STRING"]; ?>&")