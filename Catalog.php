<?php
require_once 'api/web/config.php';
include 'api/web/header.php';

if (isset($_REQUEST['m'])) {
$m= $_REQUEST['m'];
switch ($m){
case "TopFavorites":
$mthing = "Top Favorites";
$mthing3 = "TopFavorites";
 break; 
case "BestSelling":
$mthing = "Best Selling";
 $mthing3 = "BestSelling";
break;
case "RecentlyUpdated":
$mthing = "Recently Updated";
 $mthing3 = "RecentlyUpdated";
 $bthing2 = 0; 
break;
 case "ForSale":
$mthing = "For Sale";
$mthing3 = "ForSale";
break;
default:
$mthing = "Top Favorites";
$mthing3 = "TopFavorites";
 $bthing2 = 0; 
 break;
}
} else {
   $mthing = "Top Favorites";
$mthing3 = "TopFavorites";
 $bthing2 = 0; 
    $m= null;
}
if (isset($_REQUEST['c'])) {
$c= (int)$_REQUEST['c'];
switch($c){
case 7:
$tthing = "Heads";
  $bthing = "7";
 break;   
case 8:
$tthing = "Faces";
$bthing = "8";
break;
  
case 1:
$tthing = "Hats";
$bthing = "1";
break;
case 2:
$tthing = "T-Shirts";
$bthing = "2";
break;
  
case 5:
$tthing = "Shirts";
$bthing = "5";
break;
  
 case 6:
$tthing = "Pants";
$bthing = "6";
break;
  
case 4:
$tthing = "Decals";
$bthing = "4";
break;
case 3:
$tthing = "Models";
$bthing = "3";
break;
  
 case 20:
$tthing = "Places";
$bthing = "20";
break;
 case 20:
$tthing = "Places";
$bthing = "9";
break;
default:
    $tthing = "Hats";
$bthing = "1";
    break;  
}
    
} else {
   $tthing = "Hats";
$bthing = "1";
$c =null;
    
}
 $type2 = null;
if (isset($tthing)) {
    switch ($tthing) {
        case "Heads":
            $type2 = "head";
            break;
        case "Faces":
            $type2 = "face";
            break;
        case "Hats":
            $type2 = "hat";
            break;
        case "T-Shirts":
            $type2 = "tshirt";
            break;
        case "Shirts":
            $type2 = "shirt";
            break;
        case "Pants":
            $type2 = "pants";
            break;
        case "Decals":
            $type2 = "decal";
            break;
        case "Models":
            $type2 = "model";
            break;
       case "Places":
            $type2 = "place";
            break;
        default:
           $type2 = "hat";
            break;
    }
}
$sqlthing = "SELECT * FROM catalog WHERE `type` = :type2";
$params = ['type2' => $type2];
if (isset($_GET['m'])) {
    switch ($m) {
        case "ForSale":
            $sqlthing .= " AND isoffsale = 0";
         
            break;
     
  
    }    }

if(!empty($_REQUEST['q'])) {
$search = htmlspecialchars(strtolower($_REQUEST['q']), ENT_QUOTES, 'UTF-8');
$search2isuck = htmlspecialchars(($_REQUEST['q']), ENT_QUOTES, 'UTF-8');
}
if (!empty($search)) {
                $sqlthing .= " AND (LOWER(name) LIKE :search)";
                $params['search'] = '%' . $search . '%';
            }
$stmt = $con->prepare($sqlthing);
$stmt->execute($params);
$results = $stmt->fetchAll();
$resultsperpage = 20;
$usercount = count($results);
$numberofpages = ceil($usercount / $resultsperpage);
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$page = max(min($page, $numberofpages), 1);
$offset = ($page - 1) * $resultsperpage;
$currentResults = array_slice($results, $offset, $resultsperpage);
?>

