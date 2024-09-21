<?php

if (!isset($_GET['ID'])) {
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/web/header.php");
 if($auth == false){
  header("Location: /Login/Default.aspx");
exit;
     
 }
$row = 0;
$query = "SELECT * FROM catalog WHERE creatorid = :creator_id AND type = 'place'";
$stmt = $con->prepare($query);
$stmt->bindParam(':creator_id', $_USER['id'], PDO::PARAM_INT);
$stmt->execute();

$ownedplaces = $stmt->rowCount();

$mathgrr = 3;

if ($_USER['USER_PERMISSIONS'] === "Administrator") {
    $mathgrr = "∞";
} elseif ($_USER['BC'] === "BuildersClub") {
    $mathgrr = 10 - $ownedplaces;
} else {
  
    $mathgrr = 3 - $ownedplaces;
    if ($mathgrr < 0) {
        $mathgrr = 0;
    }
}


?>       
<title>GOLDBLOX: A FREE Virtual World-Building Game with Avatar Chat, 3D Environments, and Physics</title>
<div id="Body">
<div id="UserContainer">
  <div id="LeftBank">
  <div>
    <div id="ProfilePane">
    <table width="100%" bgcolor="lightsteelblue" cellpadding="6" cellspacing="0">
      <tbody><tr>
      <td>
        <font face="Verdana"><span class="Title">Hi, <?php echo $_USER['username'] ; ?>!</span><br></font>
      </td>
      </tr>
      <tr>
      <td>
      <font face="Verdana">
        <span>Your GOLDBLOX:</span><br>
        <a href="/User.aspx?ID=<?php echo $_USER['id'] ; ?>">http://www.rccs.lol/User.aspx?ID=<?php echo $_USER['id'] ; ?></a>
        <br>
        <br>
        <div style="left: 0px; float: left; position: relative; top: 0px;margin-top:67px;margin-left:10px">
        <a disabled="disabled" title="<?php echo htmlspecialchars($_USER['username']);?>" onclick="return false">
          <img src="/Thumbs/Avatar.ashx?assetId=<?php echo $_USER['id'];?>"></iframe>
        </a>
        <br>
        </div>
      <div style="float:right;text-align:left;width:210px;"><font face="Verdana">
        <p><a href="/My/Inbox.aspx">Inbox</a>&nbsp;</p>
        <p><a href="/My/Character.aspx">Change Character</a></p>
        <p><a href="/My/Profile.aspx">Edit Profile</a></p>
        <p><a href="/Upgrades/BuildersClub.aspx">Account Upgrades</a></p>
        <p><a href="/My/AccountBalance.aspx">Account Balance</a></p>
        <p><a href="/User.aspx?ID=<?php echo $_USER['id']; ?>">View Public Profile</a></p>
        <p>
        <?php if ($mathgrr <= 0) { ?>
           <a style='gray'>Create New Place</a> <br>
      (<?php echo $mathgrr ?> Remaining)
        <?php } else { ?>
            <a href="/My/Place.aspx">Create New Place</a> <br>
         (<?php echo $mathgrr ?> Remaining)
        <?php } ?>
    </p>
        <p><a href="/My/InviteAFriend.aspx">Share GOLDBLOX</a></p>
        <p><a href="/Upgrades/GOLDBUX.aspx">Buy GOLDBUX</a></p>
        <p><a href="/Marketplace/TradeCurrency.aspx">Trade Currency</a></p>
      
        <p><a href="/info/TermsOfService.aspx">Terms, Conditions, and Rules</a></p>
       
         
        </font>
        </div>
      </font></td>
      </tr>
    </tbody></table>
<?php if($_USER['BC'] == 'BuildersClub')  {  
    $timestamp = strtotime($_USER['BCExpire']);
    $new_date_format = date('m/d/Y', $timestamp);
?>

 
<?php 
$friendQuery = $con->prepare("SELECT * FROM friends WHERE (user_from = :user_id AND arefriends = 1) OR (user_to = :user_id AND arefriends = 1 AND id != :user_id)");
$friendQuery->execute([':user_id' => $_USER['id']]);
$friendcount = $friendQuery->rowCount();
?>
    <div class="Header">
       <h4 style="font-size: medium; font-family: 'Comic Sans MS', cursive;">Builders Club Subscriber until <?php echo htmlspecialchars($new_date_format);?></h4>
       </div>
    <?php } ?>
        </div>
  </div>
  <div>
 <div id="UserBadgesPane">
    <div id="UserBadges" >
   <div class="Header">
      <h4 style="font-family: 'Comic Sans MS', cursive;" ><a href="/Badges.aspx">Badges</a></h4>
        <span class="PanelToggle"></span>
        </div>
    <table cellspacing="0" border="0" align="Center">
      <tbody>
      <td>
       <tr>
  <?php 
                $herewego = 0; 

                $result = $con->prepare("SELECT * FROM badgesowned WHERE userid = :userid");
                $result->execute([':userid' => $_USER["id"]]);

                if($result->rowCount() > 0){
                    echo "<tr>";
                } else {
                   echo '
                    <div id="ctl00_cphGOLDBLOX_rbxFavoritesPane_NoResultsPanel" class="NoResults">
		
				    <span id="ctl00_cphGOLDBLOX_rbxFavoritesPane_NoResultsLabel" class="NoResults">You do not have any GOLDBLOX badges.' . '</span>
                    '
                    
                    ;
              
                }

                while ($badge = $result->fetch(PDO::FETCH_ASSOC)) { 
                    $herewego++;

                    $result2 = $con->prepare("SELECT * FROM badges WHERE id = :badgeid");
                    $result2->execute([':badgeid' => $badge["badgeid"]]);
                    $badge2 = $result2->fetch(PDO::FETCH_ASSOC);

                    if($herewego == 5){ 
                        $herewego = 1; 
                        echo "</tr><tr>";
                    }
                ?>
    <td><td><div class="Badge">
        <div class="BadgeImage">
          <a href="/Badges.aspx"><img src="<?php echo htmlspecialchars($badge2['image']);?>" title="<?php echo htmlspecialchars($badge2['description']);?>" alt="<?php echo htmlspecialchars($badge2['name']);?>" height="75px"></a><br>
          <div class="BadgeLabel"><a href="/Badges.aspx"><?php echo htmlspecialchars($badge2['name']);?></a>
        </div>
        </div>
 
          <?php } ?>
    </tr>
</tbody></table>
</div>
<?php
$okthiscould = $con->prepare("SELECT * FROM friends WHERE ((user_from = :user_id AND arefriends = 1) OR (user_to = :user_id AND arefriends = 1 AND id != :user_id)) AND date BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW()");
$okthiscould->execute([':user_id' => $_USER['id']]);
$friendslastweek = $okthiscould->rowCount();

$forumnew = $con->prepare("SELECT * FROM forum WHERE author = :user_id");
$forumnew->execute([':user_id' => $_USER['id']]);
$forumcount = $forumnew->rowCount();

$helplol = $con->prepare("SELECT * FROM forum WHERE author = :user_id AND FROM_UNIXTIME(time_posted) BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW()");
$helplol->execute([':user_id' => $_USER['id']]);
$forumlastweek = $helplol->rowCount();
$page_views_query = $con->prepare("SELECT * FROM profileviews WHERE profile = :user_id");
$page_views_query->execute([':user_id' => $_USER['id']]);
$page_views = $page_views_query->rowCount();

$lastweekprofileviews_query = $con->prepare("
    SELECT * 
    FROM profileviews 
    WHERE profile = :user_id 
    AND date BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW()
");
$lastweekprofileviews_query->execute([':user_id' => $_USER['id']]);
$lastweekprofileviews = $lastweekprofileviews_query->rowCount();
$sql = "SELECT visits FROM catalog WHERE creatorid = :creator_id AND type = 'place'";
$query = $con->prepare($sql);
$query->bindParam(':creator_id', $_USER['id'], PDO::PARAM_INT);
$query->execute();

$totalvisits = 0;
while ($skibidi = $query->fetch(PDO::FETCH_ASSOC)) {
    $totalvisits += $skibidi['visits'];
}
?>
    </div>
  <div id="UserStatisticsPane" style="margin-bottom: 10px;">
      <div id="UserStatistics">
      <div id="StatisticsPanel" style="transition: height 0.5s ease-out 0s; overflow: hidden; height: 200px;">
        <div class="Header">
        <h4 style="font-family: 'Comic Sans MS', cursive;">Statistics</h4>
        <span class="PanelToggle"></span>
        </div>
        <div style="margin: 10px 10px 150px 10px;" id="Results">
        <div class="Statistic">
        <div class="Label"><acronym title="The number of this user's friends.">Friends</acronym>:</div>
        <div class="Value"><span><?php echo $friendcount;?> (<?php echo $friendslastweek;?> last week)</span></div>
      </div>
            <div class="Statistic">
        <div class="Label"><acronym title="The number of posts this user has made to the GOLDBLOX forum.">Forum Posts</acronym>:</div>
        <div class="Value"><span><?php echo $forumcount;?> (<?php echo $forumlastweek;?> last week)</span></div>
      </div>
      <div class="Statistic">
        <div class="Label"><acronym title="The number of times this user's profile has been viewed.">Profile Views</acronym>:</div>
        <div class="Value"><span><?php echo $page_views;?> (<?php echo $lastweekprofileviews;?>  last week)</span></div>
      </div>
<div class="Statistic">
							<div class="Label"><acronym title="The number of times this user's place has been visited.">Place Visits</acronym>:</div>
						
							<div class="Value"><span><?php echo $totalvisits;?> (0 last week)</span></div>
						</div>
					<div class="Statistic">
							<div class="Label"><acronym title="The number of times this user's models have been viewed - unfinished.">Model Views</acronym>:</div>
							<div class="Value"><span>TODO</span></div>
						</div>
						<div class="Statistic">
							<div class="Label"><acronym title="The number of times this user's character has destroyed another user's character in-game.">Knockouts</acronym>:</div>
							<div class="Value"><span>0 (0 last week)</span></div>
						</div>
						<div class="Statistic">
							<div class="Label"><acronym title="The number of times this user's character has been destroyed by another user's character in-game.">Wipeouts</acronym>:</div>
							<div class="Value"><span>0 (0 last week)</span></div>
						</div>
     

        </div>
      </div>
      </div>
    </div>
</div>
    </div>
  </div>
  </div>
 
  <div id="RightBank">
  <div>
    <div id="UserPlacesPane">
<script>
Sys.Application.add_init(function() {
  $create(Sys.Extended.UI.AccordionBehavior, {"ClientStateFieldID":"AccordionExtender_ClientState","FramesPerSecond":40,"HeaderCssClass":"AccordionHeader","id":"ShowcasePlacesAccordion_AccordionExtender"}, null, null, $get("ShowcasePlacesAccordion")); 
}); 
</script>
 
<div id="UserPlaces">
  <h4 class="Showcase">Showcase</h4>
  <div id="ShowcasePlacesAccordion" style="height: auto; overflow: auto;">
      <input type="hidden" name="AccordionExtender_ClientState" id="AccordionExtender_ClientState" value="0">
          
 <?php
                $usersQuery = "SELECT * FROM catalog WHERE creatorid = :creatorid AND type = 'place' ORDER BY id DESC";
                $usersResult = $con->prepare($usersQuery);
                $usersResult->execute([':creatorid' => $_USER['id']]);
                $thejlol = $usersResult->rowCount();
                if($thejlol == 0){
                ?>
   <style>.Showcase{display:none!important;}</style>
                <div id="UserPlacesPane" style="border: 0px!important;">
                    <p style="padding:10px">You don't have any GOLDBLOX places.</p>
                </div>
                <?php }
                while($rowUser = $usersResult->fetch(PDO::FETCH_ASSOC)){ ?>
<div class="AccordionHeader"><?php echo $rowUser['name'];?></div>
<div style="height: 0px; overflow: hidden; display: none;"><div style="display: block; height: auto; overflow: hidden;">
<div class="Place">

<div class="PlayStatus">
        <span id="BetaTestersOnly" style="display:none;"><img src="/web/20210220003229im_/https://goodblox.xyz/resources/tinybeta.png" style="border-width:0px;">&nbsp;Beta testers only</span>
        <span id="FriendsOnlyLocked" style="display:none;"><img src="/web/20210220003229im_/https://goodblox.xyz/resources/unlocked.png" style="border-width:0px;">&nbsp;Friends-only: You have access</span>
        <span id="FriendsOnlyUnlocked" style="display:none;"><img src="/web/20210220003229im_/https://goodblox.xyz/resources/locked.png" style="border-width:0px;">&nbsp;Friends-only</span>
        <?php if($rowUser['State'] == "friends"){?><span id="FriendsOnlyUnlocked" style="display:inline;"><img src="http://web.archive.org/web/20210220003229im_/https://goodblox.xyz/resources/unlocked.png" style="border-width:0px;">&nbsp;Friends-only: You have access</span><?php }else{ ?><span id="Public" style="display:inline;"><img src="/images/public.png" style="border-width:0px;">&nbsp;Public</span><?php } ?></div>
<br>
	<div class="PlayOptions"> 																<input type="image" id="MultiplayerVisitButton" class="ImageButton" src="http://rccs.lol/images/VisitOnline.png" alt="Visit Online" data-place-id="1" onclick="JoinGame(<?=$rowUser['id']?>)">           													</div> 							<div class="Statistics">
								<span>Visited <?php echo $rowUser['visits'];?> times </span>
							</div>


<div class="Thumbnail" style="height: 230px; width: 420px; border: solid 1px #000;">
    <a id="ctl00_cphGOLDBLOX_rbxGames_dlGames_ctl00_ciGame" 
       title="<?php echo $rowUser['name'];?>" 
       href="/Item.aspx?ID=<?php echo $rowUser['id'];?>" 
       style="display:inline-block; cursor:pointer; width: 100%; height: 100%;">
        <img src="/Thumbs/Asset.ashx?assetId=<?php echo $rowUser['id'];?>" 
             width="420" 
             height="230" 
             border="0" 
             id="img" 
             alt="<?php echo $rowUser['name'];?>" 
             onerror="this.onerror=null;this.src='/images/unavail-160x100.png';"
             style="width: 420px; height: 230px;">
    </a>
</div>


              <div>
<?php if($rowUser['description'] !== '') { ?><div class="Description">
<span><?php echo $rowUser['description'];?></span>
</div><?php } ?>
<div class="Description">
<span><center><a href="/My/Place.aspx?ID=<?php echo $rowUser['id'];?>">Configure this Place</a></center></span>
</div>
</div>
              </div>
</div></div>
<?php }?>
          </div>    
    
  
    
  
  
 </div>
    </div>
    <div id="FriendsPane" style="background-color:white;">
    <div id="Friends">
       <?php
            $friendnew = $con->prepare("SELECT * FROM friends WHERE (user_from = :user_id AND arefriends = 1) OR (user_to = :user_id AND arefriends = 1)");
            $friendnew->execute([':user_id' => $_USER['id']]);
            $friendcountm = $friendnew->rowCount();
            ?>
            <h4>My Friends <a href="/Friend.aspx?UserID=<?php echo $_USER['id'];?>">See all <?php echo $friendcountm; ?></a> (<a href="/My/EditFriends.aspx">Edit</a>)</h4>
            <table cellspacing="0" align="Center" border="0" style="border-collapse:collapse;">
                <tbody>
                    <tr>
                    <?php
                    $resultsperpage = 6;
                    $thispagefirstresult = 0; 
                    $friendq = $con->prepare("SELECT * FROM friends WHERE (user_from = :user_id AND arefriends = 1) OR (user_to = :user_id AND arefriends = 1)     ORDER BY id DESC LIMIT :start, :resultsperpage ");
                    $friendq->bindValue(':user_id', $_USER['id'], PDO::PARAM_INT);
                    $friendq->bindValue(':start', $thispagefirstresult, PDO::PARAM_INT);
                    $friendq->bindValue(':resultsperpage', $resultsperpage, PDO::PARAM_INT);
                    $friendq->execute();

                    if ($friendcountm < 1) {
                        echo "<p style=\"padding: 10px 10px 10px 10px;\">You don't have any GOLDBLOX friends.</p>";
                    } else {
                        echo "<div class=\"columns\">";
                        $total = 0;
                        $cinnamonroll = 0;

                        while ($friend = $friendq->fetch(PDO::FETCH_ASSOC)) {
                            $friendid = ($friend['user_from'] == $_USER['id']) ? $friend['user_to'] : $friend['user_from'];
                            $friend_online = $con->prepare("SELECT * FROM users WHERE id = :friendid");
                            $friend_online->execute([':friendid' => $friendid]);
                            $finfo = $friend_online->fetch(PDO::FETCH_ASSOC);

                            $usr = $con->prepare("SELECT * FROM users WHERE id = :friendid LIMIT :start, :resultsperpage");
                            $usr->bindValue(':friendid', $friendid, PDO::PARAM_INT);
                            $usr->bindValue(':start', $thispagefirstresult, PDO::PARAM_INT);
                            $usr->bindValue(':resultsperpage', $resultsperpage, PDO::PARAM_INT);
                            $usr->execute();
                            $user_info = $usr->fetch(PDO::FETCH_ASSOC);

                            echo "<td><div class=\"Friend\">
                                    <div class=\"Avatar\">
                                        <a title=\"{$user_info['username']}\" href=\"/User.aspx?ID=$friendid\" style=\"display:inline-block;max-height:100px;max-width:100px;cursor:pointer;\">
                                            <img src=\"/Thumbs/Avatar.ashx?assetId=".$user_info['id']."&IsSmall\" width=\"95\" border=\"0\" alt=\"{$user_info['username']}\" blankurl=\"http://t6.www.rccs.lol:80/blank-100x100.gif\">
                                        </a>
                                    </div>
                                    <div class=\"Summary\">
                                        <span class=\"OnlineStatus\">";
                                        $onlinetest = ($finfo['expiretime'] < $now) ? "<img src=\"/images/Offline.gif\" style=\"border-width:0px;\">" : "<img src=\"/images/Online.gif\" style=\"border-width:0px;\">";
                                        echo "$onlinetest</span>
                                        <span class=\"Name\"><a href=\"/User.aspx?ID=$friendid\">{$user_info['username']}</a></span>
                                    </div>
                                </div></td>";
                            $total++;
                            $cinnamonroll++;
                            if ($cinnamonroll >= 3) {
                                echo "</tr></div><tr><div class=\"columns\">";
                                $cinnamonroll = 0;
                            }
                        }
                        echo "</div>";
                    }
                    ?></tr></tbody></table>
</div>
    </div>
    
    <div id="UserContainer">
    
    <div id="FavoritesPane" style="clear: right; margin: 10px 0 0 0; border: solid 1px #000;">
    <div>
 <div id="Favorites" style="text-align: left;">
      <h4>Favorites</h4>
      <div id="FavoritesContent"></div>
      <div class="PanelFooter">
      Category:&nbsp;
      <select id="FavCategories">
        <option value="7">Heads</option>
        <option value="8">Faces</option>
        <option value="2">T-Shirts</option>
        <option value="5">Shirts</option>
        <option value="6">Pants</option>
        <option value="1">Hats</option>

        <option value="4">Decals</option>
        
        <option selected="selected" value="0">Places</option>
      </select>
      </div>
    </div>
    </div>
  </div>
  </div>
  
  </div>
  


  
  
    <tbody><tr>

<tr>
</tr>
                         </tr>
    </tbody></table>
 
 
  <div>
  </div>
</div>
<script>function getInventory(type, page, event)
{
  if(page == undefined){ page = 1; }
  if(event != undefined){ event.preventDefault(); }
  $.post("/api/inventory.aspx", {uid:<?php echo $_USER['id'];?>,type:type,page:page}, function(data)
  {
  $("#AssetsContent").empty();
  $("#AssetsContent").html(data);
  })
  .fail(function()
  {
  $("#AssetsContent").text("something shitted please report this to copy.php");
  });
  $('*[data-id]').removeClass().addClass("AssetsMenuItem");
  $('*[data-id]').children().removeClass().addClass("AssetsMenuButton");
  $('*[data-id="'+type+'"]').removeClass().addClass("AssetsMenuItem_Selected");
  $('*[data-id="'+type+'"]').children().removeClass().addClass("AssetsMenuButton_Selected");
}
  getInventory(1)
function getFavs(type,page,event)
{
  if(page == undefined){ page = 1; }
  if(event != undefined){ event.preventDefault(); }
  $.post("/api/fetchdeletefavorites.aspx", {uid:<?php echo $_USER['id'];?>,type:type,page:page}, function(data)
  {
  $("#FavoritesContent").empty();
  $("#FavoritesContent").html(data);
  })
  .fail(function()
  {
  $("#FavoritesContent").html("something shitted please report this to copy.php");
  });
}
$(function()
{
  $('.AssetsMenuItem').on('click', this, function(){ getInventory($(this).attr("data-id")); });
  $("#FavCategories").on("change", function() { getFavs(this.value); });
  getFavs(0);
  getInventory(1);
});
</script>

<div id="UserContainer">

<?php

$asspisscock = $con->prepare("
    SELECT m.*, u.username, u.expiretime
    FROM friends m
    LEFT JOIN users u ON m.user_from = u.id
    LEFT JOIN friends f ON ((m.user_from = f.user_from AND f.user_to = :user_id) OR (m.user_from = f.user_to AND f.user_from = :user_id))
    WHERE m.user_to = :user_id  AND f.arefriends = 0  ORDER BY f.id DESC 
");
$asspisscock->bindParam(':user_id', $_USER['id'], PDO::PARAM_INT);
$asspisscock->execute();
$howmany = $asspisscock->rowCount();

if ($asspisscock->rowCount() < 1) {
} else {
    echo "


<div id=\"FriendRequestsPane\">
<div id=\"FriendRequests\">
<span id=\"FriendRequestsHeaderLabel\"><h4>Friend Requests ($howmany)</h4></span>
   <center>
    <table>
    
    ";
    $total = 0;
    $cinnamonroll = 0;

    while ($request = $asspisscock->fetch(PDO::FETCH_ASSOC)) {
       
        $islifeless = ($request['expiretime'] < $now) ? "<img src=\"/images/Offline.gif\" style=\"border-width:0px;\">" : "<img src=\"/images/Online.gif\" style=\"border-width:0px;\">";

        $aaaa = "/My/FriendInvitation.aspx?InvitationID=" . $request['id'];

        echo "<td><div class=\"Friend\">
                <div class=\"Avatar\">
                    <a title=\"{$request['username']}\" href=\"$aaaa\" style=\"display:inline-block;max-height:100px;max-width:100px;cursor:pointer;\">
                        <img src=\"/Thumbs/Avatar.ashx?assetId={$request['user_from']}&IsSmall\" width=\"95\" border=\"0\" alt=\"{$request['username']}\" blankurl=\"http://t6.www.rccs.lol:80/blank-100x100.gif\">
                    </a>
                </div>
                <div class=\"Summary\">
                    <span class=\"OnlineStatus\">$islifeless</span>
                    <span class=\"Name\"><a href=\"$aaaa\">{$request['username']}</a></span>
                </div>
            </div></td>";

        $total++;
        $cinnamonroll++;
        if ($cinnamonroll >= 10) {
            echo "</tr><tr>";
            $cinnamonroll = 0;
        }
    }

    echo "</tr></table></div></div>
</center>";
}
?>

</tr></tbody></table>
</center>

<div id="UserAssetsPane">
  <div id="UserAssets">
    <h4 style="font-family: 'Comic Sans MS', cursive;">Stuff</h4>
    <div id="AssetsMenu">
      <div class="AssetsMenuItem" data-id="7" onclick="getInventory(7)">
        <a class="AssetsMenuButton">Heads</a>
      </div>
      <div class="AssetsMenuItem" data-id="8" onclick="getInventory(8)">
        <a class="AssetsMenuButton">Faces</a>
      </div>
      <div class="AssetsMenuItem_Selected" data-id="1" onclick="getInventory(1)">
        <a class="AssetsMenuButton_Selected">Hats</a>
      </div>
      <div class="AssetsMenuItem" data-id="2" onclick="getInventory(2)">
        <a class="AssetsMenuButton">T-Shirts</a>
      </div>
      <div class="AssetsMenuItem" data-id="5" onclick="getInventory(5)">
        <a class="AssetsMenuButton">Shirts</a>
      </div>
      <div class="AssetsMenuItem" data-id="6" onclick="getInventory(6)">
        <a class="AssetsMenuButton">Pants</a>
       
      </div>
    <div class="AssetsMenuItem" data-id="4" onclick="getInventory(4)">
        <a class="AssetsMenuButton">Decals</a>
      </div>
      
      <div class="AssetsMenuItem" data-id="0" onclick="getInventory(0)">
         <a class="AssetsMenuButton">Places</a>
       </div>
    </div>
    <div id="AssetsContent"></div>
    <div style="clear:both;"></div>
  </div>
  </div>
</div>
           
  </div>
  
  </div>
</div>
<div style="clear:both"></div>
<?php
include 'api/web/footer.php';
exit;
}
?>
<?php
require_once 'api/web/config.php';

$idOrName = '';
$id = null;

try {
        $id = (int)$_REQUEST['ID'];
      



    $stmt = $con->prepare("SELECT * FROM users WHERE id = :id AND bantype = 'None'");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        header('HTTP/1.1 404 Not Found');
        include("error.php");
        exit;
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $lastseen = "Website";

    $shirtid33 = $con->prepare("SELECT playing FROM users WHERE id = :id");
    $shirtid33->bindParam(':id', $id, PDO::PARAM_INT);
    $shirtid33->execute();
    $gameidshit = $shirtid33->fetch(PDO::FETCH_ASSOC);

    if ($gameidshit && $gameidshit['playing'] != 0) {
        $gameshit = $gameidshit['playing'];

        $gameshitterqury = $con->prepare("SELECT name FROM catalog WHERE id = :gameshit");
        $gameshitterqury->bindParam(':gameshit', $gameshit, PDO::PARAM_INT);
        $gameshitterqury->execute();
        $game_row = $gameshitterqury->fetch(PDO::FETCH_ASSOC);

        if ($game_row) {
            $lastseen = $game_row['name'];
        }
    }

    $title = htmlspecialchars($row['username']) . "'s GOLDBLOX Home Page";

    include 'api/web/header.php';
    include 'api/web/nav.php';

  
 if(!empty($row['blurb'])) {
 if($filterenabled) {
$blurbington = nl2br($ch->obfuscateIfProfane($row['blurb']));
 } else {
 $blurbington = $row['blurb'];    
}
} else {
$blurbington = null;        
}


    if ($_USER['id'] != $id && $auth == true) {
        $skibidiviews = $con->prepare("INSERT INTO `profileviews`(`profile`) VALUES (:profile)");
        $skibidiviews->bindParam(':profile', $id, PDO::PARAM_INT);
        $skibidiviews->execute();
    }

    $pageshitterviewsmtm = $con->prepare("SELECT COUNT(*) FROM profileviews WHERE profile = :profile");
    $pageshitterviewsmtm->bindParam(':profile', $id, PDO::PARAM_INT);
    $pageshitterviewsmtm->execute();
    $page_views = $pageshitterviewsmtm->fetchColumn();
$skibidiend = date('Y-m-d H:i:s', strtotime('last Sunday'));
$shitstart = date('Y-m-d H:i:s', strtotime('last Sunday - 1 week'));
  $bbcbigblackcock = $con->prepare("SELECT COUNT(*) FROM profileviews WHERE profile = :profile AND date BETWEEN :start_date AND :end_date");
 /* as you can see the goldblox src code is very interesting... - mii 9/21/2024 /* 
$bbcbigblackcock->bindParam(':profile', $id, PDO::PARAM_INT);
$bbcbigblackcock->bindParam(':start_date', $shitstart);
$bbcbigblackcock->bindParam(':end_date', $skibidiend);
$bbcbigblackcock->execute();
$lastweekprofileviews = $bbcbigblackcock->fetchColumn();

    $skibidigame = $con->prepare("SELECT * FROM catalog WHERE id = :id AND type = 'place'");
    $skibidigame->bindParam(':id', $id, PDO::PARAM_INT);
    $skibidigame->execute();
    $game = $skibidigame->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>


    <?php
  $friendQuery = $con->prepare("
        SELECT * FROM friends 
        WHERE 
            (`user_from` = :userId AND `arefriends` = 1) 
            OR 
            (`user_to` = :userId AND `arefriends` = 1)
    ");
    
    $friendQuery->bindParam(':userId', $row['id'], PDO::PARAM_INT);

    $friendQuery->execute();

    $friendcount = $friendQuery->rowCount(); ?>    
  <title> <?=$row['username']?>'s GOLDBLOX Home Page</title>
  <div id="Body">
  <div id="UserContainer">
  <div id="LeftBank">
  <div id="ProfilePane">
				
<table width="100%" cellspacing="0" cellpadding="6" bgcolor="lightsteelblue">
    <tbody><tr>
        <td>
            <span id="ctl00_cphGoldblox_rbxUserPane_lUserName" class="Title"><?php echo $row['username']; ?></span><br>
         <?php if($row['expiretime'] < $now){ ?><span id="ctl00_cphGOLDBLOX_rbxUserPane_lUserOnlineStatus" class="UserOfflineMessage">[ Offline ]</span><?php } else { ?>
               <span id="ctl00_cphGOLDBLOX_rbxUserPane_lUserOnlineStatus" class="UserOnlineMessage">[ Online: <?php echo ($lastseen);?> ]</span><?php }?>
        </td>
    </tr>
    <tr>
        <td>
            <span id="ctl00_cphGoldblox_rbxUserPane_lUserGoldbloxURL"><?php echo $row['username']; ?>’s GOLDBLOX:</span><br>
            <a id="ctl00_cphGoldblox_rbxUserPane_hlUserGoldbloxURL" href="/User.aspx?ID=<?php echo $row['id']; ?>">http://www.rccs.lol/User.aspx?ID=<?php echo $row['id']; ?></a><br>
            <br>
            <div style="left: 0px; float: left; position: relative; top: 0px">
                <a id="ctl00_cphGoldblox_rbxUserPane_Image1" disabled="disabled" title="<?php echo $row['username'] ?>" onclick="return false" style="display:inline-block;"><img src=/Thumbs/Avatar.ashx?assetId=<?php echo $row['id']; ?> alt="<?php echo $row['username']; ?>" blankurl="http://t7.roblox.com:80/blank-180x220.gif" border="0"></a><br>
                <div id="ctl00_cphGoldblox_rbxUserPane_AbuseReportButton1_AbuseReportPanel" class="ReportAbusePanel">
	<?php if ($id != $_USER['id'] && $auth == true) { ?>
    <span class="AbuseIcon"><a id="ctl00_cphGoldblox_rbxUserPane_AbuseReportButton1_ReportAbuseIconHyperLink" href=“report?id=<?php echo $row['id']; ?>&type=3”><img src="/images/abuse.gif" alt="Report Abuse" border="0"></a></span>
    <span class="AbuseButton"><a id="ctl00_cphGoldblox_rbxUserPane_AbuseReportButton1_ReportAbuseTextHyperLink" href="/report?id=<?php echo $row['id']; ?>&type=3">Report Abuse</a></span>
<?php }
  ?>

</div>
          
            </div>
       
            




        <?php
             
        
              if ($id != $_USER['id'] && $auth == true) {
              ?>
                 <p>
                 <a href="/My/PrivateMessage.aspx?RecipientID=<?php echo $row['id'];?>">Send Message</a>
         </p>
              <?php } ?>
			
			  
			  
			  
		
			  
			  
			<?php
try {
  
    $stmt = $con->prepare("SELECT * FROM friends WHERE 
                          (`user_from` = :user_from AND `user_to` = :user_to AND `arefriends` = 1) 
                          OR 
                          (`user_from` = :user_to AND `user_to` = :user_from AND `arefriends` = 1)
                        
                          OR
                             (`user_from` = :user_from AND `user_to` = :user_to AND `arefriends` = 0) 
                          OR 
                           (`user_from` = :user_to AND `user_to` = :user_from AND `arefriends` = 0)
                          ");
    
  
    $stmt->bindParam(':user_from', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user_to', $_USER['id'], PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() == 0 && $id != $_USER['id'] && $auth) {
?>
      <p>
        <a href="/My/FriendInvitation.aspx?RecipientID=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>">Send Friend Request</a></p>
<?php
    }
} catch (PDOException $e) {
   
    echo 'egfgegrg sfergfrfsa: ' . $e->getMessage();
}
?>

  <p><span id="ctl00_cphGoldblox_rbxUserPane_rbxPublicUser_lBlurb" style='white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;word-wrap:break-word;'><?php echo $blurbington; ?></span></p>          
        </td>
    </tr>
</tbody></table>

      <?php if($row['BC'] == 'BC') {  
$timestamp = strtotime($row['BCExpire']);
$new_date_format = date('m/d/Y', $timestamp);
?>
    <div class="Header">
        <h4 style="font-size: medium;">Builders Club Member until <?php echo htmlspecialchars($new_date_format);?></h4>
       </div>
    <?php } ?>
    </div>
  
    <div id="UserBadgesPane">
    

 
<div id="UserBadges">
  <h4><a id="ctl00_cphGOLDBLOX_rbxUserBadgesPane_hlHeader" href="/Badges.aspx">Badges</a></h4>
  <table id="ctl00_cphGOLDBLOX_rbxUserBadgesPane_dlBadges" cellspacing="0" align="Center" border="0">
  <td> <?php 
                $herewego = 0; 

                $result = $con->prepare("SELECT * FROM badgesowned WHERE userid = :userid");
                $result->execute([':userid' => $row["id"]]);

                if($result->rowCount() > 0){
                    echo "<tr>";
                } else {
                    echo '
                    <div id="ctl00_cphGOLDBLOX_rbxFavoritesPane_NoResultsPanel" class="NoResults">
		
				    <span id="ctl00_cphGOLDBLOX_rbxFavoritesPane_NoResultsLabel" class="NoResults">' . $row['username'] . ' does not have any GOLDBLOX badges.' . '</span>
                    '
                    
                    ;
              
                }

                while ($badge = $result->fetch(PDO::FETCH_ASSOC)) { 
                    $herewego++;

                    $result2 = $con->prepare("SELECT * FROM badges WHERE id = :badgeid");
                    $result2->execute([':badgeid' => $badge["badgeid"]]);
                    $badge2 = $result2->fetch(PDO::FETCH_ASSOC);

                    if($herewego == 5){ 
                        $herewego = 1; 
                        echo "</tr><tr>";
                    }
                ?>
    <td><td><div class="Badge">
        <div class="BadgeImage">
          <a href="/Badges.aspx"><img src="<?php echo htmlspecialchars($badge2['image']);?>" title="<?php echo htmlspecialchars($badge2['description']);?>" alt="<?php echo htmlspecialchars($badge2['name']);?>" height="75px"></a><br>
          <div class="BadgeLabel"><a href="/Badges.aspx"><?php echo htmlspecialchars($badge2['name']);?></a>
        </div>
        </div>
 
          <?php } ?>
  </td><td></td><td></td><td></td>
  </tr>
</table>
<?php
try {

    $userId = (int)$row['id'];


    $skibidimon = date('Y-m-d', strtotime('last monday', strtotime('-1 week')));
    $skibidisun = date('Y-m-d', strtotime('last sunday', strtotime('-1 week')));

    $query = "SELECT * FROM friends 
              WHERE ((user_from = :user_id AND arefriends = 1) 
              OR (user_to = :user_id AND arefriends = 1 AND id != :user_id)) 
              AND date BETWEEN :last_monday AND :last_sunday";
    $stmt = $con->prepare($query);
    $stmt->execute(['user_id' => $userId, 'last_monday' => $skibidimon, 'last_sunday' => $skibidisun]);
    $friendslastweek = $stmt->rowCount();


    $query = "SELECT * FROM forum WHERE author = :user_id";
    $stmt = $con->prepare($query);
    $stmt->execute(['user_id' => $userId]);
    $forumcount = $stmt->rowCount();

    $query = "UPDATE users SET ForumPost = :forumcount WHERE id = :user_id";
    $stmt = $con->prepare($query);
    $stmt->execute(['forumcount' => $forumcount, 'user_id' => $userId]);

    $query = "SELECT * FROM forum 
              WHERE author = :user_id 
              AND FROM_UNIXTIME(time_posted) 
              BETWEEN :last_monday AND :last_sunday";
    $stmt = $con->prepare($query);
    $stmt->execute(['user_id' => $userId, 'last_monday' => $skibidimon, 'last_sunday' => $skibidisun]);
    $forumlastweek = $stmt->rowCount();

  
} catch (PDOException $e) {
    echo 'fuck: ' . $e->getMessage();
}
?>

  <?php   $toilet = "SELECT visits FROM catalog WHERE creatorid = :creator_id AND type = 'place'";
$toilet = $con->prepare($toilet);
$toilet->bindParam(':creator_id', $userId, PDO::PARAM_INT);
$toilet->execute();

$totalvisits = 0;
while ($rowvist = $toilet->fetch(PDO::FETCH_ASSOC)) {
    $totalvisits += $rowvist['visits'];
}
?>
</div>
    </div>
    <div id="UserStatisticsPane" style="margin-bottom: 0px;">
    <div id="UserStatistics">
    <div id="StatisticsPanel" style="transition: height 0.5s ease-out; overflow: hidden; height: 200px;">
      <h4>Statistics</h4>    
      <div style="margin: 10px 10px 150px 10px;" id="Results">
      <div class="Statistic">
        <div class="Label"><acronym title="The number of this user's friends.">Friends</acronym>:</div>
        <div class="Value"><span><?php echo htmlspecialchars($friendcount);?> (<?php echo htmlspecialchars($friendslastweek);?> last week)</span></div>
      </div>
            <div class="Statistic">
        <div class="Label"><acronym title="The number of posts this user has made to the GOLDBLOX forum.">Forum Posts</acronym>:</div>
        <div class="Value"><span><?php echo htmlspecialchars($forumcount);?> (<?php echo htmlspecialchars($forumlastweek);?> last week)</span></div>
      </div>
      <div class="Statistic">
        <div class="Label"><acronym title="The number of times this user's profile has been viewed.">Profile Views</acronym>:</div>
        <div class="Value"><span><?php echo htmlspecialchars($page_views);?> (<?php echo htmlspecialchars($lastweekprofileviews);?> last week)</span></div>
      </div>
  <div class="Statistic">
	<div class="Label"><acronym title="The number of times this user's place has been visited.">Place Visits</acronym>:</div>
						
							<div class="Value"><span><?php echo $totalvisits;?> (0 last week)</span></div>
						</div>
					<div class="Statistic">
							<div class="Label"><acronym title="The number of times this user's models have been viewed - unfinished.">Model Views</acronym>:</div>
							<div class="Value"><span>TODO</span></div>
						</div>
						<div class="Statistic">
							<div class="Label"><acronym title="The number of times this user's character has destroyed another user's character in-game.">Knockouts</acronym>:</div>
							<div class="Value"><span>0 (0 last week)</span></div>
						</div>
						<div class="Statistic">
							<div class="Label"><acronym title="The number of times this user's character has been destroyed by another user's character in-game.">Wipeouts</acronym>:</div>
							<div class="Value"><span>0 (0 last week)</span></div>
		  </div>	
      </div>
    </div>
    </div>
  </div>
  </div>
  <div id="RightBank">
    <div id="UserPlacesPane">
<script>
Sys.Application.add_init(function() {
  $create(Sys.Extended.UI.AccordionBehavior, {"ClientStateFieldID":"AccordionExtender_ClientState","FramesPerSecond":40,"HeaderCssClass":"AccordionHeader","id":"ShowcasePlacesAccordion_AccordionExtender"}, null, null, $get("ShowcasePlacesAccordion")); 
}); 
</script>
  
<div id="UserPlaces">
  <h4 class="Showcase">Showcase</h4>
  <div id="ShowcasePlacesAccordion" style="height: auto; overflow: auto;">
      <input type="hidden" name="AccordionExtender_ClientState" id="AccordionExtender_ClientState" value="0">
          
<?php
                $usersQuery = "SELECT * FROM catalog WHERE creatorid = :creatorid AND type = 'place' ORDER BY id DESC";
                $usersResult = $con->prepare($usersQuery);
                $usersResult->execute([':creatorid' => $row['id']]);
                $thejlol = $usersResult->rowCount();
              $nagahlemniggerrrr43344333 = $row['username'];
                if($thejlol == 0){
                ?>
   <style>.Showcase{display:none!important;}</style>
                <div id="UserPlacesPane" style="border: 0px!important;">
                    <p style="padding:10px"><?php echo $nagahlemniggerrrr43344333 ?> doesn't have any GOLDBLOX places.</p>
                </div>
                <?php }
                while($rowUser = $usersResult->fetch(PDO::FETCH_ASSOC)){ ?>
<div class="AccordionHeader"><?php echo $rowUser['name'];?></div>
<div style="height: 0px; overflow: hidden; display: none;"><div style="display: block; height: auto; overflow: hidden;">
<div class="Place">
<div class="PlayStatus">
        <span id="BetaTestersOnly" style="display:none;"><img src="/web/20210220003229im_/https://goodblox.xyz/resources/tinybeta.png" style="border-width:0px;">&nbsp;Beta testers only</span>
        <span id="FriendsOnlyLocked" style="display:none;"><img src="/web/20210220003229im_/https://goodblox.xyz/resources/unlocked.png" style="border-width:0px;">&nbsp;Friends-only: You have access</span>
        <span id="FriendsOnlyUnlocked" style="display:none;"><img src="/web/20210220003229im_/https://goodblox.xyz/resources/locked.png" style="border-width:0px;">&nbsp;Friends-only</span>
        <?php if($rowUser['State'] == "friends"){?><span id="FriendsOnlyUnlocked" style="display:inline;"><img src="/images/locked.png" style="border-width:0px;">&nbsp;Friends-only</span><?php }else{ ?><span id="Public" style="display:inline;"><img src="/images/public.png" style="border-width:0px;">&nbsp;Public</span><?php } ?>
</div>
<br>
 	<div class="PlayOptions"> 																<?php if($rowUser['State'] == 'public') { ?><input type="image" id="MultiplayerVisitButton" class="ImageButton" src="/images/VisitOnline.png" alt="Visit" data-place-id="1" onclick="JoinGame(<?php echo $rowUser['id']; ?>)"> <?php } ?>           													</div> 							<div class="Statistics">
								<span>Visited <?php echo $rowUser['visits']; ?> times</span>
							</div>
<div class="Thumbnail" style="height: 230px; width: 420px; border: solid 1px #000;">
    <a id="ctl00_cphGOLDBLOX_rbxGames_dlGames_ctl00_ciGame" 
       title="<?php echo $rowUser['name'];?>" 
       href="/Item.aspx?ID=<?php echo $rowUser['id'];?>" 
       style="display:inline-block; cursor:pointer; width: 100%; height: 100%;">
        <img src="/Thumbs/Asset.ashx?assetId=<?php echo $rowUser['id'];?>" 
             width="420" 
             height="230" 
             border="0" 
             id="img" 
             alt="<?php echo $rowUser['name'];?>" 
             onerror="this.onerror=null;this.src='/images/unavail-160x100.png';"
             style="width: 420px; height: 230px;">
    </a>
</div>       <?php if($rowUser['description'] !== ""){?><div>
<div class="Description">
<span><?php echo $rowUser['description'];?></span>
</div>
</div><?php } ?>
              </div>
</div></div>
<?php  }?>
          </div>    
    
  
    
  
  
 </div>
    </div>
    <div id="FriendsPane" style="background:white;">
    
<div id="Friends">
  <?php if($friendcount > 0){?><h4><?php echo htmlspecialchars($row['username']); ?>'s Friends <a href="/Friend.aspx?UserID=<?php echo $row['id'];?>">See all <?php echo htmlspecialchars($friendcount);?></a></h4><?php }?>
  
  <table id="ctl00_cphGOLDBLOX_rbxFriendsPane_dlFriends" cellspacing="0" align="Center" border="0">
  <tr>
 <?php
            $friendnew = $con->prepare("SELECT * FROM friends WHERE (user_from = :user_id AND arefriends = 1) OR (user_to = :user_id AND arefriends = 1)");
            $friendnew->execute([':user_id' => $row['id']]);
            $friendcountm = $friendnew->rowCount();
            ?>
        
                    <?php
                    $resultsperpage = 6;
                    $thispagefirstresult = 0; 
                    $friendq = $con->prepare("SELECT * FROM friends WHERE (user_from = :user_id AND arefriends = 1) OR (user_to = :user_id AND arefriends = 1)   ORDER BY id DESC  LIMIT :start, :resultsperpage");
                    $friendq->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
                    $friendq->bindValue(':start', $thispagefirstresult, PDO::PARAM_INT);
                    $friendq->bindValue(':resultsperpage', $resultsperpage, PDO::PARAM_INT);
                    $friendq->execute();
$usershitter = $row['username'];
                    if ($friendcountm < 1) {
                        echo "<p style=\"padding: 10px 10px 10px 10px;\">$usershitter doesn't have any GOLDBLOX friends.</p>";
                    } else {
                        echo "<div class=\"columns\">";
                        $total = 0;
                        $cinnamonroll = 0;

                        while ($friend = $friendq->fetch(PDO::FETCH_ASSOC)) {
                            $friendid = ($friend['user_from'] == $row['id']) ? $friend['user_to'] : $friend['user_from'];
                            $friend_online = $con->prepare("SELECT * FROM users WHERE id = :friendid");
                            $friend_online->execute([':friendid' => $friendid]);
                            $finfo = $friend_online->fetch(PDO::FETCH_ASSOC);

                            $usr = $con->prepare("SELECT * FROM users WHERE id = :friendid LIMIT :start, :resultsperpage");
                            $usr->bindValue(':friendid', $friendid, PDO::PARAM_INT);
                            $usr->bindValue(':start', $thispagefirstresult, PDO::PARAM_INT);
                            $usr->bindValue(':resultsperpage', $resultsperpage, PDO::PARAM_INT);
                            $usr->execute();
                            $user_info = $usr->fetch(PDO::FETCH_ASSOC);

                            echo "<td><div class=\"Friend\">
                                    <div class=\"Avatar\">
                                        <a title=\"{$user_info['username']}\" href=\"/User.aspx?ID=$friendid\" style=\"display:inline-block;max-height:100px;max-width:100px;cursor:pointer;\">
                                            <img src=\"/Thumbs/Avatar.ashx?assetId=".$user_info['id']."&IsSmall\" width=\"95\" border=\"0\" alt=\"{$user_info['username']}\" blankurl=\"http://t6.www.rccs.lol:80/blank-100x100.gif\">
                                        </a>
                                    </div>
                                    <div class=\"Summary\">
                                        <span class=\"OnlineStatus\">";
                                        $onlinetest = ($finfo['expiretime'] < $now) ? "<img src=\"/images/Offline.gif\" style=\"border-width:0px;\">" : "<img src=\"/images/Online.gif\" style=\"border-width:0px;\">";
                                        echo "$onlinetest</span>
                                        <span class=\"Name\"><a href=\"/User.aspx?ID=$friendid\">{$user_info['username']}</a></span>
                                    </div>
                                </div></td>";
                            $total++;
                            $cinnamonroll++;
                            if ($cinnamonroll >= 3) {
                                echo "</tr></div><tr><div class=\"columns\">";
                                $cinnamonroll = 0;
                            }
                        }
                        echo "</div>";
                    }
                    ?></tr></tbody></table>
<style>
fix {
  display: table-cell;
  vertical-align: inherit;
}
</style>
  </tr>
</table>
  
</div>
    </div>
    <div id="FavoritesPane" style="margin-top: 10px; margin-bottom: 0px;">
   <div id="Favorites" style="text-align: left;">

    <h4>Favorites</h4>
    <div id="FavoritesContent"></div>
    <div class="PanelFooter">
      Category:&nbsp;
    <select id="FavCategories">
      
      <option value="7">Heads</option>
      <option value="8">Faces</option>
      <option value="2">T-Shirts</option>
      <option value="5">Shirts</option>
      <option value="6">Pants</option>
      <option value="1">Hats</option>
      <option value="4">Decals</option>
       
      <option selected="selected" value="0">Places</option>
      </select>
       </div>
    </div>
  </div>
  </div>
<script>
function getFavs(type, page, event) 
{
  if(page == undefined){ page = 1; }
  if(event != undefined){ event.preventDefault(); }
  $.post("/api/getfavorites.php", {uid:<?php echo $row['id'];?>,type:type,page:page}, function(data) 
  {
  $("#FavoritesContent").empty();
  $("#FavoritesContent").html(data);
  })
  .fail(function() 
  {
  $("#FavouritesContent").text("An error occurred while fetching this user's favorites");
  });
  $('*[data-id]').removeClass().addClass("AssetsMenuItem");
  $('*[data-id]').children().removeClass().addClass("AssetsMenuButton");
  $('*[data-id="'+type+'"]').removeClass().addClass("AssetsMenuItem_Selected");
  $('*[data-id="'+type+'"]').children().removeClass().addClass("AssetsMenuButton_Selected");
}
  getInventory(1)
function getInventory(type, page, event) 
{
  if(page == undefined){ page = 1; }
  if(event != undefined){ event.preventDefault(); }
  $.post("/api/inventory.php", {uid:<?php echo $row['id'];?>,type:type,page:page}, function(data) 
  {
  $("#AssetsContent").empty();
  $("#AssetsContent").html(data);
  })
  .fail(function() 
  {
  $("#AssetsContent").text("An error occurred while fetching this user's inventory");
  });
  $('*[data-id]').removeClass().addClass("AssetsMenuItem");
  $('*[data-id]').children().removeClass().addClass("AssetsMenuButton");
  $('*[data-id="'+type+'"]').removeClass().addClass("AssetsMenuItem_Selected");
  $('*[data-id="'+type+'"]').children().removeClass().addClass("AssetsMenuButton_Selected");
}
  getInventory(1)
function getFavs(type,page,event) 
{
  if(page == undefined){ page = 1; }
  if(event != undefined){ event.preventDefault(); }
  $.post("/api/getfavorites.php", {uid:<?php echo $row['id'];?>,type:type,page:page}, function(data) 
  {
  $("#FavoritesContent").empty();
  $("#FavoritesContent").html(data);
  })
  .fail(function() 
  {
  $("#FavoritesContent").html("Failed to get favourites");
  });
}
$(function() 
{
  $('.AssetsMenuItem').on('click', this, function(){ getInventory($(this).attr("data-id")); });
  $("#FavCategories").on("change", function() { getFavs(this.value); });
  getFavs(0);
  getInventory(1); 
});
</script>
    <div style="clear:both;"></div>
<div id="UserAssetsPane">
  <div id="UserAssets">
    <h4>Stuff</h4>
    <div id="AssetsMenu">
      
    <div class="AssetsMenuItem" data-id="7" onclick="getInventory(7)">
        <a class="AssetsMenuButton">Heads</a>
      </div>
       
    <div class="AssetsMenuItem" data-id="8" onclick="getInventory(8)">
        <a class="AssetsMenuButton">Faces</a>
      </div>
      <div class="AssetsMenuItem_Selected" data-id="1" onclick="getInventory(1)">
        <a class="AssetsMenuButton_Selected">Hats</a>
      </div>
      <div class="AssetsMenuItem" data-id="2" onclick="getInventory(2)">
        <a class="AssetsMenuButton">T-Shirts</a>
      </div>
      <div class="AssetsMenuItem" data-id="5" onclick="getInventory(5)">
        <a class="AssetsMenuButton">Shirts</a>
      </div>
      <div class="AssetsMenuItem" data-id="6" onclick="getInventory(6)">
        <a class="AssetsMenuButton">Pants</a>
      </div>
    <div class="AssetsMenuItem" data-id="4" onclick="getInventory(4)">
        <a class="AssetsMenuButton">Decals</a>
      </div>
      
      <div class="AssetsMenuItem" data-id="0" onclick="getInventory(0)">
         <a class="AssetsMenuButton">Places</a>
       </div>
    
    </div>
    <div id="AssetsContent"></div>
    <div style="clear:both;"></div>
  </div>
  </div>
</div>
  </div>
  </div>
  
    </div>
    </div>
<?php include 'api/web/footer.php'; ?>
