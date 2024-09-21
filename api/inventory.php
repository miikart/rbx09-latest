<?php
include("../api/web/config.php");

try {
 
    $realtype = 0;
    $createtype = null;

    if (!isset($_POST['type']) || !isset($_POST['uid'])) {
        die("Invalid request");
    }

    switch ((int)$_POST['type']) {
        case 1:
            $realtype = "hat";
            break;
        case 2:
            $realtype = "tshirt";
            $createtype = 0;
            break;
        case 5:
            $realtype = "shirt";
            $createtype = 1;
            break;
        case 6:
            $realtype = "pants";
            $createtype = 2;
            break;
        case 4:
            $realtype = "decal";
            $createtype = 3;
            break;
        case 8:
            $realtype = "face";
            $createtype = 4;
            break;
        case 69:
            $realtype = "gear";
            $createtype = 69;
            break;
        case 7:
            $realtype = "head";
            $createtype = 7;
            break;
        case 0:
            $realtype = "place";
            $createtype = 0;
            break;
        default:
            die("Invalid type");
    }

    $uid = (int)$_POST['uid'];
    $stmt = $con->prepare("SELECT COUNT(*) FROM owned_items WHERE ownerid = :uid AND type = :type");
    $stmt->execute(['uid' => $uid, 'type' => $realtype]);
    $whyugh = $stmt->fetchColumn();

    $resultsperpage = 15;
    $numberofpages = ceil($whyugh / $resultsperpage);

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $prev = $page - 1;
    $next = $page + 1;
    $thispagefirstresult = ($page - 1) * $resultsperpage;
?>
<font size="small">
  <div style="margin: 0 auto; width: 100px;text-align:center">
 

  </div>
</font>



<table cellspacing="0" border="0">
  <tbody>
    <?php
    $limit = $resultsperpage;
    $offset = $thispagefirstresult;
    $stmt = $con->prepare("SELECT * FROM owned_items WHERE ownerid = :uid AND type = :type ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stmt->bindParam(':type', $realtype, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $counter = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          if ($counter % 5 == 0) { 
            echo '<tr>';
        }

        $uh++;
        $whar = $con->prepare("SELECT * FROM catalog WHERE id = :itemid");
        $whar->execute(['itemid' => $row['itemid']]);
        $item = $whar->fetch(PDO::FETCH_ASSOC);

        $thumburl = "/Thumbs/Asset.ashx?assetId=" . $item['id'] . "&isSmall";
        $platosismybeb = $con->prepare("SELECT * FROM users WHERE id = :creatorid");
        $platosismybeb->execute(['creatorid' => $item['creatorid']]);
        $_gay = $platosismybeb->fetch(PDO::FETCH_ASSOC);

        $name = $item['name'];
        $price = htmlspecialchars($item['price']);
        $creator = htmlspecialchars($_gay['username']);
        $creatorid = htmlspecialchars($_gay['id']);
        
        $cssprice = "PriceInTickets";
        $pricename = "Free";
        if ($item['price'] > 0) {
            if ($item['buywith'] == "tix") {
                $cssprice = "PriceInTickets";
                $pricename = "Tx";
            } else if ($item['buywith'] == "GOLDBUX") {
                $cssprice = "PriceInGOLDBUX";
                $pricename = "G$";
            }
        } else {
            $cssprice = "PriceInTickets\" style='color: blue;'";
        }
    ?>
      <td class="Asset" valign="top">
        <div style="padding:5px">
          <div class="AssetThumbnail" style="position: relative;">
            <a id="ctl00_cphGoldblox_rbxUserAssetsPane_UserAssetsDataList_ctl00_AssetThumbnailHyperLink" title="<?=$name?>" href="/Item.aspx?ID=<?=$item['id']?>" style="display:inline-block;cursor:pointer;">
              <div class="image-wrapper" style="width: 110px; height: 110px; background-image: url('<?=$thumburl?>'); background-size: cover; background-position: center;" alt="<?=$name?>" border="0" blankurl="http://t6.roblox.com:80/blank-110x110.gif">
              </div>
            </a>
          </div>
          <div class="AssetDetails">
            <div class="AssetName"><a id="ctl00_cphGoldblox_rbxUserAssetsPane_UserAssetsDataList_ctl00_AssetNameHyperLink" href="Item.aspx?ID=<?=$item['id']?>"><?=$name?></a></div>
            <div class="AssetCreator"><span class="Label">Creator:</span> <span class="Detail"><a id="ctl00_cphGoldblox_rbxUserAssetsPane_UserAssetsDataList_ctl00_GameCreatorHyperLink" href="/User.aspx?ID=<?=$creatorid?>"><?=$creator?></a></span></div>
           <?php if ($item['isoffsale'] == 0 && $item['type'] != 'place') { ?>
    <?php if ($item['price'] > 0 || $item['price2'] > 0) { ?>
        <?php if ($item['price'] > 0 && $item['buywith'] != "tix") { ?>
            <div class="AssetPrice" style="width:120px;height:11px">
                <span class="PriceInGOLDBUX">G$: <?= number_format($item['price']) ?></span>
            </div>
        <?php } ?>
        
        <?php if ($item['price2'] > 0 && $item['buywith2'] != "tix") { ?>
            <div class="AssetPrice" style="width:120px;height:11px">
                <span class="PriceInGOLDBUX">G$: <?= number_format($item['price2']) ?></span>
            </div>
        <?php } ?>
        
        <?php if ($item['price'] > 0 && $item['buywith'] == "tix") { ?>
            <div class="AssetPrice" style="width:120px;height:11px">
                <span class="PriceInTickets">Tx: <?= number_format($item['price']) ?></span>
            </div>
        <?php } ?>
        
        <?php if ($item['price2'] > 0 && $item['buywith2'] == "tix") { ?>
            <div class="AssetPrice" style="width:120px;height:11px">
                <span class="PriceInTickets">Tx: <?= number_format($item['price2']) ?></span>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="AssetPrice" style="width:120px;height:11px">
            <span class="PriceInTickets" style='color:blue;' >Free</span>
        </div>
    <?php } ?>
<?php } ?>
          </div>
        </div>
      </td>
    <?php
                  $counter++;
           if ($counter % 5 == 0) { 
            echo '</tr>';
        }
    }


    if ($counter % 5 != 0) {
        echo '</tr>';
    }
    
    ?>

  </tbody>
</table>

<?php if ($numberofpages > 1) { ?>
  <div class="FooterPager">
    <a href="#" onclick="getInventory(<?=$_POST['type'];?>, <?=$prev;?>, event)">
      <?php if ($page > 1) { ?>
        <span class="NavigationIndicators"><<</span> Previous
      <?php } ?>
    </a>
    <span>Page <?=$page;?> of <?=$numberofpages;?>:</span>
    <a href="#" onclick="getInventory(<?=$_POST['type'];?>, <?=$next;?>, event)">
      <?php if ($page < $numberofpages) { ?>
        Next <span class="NavigationIndicators">&gt;&gt;</span>
      <?php } ?>
    </a>
  </div>
<?php } ?>


<?php
} catch (PDOException $e) {
    echo "nagahelm error: " . $e->getMessage();
}
?>