<div id="CatalogContainer" style="margin-top: 10px;">
 <form action="" method="POST">
                <div id="SearchBar" class="SearchBar">
                    <span class="SearchBox">
                        <input name="q" type="text" maxlength="100" id="ctl00_cphGoldblox_SearchTextBox" class="TextBox" value="<?php if(!empty($search)) { ?><?= $search2isuck;?><?php } ?>">
                 
                    </span>
                    <span class="SearchButton">
                        <input type="submit" name="ctl00$cphGoldblox$SearchButton" value="Search" id="ctl00_cphGoldblox_SearchButton">
                    </span>
                    <span class="SearchLinks">
                        <sup><a id="ctl00_cphGoldblox_ResetSearchButton" href="/Browse.aspx">Reset</a>&nbsp;|&nbsp;</sup>
                        <a href="#" class="tips"><sup>Tips</sup>
                            <span>
                                Exact Phrase: "red brick"<br>
                                Find ALL Terms: red and brick =OR= red + brick<br>
                                Find ANY Term: red or brick =OR= red | brick<br>
                                Wildcard Suffix: tel* (Finds teleport, telamon, telephone, etc.)<br>
                                Terms Near each other: red near brick =OR= red ~ brick<br>
                                Excluding Terms: red and not brick =OR= red - brick<br>
                                Grouping operations: brick and (red or blue) =OR= brick + (red | blue)<br>
                                Combinations: "red brick" and not (tele* or tower) =OR= "red brick" - (tele* | tower)<br>
                                Wildcard Prefix is NOT supported: *port will not find teleport, airport, etc.
                            </span>
                        </a>
                    </span>
                </div>
            </form>
 
  <div class="pt-3"></div>
 <div class="DisplayFilters">
    <h2>Catalog</h2>
    <title>GOLDBLOX - Catalog</title>
    <div id="BrowseMode">
           <h4><a id="ctl00_cphGoldblox_rbxCatalog_CafePressHyperLink" href="#">Buy GOLDBLOX Stuff!</a></h4>
        <h4><a id="ctl00_cphGoldblox_rbxCatalog_CafePressHyperLink" href="#">Buy GOLDBUX!</a></h4>
      <h4><a id="ctl00_cphGoldblox_rbxCatalog_CafePressHyperLink" href="/Marketplace/TradeCurrency.aspx">Trade Currency!</a></h4>
 
   
    <h4>Browse</h4>
  
  <?php $sel = '<img id="ctl00_cphGoldblox_rbxGames_MostPopularBullet" class="GamesBullet" src="/images/games_bullet.png" border="0"/>'; ?>
    <ul>
      <li><?php if($mthing == "Top Favorites"){ echo $sel; }?><a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Catalog.aspx?m=TopFavorites&c=<?=( $bthing);?>"><?php if($mthing == "Top Favorites"){?><b><?}?>Top Favorites<?php if($mthing == "Top Favorites"){?></b><?}?></a></li>
      <li><?php if($mthing == "Best Selling"){ echo $sel; }?><a id="ctl00_cphGoldblox_rbxGames_hlTopFavorites" href="Catalog.aspx?m=BestSelling&c=<?=( $bthing);?>"><?php if($mthing == "Best Selling"){?><b><?}?>Best Selling<?php if($mthing == "Best Selling"){?></b><?}?></a></li>
      <li><?php if($mthing == "Recently Updated"){ echo $sel; }?><a id="ctl00_cphGoldblox_rbxGames_hlRecentlyUpdated" href="Catalog.aspx?m=RecentlyUpdated&c=<?=($bthing);?>"><?php if($mthing == "Recently Updated"){?><b><?}?>Recently Updated<?php if($mthing == "Recently Updated"){?></b><?}?></a></li>
      <li><?php if($mthing == "For Sale"){ echo $sel; }?><a id="ctl00_cphGoldblox_rbxGames_hlRecentlyUpdated" href="Catalog.aspx?m=ForSale&c=<?=($bthing);?>"><?php if($mthing == "For Sale"){?><b><?}?>For Sale<?php if($mthing == "For Sale"){?></b><?}?></a></li>
    <li><a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="#">Public Domain</a></li>
    </ul>
    </div>
    <div id="Category">
    <h4>Category</h4>
    <?php $sel2 = '<img id="ctl00_cphGoldblox_rbxGames_TimespanNowBullet" class="GamesBullet" src="/images/games_bullet.png" border="0"/>'; ?>
      <ul>
      <li><?php if($tthing == "Heads"){ echo $sel2; }?><a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Catalog.aspx?m=<?=($mthing3);?>&c=7"><?php if($tthing == "Heads"){?><b><?}?>Heads<?php if($tthing == "Heads"){?></b><?}?></a></li>
      <li><?php if($tthing == "Faces"){ echo $sel2; }?><a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Catalog.aspx?m=<?=($mthing3);?>&c=8"><?php if($tthing == "Faces"){?><b><?}?>Faces<?php if($tthing == "Faces"){?></b><?}?></a></li>
      <li><?php if($tthing == "Hats"){ echo $sel2; }?><a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Catalog.aspx?m=<?=($mthing3);?>&c=1"><?php if($tthing == "Hats"){?><b><?}?>Hats<?php if($tthing == "Hats"){?></b><?}?></a></li>
      <li><?php if($tthing == "T-Shirts"){ echo $sel2; }?><a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Catalog.aspx?m=<?=($mthing3);?>&c=2"><?php if($tthing == "T-Shirts"){?><b><?}?>T-Shirts<?php if($tthing == "T-Shirts"){?></b><?}?></a></li>
      <li><?php if($tthing == "Shirts"){ echo $sel2; }?><a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Catalog.aspx?m=<?=($mthing3);?>&c=5"><?php if($tthing == "Shirts"){?><b><?}?>Shirts<?php if($tthing == "Shirts"){?></b><?}?></a></li>
      <li><?php if($tthing == "Pants"){ echo $sel2; }?><a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Catalog.aspx?m=<?=($mthing3);?>&c=6"><?php if($tthing == "Pants"){?><b><?}?>Pants<?php if($tthing == "Pants"){?></b><?}?></a></li>

      
        <li><?php if($tthing == "Decals"){ echo $sel2; }?><a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Catalog.aspx?m=<?= str_replace(' ', '', $mthing); ?>&c=4"><?php if($tthing == "Decals"){?><b><?}?>Decals<?php if($tthing == "Decals"){?></b><?}?></a></li>
        <li><?php if($tthing == "Places"){ echo $sel2; }?><a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Catalog.aspx?m=<?= str_replace(' ', '', $mthing); ?>&c=20"><?php if($tthing == "Places"){?><b><?}?>Places<?php if($tthing == "Places"){?></b><?}?></a></li>
             

      
    </ul>

    </div>
    
  </div>
 <?php if($usercount == 0) { ?>
 <?php 
 if ($m === 'TopFavorites' || !$m) {

    echo '<span id="ctl00_cphGoldblox_rbxCatalog_AssetsDisplaySetLabel" class="AssetsDisplaySet">';
    echo 'Favorite ' . htmlspecialchars($tthing);
    echo '</span>';
} elseif (!$m) {

    echo '<span id="ctl00_cphGoldblox_rbxCatalog_AssetsDisplaySetLabel" class="AssetsDisplaySet">';
     echo 'Favorite ' . htmlspecialchars($tthing);
    echo '</span>';
     
 } elseif ($m === 'ForSale') {

    echo '<span id="ctl00_cphGoldblox_rbxCatalog_AssetsDisplaySetLabel" class="AssetsDisplaySet">';
    echo htmlspecialchars($tthing) . ' ' . htmlspecialchars($mthing);
    echo '</span>';
} else {
    echo '<span id="ctl00_cphGoldblox_rbxCatalog_AssetsDisplaySetLabel" class="AssetsDisplaySet">';
    echo htmlspecialchars($mthing) . ' ' . htmlspecialchars($tthing);
    echo '</span>';
}
?><br><br>
  No results found.
 <?php }else{ ?>
  <div class="Assets">
 <?php 
 if ($m === 'TopFavorites' || !$m) {

    echo '<span id="ctl00_cphGoldblox_rbxCatalog_AssetsDisplaySetLabel" class="AssetsDisplaySet">';
    echo 'Favorite ' . htmlspecialchars($tthing);
    echo '</span>';

     
 } elseif ($m === 'ForSale') {

    echo '<span id="ctl00_cphGoldblox_rbxCatalog_AssetsDisplaySetLabel" class="AssetsDisplaySet">';
    echo htmlspecialchars($tthing) . ' ' . htmlspecialchars($mthing);
    echo '</span>';
} else {
    echo '<span id="ctl00_cphGoldblox_rbxCatalog_AssetsDisplaySetLabel" class="AssetsDisplaySet">';
    echo htmlspecialchars($mthing) . ' ' . htmlspecialchars($tthing);
    echo '</span>';
}
?>
    <div id="ctl00_cphGoldblox_rbxCatalog_HeaderPagerPanel" class="HeaderPager">
    <?php if($page > 1){if($numberofpages > 0){?><a id="ctl00_cphGoldblox_rbxGames_hlHeaderPager_Next" href="Catalog.aspx?m=<?=($mthing3);?>&c=<?=($bthing);?>&p=<?=$page -1;?>"><span class="NavigationIndicators"><<</span> Previous</a><?}}?>
      <span id="ctl00_cphGoldblox_rbxGames_HeaderPagerLabel">Page <?=$page;?> of <?=$numberofpages;?>:</span>
      
      <?php if($page < $numberofpages){?><a id="ctl00_cphGoldblox_rbxGames_hlHeaderPager_Next" href="Catalog.aspx?m=<?=($mthing3);?>&c=<?=($bthing);?>&p=<?=$page +1;?>">Next <span class="NavigationIndicators">&gt;&gt;</span></a><?}?>
    </div>
    <table id="ctl00_cphGoldblox_rbxCatalog_AssetsDataList" cellspacing="0" align="Center" border="0" width="610">
<?php
$page = isset($page) ? (int)$page : 1;
$resultsperpage = isset($resultsperpage) ? (int)$resultsperpage : 10;
$thispagefirstresult = ($page - 1) * $resultsperpage;
$type2 = isset($type2) ? $type2 : '';

switch ($m) {
    case "ForSale":
        $sql = "SELECT * FROM catalog WHERE `type` = :type2 AND isoffsale = 0";
        if (!empty($search)) $sql .= " AND LOWER(name) LIKE :search";
        $sql .= " ORDER BY id DESC , catalog.id DESC LIMIT :start, :limit";
        break;
    
    case "BestSelling":
        $sql = "SELECT catalog.*, COUNT(owned_items.itemid) AS owned 
                FROM catalog 
                LEFT JOIN owned_items ON catalog.id = owned_items.itemid 
                WHERE catalog.`type` = :type2";
        if (!empty($search)) $sql .= " AND LOWER(catalog.name) LIKE :search";
        $sql .= " GROUP BY catalog.id ORDER BY owned DESC, catalog.id DESC LIMIT :start, :limit";
        break;
    
    case "RecentlyUpdated":
        $sql = "SELECT * FROM catalog WHERE `type` = :type2";
        if (!empty($search)) $sql .= " AND LOWER(name) LIKE :search";
        $sql .= " ORDER BY creation_date DESC, catalog.id DESC LIMIT :start, :limit";
        break;
  
    default:
        $sql = "SELECT catalog.*, COUNT(favorites.itemid) AS favorites 
                FROM catalog 
                LEFT JOIN favorites ON catalog.id = favorites.itemid 
                WHERE catalog.`type` = :type2";
        if (!empty($search)) $sql .= " AND LOWER(catalog.name) LIKE :search";
        $sql .= " GROUP BY catalog.id ORDER BY favorites DESC, catalog.id DESC  LIMIT :start, :limit";
        break;
}

$stmt = $con->prepare($sql);
$stmt->bindParam(':type2', $type2, PDO::PARAM_STR);
if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
}
$stmt->bindParam(':start', $thispagefirstresult, PDO::PARAM_INT);
$stmt->bindParam(':limit', $resultsperpage, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result) > 0) {
    $count = 0;
    echo '<tr>';
    foreach ($result as $row) {
        if ($count % 4 == 0 && $count != 0) {
            echo '</tr><tr>';
        }
        $creators = $con->prepare("SELECT * FROM users WHERE id = :creatorid");
        $creators->bindParam(':creatorid', $row['creatorid'], PDO::PARAM_INT);
        $creators->execute();
        $creator = $creators->fetch(PDO::FETCH_ASSOC);
     
        $count++;
?>
            <td valign="top">
                <div class="Asset">
                    <div class="AssetThumbnail">
       <a id="AssetThumbnailHyperLink" title="<?= $row['name']; ?>" href="/Item.aspx?ID=<?= $row['id']; ?>"
   style="display:inline-block; cursor:pointer; width: 110px; height: 110px; overflow: hidden;">
   <img src="/Thumbs/Asset.ashx?assetId=<?= $row['id']; ?>&isSmall" id="img" alt="<?= $row['name']; ?>"
        style="width: 110px; height: 110px; object-fit: cover; object-position: center center; border: 0;">
</a>


                    </div>
                    <div class="AssetDetails">
                        <strong><a id="ctl00_cphGoldblox_rbxUserAssetsPane_UserAssetsDataList_ctl06_AssetNameHyperLink" href="/Item.aspx?ID=<?= $row['id']; ?>"><?= $row['name']; ?></a></strong>
                        <div class="AssetLastUpdate"><span class="Label">Updated:</span> <span class="Detail">
                                <?php
  	  $updated = context::updated($row['creation_date']);
                                ?>
                         <?php echo $updated ?>
                            </span></div>
                        <div class="AssetCreator"><span class="Label">Creator:</span> <span class="Detail"><a id="ctl00_cphGoldblox_rbxCatalog_AssetsDataList_ctl00_GameCreatorHyperLink" href="/User.aspx?ID=<?= $creator['id']; ?>"><?= htmlspecialchars($creator['username']); ?></a></span></div>
                        <?php
                      $ownedq = "SELECT * FROM owned_items WHERE itemid = :itemid";
$owneds = $con->prepare($ownedq);
$owneds->execute([':itemid' => $row['id']]);
$ownedc = $owneds->rowCount();
                        ?>
                        <div class="AssetsSold"><span class="Label">Number Owned:</span> <span class="Detail"><?= htmlspecialchars($ownedc); ?></span></div>
                       
                        <?php
$favoritesq = "SELECT * FROM favorites WHERE itemid = :itemid";
$favoritese = $con->prepare($favoritesq);
$favoritese->execute([':itemid' => $row['id']]);
$favorites = $favoritese->rowCount();
?>
                        <div class="AssetFavorites"><span class="Label">Favorited:</span> <span class="Detail"><?= htmlspecialchars($favorites); ?> time<?php if ($favorites != 1) {
                                        echo "s";
                                    } ?></span></div>
                    
   <?php if ($row['isoffsale'] == 0 && $row['type'] != 'place') { ?>
    <?php if ($row['price'] > 0 || $row['price2'] > 0) { ?>
        <?php if ($row['buywith2'] == "GOLDBUX") { ?>
            <div class="AssetPrice" style="width:120px;height:11px">
                <span class="PriceInGOLDBUX">G$: <?= number_format($row['price2']) ?></span>
            </div>
        <?php } ?>
        <?php if ($row['buywith'] == "tix") { ?>
            <div class="AssetPrice" style="width:120px;height:11px">
                <span class="PriceInTickets">Tx: <?= number_format($row['price']) ?></span>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="AssetPrice" style="width:120px;height:11px">
            <span class="PriceForFree">Free</span>
        </div>
    <?php } ?>
<?php } ?>

                    </div>
                </div>
            </td>
<?php
    }

    if ($count < 4) {
        $pisscock = 4 - $count; 
        for ($i = 0; $i < $pisscock; $i++) {
?>
            <td valign="top">
                <div class="Asset">
                 
                </div>
            </td>
<?php
        }
    }
    echo '</tr>';
}
?>

</table>
    <div id="ctl00_cphGoldblox_rbxCatalog_FooterPagerPanel" class="FooterPager">
      
      <?php if($page > 1){if($numberofpages > 0){?><a id="ctl00_cphGoldblox_rbxGames_hlHeaderPager_Next" href="Catalog.aspx?m=<?=($mthing3);?>&c=<?=($bthing);?>&p=<?=$page -1;?>"><span class="NavigationIndicators"><<</span> Previous</a><?}}?>
      <span id="ctl00_cphGoldblox_rbxGames_HeaderPagerLabel">Page <?=$page;?> of <?=$numberofpages;?>:</span>
      
      <?php if($page < $numberofpages){?><a id="ctl00_cphGoldblox_rbxGames_hlHeaderPager_Next" href="Catalog.aspx?m=<?=($mthing3);?>&c=<?=($bthing);?>&p=<?=$page +1;?>">Next <span class="NavigationIndicators">&gt;&gt;</span></a><?}?>
    </div><?php } ?>
  </div>
 
 <div id="AdvertisingSkyscraper">
     
</div>
  <div style="clear: both;"/>
</div>
    </div>
<? include 'api/web/footer.php'; ?>