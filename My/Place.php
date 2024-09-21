<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/header.php");
 header("Location: /error.php"); 
    exit;
if ($auth == false) {
    header("Location: /Login/Default.aspx");
    exit();
}

$id = intval((int)(isset($_REQUEST["ID"])));
if (!isset($_REQUEST["ID"])) {

try {
    
   $max_places = ($_USER['BC'] === "BuildersClub") ? 10 : 3;

$stmt = $con->prepare("SELECT * FROM catalog WHERE creatorid = :creatorid AND type = 'place'");
$stmt->execute(['creatorid' => $_USER['id']]);
$counter = $stmt->rowCount();

if ($counter >= $max_places && $_USER['USER_PERMISSIONS'] !== "Administrator") {
    header("Location: /Default.aspx");
    exit();
}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['new'])) {
            $new = (int)$_POST['new'];

           if (in_array($new, [0, 1, 2])) {
                $name = htmlspecialchars($_USER['username']) . "'s Place Number: " . ($counter + 1);
                $creation_date = time() -3600;
                $type = 'place';
                $moderation = 'accepted';

                $stmt = $con->prepare("
                    INSERT INTO catalog 
                    (name, description, creatorid,  isoffsale, thumbnail, creation_date, price, type, assetid, filename, mesh, texture, moderation, state) 
                    VALUES 
                    (:name, '', :creatorid,  0, '0', :creation_date, 0, :type, 0, '0', '0', '0', :moderation, :state)
                ");
                $stmt->execute([
                    'name' => $name,
                    'creatorid' => $_USER['id'],
                    'creation_date' => $creation_date,
                    'type' => $type,
                    'moderation' => $moderation,
               'state' => 'public'
                ]);
                $real2 = $con->lastInsertId();
            } else {
                echo 'Invalid selection';
                exit();
            }

            switch ($new) {
                case 0:
                    $source = $_SERVER["DOCUMENT_ROOT"] . "/Starter/happyhome.rbxl";
                    $source2 = $_SERVER["DOCUMENT_ROOT"] . "/images/HappyHomeBig.png";
                    break;
                case 1:
                    $source = $_SERVER["DOCUMENT_ROOT"] . "/Starter/startingbrickbattle.rbxl";
                    $source2 = $_SERVER["DOCUMENT_ROOT"] . "/images/BrickBattleBigNew.png";
                    break;
                case 2:
                    $source = $_SERVER["DOCUMENT_ROOT"] . "/Starter/baseplate.rbxl";
                    $source2 = $_SERVER["DOCUMENT_ROOT"] . "/images/EmptyBaseBig.png";
                    break;
                default:
                    echo 'Invalid selection';
                    exit();
            }

            $destination = $_SERVER["DOCUMENT_ROOT"] . "/PlaceAsset/9yI7Js2rTpU5gWxLqHhFGaA1vlKwDcXBN6RfC0bnZPxSYjMi3e/$real2.rbxl";
            $destination2 = $_SERVER["DOCUMENT_ROOT"] . "/Thumbs/CATALOG/$real2.png";

            if (!file_exists($source)) {
                echo "Source file not found: $source";
                exit();
            }

            if (!copy($source, $destination)) {
                $error = error_get_last();
                echo "Failed to copy file from $source to $destination. Error: " . $error['message'];
                exit();
            }

            if (!file_exists($source2)) {
                echo "Source image not found: $source2";
                exit();
            }

            if (!copy($source2, $destination2)) {
                $error = error_get_last();
                echo "Failed to copy image from $source2 to $destination2. Error: " . $error['message'];
                exit();
            }

            header("Location: /My/Place.aspx?ID=$real2");
            exit();
        } else {
            echo 'Invalid request';
            exit();
        }
    }
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
    exit();
}
?>

