<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/config.php';
try {
  
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_USER['USER_PERMISSIONS'] !== 'Administrator') {
    include $_SERVER["DOCUMENT_ROOT"].'/Error/Default.php';
    exit;
}

if (!$auth) {
    header("Location: /error.php");
    exit;
}

if ($_USER['id'] == "0") {
    die("Error rendering."); // temporary thing until i add some render blacklist
}

if ($type == "Face") {
    die("Error rendering."); // temporary thing until i add some render blacklist
}

if (!isset($_POST['id'])) {
    $id = (int)$_GET['id'];
} else {
    $id = (int)$_POST['id'];
}




$stmt = $con->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$forassets = "http://$domain/asset/?id=";

$sql = "SELECT * FROM catalog WHERE id = :id";
$stmt = $con->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    // Handle case where item with specified id is not found
    die("Item not found.");
}

$type = "";

switch ($item['type']) {
    case "head":
        $type = "Head";
        break;
    case "face":
        $type = "Face";
        break;
    case "hat":
        $type = "Hat";
        break;
    case "tshirt":
        $type = "T-Shirt";
        break;
    case "shirt":
        $type = "Shirt";
        break;
    case "pants":
        $type = "Pants";
        break;
    case "decal":
        $type = "Decal";
        break;
    case "model":
        $type = "Model";
        break;
    case "gear":
        $type = "Gear";
        break;
}

if ($type == "Face") {
    die("you can't and you won't render a face."); // temporary thing until i add some render blacklist
}

// Construct item script based on item type
$itemscript = "";
$char = false; // Assuming default is false, adjust as needed

switch ($type) {
    case "T-Shirt":
         $itemscript = "local Hat = game:GetObjects('".$forassets.$id."')[1]
        Hat.Parent = game.Players.LocalPlayer.Character";
        $char = true;
        break;
    case "Shirt":
             $itemscript = "local Hat = game:GetObjects('".$forassets.$id."')[1]
        Hat.Parent = game.Players.LocalPlayer.Character";
        $char = true;
        break;
    case "Pants":
            $itemscript = "local Hat = game:GetObjects('".$forassets.$id."')[1]
        Hat.Parent = game.Players.LocalPlayer.Character";
        $char = true;
        break;
    case "Hat":
        $itemscript = "local Hat = game:GetObjects('".$forassets.$id."')[1]
        Hat.Parent = game.Workspace";
        $char = false;
        break;
    case "Gear":
        $itemscript = "local Tool = game:GetObjects('".$forassets.$id."')[1]
        Tool.Parent = game.Workspace";
        $char = false;
        break;
case "Head":
        $itemscript = 'local heaa = game:GetObjects("http://'. $domain. '/asset/?id=1392&amp;a='.rand(1,getrandmax()).'")
heaa[1].Parent = workspace
heaa[2].Parent = workspace
local head = heaa[1]
local item = game:GetObjects("http://'. $domain. '/asset/?id='.($id - 2).'&amp;a='.rand(1,getrandmax()).'")
item[1].Parent = head
local face = heaa[2]
local item2 = game:GetObjects("http://'. $domain. '/asset/?id='.($id - 1).'&amp;a='.rand(1,getrandmax()).'")
item2[1].Parent = face
item2[1].Scale = Vector3.new(1.05,1.05,1.05,1.05)
item2[1].TextureId = "http://'. $domain. '/asset/?id=1185"
--local result = game:GetService("ThumbnailGenerator"):Click("PNG", 500, 500, true)
--return result';
        $char = false;
        break;
    
}

 
// Set up body colors
$torsoColor = '"'.$user['HeadColor'].'"';
$rightLegColor = '"'.$user['RightLegColor'].'"';
$leftLegColor = '"'.$user['LeftLegColor'].'"';
$rightArmColor = '"'.$user['RightArmColor'].'"';
$leftArmColor = '"'.$user['LeftArmColor'].'"';
$headColor = '"'.$user['HeadColor'].'"';
$tShirt = '""'; // Adjust as needed
$pants = '""'; // Adjust as needed
$shirt = '""'; // Adjust as needed
$face = '"rbxasset://textures/face.png"'; // Default face texture
$studs = '""'; // Default studs texture

// Process item rendering
if ($char) {
    $charpart1 = "game.Players:CreateLocalPlayer(0)
    game.Players.LocalPlayer:LoadCharacter()";

    $charpart2 = 'bodyColors = Instance.new("BodyColors", game.Players.LocalPlayer.Character)
    bodyColors.HeadColor = BrickColor.new(1)
    bodyColors.LeftArmColor = BrickColor.new(1)
    bodyColors.RightArmColor = BrickColor.new(1)
    bodyColors.LeftLegColor = BrickColor.new(1)
    bodyColors.RightLegColor = BrickColor.new(1)
    bodyColors.TorsoColor = BrickColor.new(1)

    local char = game.Players.LocalPlayer.Character or game.Players.LocalPlayer.Character.CharacterAdded:Wait()';
}
$location = $_SERVER["DOCUMENT_ROOT"]."/Thumbs/CATALOG/".$id.".png";
$location2 = $_SERVER["DOCUMENT_ROOT"]."/Thumbs/CATALOG/".$id."-small.png";
$decoded = base64_decode($RCCAPI->render(2009, $charpart1."\n".$itemscript."\n".$charpart2, 480, 480));
  if(!empty($decoded)) {
    $image = imagecreatefromstring($decoded);
            $width = imagesx($image);
            $height = imagesy($image);

       
                $newWidth = 250;
                $newHeight = 250;
          

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagesavealpha($resizedImage, true);
            $transparentColor = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
            imagefill($resizedImage, 0, 0, $transparentColor);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

       
            imagepng($resizedImage, $location);

            imagedestroy($image);
            imagedestroy($resizedImage);
    $sigmaimage = imagecreatefromstring($decoded);
            $width = imagesx($sigmaimage);
            $height = imagesy($sigmaimage);

       
                $newWidth = 120;
                $newHeight = 120;
          

            $sigma = imagecreatetruecolor($newWidth, $newHeight);
            imagesavealpha($sigma, true);
            $transparentColor = imagecolorallocatealpha($sigma, 0, 0, 0, 127);
            imagefill($sigma, 0, 0, $transparentColor);
            imagecopyresampled($sigma, $sigmaimage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

      
            imagepng($sigma, $location2);

            imagedestroy($sigmaimage);
            imagedestroy($sigma);
exit('Success');
} else {
exit('fail');   
}
?>
