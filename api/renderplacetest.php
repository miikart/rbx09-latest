<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/api/web/config.php';
if ($auth == false) {
    header("Location: /error.php");
    exit;
}
if ($_USER['id'] == "0") {
    die("Error rendering."); // temporary thing until i add some render blacklist
}

$id = (int)$_GET['id'];
error_reporting(0);

// Database query to fetch user
$userq = mysqli_query($link, "SELECT * FROM users WHERE id='" . $id . "'") or die(mysqli_error($link));
$user = mysqli_fetch_assoc($userq);

$forassets = "http://rccs.lol/";
$sql = "SELECT * FROM catalog WHERE id='" . $id . "'";
$result = mysqli_query($link, $sql);
$resultCheck = mysqli_num_rows($result);

if ($resultCheck > 0) {
    $item = mysqli_fetch_assoc($result);
    $type = ucfirst($item['type']);
    
    $filename = $item['filename'];
    $itemscript = '';
    $char = false;

    switch ($type) {
        case "T-Shirt":
            $itemscript = 'local TShirt = Instance.new("Decal")
            TShirt.Parent = game.Players.LocalPlayer.Character.Torso
            TShirt.Texture = "' . $forassets . $filename . '"';
            $char = true;
            break;
        case "Shirt":
            $itemscript = 'local Shirt = Instance.new("Shirt", game.Players.LocalPlayer.Character)
            Shirt.ShirtTemplate = "' . $forassets . $filename . '"';
            $char = true;
            break;
        case "Pants":
            $itemscript = 'local Pants = Instance.new("Pants", game.Players.LocalPlayer.Character)
            Pants.PantsTemplate = "' . $forassets . $filename . '"';
            $char = true;
            break;
        case "Hat":
            $itemscript = "local Hat = game:GetObjects('" . $forassets . $filename . "')[1]
            Hat.Parent = game.Workspace";
            break;
        case "Gear":
            $itemscript = "local Tool = game:GetObjects('" . $forassets . $filename . "')[1]
            Tool.Parent = game.Workspace";
            break;
        case "Face":
            header("Location /Item.aspx?ID=" . $id); // temporary thing until i add some render blacklist
            exit;
            break;
    }

    // Default body color settings
    $headColor = '"' . $user['HeadColor'] . '"';
    $leftArmColor = '"' . $user['LeftArmColor'] . '"';
    $rightArmColor = '"' . $user['RightArmColor'] . '"';
    $leftLegColor = '"' . $user['LeftLegColor'] . '"';
    $rightLegColor = '"' . $user['RightLegColor'] . '"';
    $torsoColor = '"' . $user['TorsoColor'] . '"';

    $xml = '<?xml version="1.0" encoding="UTF-8"?>
    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:ns2="http://roblox.com/RCCServiceSoap" xmlns:ns1="http://roblox.com/" xmlns:ns3="http://roblox.com/RCCServiceSoap12">
      <SOAP-ENV:Body>
        <ns1:OpenJob>
          <ns1:job>
            <ns1:id>' . $id . '</ns1:id>
            <ns1:expirationInSeconds>15</ns1:expirationInSeconds>
            <ns1:category>1</ns1:category>
            <ns1:cores>321</ns1:cores>
          </ns1:job>
          <ns1:script>
            <ns1:name>Script</ns1:name>
            <ns1:script>
  print("Place '.$id.'")
game:load("http://www.rccs.lol/PlaceAsset/?id=' . $id . '&amp;ticket=' . $_USER['accountcode'] . '")

b64 = game:GetService("ThumbnailGenerator"):Click("png", 1280, 720, false)
print("Done!")
return b64
            </ns1:script>
          </ns1:script>
        </ns1:OpenJob>
      </SOAP-ENV:Body>
    </SOAP-ENV:Envelope>';

    // The URL that you want to send your XML to.
    $url = $renderServerUrl;
    // Initiate cURL
    $curl = curl_init($url);
    // Set the Content-Type to text/xml.
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
    // Set CURLOPT_POST to true to send a POST request.
    curl_setopt($curl, CURLOPT_POST, true);
    // Attach the XML string to the body of our request.
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
    // Tell cURL that we want the response to be returned as a string instead of being dumped to the output.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // Execute the POST request and send our XML.
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        die("couldn't connect to rccservice.");
    }
    // Close the cURL handle.
    curl_close($curl);

    $funnybase = $result;
    $luashit = array('LUA_TTABLE', "LUA_TSTRING");
    $data = str_replace($luashit, "", $funnybase);
    $almost = strstr($data, '<ns1:value>');
    $luashit = array('<ns1:value>', "</ns1:value></ns1:OpenJobResult><ns1:OpenJobResult><ns1:type></ns1:type><ns1:table></ns1:table></ns1:OpenJobResult></ns1:OpenJobResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>");
    $yeah = str_replace($luashit, "", $almost);
    $yeah = str_replace("</ns1:value></ns1:OpenJobResult></ns1:OpenJobResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>", "", $yeah);

    $location = $_SERVER["DOCUMENT_ROOT"] . "/Thumbs/CATALOG/" . $id . ".png";

    $imageData = base64_decode($yeah);
    if ($imageData === false) {
        die("Base64 decode failed.");
    }

    // Create an image resource from the binary data
    $image = imagecreatefromstring($imageData);
    if ($image === false) {
        die("Image creation failed.");
    }

    // Get the original dimensions
    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);

    // Calculate the new dimensions
    $newWidth = 420;
    $newHeight = 230;

    // Create a new image resource for the resized image (with transparent background)
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    imagealphablending($resizedImage, false);
    imagesavealpha($resizedImage, true);
    $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
    imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);

    // Resize the image
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

    // Save the resized image
    if (!imagepng($resizedImage, $location)) {
        die("Failed to save image.");
    }

    // Clean up
    imagedestroy($image);
    imagedestroy($resizedImage);

    header("Location: /Games.aspx");
    exit;
} else {
    exit("Something went wrong.");
}
?>
