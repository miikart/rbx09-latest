<?php
if ((int)($_GET['function'] ?? 0) === 1) {
    require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/config.php';
    
    if ($auth === false) {
        header("Location: /Login/Default.aspx");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("Invalid request method.");
    }

    $allowedTypes = ["face", "tshirt", "shirt", "pants", "hat", "head"];
    $queryType = isset($_POST['type']) && (int)$_POST['type'] >= 1 ? (int)$_POST['type'] : 1;
    $queryTypeMap = [2 => "tshirt", 3 => "shirt", 4 => "pants", 5 => "hat", 6 => "face", 7 => "head"];
    $queryType = $queryTypeMap[$queryType] ?? "hat";
    $type = array_search($queryType, $queryTypeMap) ?: 5;

    $resultsPerPage = 8;
    $page = isset($_POST['p']) ? (int)$_POST['p'] : 1;
    $thisPageFirstResult = ($page - 1) * $resultsPerPage;

    // Get items for the current page
    $itemsq = $con->prepare("SELECT * FROM owned_items WHERE ownerid = :ownerid AND type = :type ORDER BY id DESC LIMIT :firstResult, :resultsPerPage");
    $itemsq->bindParam(':ownerid', $_USER["id"], PDO::PARAM_INT);
    $itemsq->bindParam(':type', $queryType, PDO::PARAM_STR);
    $itemsq->bindValue(':firstResult', $thisPageFirstResult, PDO::PARAM_INT);
    $itemsq->bindValue(':resultsPerPage', $resultsPerPage, PDO::PARAM_INT);
    $itemsq->execute();
    $items = $itemsq->fetchAll();

    // Get all items of the specified type
    $itemsaha = $con->prepare("SELECT * FROM owned_items WHERE ownerid = :ownerid AND type = :type ORDER BY id DESC");
    $itemsaha->bindParam(':ownerid', $_USER["id"], PDO::PARAM_INT);
    $itemsaha->bindParam(':type', $queryType, PDO::PARAM_STR);
    $itemsaha->execute();
    $allItems = $itemsaha->fetchAll();

    $numberOfPages = ceil(count($allItems) / $resultsPerPage);

    // Output table rows
    echo "<table ><tbody>";

    $counter = 0;
    foreach ($items as $row) {
        $itemq = $con->prepare("SELECT * FROM catalog WHERE id = :itemid");
        $itemq->bindParam(':itemid', $row['itemid'], PDO::PARAM_INT);
        $itemq->execute();
        $item = $itemq->fetch(PDO::FETCH_ASSOC);

        $iteml = $con->prepare("SELECT * FROM users WHERE id = :creatorid");
        $iteml->bindParam(':creatorid', $item['creatorid'], PDO::PARAM_INT);
        $iteml->execute();
        $_USER = $iteml->fetch(PDO::FETCH_ASSOC);

        $name = ($item['name']);
        $creatorId = htmlspecialchars($item['creatorid']);
        $creator = htmlspecialchars($_USER['username']);

        if ($counter % 4 === 0) {
            if ($counter > 0) {
                echo "</tr>"; 
            }
            echo "<tr>"; 
        }

        echo "
  
        <td valign='top'>
            <div class='Asset'>
                <div class='AssetThumbnail'>
                    <a id='AssetThumbnailHyperLink' title='click to wear'  href='javascript:void(0);'   onclick='wearitem({$item['id']});' style='display:inline-block; cursor:pointer; width: 110px; height: 110px; overflow: hidden;'>
                        <img src='/Thumbs/Asset.ashx?assetId={$item['id']}&amp;isSmall' id='img' alt='{$name}' style='width: 110px; height: 110px; object-fit: cover; object-position: center center; border: 0;'>
                    </a>
                    <a href='javascript:void(0);' onclick='wearitem({$item['id']});' style='
    position: absolute;
    top: 1px;
    right: 1px;
    background-color:#EEE;
    border:solid 1px #000;
    color:blue;font-size:10px;
    font-weight:lighter;'>[ wear ]</a>
                </div>
                <div class='AssetDetails'>
                    <div class='AssetName'><a id='ctl00_cphGoldblox_rbxUserAssetsPane_UserAssetsDataList_ctl06_AssetNameHyperLink' href='/Item.aspx?ID={$item['id']}'>{$name}</a></div>
                    <div><span class='Detail' style='font-weight: bold;'>Creator:</span> <span class='Detail'><a id='ctl00_cphGoldblox_rbxCatalog_AssetsDataList_ctl00_GameCreatorHyperLink' href='/User.aspx?ID={$creatorId}'>{$creator}</a></span></div>
                </div>
            </div>
        </td>";

        $counter++;
    }

    if ($counter % 4 !== 0) {
        echo "</tr>";
    }

    echo "</tbody></table>";

    // Adjust check query for pagination
    $checkt = $con->prepare("SELECT * FROM owned_items WHERE ownerid = :ownerid AND type = :type ORDER BY itemid DESC LIMIT :firstResult, :resultsPerPage");
    $checkt->bindParam(':ownerid', $_USER["id"], PDO::PARAM_INT);
    $checkt->bindParam(':type', $queryType, PDO::PARAM_STR);
    $checkt->bindValue(':firstResult', $thisPageFirstResult, PDO::PARAM_INT);
    $checkt->bindValue(':resultsPerPage', $resultsPerPage, PDO::PARAM_INT);
    $checkt->execute();

    $linkTypeMap = [
        "tshirt" => 2, "hat" => 1, "head" => 7, "face" => 8, "shirt" => 5
    ];
    $catalogLink = isset($linkTypeMap[$queryType]) ? "/Catalog.aspx?c=" . $linkTypeMap[$queryType] : "/Catalog.aspx?c=6";

    if (count($items) === 0) {
        $displayType = $queryType === "tshirt" ? "T-Shirts" : ($queryType === "pants" ? "Pants" : ucfirst($queryType) . 's');
        echo '
        <div id="ctl00_cphGoldblox_rbxWardrobePane_NoResultsPanel" class="NoResults">
            <span id="ctl00_cphGoldblox_rbxWardrobePane_NoResultsLabel" class="NoResults"> You do not own any ' . $displayType . '. Why not shop for some in the <a href="' . $catalogLink . '">Catalog</a>?</span>
        </div>';
    }

    echo "
       <div style=\"clear:both;\"></div>
    <div class='FooterPager'>";
    if ($page > 1) {
        echo "<a href=\"javascript:void(0);\" onclick=\"getwardrobe({$type})\">First</a> ";
        echo "<a href=\"javascript:void(0);\" onclick=\"getwardrobe({$type}, " . ($page - 1) . ")\">Previous</a> ";
    } else {
        echo "<a style=\"color: gray; \">First</a> ";
        echo "<a style=\"color: gray;\">Previous</a> ";
    }

    for ($i = 1; $i <= $numberOfPages; $i++) {
        if ($i === $page) {
            echo "<span>{$i}</span> ";
        } else {
            echo "<a href=\"javascript:void(0);\" onclick=\"getwardrobe({$type}, {$i})\">{$i}</a> ";
        }
    }

    if ($page < $numberOfPages) {
        echo "<a href=\"javascript:void(0);\" onclick=\"getwardrobe({$type}, " . ($page + 1) . ")\">Next</a> ";
        echo "<a href=\"javascript:void(0);\" onclick=\"getwardrobe({$type}, {$numberOfPages})\">Last</a> ";
    } else {
        echo "<a style=\"color: gray; \">Next</a> ";
        echo "<a style=\"color: gray; \">Last</a> ";
    }
    echo "</div>";

    exit;
}
?>



