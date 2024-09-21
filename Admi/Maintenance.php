<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/api/web/config.php";

if ($auth === false) {
    header("Location: /Error/Default.aspx");
    exit();
}

if ($_USER['USER_PERMISSIONS'] !== "Administrator") {
    header("Location: /Error/Default.aspx");
    exit();
}

try {
  if ((int)$_REQUEST['enable'] === 1) {
      $test = "UPDATE sitesettings SET offline = 'true'";
                $test = $con->prepare($test);
                $test->execute();
    
    
    header('Location: /Admi/Default.aspx');
    exit;
  } else {


    $test = "UPDATE sitesettings SET offline = 'false'";
                $test = $con->prepare($test);
                $test->execute();
    header('Location: /Admi/Default.aspx');
    exit;

}
} catch (PDOException $e) {
    echo 'fukc: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'fukc: ' . $e->getMessage();
}
?>