<title>GOLDBLOX - Create Game</title>
<div id="Body">
<span style="font-size:24px; margin: 270px; text-align: center;">Select Your Starter Template</span>
<br><br>
<span style="font-size:16px; text-align: center; margin: 280px;">This will be used to create your new Place.<br><br></span>

<form action="" method="post">
    <table id="PlaceSelections" cellspacing="0" cellpadding="10" align="Center" border="0" style="border-collapse:collapse;">
        <tbody>
            <tr>
                <td align="center" valign="middle" style="color:#003399;background-color:White;">
                    <a supportsalphachannel="false" title="Happy Home in Goldbloxia" style="display:inline-block;height:140px;width:240px;cursor:pointer;">
                        <button type="submit" name="new" value="0" style="all: unset;"><img src="/images/HappyHomeBig.png" width="240" height="140" border="0" id="img" alt="Happy Home in Goldbloxia"></button>
                    </a>
                    <br>
                    <span style="font-size: 14px;">Happy Home in Goldbloxia</span>
                </td>
                <td align="center" valign="middle" style="color:#003399;background-color:White;">
                    <a supportsalphachannel="false" title="Starting BrickBattle Map" style="display:inline-block;height:140px;width:240px;cursor:pointer;">
                        <button type="submit" name="new" value="1" style="all: unset;"><img src="/images/BrickBattleBigNew.png" width="240" height="140" border="0" id="img" alt="Starting BrickBattle Map"></button>
                    </a>
                    <br>
                    <span style="font-size: 14px;">Starting BrickBattle Map</span>
                </td>
            </tr>
            <tr>
                <td align="center" valign="middle" style="color:#003399;background-color:White;">
                    <a supportsalphachannel="false" title="Empty Baseplate" style="display:inline-block;height:140px;width:240px;cursor:pointer;">
                        <button type="submit" name="new" value="2" style="all: unset;"><img src="/images/EmptyBaseBig.png" width="240" height="140" border="0" id="img" alt="Empty Baseplate"></button>
                    </a>
                    <br>
                    <span style="font-size: 14px;">Empty Baseplate</span>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
</form>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; 
exit;
}
?>
<?php
$gameq = $con->prepare("SELECT * FROM catalog WHERE id = :id AND type = 'place'");
$gameq->bindParam(':id', $id, PDO::PARAM_INT);
$gameq->execute();
$game = $gameq->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    die("Game not found.");
}

$creatorq = $con->prepare("SELECT * FROM users WHERE id = :creatorid");
$creatorq->bindParam(':creatorid', $game['creatorid'], PDO::PARAM_INT);
$creatorq->execute();
$creator = $creatorq->fetch(PDO::FETCH_ASSOC);

if (!$auth) {
    header("Location: /Login/Default.aspx");
    exit;
}

if ($creator['id'] !== $_USER['id']) {
    header('Location: /error.php');
    exit;
}
   $error = null;
if(isset($_POST['resettype'])) {
        $new = (int)$_POST['resettype'];
   
        $real2 = $game['id'];
        $source = "";
        if($new == 0) {
            $source = $_SERVER["DOCUMENT_ROOT"] . "/Starter/happyhome.rbxl";
            $source2 = $_SERVER["DOCUMENT_ROOT"] . "/images/HappyHomeBig.png";
            $source3 = $_SERVER["DOCUMENT_ROOT"] . "/images/HappyHomeSmall.png";
        } elseif($new == 1) {
            $source = $_SERVER["DOCUMENT_ROOT"] . "/Starter/startingbrickbattle.rbxl";
            $source2 = $_SERVER["DOCUMENT_ROOT"] . "/images/BrickBattleBigNew.png";
      
        } elseif($new == 2) {
            $source = $_SERVER["DOCUMENT_ROOT"] . "/Starter/baseplate.rbxl";
            $source2 = $_SERVER["DOCUMENT_ROOT"] . "/images/EmptyBaseBig.png";
         
        }

        $destination = $_SERVER["DOCUMENT_ROOT"] . "/PlaceAsset/9yI7Js2rTpU5gWxLqHhFGaA1vlKwDcXBN6RfC0bnZPxSYjMi3e/$real2.rbxl";
        $destination2 = $_SERVER["DOCUMENT_ROOT"] . "/Thumbs/CATALOG/$real2.png";
      
        
        if (copy($source, $destination)) {
        } else {
            echo "a";
            exit();
        }
        if (copy($source2, $destination2)) {
        } else {
            echo "a";
            exit();
        }
    
        header("Location: /Item.aspx?ID=$real2");
        exit();
}

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
// idk why but nolans code thing wasnt working so i just did this
if (!$_POST['commentschk']) {
$uca35 = $con->prepare("UPDATE catalog SET commentsenabled = 0 WHERE id=?");
$uca35->execute([$game['id']]);
}
if ($_POST['commentschk']) {
$uca35 = $con->prepare("UPDATE catalog SET commentsenabled = 1 WHERE id=?");
$uca35->execute([$game['id']]);
}
    
