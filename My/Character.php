<?php
 /* good luck with rendering...  - mii 9/21/2024 /*
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
    $queryType = isset($_REQUEST['type']) && (int)$_REQUEST['type'] >= 1 ? (int)$_REQUEST['type'] : 1;
    $queryTypeMap = [2 => "tshirt", 3 => "shirt", 4 => "pants", 5 => "hat", 6 => "face", 7 => "head"];
    $queryType = $queryTypeMap[$queryType] ?? "hat";
    $type = array_search($queryType, $queryTypeMap) ?: 5;

    $resultsPerPage = 8;
    $page = isset($_REQUEST['p']) ? (int)$_REQUEST['p'] : 1;
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
                    <a href='javascript:void(0);' class='DeleteButtonOverlay' onclick='wearitem({$item['id']});'>[ wear ]</a>
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
$counter = null;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $itemthingy = $con->prepare("SELECT * FROM catalog WHERE id = :itemid");
            $itemthingy->execute([':itemid' => $row['itemid']]);
            $item = $itemthingy->fetch(PDO::FETCH_ASSOC);


            $creatorthingy = $con->prepare("SELECT * FROM users WHERE id = :creatorid");
            $creatorthingy->execute([':creatorid' => $item['creatorid']]);
            $creator = $creatorthingy->fetch(PDO::FETCH_ASSOC);

            $thumburl = "/Thumbs/Asset.ashx?assetId=" . $item['id'] . "&isSmall";
            $name = $item['name'];
            $creator = ($creator['username']);
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
                echo "</tr>";
            }
            echo "<tr>";
        }

        echo "
        <td valign='top'>
            <div class='Asset'>
                <div class='AssetThumbnail'>
                    <a id='AssetThumbnailHyperLink' title='click to remove'  href='javascript:void(0);' onclick='removeitem({$item['id']});' style='display:inline-block; cursor:pointer; width: 110px; height: 110px; overflow: hidden;'>
                        <img src='/Thumbs/Asset.ashx?assetId={$item['id']}&amp;isSmall' id='img' alt='{$name}' style='width: 110px; height: 110px; object-fit: cover; object-position: center center; border: 0;'>
                    </a>
                    <a href='javascript:void(0);' class='DeleteButtonOverlay' onclick='removeitem({$item['id']});'>[ remove ]</a>
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

   

 echo "</tbody></table>
 <div class=\"FooterPager\"><a style=\"color: gray; \">First</a> <a style=\"color: gray;\">Previous</a> <span>1</span> <a style=\"color: gray; \">Next</a> <a style=\"color: gray; \">Last</a> </div>

 ";


        
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

$numbercolor = getColorValue(strtoupper($_REQUEST['color']));
$bodypartnumber = $_REQUEST['bodyP'];

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
// exit("rendering back soon when copy's lazy ass decides to add rate limiting (without it, it just crashes the entire server :D)");
require_once $_SERVER["DOCUMENT_ROOT"]."/api/web/config.php";
  ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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
 $checkStmt = $con->prepare("SELECT * FROM users WHERE id = :userid ORDER BY id DESC LIMIT 1");
    $checkStmt->execute(['userid' => $user['id']]);

    $ratelimited = false;
    if ($checkStmt->rowCount() == 1) {
        $ratelimit = $checkStmt->fetch(PDO::FETCH_ASSOC);
        if (time() <= $ratelimit['renderedfirsttime']) {
            $ratelimited = true;
        }
    }
    if ($ratelimited) {
        context::boughtitem("# " . $_USER['username'] . " (id " . $_USER['id'] . ") MIGHT be a faggot that tried to spam the site");
        echo("ill add automatic ban retard.");
        die();
  
    }
$timeout = time() + 3;  
            //i just reuse the same ratelimit code idc 
             $skibidi = $con->prepare("UPDATE users SET renderedfirsttime = :timeout WHERE id = :userid");
    $skibidi->execute([
        'timeout' => $timeout,
        'userid' => $user['id'],
    ]); 
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
 $fart = []; 
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $stmt2 = $con->prepare("SELECT * FROM owned_items WHERE itemid = :itemId");
    $stmt2->bindParam(':itemId', $row['itemid'], PDO::PARAM_INT);
    $stmt2->execute();

    if ($stmt2->rowCount() < 1) {
        exit('hi');
    }

    $item = $stmt2->fetch(PDO::FETCH_ASSOC);
    $stmt3 = $con->prepare("SELECT * FROM catalog WHERE id = :catalogId AND moderation ='accepted'");
    $stmt3->bindParam(':catalogId', $item['itemid'], PDO::PARAM_INT);
    $stmt3->execute();
    $catalogItem = $stmt3->fetch(PDO::FETCH_ASSOC);
    if ($catalogItem && $catalogItem['moderation'] === 'accepted') {
        if ($catalogItem['type'] == "face") {
            $face = $catalogItem['id'];
        } elseif ($catalogItem['type'] == "head") {
            $newhead = $item['itemid'];
        } else {
            $fart[] = $item['itemid'];
        }
    }
}
$items = '';
$lastItem = end( $fart);
foreach ($fart as $key => $item) {
    $items .= ";http://$domain/asset/?id=" . $item;
 
}
$script1 = '
local plr = game.Players:CreateLocalPlayer('.$_USER['id'].')
plr.CharacterAppearance = "http://'. $domain .'/asset/BodyColors.ashx?userId='.$_USER['id'].'&amp;a='.rand(1,getrandmax()).''.$items.'"
plr:LoadCharacter()
print("rccapi by nolanwhy pookie bear :3")
';
if($face != "") {
    $script1 .= '  FakeFace = game.Players.LocalPlayer.Character.FakeFace
                  FakeFace.Mesh.TextureId = "http://'. $domain .'/Thumbs/Asset.ashx?assetId=' . ($face) . '";';
}
if($newhead != "") {
    $script1 .= '
    plr.Character.Head.Mesh:remove()
    local item = game:GetObjects("http://'. $domain .'/asset/?id='.($newhead - 2).'&amp;a='.rand(1,getrandmax()).'")
    item[1].Parent = plr.Character.Head
    plr.Character.FakeFace.Mesh:remove()
    plr.Character.FakeFace.Transparency = 0
    local item2 = game:GetObjects("http://'. $domain .'/asset/?id='.($newhead - 1).'&amp;a='.rand(1,getrandmax()).'")
    item2[1].Parent = plr.Character.FakeFace
    plr.Character.FakeFace.Mesh.Scale = Vector3.new(1.05,1.05,1.05)';
    if($face != "") {
        $script1 .= '  FakeFace = game.Players.LocalPlayer.Character.FakeFace
                  FakeFace.Mesh.TextureId = "http://'. $domain .'/Thumbs/Asset.ashx?assetId=' . ($face) . '" ';
    } else {
        $script1 .= ' plr.Character.FakeFace.Mesh.TextureId = "rbxasset://textures\face.png" ';
    }
}

// things
$randomthing = rand(1, getrandmax());
$cock = "JobItem " . $randomthing . "";
$suckass = "**" . $_USER['username'] . " (id " . $_USER['id'] . ") has started JobItem " . $randomthing . "**";
$shit = $cock . "\n" . $suckass;
context::rccthing($shit);
$timems = timems();
try {
$render = $RCCAPI->render(2009, $script1, 480, 640);
$rendersmall = $RCCAPI->render(2009, $script1, 495, 505);
if(!empty($render)){
$location = $_SERVER["DOCUMENT_ROOT"]."/Thumbs/USERS/".$assetId.".png";
$location2 = $_SERVER["DOCUMENT_ROOT"]."/Thumbs/USERS/".$assetId."-small.png";
$location3 = $_SERVER["DOCUMENT_ROOT"]."/Thumbs/USERS/".$assetId."-square.png";
$RCCAPI->resizeAndSaveImage($location, $render, 150, 200);
$RCCAPI->resizeAndSaveImage($location2, $rendersmall, 100, 100);
$RCCAPI->resizeAndSaveImage($location3, $rendersmall, 354, 354);
$timetook = timems() - $timems;
context::rccthing("# Rendering success\nThe user \"".$_USER["username"]."\" has successfully rendered their avatar.\nRender took ".$timetook."ms.", "http://".$domain."/Thumbs/Avatar.ashx?assetId=".(int)$_USER["id"]."&rand=".random_int(1, getrandmax()));
exit('Success');
} else {
    throw new Exception("Empty render");
}
} catch (Exception $e) {
echo('Failed to render ');
echo $e->getMessage();
context::rccthing("# Rendering Failure\nThe user \"".$_USER["username"]."\" has failed to render their avatar.\n". $e->getMessage() ." ");
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
$stmt->execute([':id' => (int)$_REQUEST['id']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Item not found.");
}


$sql = "SELECT * FROM owned_items WHERE itemid = :itemid AND ownerid = :ownerid";
$stmt = $con->prepare($sql);
$stmt->execute([':itemid' => $_REQUEST['id'], ':ownerid' => $_USER['id']]);
$owneditems = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$owneditems) {
    die("feature coming soon where you get banned if you try this shit :p");
}

$sql = "SELECT * FROM wearing WHERE userid = :userid AND itemid = :itemid";
$stmt = $con->prepare($sql);
$stmt->execute([':userid' => $_USER['id'], ':itemid' => $_REQUEST['id']]);
if ($stmt->rowCount() > 0) {
    exit;
}

if ($row['type'] === 'hat') {
    
    $sql = "SELECT * FROM wearing WHERE userid = :userid AND type = 'hat'";
    $stmt = $con->prepare($sql);
    $stmt->execute([':userid' => $_USER['id']]);
    $numHats = $stmt->rowCount();

    if ($numHats >= 1) {
       
        $sql = "SELECT id FROM wearing WHERE userid = :userid AND type = 'hat' ORDER BY id DESC LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->execute([':userid' => $_USER['id']]);
        $newestHatId = $stmt->fetchColumn();

      
        $sql = "UPDATE wearing SET itemid = :itemid WHERE id = :id";
        $stmt = $con->prepare($sql);
        $stmt->execute([':itemid' => $_REQUEST['id'], ':id' => $newestHatId]);
        exit;
    } else {
   
        $sql = "INSERT INTO wearing (userid, itemid, type) VALUES (:userid, :itemid, :type)";
        $stmt = $con->prepare($sql);
        $stmt->execute([':userid' => $_USER['id'], ':itemid' => $_REQUEST['id'], ':type' => $row['type']]);
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
    $stmt->execute([':itemid' => $_REQUEST['id'], ':id' => $row_check_type['id']]);
} else {

    $sql = "INSERT INTO wearing (userid, itemid, type) VALUES (:userid, :itemid, :type)";
    $stmt = $con->prepare($sql);
    $stmt->execute([':userid' => $_USER['id'], ':itemid' => $_REQUEST['id'], ':type' => $row['type']]);
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
 

    if (!isset($_REQUEST["id"])) {
        throw new Exception("Missing item ID");
    exit;
    }
$id = (int)$_REQUEST["id"];
   
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
                $("#CreateAttire").attr("href", "ContentBuilder.aspx?ContentType=2");
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
        	$("#wardrobe").html(data);
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
let rendering = false;
function redraw() {
       if (rendering) return;
    getwearing(0);
    rendering = true;
      const renderwheel = document.querySelector("#rendering");
    renderwheel.style.display = "block";
     const thingy = document.getElementById("Character");
    thingy.src = "/images/unavail-354x354.Png";
    const errorDiv = document.getElementById("errorthing");
    const errorMsg = document.getElementById("error");
    $.post("/My/Character.aspx?function=4", function(data) {
             if (data.trim() === 'Success') {
                thingy.src = "/Thumbs/Avatar.ashx?assetId=<?php echo $_USER['id']; ?>&square&" + generateRandomString(10);
            } else {
        errorDiv.style.display = "block";
        errorMsg.textContent = data;
    }
})
        .always(function() {
            thingy.style.animation = null;
            renderwheel.style.display = "none";
            rendering = false;
        });
}

  
function wearitem(itemId, querytype) {
       if (rendering) return;
        $.post('/My/Character.aspx?function=5', { id: itemId, type: querytype }, function(response) {
           getwearing(0);
      
            redraw();
        
    }).fail(function(xhr, status, error) {
     
        console.error("Error: " + error);
    });
}

function removeitem(itemId, querytype) {
    if (rendering) return;
    $.post('/My/Character.aspx?function=6', { id: itemId, type: querytype }, function(response) {
        getwearing(0);
     
            redraw();
       
    }).fail(function(xhr, status, error) {
     
        console.error("Error: " + error);
    });
}


  function changeBC(bdp, colour) {
   	$("#"+bdp).css("background-color", colour);
    $.post("/My/Character.aspx?function=3", {bodyP: bdp, color: colour}, function(){ 
     redraw();
    })
    .fail(function() {
      $("#wardrobe").html("Failed to change body colour");
    });
  }

  
function openColorPanel(bodyPart) {
	    if (rendering) return;
		if ($("#colorPanel").is(":visible")) 
		{
			if(bodyPart !== BP) 
			{
				BP = bodyPart;
			} 
			else 
			{
				$("#colorPanel").hide();
			}
		} 
		else 
		{
			BP = bodyPart;
			$("#colorPanel").show();
		}
		//$("#colorPanel").attr("data-body-part", BP);
	}

  var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");
  
  function rgb2hex(rgb) {
       if (rendering) return;
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
  }
  
  function hex(x) {
    return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
  }
function selBodyC(color) {
          		changeBC(BP, rgb2hex(color));
    let hexColor = rgb2hex(color);
    $('.' + BP).css('background-color', hexColor);

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
	</div>
	
		        <div id="wardrobe">
			</div>	</div>
			
			        <div class="CharacterViewer">
                	<h4>My Character</h4>
                		    <img id="rendering" src="/images/ProgressIndicator2.gif" style="display: none;position: absolute;width: 15px;z-index: 99999" >
                                            

<a id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AvatarThumbnail" disabled="disabled" title="<?php echo $_USER['username'] ?>" onclick="return false" style="display:inline-block;height:352px;width:352px;"><img id="Character" src="/Thumbs/Avatar.ashx?assetId=<?php echo $_USER['id'] ?>&square" onerror="return Roblox.Controls.Image.OnError(this)" alt="<?php echo $_USER['username'] ?>" border="0"></a>
<div class="ReDrawAvatar">
                                <span id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_lblInvalidateThumbnails">Something wrong with your Avatar?</span>
                                <a id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_cmdInvalidateThumbnails"  onclick="redraw();"href="javascript:__doPostBack('ctl00$ctl00$cphRoblox$cphMyRobloxContent$cmdInvalidateThumbnails','')">Click here to re-draw it!</a>
                            </div>

</div>		




    
    
<div id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_ColorChooser" class="Mannequin">
                    <h4>Color Chooser</h4>
                            <p>
                                Click a body part to change its color:</p>
                            <div id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_ColorChooserFrame" class="ColorChooserFrame" style="height:236px;width:176px;text-align:center;">
		
                                <div style="position: relative; margin: 11px 11px; height: 1%;">
                                    <div style="position: absolute; left: 120px; top: 44px; cursor: pointer">
                                        <div class="leftarm" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_LeftArmSelector"  onclick="openColorPanel('leftarm');" style="background-color:<?= $colors['leftarm']; ?>;height:72px;width:32px;">
			
                                        
		</div>
                                    </div>
                                    <div style="position: absolute; left: 40px; top: 44px; cursor: pointer">
                                        <div class="torso" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_TorsoSelector"  onclick="openColorPanel('torso');" style="background-color:<?= $colors['torso']; ?>;height:72px;width:72px;">
			
                                        
		</div>
                                    </div>
                                    <div style="position: absolute; left: 0px; top: 44px; cursor: pointer">
                                        <div class="rightarm" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_RightArmSelector"  onclick="openColorPanel('rightarm');" style="background-color:<?= $colors['rightarm']; ?>;height:72px;width:32px;">
			
                                        
		</div>
                                    </div>
                                    <div style="position: absolute; left: 58px; top: 0px; cursor: pointer">
                                        <div class="head"  id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_HeadSelector"  onclick="openColorPanel('head');" style="background-color:<?= $colors['head']; ?>;height:36px;width:36px;">
			
                                        
		</div>
                                    </div>
                                    <div style="position: absolute; left: 40px; top: 124px; cursor: pointer">
                                        <div class="rightleg" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_RightLegSelector"  onclick="openColorPanel('rightleg');" style="background-color:<?= $colors['rightleg']; ?>;height:72px;width:32px;">
			
                                        
		</div>
                                    </div>
                                    <div style="position: absolute; left: 80px; top: 124px; cursor: pointer">
                                        <div class="leftleg" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_LeftLegSelector"  onclick="openColorPanel('leftleg');" style="background-color:<?= $colors['leftleg']; ?>;height:72px;width:32px;">
			
                                        
		</div>
                                    </div>
                                </div>
                            
	</div></div>
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
<center>
<div id="errorthing" style="display:none;position: fixed; z-index: 1; left: 0px; top: 0px; width: 100%; height: 100%; overflow: auto; background-color: rgba(100, 100, 100, 0.25);">
    <div  style="width: 27em; position: absolute; top: 50%; left: 47%; transform: translateX(-50%) translateY(-50%);">

<div id="ErrorPane"> <h5>Uh oh!</h5>
  <div>
 <p>   <a>  
  <img id="img" src="/images/Error.png" border="0">
    </a>  </p>
    <h1>An Error occurred!</h1>      
</div>
  <span><div id="error"></div></span>    
   <p>
          <input type="button" value="Ok!" onclick="$('#errorthing').hide();"/>
       </p>
  
			
						</div>

</div>
</div>
</div>
</center>
<div style="clear:both;"></div><div style="clear:both;"></div>
<?php include $_SERVER["DOCUMENT_ROOT"].'/api/web/footer.php'; ?>