<?php

if ((int)($_GET['function'] ?? 0) == 2) {
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/config.php';

 
 if (!$auth) {
    header("Location: /Login/Default.aspx");
    exit();
}
 if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("fag");
    }
    $stmt = $con->prepare("SELECT * FROM wearing WHERE userid = :userid");
    $stmt->execute([':userid' => $_USER['id']]);

    if ($stmt->rowCount() == 0) {
        echo "
       
      <div id=\"ctl00_cphGoldblox_rbxWearingPane_NoResultsPanel\" class=\"NoResults\">
		
				    <span id=\"ctl00_cphGoldblox_rbxWearingPane_NoResultsLabel\" class=\"NoResults\"> You are not wearing any items from your wardrobe.</span>
				
	</div>

";
  
  
    } else {
         // Output table rows
    echo "<table><tbody>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $itemthingy = $con->prepare("SELECT * FROM catalog WHERE id = :itemid");
            $itemthingy->execute([':itemid' => $row['itemid']]);
            $item = $itemthingy->fetch(PDO::FETCH_ASSOC);


            $creatorthingy = $con->prepare("SELECT * FROM users WHERE id = :creatorid");
            $creatorthingy->execute([':creatorid' => $item['creatorid']]);
            $creator = $creatorthingy->fetch(PDO::FETCH_ASSOC);

            $thumburl = "/Thumbs/Asset.ashx?assetId=" . $item['id'] . "&isSmall";
            $name = $item['name'];
            $creator = htmlspecialchars($creator['username']);
            $creatorId = $item['creatorid'];
            $querytype = null;
            switch ($item['type']) {
                case 'head': $type = "Head"; break;
                case 'face': $type = "Face"; break;
                case 'hat': $type = "Hat"; break;
                case 'tshirt': $type = "T-Shirt"; break;
                case 'shirt': $type = "Shirt"; break;
                case 'pants': $type = "Pants"; break;
                case 'decal': $type = "Decal"; break;
                case 'model': $type = "Model"; break;
                case 'gear': $type = "Gear"; break;
                default: $type = "Unknown"; break;
            }

          if ($counter % 4 === 0) {
            if ($counter > 0) {
                echo "</tr>"; // Close previous row
            }
            echo "<tr>"; // Start a new row
        }

        echo "
        <td valign='top'>
            <div class='Asset'>
                <div class='AssetThumbnail'>
                    <a id='AssetThumbnailHyperLink' title='click to remove'  href='javascript:void(0);' onclick='removeitem({$item['id']});' style='display:inline-block; cursor:pointer; width: 110px; height: 110px; overflow: hidden;'>
                        <img src='/Thumbs/Asset.ashx?assetId={$item['id']}&amp;isSmall' id='img' alt='{$name}' style='width: 110px; height: 110px; object-fit: cover; object-position: center center; border: 0;'>
                    </a>
                    <a href='javascript:void(0);' onclick='removeitem({$item['id']});' s style='
    position: absolute;
    top: 1px;
    right: 1px;
    background-color:#EEE;
    border:solid 1px #000;
    color:blue;font-size:10px;
    font-weight:lighter;'>[ remove ]</a>
                </div>
                <div class='AssetDetails'>
                    <div class='AssetName'><a id='ctl00_cphGoldblox_rbxUserAssetsPane_UserAssetsDataList_ctl06_AssetNameHyperLink' href='/Item.aspx?ID={$item['id']}'>{$name}</a></div>
                    
                     <div><span class='Detail'   style='font-weight: bold;'>Type:</span> <span class='Detail'>{$type}</span></div>
                    <div><span class='Detail' style='font-weight: bold;'>Creator:</span> <span class='Detail'><a id='ctl00_cphGoldblox_rbxCatalog_AssetsDataList_ctl00_GameCreatorHyperLink' href='/User.aspx?ID={$creatorId}'>{$creator}</a></span></div>
                </div>
            </div>
        </td>";

        $counter++;
    }

    if ($counter % 4 !== 0) {
        echo "</tr>";
    }

   

 echo "</tbody></table>";


        
}
  exit;  
}


