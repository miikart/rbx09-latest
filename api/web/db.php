<?php
// AMAZING site works! (db connection)
define('DBS', 'databaseserver');
define('DBU', 'databaseusername');
define('DBP', 'databasepassword');
define('DBN', 'databasename');
try {
$link = mysqli_connect(DBS, DBU, DBP, DBN);
$conn = $link;
if (!$link) {
throw new Exception(mysqli_connect_error());
}
} catch (Exception $e) {
echo '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>trashcan</title>
</head>
<body>
<div style="margin: auto; text-align:left; padding: 20px;">
<div style="font-size:16px;">
WAKE UP TRASHCAN!!!<br>
fail: ' . $e->getMessage() . ' this is mysqli failure (used for things not rewritten yet).
</div>
</div>
</body>
</html>';
exit;
}
?>