$desiredblurb = $ep->remove(htmlspecialchars(trim($_POST["iDesc"]), ENT_QUOTES, 'UTF-8'));
$name = $ep->remove(htmlspecialchars(trim($_POST["iName"]), ENT_QUOTES, 'UTF-8'));


   
    if (empty($name)) {
        $error = '<span style="color:Red;">A Name is Required</span>';
    } else {
        $type = '';

        if (isset($_POST['access']) && ($_POST['access'] == 'public' || $_POST['access'] == 'friends')) {
            $type = $_POST['access'];
        }


        $stmt = $con->prepare("UPDATE catalog SET description = :description, name = :name, State = :state, creation_date = NOW() WHERE id = :id");
        $stmt->bindParam(':description', $desiredblurb, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':state', $type, PDO::PARAM_STR);
        $stmt->bindParam(':id', $game['id'], PDO::PARAM_INT);
        $stmt->execute();


       
       header('Location: /Item.aspx?ID='. $game['id']);
  exit;
    }

 

}
?>

<div id="Body">
    <form method="POST">
        <div id="EditItemContainer">
            <div id="EditItem">
                <h2>Configure Place</h2>
                <div id="ItemName">
                    <span style="font-weight: bold;">Name:</span><br>
                    <input style="width: 410px"  name="iName" type="text" value="<?=$game["name"]; ?>" maxlength="35" class="TextBox">
                    <?php if ($error) echo $error; ?> 
                    
                </div>
		<br>
			<div class="PlaceThumbnail" style="text-align:center;">
				<a title="<?= $game["name"];?>" href="/Item.aspx?ID=<?=$game["id"];?>" style="cursor:pointer;"><img style="max-width:420px;border-color:black;" src="/Thumbs/Asset.ashx?assetId=<?= $game["id"];?>" border="1" id="img" alt="<?= $game["name"];?>"></a>
			</div>
			<div id="ItemDescription">
				<span style="font-weight: bold;">Description:</span><br>
				<textarea name="iDesc" maxlength="200" rows="2" cols="20" class="TextBox" style="height:150px;width: 410px;padding: 5px;"><?= $game["description"];?></textarea>
			</div>
					 
			
						<div id="Comments">
				<fieldset title="Turn comments on/off">
					<legend>Turn comments on/off</legend>
					<div class="Suggestion">
						Choose whether or not this item is open for comments.
					</div>
				<div class="EnableCommentsRow">
						<input class="commentCheckbox" type="checkbox" name="commentschk" ><label>Allow Comments</label>
					</div>
				</fieldset>
			</div>
		
		
			<div id="Comments">
				<fieldset title="Access">
					<legend>Access</legend>
					<div class="Suggestion">
						This determines who can access your place.
					</div>
					<div class="AccessRow" style="float:right;">
						  <input id="access" type="radio" name="access" value="public"  <?php if($game['State'] == "public"){ ?>checked="" <?php } ?>tabindex="6"><label><img src="/images/public.png"> Public: Anybody can visit my place</label><br>   
    <input id="access" type="radio" name="access" value="friends" <?php if($game['State'] == "friends"){?>checked="" <?php } ?>tabindex="6"><label><img src="/images/locked.png"> Private:  Only my friends can visit my place</label>
					
					</div>
				</fieldset>
			</div>
		
			<div id="Comments">
				<fieldset title="Copy Protection">
					<legend>Copy Protection</legend>
					<div class="Suggestion">
						Checking this will prevent your place from being copied but will also make it available to others only in online mode.
					</div>
					<div class="EnableCommentsRow">
						<input class="commentCheckbox" type="checkbox" name="copylock" checked="" disabled=""><label>Copy-Lock my place</label>
					</div>
				</fieldset>
			
		

			</div>
	        <?php if($game['commentsenabled'] == 1) { ?>
     <script>
       $(document).ready(function() {
    $(".commentCheckbox").prop("checked", true);
});
    </script> 
      <?php } ?> 
        <script>
            function openResetBox() {
                var boxidfk = document.getElementById("placeBox");
                var idfkkk = document.getElementById("PlaceReset")
                
                boxidfk.style.display = "block";
                idfkkk.style.marginBottom = "150px";
            }
            function closeResetBox() {
                var boxidfk = document.getElementById("placeBox");
                var idfkkk = document.getElementById("PlaceReset")
                
                boxidfk.style.display = "none";
                idfkkk.style.marginBottom = "0px";
            }
            function resetPlace(real) {
                console.log(real);
            }
   
        </script>
      <center> 
        <div id="PlaceReset">
			<div class="popupControl" id="placeBox" style="width:418px;">
    <div>
        <div align="right">
            <a class="PopUpOption" href="javascript:closeResetBox()">[ close window ]</a>
        </div>
        <div class="PopUpInstruction">To reset your place, click an image below:</div>
        <form id="resetForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <table cellspacing="0" cellpadding="10" align="Center" border="0" style="border-collapse:collapse;">
                <tbody>
                    <tr>
                        <td align="center" valign="middle" style="color:#003399;background-color:White;">
                            <button type="submit" name="resettype" value="0" style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;"><img src="/images/HappyHomeBig.png" width="120px" height="70px" border="0" alt="Happy Home in Goldbloxia"></button><br>
                            <span>Happy Home in Goldbloxia</span>
                        </td>
                        <td align="center" valign="middle" style="color:#003399;background-color:White;">
                            <button type="submit" name="resettype" value="1" style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;"><img src="/images/BrickBattleBigNew.png" width="120px" height="70px" border="0" alt="Starting BrickBattle Map"></button><br>
                            <span>Starting BrickBattle Map</span>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="middle" style="color:#003399;background-color:White;">
                            <button type="submit" name="resettype" value="2" style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;"><img src="/images/EmptyBaseBig.png" width="120px" height="70px" border="0" alt="Empty Baseplate"></button><br>
                            <span>Empty Baseplate</span>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

			<fieldset title="Reset Place">
				<legend>Reset Place</legend>
				<div class="Suggestion">
					Only do this if you want to reset your place to one of our starting templates.  This will cause you to lose any changes you have made and cannot be un-done.
				</div>
		
				<div class="ResetPlaceRow">
					<div class="Button" onclick="openResetBox()">
	
						Reset Place
					
</div>
	
				</div>
			
			</fieldset>
			
        </div>
</center> 
<div class="Buttons">
				<input name="updateall" tabindex="4" class="Button" type="submit" value="Update">&nbsp;
				<a id="Cancel" tabindex="5" class="Button" href="/PlaceItem.aspx?ID=<?=htmlspecialchars ($game["id"]);?>">Cancel</a>&nbsp;
		
			
</div>
		</div>
		
<div style="clear: both;"></div>
	</div></form>

<?php include($_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"); ?>