?>
<?php

if ((int)($_GET['function'] ?? 0) == 3) {
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("invaild request method.");
}
if(!$auth == true){
	header("Location: /Login/Default.aspx");
exit;
    
}

$GoldbloxColors = array(
    1,          //1
    208,        //2
    194,        //3
    199,        //4
    26,         //5
    21,         //6
    24,         //7
    226,        //8
    23,         //9
    107,        //10
    102,        //11
    11,         //12
    45,         //13
    135,        //14
    106,        //15
    105,        //16
    141,        //17
    28,         //18
    37,         //19
    119,        //20
    29,         //21
    151,        //22
    38,         //23
    192,        //24
    104,        //25
    9,          //26
    101,        //27
    5,          //28
    153,        //29
    217,        //30
    18,         //31
    125         //32
);

$GoldbloxColorsHtml = array(
    "#F2F3F2",  //1
    "#E5E4DE",  //2
    "#A3A2A4",  //3
    "#635F61",  //4
    "#1B2A34",  //5
    "#C4281B",  //6
    "#F5CD2F",  //7
    "#FDEA8C",  //8
    "#0D69AB",  //9
    "#008F9B",  //10
    "#6E99C9",  //11
    "#80BBDB",  //12
    "#B4D2E3",  //13
    "#74869C",  //14
    "#DA8540",  //15
    "#E29B3F",  //16
    "#27462C",  //17
    "#287F46",  //18
    "#4B974A",  //19
    "#A4BD46",  //20
    "#A1C48B",  //21
    "#789081",  //22
    "#A05F34",  //23
    "#694027",  //24
    "#6B327B",  //25
    "#E8BAC7",  //26
    "#DA8679",  //27
    "#D7C599",  //28
    "#957976",  //29
    "#7C5C45",  //30
    "#CC8E68",  //31
    "#EAB891"   //32
);

function getColorValue($color) {
    global $GoldbloxColorsHtml, $GoldbloxColors;

    $index = array_search($color, $GoldbloxColorsHtml);

    if ($index !== false) {
        return $GoldbloxColors[$index];
    }

    return null;
}

$numbercolor = getColorValue(strtoupper($_POST['color']));
$bodypartnumber = $_POST['bodyP'];

if($bodypartnumber == "head"){
	$sql = "UPDATE users SET HeadColor = :numbercolor WHERE id = :id";
}elseif($bodypartnumber == "leftarm"){
	$sql = "UPDATE users SET LeftArmColor = :numbercolor WHERE id = :id";
}elseif($bodypartnumber == "torso"){
	$sql = "UPDATE users SET TorsoColor = :numbercolor WHERE id = :id";
}elseif($bodypartnumber == "rightarm"){
	$sql = "UPDATE users SET RightArmColor = :numbercolor WHERE id = :id";
}elseif($bodypartnumber == "rightleg"){
	$sql = "UPDATE users SET RightLegColor = :numbercolor WHERE id = :id";
}elseif($bodypartnumber == "leftleg"){
	$sql = "UPDATE users SET LeftLegColor = :numbercolor WHERE id = :id";
}else{
	die("bodyp not found.");
}

