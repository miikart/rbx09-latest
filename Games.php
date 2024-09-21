<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/api/web/header.php";
$m = $_REQUEST['m'];  
switch($m) {
case "MostPopular";
$m = "MostPopular";
break;
case "RecentlyUpdated";
$m = "RecentlyUpdated";
break;
case "TopFavorites";
$m = "TopFavorites";
break;   
default:
$m = "MostPopular";
break;  
}
$resultsperpage = 15;
$stmt = $con->query("SELECT COUNT(*) FROM catalog WHERE type = 'place'");
$usercount = $stmt->fetchColumn();
$numberofpages = ceil($usercount / $resultsperpage);
if($numberofpages == 0) {
$numberofpages = 1;
}
if(isset($_REQUEST['p']))    {
$page = (int)$_REQUEST['p'];
} else {
$page = 1;   
}
$thispagefirstresult = ($page - 1) * $resultsperpage;
    switch ($m) {
        case "TopFavorites":
            $sql = "SELECT catalog.*, COUNT(favorites.id) AS total_favorites
                    FROM catalog
                    LEFT JOIN favorites ON catalog.id = favorites.itemid
                    WHERE catalog.type = 'place'
                    GROUP BY catalog.id
                    ORDER BY total_favorites DESC, catalog.id DESC
                    LIMIT :firstResult, :resultsPerPage";
            break;
        case "RecentlyUpdated":
            $sql = "SELECT * FROM catalog
                    WHERE type = 'place'
                    ORDER BY creation_date DESC, id DESC 
                    LIMIT :firstResult, :resultsPerPage";
            break;
        case "MostPopular":
        default:
            $sql = "SELECT * FROM catalog
                    WHERE type = 'place'
                    ORDER BY players DESC, visits DESC, id DESC
                    LIMIT :firstResult, :resultsPerPage";
            break;
    }

    $stmt = $con->prepare($sql);
    $stmt->bindParam(':firstResult', $thispagefirstresult, PDO::PARAM_INT);
    $stmt->bindParam(':resultsPerPage', $resultsperpage, PDO::PARAM_INT);
    $stmt->execute();
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<div id="Body">
    <div id="GamesContainer">
        <div id="ctl00_cphGoldblox_rbxGames_GamesContainerPanel">
            <div class="DisplayFilters">
                <h2>Games&nbsp;<a id="ctl00_cphGoldblox_rbxGames_hlNewsFeed" href="/Games.aspx?feed=rss"><img src="/images/feed-icon-14x14.png" alt="RSS" border="0"></a></h2>
                <div id="BrowseMode">
                    <h4>Browse</h4>
                    <ul>
                        <li>
                            <?php if ($m == "MostPopular") { ?>
                                <img id="ctl00_cphGoldblox_rbxCatalog_BrowseModeTopFavoritesBullet" class="GamesBullet" src="/images/games_bullet.png" border="0"/>
                                <a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Games.aspx?m=MostPopular"><b>Most Popular</b></a>
                            <?php } else { ?>
                                <a id="ctl00_cphGoldblox_rbxGames_hlMostPopular" href="Games.aspx?m=MostPopular">Most Popular</a>
                            <?php } ?>
                            <title><?php echo "GOLDBLOX Games - " . ($m == "MostPopular" ? "Most Popular" : ($m == "TopFavorites" ? "Top Favorites" : "Recently Updated")) . ""; ?></title>
                        </li>
                        <li>
                            <?php if ($m == "TopFavorites") { ?>
                                <img id="ctl00_cphGoldblox_rbxCatalog_BrowseModeTopFavoritesBullet" class="GamesBullet" src="/images/games_bullet.png" border="0"/>
                                <a id="ctl00_cphGoldblox_rbxGames_hlTopFavorites" href="Games.aspx?m=TopFavorites"><b>Top Favorites</b></a>
                            <?php } else { ?>
                                <a id="ctl00_cphGoldblox_rbxGames_hlTopFavorites" href="Games.aspx?m=TopFavorites">Top Favorites</a>
                            <?php } ?>
                        </li>
                        <li>
                            <?php if ($m == "RecentlyUpdated") { ?>
                                <img id="ctl00_cphGoldblox_rbxCatalog_BrowseModeTopFavoritesBullet" class="GamesBullet" src="/images/games_bullet.png" border="0"/>
                                <a id="ctl00_cphGoldblox_rbxGames_hlRecentlyUpdated" href="Games.aspx?m=RecentlyUpdated"><b>Recently Updated</b></a>
                            <?php } else { ?>
                                <a id="ctl00_cphGoldblox_rbxGames_hlRecentlyUpdated" href="Games.aspx?m=RecentlyUpdated">Recently Updated</a>
                            <?php } ?>
                        </li>
                        <li>
                            <a id="ctl00_cphGoldblox_rbxGames_hlFeatured" href="/User.aspx?ID=1">Featured Games</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="Games">
                <span id="ctl00_cphGoldblox_rbxGames_lGamesDisplaySet" class="GamesDisplaySet"><?php echo $m == "TopFavorites" ? "Top Favorite" : ($m == "RecentlyUpdated" ? "Recently Updated" : "Most Popular"); ?> Games</span>
                <div id="ctl00_cphGoldblox_rbxGames_HeaderPagerPanel" class="HeaderPager">
                    <span id="ctl00_cphGoldblox_rbxCatalog_HeaderPagerLabel">
            <?php if ($page > 1) {
                if ($numberofpages > 0) { ?><a id="ctl00_cphGoldblox_rbxGames_hlHeaderPager_Next" href="Games.aspx?m=<?= ($m) ; ?>&p=<?= $page - 1; ?>"><span class="NavigationIndicators"><<</span> Previous</a><? }
            } ?>
            <span id="ctl00_cphGoldblox_rbxGames_HeaderPagerLabel">Page <?= $page; ?> of <?= $numberofpages; ?>:</span>

            <?php if ($page < $numberofpages) { ?><a id="ctl00_cphGoldblox_rbxGames_hlHeaderPager_Next" href="Games.aspx?m=<?= ($m) ; ?>&p=<?= $page + 1; ?>">Next <span class="NavigationIndicators">&gt;&gt;</span></a><? } ?>
	        </div>

	    
                <table id="ctl00_cphGoldblox_rbxGames_dlGames" cellspacing="0" align="Center" border="0" width="550">
                    <tbody>
                      <?php if (count($games) > 0) {
    $counter = 0; 
    foreach ($games as $row) {
        if ($counter % 3 == 0) { 
            echo '<tr>';
        }

        $creatorshit = $con->prepare("SELECT * FROM users WHERE id = :creatorid");
        $creatorshit->bindParam(':creatorid', $row['creatorid'], PDO::PARAM_INT);
        $creatorshit->execute();
        $creator = $creatorshit->fetch(PDO::FETCH_ASSOC);

        $favorites = $con->prepare("SELECT COUNT(*) FROM favorites WHERE itemid = :itemid");
        $favorites->bindParam(':itemid', $row['id'], PDO::PARAM_INT);
        $favorites->execute();
        $totalfavorite = $favorites->fetchColumn();

        $updated = context::updated($row['creation_date']);
        ?>
                             <td class="Game" valign="top">
                                    <div style="padding-bottom:5px">
                                        <div class="GameThumbnail">
                                            <a id="ctl00_cphGoldblox_rbxGames_dlGames_ctl00_ciGame" title="<?=  ($row['name']) ?>" href="/Item.aspx?ID=<?=  ($row['id']) ?>" style="display:inline-block;cursor:pointer;"><img src="/Thumbs/Asset.ashx?assetId=<?=  ($row['id']) ?>&isSmall" border="0" alt="<?=  ($row['name']) ?>"></a>
                                        </div>
                                        <div class="GameDetails">
                                            <div class="GameName"><a id="ctl00_cphGoldblox_rbxGames_dlGames_ctl00_hlGameName" href="/Item.aspx?ID=<?=  ($row['id']) ?>"><?=  ($row['name']) ?></a></div>
                                            <div class="GameLastUpdate"><span class="Label">Updated:</span> <span class="Detail"><?=  ($updated) ?></span></div>
                                            <div class="GameCreator"><span class="Label">Creator:</span> <span class="Detail"><a id="ctl00_cphGoldblox_rbxGames_dlGames_ctl00_hlCreator" href="/User.aspx?ID=<?=  ($creator['id']) ?>"><?=  ($creator['username']) ?></a></span></div>
                                            <div class="GameFavorites"><span class="Label">Favorited:</span> <span class="Detail"><?=  ($totalfavorite) ?> times</span></div>
                                                    <div class="GamePlays"><span class="Label">Played:</span> <span class="Detail"><?=$ep->remove($row['visits'])?> times</span></div>
                                                   <?php if($row['players'] > 0){ ?><div class="GameCurrentPlayers"><span class="DetailHighlighted"><?=number_format($server['players'])?> players online</span></div><?php } ?>
                                   
                             	</div>
		        </div>
		    </div>
        </td>
        <?php
        $counter++;
        if ($counter % 3 == 0) { 
           
            echo '</tr>';
        }
    }
if($counter < 3) {
      echo '<td class="Game" valign="top">';
echo '<div style="padding-bottom:5px"></div>';
echo '</td>';
} elseif($counter == 1) {
echo '<td class="Game" valign="top">';
echo '<div style="padding-bottom:5px"></div>';
echo '</td>'; 
echo '<td class="Game" valign="top">';
echo '<div style="padding-bottom:5px"></div>';
echo '</td>';  
}
 
    if ($counter % 3 != 0) {
        echo '</tr>';
    }
} else { ?>
    <p>No results found.</p>
<?php } ?>

	</tbody></table>
        <div id="ctl00_cphGoldblox_rbxGames_FooterPagerPanel" class="HeaderPager">
            
            <span id="ctl00_cphGoldblox_rbxCatalog_HeaderPagerLabel">   <?php if ($page > 1) {
                if ($numberofpages > 0) { ?><a id="ctl00_cphGoldblox_rbxGames_hlHeaderPager_Next" href="Games.aspx?m=<?= ($m) ; ?>&p=<?= $page - 1; ?>"><span class="NavigationIndicators"><<</span> Previous</a><? }
            } ?>
            <span id="ctl00_cphGoldblox_rbxGames_HeaderPagerLabel">Page <?= $page; ?> of <?= $numberofpages; ?>:</span>

            <?php if ($page < $numberofpages) { ?><a id="ctl00_cphGoldblox_rbxGames_hlHeaderPager_Next" href="Games.aspx?m=<?= ($m) ; ?>&p=<?= $page + 1; ?>">Next <span class="NavigationIndicators">&gt;&gt;</span></a><? } ?>
	        
    </div>

</div>
        
<div class="Ads_WideSkyscraper">
    
    

     

        
</div>
        <div style="clear: both;"></div>
    </div>

				</div>

<?php include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>
