<?php
include("../api/web/config.php");

try {
  
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

$realtype = "";
$createtype = 0;

if (isset($_POST['type'])) {
    switch ($_POST['type']) {
        case "1":
          $realtype2 = "hats";
            $realtype = "hat";
             $createtype = 1;
            break;
        case "2":
                     $realtype2 = "t-shirts";

            $realtype = "tshirt";
            $createtype = 2;
            break;
        case "5":
                    $realtype2 = "shirts";

            $realtype = "shirt";
            $createtype = 5;
            break;
        case "6":
                    $realtype2 = "pants";

            $realtype = "pants";
            $createtype = 6;
            break;
        case "4":
                     $realtype2 = "decals";

            $realtype = "decal";
            $createtype = 4;
            break;
        case "8":
                     $realtype2 = "faces";

            $realtype = "face";
            $createtype = 8;
            break;
        case "7":
                    $realtype2 = "heads";

            $realtype = "head";
            $createtype = 7;
            break;
        case "3":
                     $realtype2 = "models";

            $realtype = "model";
            $createtype = 3;
            break;
        case "0":
                    $realtype2 = "places";

            $realtype = "place";
            $createtype = 0;
            break;
        case "69":
                      $realtype2 = "gears";

            $realtype = "gear";
            $createtype = 69;
            break;
        default:
            die("Invalid type");
    }
} else {
    die("Type not set");
}


$resultsperpage = 6;
$uid = (int)$_POST["uid"];

$stmt = $con->prepare("SELECT COUNT(*) FROM favorites WHERE uid = :uid AND type = :type");
$stmt->execute(['uid' => $uid, 'type' => $realtype]);
$whyugh = $stmt->fetchColumn();

$numberofpages = ceil($whyugh / $resultsperpage);
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$prev = $page - 1;
$next = $page + 1;
$thispagefirstresult = ($page - 1) * $resultsperpage;
        $itemle = $con->prepare("SELECT * FROM users WHERE id = :id");
        $itemle->execute(['id' => $_USER['id']]);
        $_USERshit = $itemle->fetch(PDO::FETCH_ASSOC);

?>


<?php if ($numberofpages > 1) { ?>
 <div class="HeaderPager">
  <a href="#" onclick="getFavs(<?= htmlspecialchars((int)$_POST['type']); ?>, <?= $prev; ?>, event)">
      <?php if ($page > 1) { ?>
      <span class="NavigationIndicators"><<</span> Previous<?php } ?></a>
    <span>Page <?= htmlspecialchars($page); ?> of <?= htmlspecialchars($numberofpages); ?>:</span>
    <a href="#" onclick="getFavs(<?= htmlspecialchars((int)$_POST['type']); ?>, <?= htmlspecialchars($next); ?>, event)">
      <?php if ($page < $numberofpages) { ?>
      Next <span class="NavigationIndicators">&gt;&gt;</span>
      <?php } ?>
  </a>
<?php } ?>
<?php if ($numberofpages > 0) { ?>
</div>
<?php } ?>

<table cellspacing="0" border="0" style="margin: auto;">
  <tbody>
<script>
function unfavoritething(id) {
    fetch(`/api/unfavorite.php?id=${id}`, { 
        method: 'POST',
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to delete favorite.');
        }
        console.log(`Deleted ${id}`);
        getFavs(<?php echo $createtype;?>, <?php echo $page;?>, event);
    })
    .catch(error => {
        console.error('fard ', error);
    });
}
</script>


    <?php
    $stmt = $con->prepare("SELECT * FROM favorites WHERE uid = :uid AND type = :type ORDER BY id DESC LIMIT :start, :limit");
    $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stmt->bindParam(':type', $realtype, PDO::PARAM_STR);
    $stmt->bindParam(':start', $thispagefirstresult, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $resultsperpage, PDO::PARAM_INT);
    $stmt->execute();

    $uh = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $itemid = $row['itemid'];
        $itemq = $con->prepare("SELECT * FROM catalog WHERE id = :id");
        $itemq->execute(['id' => $itemid]);
        $item = $itemq->fetch(PDO::FETCH_ASSOC);

        $thumburl = "/Thumbs/Asset.ashx?assetId=" . htmlspecialchars($item['id']) . "&isSmall";

        $iteml = $con->prepare("SELECT * FROM users WHERE id = :id");
        $iteml->execute(['id' => $item['creatorid']]);
        $_USER = $iteml->fetch(PDO::FETCH_ASSOC);

        $name = $item['name'];
        $creator = htmlspecialchars($_USER['username']);
        $creatorid = htmlspecialchars($_USER['id']);
       echo
          
          
      
          "

  <td class=\"Asset\" valign=\"top\">
       
        <div style=\"padding:5px\">
   <div class=\"AssetThumbnail\" style=\"position: relative;\">

                     <a onclick=\"unfavoritething({$item['id']})\" href='javascript:void(0);'class='DeleteButtonOverlay'>[ delete ]</a>
      <a id=\"ctl00_cphGoldblox_rbxUserAssetsPane_UserAssetsDataList_ctl00_AssetThumbnailHyperLink\" title=\"$name\" href=\"/Item.aspx?ID={$item['id']}\" style=\"display:inline-block;cursor:pointer;\">
                    <div class=\"image-wrapper\" style=\"width: 110px; height: 110px; background-image: url('$thumburl'); background-size: cover; background-position: center;\" alt=\"$name\" border=\"0\" blankurl=\"http://t6.roblox.com:80/blank-110x110.gif\">
                    </div>
                </a>
            </div>
      <div class=\"AssetDetails\">
        <div class=\"AssetName\"><a id=\"ctl00_cphGoldblox_rbxUserAssetsPane_UserAssetsDataList_ctl00_AssetNameHyperLink\" href=\"Item.aspx?ID={$item['id']}\">$name</a></div>
        <div class=\"AssetCreator\"><span class=\"Label\">Creator:</span> <span class=\"Detail\"><a id=\"ctl00_cphGoldblox_rbxUserAssetsPane_UserAssetsDataList_ctl00_GameCreatorHyperLink\" href=\"/User.aspx?ID=$creatorid\">$creator</a></span></div>
        
                
        ";
			

        $uh++;
        if ($uh == 3) {
            echo "</tr><tr>";
            $uh = 0;
        }
    }
    ?>
  </tbody>
</table>

<?php if ($numberofpages > 1) { ?>
<div class="FooterPager">
  <a href="#" onclick="getFavs(<?= htmlspecialchars((int)$_POST['type']); ?>, <?= $prev; ?>, event)">
      <?php if ($page > 1) { ?>
      <span class="NavigationIndicators"><<</span> Previous<?php } ?></a>
    <span>Page <?= htmlspecialchars($page); ?> of <?= htmlspecialchars($numberofpages); ?>:</span>
    <a href="#" onclick="getFavs(<?= htmlspecialchars((int)$_POST['type']); ?>, <?= $next; ?>, event)">
      <?php if ($page < $numberofpages) { ?>
      Next <span class="NavigationIndicators">&gt;&gt;</span>
      <?php } ?>
  </a>
</div>
<?php } ?>

<?php if ($numberofpages == 0) { ?>
<div id="ctl00_cphGoldblox_rbxFavoritesPane_NoResultsPanel" class="NoResults">
		
				    <span id="ctl00_cphGoldblox_rbxFavoritesPane_NoResultsLabel" class="NoResults"> You have not chosen any favorite <?php echo $realtype2 ?>.</span>
				
	</div>
<?php } ?>