if($numbercolor !== null){
	try {
		$stmt = $con->prepare($sql);
		$stmt->execute([':numbercolor' => $numbercolor, ':id' => $_USER['id']]);
	} catch (PDOException $e) {
		die("database error: " . $e->getMessage());
	}
}else{
	die("color not found.");
}

  exit;  
}


?>
<?php

if ((int)($_GET['function'] ?? 0) == 4) {
include $_SERVER["DOCUMENT_ROOT"]."/api/web/config.php";
ignore_user_abort(true);     
if($auth == false){
 exit('kys faggot <br>
 <iframe width="560" height="315" src="/vacation.mp4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>');
}
if ($_SERVER['REQUEST_METHOD'] != 'POST' ) {
    exit('kys faggot <br>
    <iframe width="560" height="315" src="/vacation.mp4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>');
}
$assetId = (int)$_USER['id'];
if(!isset($assetId) || empty($assetId)){
    exit(
        'kys faggot <br>
        <iframe width="560" height="315" src="/vacation.mp4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>');
}
if(!$assetId == $_USER['id'] && $_USER['USER_PERMISSIONS'] == "Administrator") {
 exit('<iframe width="560" height="315" src="/vacation.mp4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>');
}
$stmt = $con->prepare("SELECT * FROM users WHERE id = :assetId");
$stmt->bindParam(':assetId', $assetId, PDO::PARAM_INT);
$stmt->execute();
if($stmt->rowCount() < 1){
  exit('<iframe width="560" height="315" src="/vacation.mp4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>');
}
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$head = $user['HeadColor'];
$ra = $user['RightArmColor'];
$torso = $user['TorsoColor'];
$la = $user['LeftArmColor'];
$rl = $user['RightLegColor'];
$ll = $user['LeftLegColor'];
$items = "";
$face = "";
$newhead = "";
$a = array();
$stmt = $con->prepare("SELECT * FROM wearing WHERE userid = :assetId");
$stmt->bindParam(':assetId', $assetId, PDO::PARAM_INT);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $stmt2 = $con->prepare("SELECT * FROM owned_items WHERE itemid = :itemId");
    $stmt2->bindParam(':itemId', $row['itemid'], PDO::PARAM_INT);
    $stmt2->execute();
    if($stmt2->rowCount() < 1){
       exit('hi');
    }
    $item = $stmt2->fetch(PDO::FETCH_ASSOC);
    $stmt3 = $con->prepare("SELECT * FROM catalog WHERE id = :catalogId");
    $stmt3->bindParam(':catalogId', $item['itemid'], PDO::PARAM_INT);
    $stmt3->execute();
    $catalogItem = $stmt3->fetch(PDO::FETCH_ASSOC);
    if($catalogItem['type'] == "face") {
        $face = $item['itemid'];
    } elseif($catalogItem['type'] == "head") {
        $newhead = $item['itemid'];
    } else {
        $a[] = $item['itemid'];
    }
}
$lastItem = end($a);
foreach ($a as $key => $item) {
    $items .= "http://www.w.rccs.lol/asset/?id=" . $item;
    if ($item !== $lastItem) {
        $items .= ";";
    }
}
$script1 = '
game:GetService("ScriptContext").ScriptsDisabled = true
local plr = game.Players:CreateLocalPlayer(0)
plr:LoadCharacter()
plr.CharacterAppearance = "'.$items.'"
plr.Character.FakeFace.BrickColor = BrickColor.new('.$head.')
plr.Character.Head.BrickColor = BrickColor.new('.$head.')
plr.Character["Right Arm"].BrickColor = BrickColor.new('.$ra.')
plr.Character.Torso.BrickColor = BrickColor.new('.$torso.')
plr.Character["Left Arm"].BrickColor = BrickColor.new('.$la.')
plr.Character["Right Leg"].BrickColor = BrickColor.new('.$rl.')
plr.Character["Left Leg"].BrickColor = BrickColor.new('.$ll.')';
if($face != "") {
    $script1 .= '  FakeFace = game.Players.LocalPlayer.Character.FakeFace
                  FakeFace.Mesh.TextureId = "http://www.w.rccs.lol/Thumbs/Asset.ashx?assetId=' . ($face) . '";';
}
if($newhead != "") {
    $script1 .= '
    plr.Character.Head.Mesh:remove()
    local item = game:GetObjects("http://www.w.rccs.lol/asset/?id='.($newhead - 2).'&amp;a='.rand(1,getrandmax()).'")
    item[1].Parent = plr.Character.Head
    plr.Character.FakeFace.Mesh:remove()
    plr.Character.FakeFace.Transparency = 0
    local item2 = game:GetObjects("http://www.w.rccs.lol/asset/?id='.($newhead - 1).'&amp;a='.rand(1,getrandmax()).'")
    item2[1].Parent = plr.Character.FakeFace
    plr.Character.FakeFace.Mesh.Scale = Vector3.new(1.05,1.05,1.05)';
    if($face != "") {
        $script1 .= '  FakeFace = game.Players.LocalPlayer.Character.FakeFace
                  FakeFace.Mesh.TextureId = "http://www.w.rccs.lol/Thumbs/Asset.ashx?assetId=' . ($face) . '" ';
    } else {
        $script1 .= ' plr.Character.FakeFace.Mesh.TextureId = "http://www.w.rccs.lol/asset/?id=1185" ';
    }
}
// things
$randomthing = rand(1, getrandmax());
$cock = "JobItem " . $randomthing . "";
$suckass = "**" . $_USER['username'] . " (id " . $_USER['id'] . ") has started JobItem " . $randomthing . "**";
$shit = $cock . "\n" . $suckass;
context::rccthing($shit);
$timems = timems();
$render = renderRCC($script1, 480, 610);
if(!empty($render)){
$location = $_SERVER["DOCUMENT_ROOT"]."/Thumbs/USERS/".$assetId.".png";
$location2 = $_SERVER["DOCUMENT_ROOT"]."/Thumbs/USERS/".$assetId."-small.png";
$freerender = base64_decode($render);
$image = imagecreatefromstring($freerender);
$thingyw = imagesx($image);
$thingyh = imagesy($image);
$thingy = 150;
$thingy69 = 200;
$thingy70 = 160 - 10; 
$thingy71 = 206 - 15; 
$coolimage = imagecreatetruecolor($thingy, $thingy69);
$skibidicolor = imagecolorallocatealpha($coolimage, 0, 0, 0, 127);
imagefill($coolimage, 0, 0, $skibidicolor);
imagesavealpha($coolimage, true);
$x = 0 / 6 + 0; 
$y = 1 / 4 + 5; 
imagecopyresampled($coolimage, $image, $x, $y, 0, 0, $thingy70, $thingy71, $thingyw, $thingyh);
imagepng($coolimage, $location);
imagedestroy($image);
imagedestroy($coolimage);
$rizzlybiar = imagecreatefromstring($freerender);
$thingy = 100;
$thingy69 = 100;
$thingy70 = 99 - 10; 
$thingy71 = 117 - 5; 
$skibidi = imagecreatetruecolor($thingy, $thingy69);
$skibidicolor = imagecolorallocatealpha($skibidi, 0, 0, 0, 127);
imagefill($skibidi, 0, 0, $skibidicolor);
imagesavealpha($skibidi, true);
$x = 5 / 2 + 3; 
$y = 5 / 60 - 7; 
imagecopyresampled($skibidi, $rizzlybiar, $x, $y, 0, 0, $thingy70, $thingy71, $thingyw, $thingyh);
imagepng($skibidi, $location2);
imagedestroy($rizzlybiar);
imagedestroy($skibidi);
$timetook = timems() - $timems;
context::rccthing("# Rendering success\nThe user \"".$_USER["username"]."\" has successfully rendered their avatar.\nRender took ".$timetook."ms.", "http://www.w.rccs.lol/Thumbs/Avatar.ashx?assetId=".(int)$_USER["id"]."&rand=".random_int(1, getrandmax()));
exit('Success');
} else {
context::rccthing("#  JobItem " . $randomthing . " started by " . $_USER['username'] . " (id " . 
$_USER['id'] . ") failed.");
exit;
}
exit;  
}

?>
<?php
if ((int)($_GET['function'] ?? 0) == 5) {
require_once($_SERVER["DOCUMENT_ROOT"] . "/api/web/config.php");
if(!$auth) {
   
    header("Location: /Login/Default.aspx");
    exit();
}

$sql = "SELECT * FROM catalog WHERE id = :id";
$stmt = $con->prepare($sql);
$stmt->execute([':id' => (int)$_POST['id']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Item not found.");
}


$sql = "SELECT * FROM owned_items WHERE itemid = :itemid AND ownerid = :ownerid";
$stmt = $con->prepare($sql);
$stmt->execute([':itemid' => $_POST['id'], ':ownerid' => $_USER['id']]);
$owneditems = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$owneditems) {
    die("feature coming soon where you get banned if you try this shit :p");
}

$sql = "SELECT * FROM wearing WHERE userid = :userid AND itemid = :itemid";
$stmt = $con->prepare($sql);
$stmt->execute([':userid' => $_USER['id'], ':itemid' => $_POST['id']]);
if ($stmt->rowCount() > 0) {
    exit;
}

if ($row['type'] === 'hat') {
    
    $sql = "SELECT * FROM wearing WHERE userid = :userid AND type = 'hat'";
    $stmt = $con->prepare($sql);
    $stmt->execute([':userid' => $_USER['id']]);
    $numHats = $stmt->rowCount();

    if ($numHats >= 3) {
       
        $sql = "SELECT id FROM wearing WHERE userid = :userid AND type = 'hat' ORDER BY id DESC LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->execute([':userid' => $_USER['id']]);
        $newestHatId = $stmt->fetchColumn();

      
        $sql = "UPDATE wearing SET itemid = :itemid WHERE id = :id";
        $stmt = $con->prepare($sql);
        $stmt->execute([':itemid' => $_POST['id'], ':id' => $newestHatId]);
        exit;
    } else {
   
        $sql = "INSERT INTO wearing (userid, itemid, type) VALUES (:userid, :itemid, :type)";
        $stmt = $con->prepare($sql);
        $stmt->execute([':userid' => $_USER['id'], ':itemid' => $_POST['id'], ':type' => $row['type']]);
        exit;
    }
}


$sql = "SELECT * FROM wearing WHERE userid = :userid AND type = :type";
$stmt = $con->prepare($sql);
$stmt->execute([':userid' => $_USER['id'], ':type' => $row['type']]);
$num_check_type = $stmt->rowCount();

if ($num_check_type > 0) {
    
    $row_check_type = $stmt->fetch(PDO::FETCH_ASSOC);
    $sql = "UPDATE wearing SET itemid = :itemid WHERE id = :id";
    $stmt = $con->prepare($sql);
    $stmt->execute([':itemid' => $_POST['id'], ':id' => $row_check_type['id']]);
} else {

    $sql = "INSERT INTO wearing (userid, itemid, type) VALUES (:userid, :itemid, :type)";
    $stmt = $con->prepare($sql);
    $stmt->execute([':userid' => $_USER['id'], ':itemid' => $_POST['id'], ':type' => $row['type']]);
}
  exit;  
}

?>
<?php
if ((int)($_GET['function'] ?? 0) == 6) {
require($_SERVER["DOCUMENT_ROOT"]."/api/web/config.php");

if($auth == false){
    header("Location: /Login/Default.aspx");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit;
}

try {
 

    if (!isset($_POST["id"])) {
        throw new Exception("Missing item ID");
    exit;
    }
$id = (int)$_POST["id"];
   
    $sql = "DELETE FROM `wearing` WHERE `itemid` = :itemid AND `userid` = :userid";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':itemid', $id, PDO::PARAM_INT);
    $stmt->bindParam(':userid', $_USER['id'], PDO::PARAM_INT);
    $stmt->execute();
 exit;
} catch (Exception $e) {
    error_log($e->getMessage());
    die('An error occurred while processing your request.');
}
    
}


?>
<?php
include $_SERVER["DOCUMENT_ROOT"].'/api/web/header.php';
if($auth == false){
header("Location: /Login/Default.aspx");
exit();
 }
$colorMappings = array(
    "1" => "#F2F3F2",
    "208" => "#E5E4DE",
    "194" => "#A3A2A4",
    "199" => "#635F61",
    "26" => "#1B2A34",
    "21" => "#C4281B",
    "24" => "#F5CD2F",
    "226" => "#FDEA8C",
    "107" => "#008F9B",
    "102" => "#6E99C9",
    "11" => "#80BBDB",
    "45" => "#B4D2E3",
    "135" => "#74869C",
    "106" => "#DA8540",
    "105" => "#E29B3F",
    "141" => "#27462C",
    "28" => "#287F46",
    "37" => "#4B974A",
    "119" => "#A4BD46",
    "29" => "#A1C48B",
    "38" => "#A05F34",
    "192" => "#694027",
    "104" => "#6B327B",
    "9" => "#E8BAC7",
    "101" => "#DA8679",
    "5" => "#D7C599",
    "153" => "#957976",
    "217" => "#7C5C45",
    "18" => "#CC8E68",
    "125" => "#EAB891",
    "210" => "#789081",
    "23" => "#0D69AB"
);
$colors = array(
    "head" => $colorMappings[$_USER['HeadColor']],
    "torso" => $colorMappings[$_USER['TorsoColor']],
    "leftarm" => $colorMappings[$_USER['LeftArmColor']],
    "rightarm" => $colorMappings[$_USER['RightArmColor']],
    "leftleg" => $colorMappings[$_USER['LeftLegColor']],
    "rightleg" => $colorMappings[$_USER['RightLegColor']]
);
  

  
 

     
?>
 <script>    
curType = 0;
    curPage = 1;
    function getwardrobe(type, page) 
    {
    	if (page == undefined){ page = 1; }
        $("#btn" + curType).removeClass("AttireCategorySelector_Selected");
        $("#btn" + type).addClass("AttireCategorySelector_Selected");
   
        curType = type;
        curPage = page;
if(type != 5 && type != 6 && type != 7) 
        {
            if(type == 2) {
                $("#CreateAttire").attr("href", "ContentBuilder.aspx?ContentType=0");
            }
            if(type == 3) {
                $("#CreateAttire").attr("href", "ContentBuilder.aspx?ContentType=1");
            }
            if(type == 4) {
                $("#CreateAttire").attr("href", "ContentBuilder.aspx?ContentType?type=2");
            }
         
          $("#CreateAttire").removeAttr("style");

        } 
        else 
        {
            $("#CreateAttire").removeAttr("href");
            $("#CreateAttire").css({"color": "gray"});

        }
        $.post("/My/Character.aspx?function=1", {type:type,p:page}, function(data) 
        {
        	$("#wardrobe").html("");
        	$("#wardrobe").html(data);
        })
        .fail(function() 
        {
        	$("#wardrobe").html("");
        	$("#wardrobe").html("Failed to get wardrobe");
        });
    }
    function getwearing() 
    {
        $.post("/My/Character.aspx?function=2", {}, function(data) 
        {
        	$("#wearing").html("");
        	$("#wearing").html(data);
        })
        .fail(function() 
        {
        	$("#wearing").html("");
        	$("#wearing").html("Failed to get wearing items");
        });
    }

 
        getwardrobe(5);
      getwearing(); 

</script>
<script>var BP = 0;
  var OP = false;
    let lastRenderTime = 0;
function generateRandomString(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
}

function redraw() {
 
   getwearing(0); 

  const thingy = document.getElementById("Character");
  const renderwheel = document.querySelector("#rendering");
  renderwheel.style.display = "block";
  thingy.src = "/images/unavail.png?t=" + generateRandomString(10);
 $.post('/My/Character.aspx?function=4')
   
    .done(function(response) {
   if (response) {
        thingy.src = "/Thumbs/Avatar.ashx?assetId=<?php echo $_USER['id']; ?>&" + generateRandomString(10);
        $("#ratelimited").hide();
        console.log('Response received.');
      } else {
        $("#wardrobe").html("No response from the server.");
      }
    })
.fail(function() {
    $("#wardrobe").html("rcc is down or render is broken lol");
})
.always(function() {
    thingy.style.animation = null;
    renderwheel.style.display = "none";
});

}
  
function wearitem(itemId, querytype) {
        $.post('/My/Character.aspx?function=5', { id: itemId, type: querytype }, function(response) {
           getwearing(0);
      
            redraw();
        
    }).fail(function(xhr, status, error) {
     
        console.error("Error: " + error);
    });
}

function removeitem(itemId, querytype) {
    $.post('/My/Character.aspx?function=6', { id: itemId, type: querytype }, function(response) {
        getwearing(0);
     
            redraw();
       
    }).fail(function(xhr, status, error) {
     
        console.error("Error: " + error);
    });
}


  function changeBC(bdp, colour) {
   	$("#"+bdp).css("background-color", colour);
    $.post("/My/Character.aspx?function=3", {bodyP: bdp, color: colour, csrf: $("#csrf_token").val()}, function(){ 
     redraw();
    })
    .fail(function() {
      $("#wardrobe").html("Failed to change body colour");
    });
  }

  
function openColorPanel(bodyPart, event) {
    var $colorPanel = $("#colorPanel");
    var posX = event.pageX;
    var posY = event.pageY;
    var panelWidth = $colorPanel.width(); 
    var bodyContainerWidth = $("Body").width(); 
    var bodyContainerOffset = $("Body").offset();

   
    var maxX = bodyContainerOffset.left + bodyContainerWidth - panelWidth;

    
    if (posX > maxX) {
        posX = maxX;
    }

 
    $colorPanel.css({
        top: posY + "px",
        left: posX + "px",
        width: panelWidth + "px" 
    });

    if ($colorPanel.is(":visible")) {
        if (bodyPart !== BP) {
            BP = bodyPart;
        } else {
            $colorPanel.hide();
        }
    } else {
        BP = bodyPart;
        $colorPanel.show();
    }
}


  var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");
  
  function rgb2hex(rgb) {
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
  }
  
  function hex(x) {
    return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
  }
function selBodyC(color) {
       		changeBC(BP, rgb2hex(color));
    $("#colorPanel").hide();
}
$(function() 
    {
   
        getwardrobe(5);
       
    });
getwearing();</script>
<title>GOLDBLOX - Change Character</title>
<div id="Body">
<div id="CustomizeCharacterContainer">
<div class="AttireChooser">
<h4>My Wardrobe</h4> 
   <div class="HeaderPager">
		    <span>
		       <div class="AttireCategory">
		          <a id="btn7" class="AttireOptions" href="javascript:void(0);" onclick="getwardrobe(7)">Heads</a>
		        |
		       <a id="btn6"  class="AttireOptions" href="javascript:void(0);" onclick="getwardrobe(6)">Faces</a>
		        |
		        <a id="btn5"  class="AttireOptions" href="javascript:void(0);" onclick="getwardrobe(5)">Hats</a>
		        |
		        <a id="btn2" class="AttireOptions" href="javascript:void(0);" onclick="getwardrobe(2)">T-Shirts</a>
		        |
		        <a id="btn3"  class="AttireOptions" href="javascript:void(0);" onclick="getwardrobe(3)">Shirts</a>
		        |
		        <a id="btn4"  class="AttireOptions" href="javascript:void(0);" onclick="getwardrobe(4)">Pants</a>
		        <br>
		        <a href="/Catalog.aspx">Shop</a> 
		      
		        <a  class="AttireOptions" id="CreateAttire">Create</a></span>
		
	</div>	</div>
	
		        <div id="wardrobe">
			</div>	</div>
			
			        <div class="CharacterViewer">
                	<h4>My Character</h4>
                		    <img id="rendering" src="/images/ProgressIndicator2.gif" style="display: none;position: absolute;width: 15px;z-index: 99999" >
                                            

<center>
    <img id="Character" style="height: 354px;" src="/Thumbs/Avatar.ashx?assetId=<?php echo $_USER['id'] ?>">
</center>
<div class="ReDrawAvatar">
Something wrong with your avatar? <a href="javascript:void(0);"  id="shitter" onclick="redraw()">Click here to re-draw it! </a></div>
</div>		



    
 <div class="Mannequin">
				            <div class="ColorChooserFrame">
   <h4>Color Chooser</h4>
   <br> 
   Click a body part to change its color:
        <br>   <br> <br>
      <button class="clickable" id="head" style="background-color:<?= $colors['head']; ?>;  width:35px;
  height: 35px;border: none;
  margin: 1px;" onclick="openColorPanel('head', event);"></button>
                <div class="seperator" style="height: 5px;"></div>
                <button class="clickable2" id="rightarm" style="background-color:<?= $colors['rightarm']; ?>;   width:32px;
  height: 70px;border: none;
  margin: 1px;" onclick="openColorPanel('rightarm', event);"></button>
                <button class="clickable3" id="torso" style="background-color:<?= $colors['torso']; ?>;   width:70px;
  height: 70px;border: none;
  margin: 1px;" onclick="openColorPanel('torso', event);"></button>
                <button class="clickable2" id="leftarm" style="background-color:<?= $colors['leftarm']; ?>;  width:32px;
  height: 70px;border: none;
  margin: 1px;" onclick="openColorPanel('leftarm', event);"></button>
                <div class="seperator" style="height: 5px;"></div>
                <button class="clickable2" id="rightleg" style="background-color:<?= $colors['rightleg']; ?>;  width:32px;
  height: 70px;border: none;
  margin: 1px;" onclick="openColorPanel('rightleg', event);"></button>
                <button class="clickable2" id="leftleg" style="background-color:<?= $colors['leftleg']; ?>;width:32px;
  height: 70px;border: none;
  margin: 1px;" onclick="openColorPanel('leftleg', event);"></button><div style="clear:both;"></div>
    <br>
</div>
</div>
 <div class="Accoutrements">
		  <h4>Currently Wearing</h4>
	
		   
		        <div id="wearing"></div>
		
</div>
	
  <div id="colorPanel" class="popupControl" style="top: 435px; right: 165px; display: none; visibility: visible !important;">
  <table cellspacing="0" border="0" style="border-width:0px;border-collapse:collapse;">
  <tr>
    <td>
   	<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#F2F3F2;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#E5E4DE;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#A3A2A4;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#635F61;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#1B2A34;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#C4281B;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#F5CD2F;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#FDEA8C;height:32px;width:32px;">
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#0D69AB;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#008F9B;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#6E99C9;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#80BBDB;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#B4D2E3;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#74869C;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#DA8540;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#E29B3F;height:32px;width:32px;">
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#27462C;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#287F46;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#4B974A;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#A4BD46;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#A1C48B;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#789081;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#A05F34;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#694027;height:32px;width:32px;">
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#6B327B;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#E8BAC7;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#DA8679;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#D7C599;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#957976;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#7C5C45;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#CC8E68;height:32px;width:32px;">
				</div>
			</td>
			<td>
				<div class="ColorPickerItem" onclick="selBodyC($(this).css('backgroundColor'))" style="display:inline-block;background-color:#EAB891;height:32px;width:32px;">
    </div>
    </td>
  </tr>
  </table>
</div></div>
<div style="clear:both;"></div>
<?php include $_SERVER["DOCUMENT_ROOT"].'/api/web/footer.php'; ?>