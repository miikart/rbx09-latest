<?php
require_once($_SERVER['DOCUMENT_ROOT']."/api/web/config.php");
header('Content-Type: application/octet-stream');

try {
  
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
     $id2 = (int)isset($_REQUEST['id']);
    $stmt = $con->prepare("SELECT * FROM catalog WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $item = $stmt->fetch(PDO::FETCH_ASSOC);


 $assetFile = __DIR__ . "/s/$id";
    if (file_exists($assetFile)) {
     $result = file_get_contents($assetFile);
      $result = str_replace(
    ["rccs.lol", "rccs.lol",  "www.rccs.lol", "www.rccs.lol","www.rccs.lol","www.rccs.lol"],
    "$domain",
    $result
);

      
        exit;
    }
   $assetFile = __DIR__ . "/realassetfrfr/$id";
  if ($item && file_exists($assetFile)) {
    $result = file_get_contents($assetFile);
    $result = str_replace(
        ["placeholder.com"],
        "$domain",
        $result
    );
    echo $result;
    exit;
} else {
    $url = 'https://assetdelivery.roblox.com/v1/asset/?' . $_SERVER["QUERY_STRING"];
    header('Location: ' . $url);
    exit;
}

} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo "Internal Server Error. report to copy.aspx";
    exit;
}
?>
