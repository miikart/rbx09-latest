<?php
define('DB_SERVER', 'useserv00fordeath');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'databasename');
try {
    $con = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD, [ PDO::ATTR_PERSISTENT => true ]);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>trashcan</title>
    </head>
    <body>
    <div style="margin: auto; text-align:left; padding: 20px;">
    <div style="font-size:16px;">
    WAKE UP TRASHCAN!!!<br>
    fail: ' . $e->getMessage() . ' this is pdo failure.
    </div>
    </div>
    </body>
    </html>';
    exit;
}
?>