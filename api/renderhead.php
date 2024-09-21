<?php require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/config.php';
if($_USER['USER_PERMISSIONS'] !== 'Administrator') { ?> <?php include '../error.php'; ?> <?php } ?>
 <?php 
if($auth == false){
  header("Location: /error.php");
exit;
    
}
  if($_USER['id'] == "0"){
die("Error rendering."); // temporary thing until i add some render blacklist
}
  if($type == "Face"){
die("Error rendering."); // temporary thing until i add some render blacklist
}

 
if(!$_GET['id']){
$id = (int)$_USER['id'];
}else{
$id = (int)$_GET['id'];
}
error_reporting(0);
$userq = mysqli_query($link, "SELECT * FROM users WHERE id='".$id."'") or die(mysqli_error($link));
$user = mysqli_fetch_assoc($userq);
$forassets = "http://www.rccs.lol/";
$sql = "SELECT * FROM catalog WHERE id='".$id."'";
$result = mysqli_query($link, $sql);
$resultCheck = mysqli_num_rows($result);
if ($resultCheck > 0) {
$item = mysqli_fetch_assoc($result);
if($item['type'] == "head") $type = "Head";
if($item['type'] == "face") $type = "Face";
if($item['type'] == "hat") $type = "Hat";
if($item['type'] == "tshirt") $type = "T-Shirt";
if($item['type'] == "shirt") $type = "Shirt";
if($item['type'] == "pants") $type = "Pants";
if($item['type'] == "decal") $type = "Decal";
if($item['type'] == "model") $type = "Model";
if($item['type'] == "gear") $type = "Gear";
$filename = $item['filename'];
if($type == "T-Shirt") {
$itemscript = 'local TShirt = Instance.new("Decal")
        TShirt.Parent = game.Players.LocalPlayer.Character.Torso
        TShirt.Texture = "'.$forassets.$filename.'"';
  $char = true;
}
if($type == "Shirt") {
$itemscript = 'local Shirt = Instance.new("Shirt", game.Players.LocalPlayer.Character)
        Shirt.ShirtTemplate = "'.$forassets.$filename.'"';
}
  $char = true;
if($type == "Pants") {
$itemscript = 'local Pants = Instance.new("Pants", game.Players.LocalPlayer.Character)
        Pants.PantsTemplate = "'.$forassets.$filename.'"';
}
if($type == "Hat") {
$itemscript = "local Hat = game:GetObjects('".$forassets.$filename."')[1]
         Hat.Parent = game.Workspace";
  $char = false;
}
if($type == "Gear") {
$itemscript = "local Tool = game:GetObjects('".$forassets.$filename."')[1]
         Tool.Parent = game.Workspace";
  $char = false;
}
if($type == "Face") {
die("you cant and you wont render a face."); // temporary thing until i add some render blacklist
}
if($type == "Head") {
$itemscript = 'local heaa = game:GetObjects("http://www.rccs.lol/asset/?id=1392&amp;a='.rand(1,getrandmax()).'")
heaa[1].Parent = workspace
heaa[2].Parent = workspace
local head = heaa[1]
local item = game:GetObjects("http://www.rccs.lol/asset/?id='.($id - 2).'&amp;a='.rand(1,getrandmax()).'")
item[1].Parent = head
local face = heaa[2]
local item2 = game:GetObjects("http://www.rccs.lol/asset/?id='.($id - 1).'&amp;a='.rand(1,getrandmax()).'")
item2[1].Parent = face
item2[1].Scale = Vector3.new(1.05,1.05,1.05,1.05)
item2[1].TextureId = "http://rccs.lol/asset/?id=1185"
--local result = game:GetService("ThumbnailGenerator"):Click("PNG", 500, 500, true)
--return result';

    $char = false;  
}

  // TO DO: hats
}
$torsoColor = "";
$rightLegColor = "";
$leftLegColor = "";
$rightArmColor = "";
$leftArmColor = "";
$headColor = "";
$tShirt = "";
$pants = "";
$shirt = "";
$face = "";
$face = '"rbxasset://textures/face.png"';
$shirt = '""';
$pants = '""';
$tShirt = '"echothing"';
$headColor = '"'.$user['HeadColor'].'"';
$leftArmColor = '"'.$user['LeftArmColor'].'"';
$rightArmColor = '"'.$user['RightArmColor'].'"';
$leftLegColor = '"'.$user['LeftLegColor'].'"';
$rightLegColor = '"'.$user['RightLegColor'].'"';
$torsoColor = '"'.$user['TorsoColor'].'"';
$test = $_GET["shirt"];
$test = '""';
if ($test == '""') {
  $studs = "rbxasset://studs.png";
}
else {
  $studs = "";
}
if($char == true){
  $charpart1 = "game.Players:CreateLocalPlayer(0)
        game.Players.LocalPlayer:LoadCharacter()";
}
if($char == true){
  $charpart2 = 'bodyColors = Instance.new("BodyColors", game.Players.LocalPlayer.Character)
        bodyColors.HeadColor = BrickColor.new(1)
        bodyColors.LeftArmColor = BrickColor.new(1)
        bodyColors.RightArmColor = BrickColor.new(1)
        bodyColors.LeftLegColor = BrickColor.new(1)
        bodyColors.RightLegColor = BrickColor.new(1)
        bodyColors.TorsoColor = BrickColor.new(1)
  
  local char = game.Players.LocalPlayer.Character or game.Players.LocalPlayer.Character.CharacterAdded:Wait()';
}
$decoded = base64_decode($RCCAPI->render(2009, $charpart1."\n".$itemscript."\n".$charpart2, 500, 500));
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/Thumbs/CATALOG/' . $id . '.png';
    file_put_contents($filePath, $decoded);
 

header("Location: /Item.aspx?ID=" . $id);
exit();
//imagepng($im);
//imagedestroy($im);
?>