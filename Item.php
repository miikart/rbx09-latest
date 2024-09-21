
<?php
require_once 'api/web/header.php';
   /* as you can see the goldblox src code is very interesting... - mii 9/21/2024 /* 
    $id = intval($_GET['ID']);
 if (!isset($id)) {
        header('HTTP/1.1 404 Not Found');
        include("error.php");
        exit;
    }
if($auth == true) { 
$fapshittroll = $con->prepare("SELECT * FROM favorites WHERE uid = :userid AND itemid = :game");
$fapshittroll->bindParam(':userid', $_USER['id'], PDO::PARAM_INT);
$fapshittroll->bindParam(':game', $id, PDO::PARAM_STR);
$fapshittroll->execute();
$favorited = false;
if ($fapshittroll->rowCount() != 0) {
    $favorited = true;
}
}
 if ($auth === false) {
      $favorited = true;
    }
    $stmt = $con->prepare("SELECT * FROM catalog WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item === false) {
        header('HTTP/1.1 404 Not Found');
        include("error.php");
        exit;
    }
    $row = $item;
    $commentText = "this ";
 $type = ucfirst($item['type']);
    if ($item['type'] === "place") {
        require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/config.php");
   
   
  
      $gameq = $con->prepare("SELECT * FROM catalog WHERE id = :id AND type = 'place'");
      $gameq->bindParam(':id', $id, PDO::PARAM_INT);
      $gameq->execute();
      $game = $gameq->fetch(PDO::FETCH_ASSOC);

      if (!$game) {
          header('HTTP/1.1 404 Not Found');
          include("error.php");
          exit;
      }
  
      $creatorq = $con->prepare("SELECT * FROM users WHERE id = :creatorid");
      $creatorq->bindParam(':creatorid', $game['creatorid'], PDO::PARAM_INT);
      $creatorq->execute();
      $creator = $creatorq->fetch(PDO::FETCH_ASSOC);

      $sql = "SELECT * FROM friends WHERE user_from = :user_from AND user_to = :user_to AND arefriends = 1";
      $stmt = $con->prepare($sql);
      $stmt->bindParam(':user_from', $game['creatorid'], PDO::PARAM_INT);
      $stmt->bindParam(':user_to', $_USER['id'], PDO::PARAM_INT);
      $stmt->execute();
      $isfriend = ($stmt->rowCount() > 0);
   if ($auth === false) {
      $favorited = true;
    }
   if($auth == true) { 
  $fapshittroll = $con->prepare("SELECT * FROM favorites WHERE uid = :userid AND itemid = :game AND type = 'place'");
  $fapshittroll->bindParam(':userid', $_USER['id'], PDO::PARAM_INT);
  $fapshittroll->bindParam(':game', $id, PDO::PARAM_STR);
  $fapshittroll->execute();
  $favorited = false;
  if ($fapshittroll->rowCount() != 0) {
      $favorited = true;
  }
  
   }
  ?>

 <title> <?=$game['name'] ?> by <?=$creator['username'] ?> - GOLDBLOX Places</title>
  
  
  <div id="Body">
   <?php if($auth == true) { ?>
    <script>
      var sid;
      var token;
      var sid2;
      var activeTab = 1;
      function showTab(num) {
        $("#tab" + activeTab).removeClass("Active");
        $("#tabb" + activeTab).hide();
        activeTab = num;
        $("#tab" + num).addClass("Active");
        $("#tabb" + num).show();
      }
    
     
      function JoinGame4(serverid = 0) {
        $("#joiningGameDiag").show();
        $.post("", {
          placeId: 1,
          serverId: serverid
        }, function(data) {
          if (isNaN(data) == false) {
            sid = data;
            setTimeout(function() {
              checkifProgressChanged();
            }, 1500);
          } else if (data.startsWith("")) {
            $("#Requesting").html("Locked.");
            $("#Spinner").hide();
            token = data;
          } else {
            $("#Spinner").hide();
            $("#Requesting").html(data);
          }
        });
      }
     
     
      function checkifProgressChanged() {
        $.getJSON("" + sid, function(result) {
          $("#Requesting").html(result.msg);
          if (result.token == null) {
            if (result.check == true) {
              setTimeout(function() {
                checkifProgressChanged()
              }, 750);
            } else {
              $("#Spinner").hide();
            }
          } else {
            token = result.token;
            location.href = "" + token;
            setTimeout(function() {
              closeModal();
            }, 2000);
          }
        });
      }
      function joinServer() {
        $.getJSON("" + sid2, function(result) {
          $("#Requesting").html(result.msg);
          if (result.token != null) {
            token = result.token;
            location.href = "" + token;
            setTimeout(function() {
              closeModal();
            }, 2000);
          }
        });
      }
      
  function PlaceLauncher(isHostRequest = 0) {
      $("#ctl00_cphGoldblox_VisitButtons_rbxPlaceLauncher_Panel1").show();
        $("#ctl00_cphGoldblox_VisitButtons_rbxPlaceLauncher_Panel2").show();
      const settings = {
          placeId: <?=$game['id']?>
      };
  
      let url = "#";
     if (isHostRequest == 2) {
          url += "&launchmode=solo";
      }
     if (isHostRequest == 1) {
          url += "&launchmode=host&port=<?=$game['port']?>"
      }
  
      setTimeout(function() {
  if (<?=$game['isoffline']?> == 3 && isHostRequest != 1) {
              $("#Requesting").hide();
            $("#Error").show();
              
          } else {
               $("#Requesting").hide();
            $("#Joining").show();
              setTimeout(function() {
                  window.location.assign(url);
              }, 1500);
              setTimeout(function() {
              closeModal();
              }, 2000);
          }
      }, 500); 
  }
  
  function closeModal() {
      $("#ctl00_cphGoldblox_VisitButtons_rbxPlaceLauncher_Panel1").hide();
      $("#ctl00_cphGoldblox_VisitButtons_rbxPlaceLauncher_Panel2").hide();
     $("#Requesting").show();
            $("#Joining").hide();
 $("#Error").hide();
  }
  function Login() {
      $("#joiningGameDiag").show(); 
      $("#Requesting").html("Please wait..."); 
      
  
      setTimeout(function() {
          window.location.href = "/Login/Default.aspx";
      }, 2000); 
  }
    </script>
  
  <?php } ?>
  
 
  <script>
      function activateTab(activeTabId, inactiveTabId) {
        var activeTab = document.getElementById(activeTabId);
        activeTab.classList.add('ajax__tab_active');
        activeTab.classList.remove('ajax__tab_hover');
  
        var inactiveTab = document.getElementById(inactiveTabId);
        inactiveTab.classList.remove('ajax__tab_active');
        inactiveTab.classList.add('ajax__tab_hover');
      }
 function getServers(page, item) 
    {
		if (page == undefined){ page = 1; }
        $.post("/api/items/getServers.php", {page:page,item:item}, function(data) 
        {
        	$("#CommentsContainer").html("");
        	$("#CommentsContainer").html(data);
        })
        .fail(function() 
        {
        	$("#CommentsContainer").html("");
        	$("#CommentsContainer").html("Failed to get gameservers");
        });
    }
	 function getComments(page, item) 
    {
		if (page == undefined){ page = 1; }
        $.post("/api/items/getCommentsItem.php", {page:page,item:item}, function(data) 
        {
        	$("#CommentsContainer").html("");
        	$("#CommentsContainer").html(data);
        })
        .fail(function() 
        {
        	$("#CommentsContainer").html("");
        	$("#CommentsContainer").html("Failed to get comments");
        });
    }
	<?php if($auth == true){ ?>
	function Comment(item)
	{
		var content = document.getElementById("comment").value;
        $.post("/api/items/CommentItem.php", {content:content,item:item}, function(data) 
        {
        	if(data == "Success")
			{
				getComments(1, <?=$id?>)
			}
			else{
				$("#CommentsContainer").html("");
				$("#CommentsContainer").html(data);
			}
        })
        .fail(function() 
        {
        	$("#CommentsContainer").html("");
        	$("#CommentsContainer").html("Failed to comment");
        });
	}
 
	<?php } ?>
    function favorite() {
      var id = '<?php echo $id ?>';
      var link = document.getElementById("favorit");
      
      fetch(`/api/favorite.php?id=${id}`, {
          method: 'POST',
      })
      .then(response => {
          if (!response.ok) {
              throw new Error('hehe.');
          }
          console.log('favorited');
          const newlink = document.createElement("p");
          newlink.textContent = "Favorite";
          newlink.style.marginTop = "0px";
          newlink.style.marginBottom = "0px";
  
          link.parentNode.replaceChild(newlink, link);
      })
      .catch(error => {
          console.error('grrr:', error);
      });
      }
      getServers(<?=$id?>);
    </script>
  
    <style>
     
    
      .gamespagebutton {
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 200ms;
      }
      .gamespagebutton:hover {
        filter: brightness(115%);
      }
    </style>
     
    <div id="ItemContainer">
    
      <div id="Item">
         <h2><?=$game['name']; ?></h2>
        <div id="Details">
        <div id="Summary" >
          <h3><?php if ($game['isPinned'] > 0) { ?>📌 <?php } ?>GOLDBLOX Place</h3>
          <div id="Creator" class="Creator">
            <div class="Avatar">
              <img src="/Thumbs/Avatar.ashx?assetId=<?= $game['creatorid']; ?>&IsSmall">
              <a title="<?= $creator['username']; ?>" href="/User.aspx?ID=<?= $game['creatorid']; ?>" style="display:inline-block;cursor:pointer;"></a>
            </div>
            Creator: <a href="/User.aspx?ID=<?= $game['creatorid']; ?>"><?= htmlspecialchars($creator['username']); ?></a>
          </div>
         
      
          <?php
    

  

  
      $favorites = 0;
      $favoritestmt = $con->prepare("SELECT * FROM `favorites` WHERE itemid= :gameid");
      $favoritestmt->bindParam(':gameid', $game['id'], PDO::PARAM_INT);
      $favoritestmt->execute();
      
   
      $favorites = $favoritestmt->rowCount();
  
      $favoriteresults = $favoritestmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
          <div id="Favorited">Favorited:  <?= number_format($favorites); ?> times</div>
          <div class="Visited">Visited: <?= number_format($game['visits']); ?> times</div>
           
          <?php if ($game['description'] != "") { ?><div>
              <div id="DescriptionLabel">Description:</div>
              <div id="Description" style="width:auto;"><?=nl2br($game['description']); ?></div>
            </div><? } ?>
         
              </center>
             <center>
          <div id="ReportAbuse">
   
             <div class="ReportAbusePanel" style="margin: 4px;">
        <?php if ($game['creatorid'] != $_USER['id'] ) { ?>
        <span class="AbuseIcon"><a href="/report/?id=<?php echo htmlspecialchars($row['id']); ?>&type=2"><img src="/images/abuse.gif" alt="Report Abuse" style="border-width:0px;"></a></span>
        <span class="AbuseButton"><a href="/report/?id=<?php echo htmlspecialchars($row['id']); ?>&type=2">Report Abuse</a></span>
         <?php } ?>
          
             
  
           
           
            </div>
          </div>
        <center>
         
            
        </div>
        <div id="Thumbnail_Place" style="height: 230px; width: 418px; border: solid 1px #555;">
          <a title="<?= $game['name']; ?>
  " style="display:inline-block;cursor:pointer;"><img src="/Thumbs/Asset.ashx?assetId=<?= $game['id']; ?>&IsSmall" width="418" height="230" border="0" alt="<?=$game['name']; ?>" onerror="this.onerror=null;this.src='/images/unavail-160x100.png';"></a>
        </div>
        <div id="Actions_Place" style="width: 408px;">
         <?php if($favorited == false) { echo '<a onclick="favorite()" href="#" id="favorit" ">Favorite</a>';}else{echo '<a disabled="disabled">Favorite</a>';}?>
  
        </div>
        <div class="PlayGames">
          <div style="text-align: center; margin: 1em 5px;"><?php if ($game['State'] == "friends") { ?>
              <span style="display:inline;"><img src="/images/locked.png" style="border-width:0px;">&nbsp;Friends-only</span><?php } else { ?>
              <span style="display:inline;"><img src="/images/public.png" style="border-width:0px;">&nbsp;Public</span><?php } ?>
            <img src="/images/CopyLocked.png" style="border-width:0px;"> Copy Protection: CopyLocked <br>
                   </div>
     <div id="ctl00_cphGoldblox_VisitButtons_rbxPlaceLauncher_Panel1" style="display:none;position: fixed; z-index: 1; left: 0px; top: 0px; width: 100%; height: 100%; overflow: auto; background-color: rgba(100, 100, 100, 0.25);"> 
    <div id="ctl00_cphGoldblox_VisitButtons_rbxPlaceLauncher_Panel2" class="modalPopup" style="display: none;  position: absolute; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);  box-shadow: black 5px 5px;width:310px;">
	
    <div style="margin: 1.5em">
        <div id="Spinner" style="float:left;margin:0 1em 1em 0">
            <img id="ctl00_cphGoldblox_VisitButtons_rbxPlaceLauncher_Image1" src="/images/ProgressIndicator2.gif" alt="Progress" border="0"/></div>
        <div id="Requesting" style="display: inline">
            Requesting a server</div>
        <div id="Waiting" style="display: none">
            Waiting for a server</div>
        <div id="Loading" style="display: none">
            A server is loading the game</div>
        <div id="Joining" style="display: none">
            The server is ready. Joining the game...</div>
        <div id="Error" style="display: none">
            An error occured. Please try again later</div>
        <div id="Expired" style="display: none">
            There are no game servers available at this time. Please try again later</div>
        <div id="GameEnded" style="display: none">
            The game you requested has ended</div>
        <div id="GameFull" style="display: none">
            The game you requested is full. Please try again later</div>
        <div style="text-align: center; margin-top: 1em">
            <input id="Cancel" type="button" class="Button" value="Cancel"/></div>
    </div>

</div></div>
       <?php
  if ($auth && $game['creatorid'] == $_USER['id']) {
      echo '<input type="image" class="ImageButton" src="/images/VisitOnline.png" alt="Visit Online" onclick="PlaceLauncher();">';
  } elseif ($auth) {
      if ($game['State'] == "friends") {
       
          $friendq = $con->prepare(
              "SELECT * FROM friends 
              WHERE 
                  ((user_from = :creatorid AND user_to = :userid) OR 
                  (user_from = :userid AND user_to = :creatorid)) AND 
                  arefriends = 1"
          );
          
         
          $friendq->bindParam(':creatorid', $game['creatorid'], PDO::PARAM_INT);
          $friendq->bindParam(':userid', $_USER['id'], PDO::PARAM_INT);
        
          $friendq->execute();
             
       
          if ($friendq->rowCount() > 0) {
                     echo '<input type="image" class="ImageButton" src="/images/VisitOnline.png" alt="Visit Online" onclick="PlaceLauncher();">';

          }    
      } else {
  
           echo '<input type="image" class="ImageButton" src="/images/VisitOnline.png" alt="Visit Online" onclick="PlaceLauncher();">';  
      }
      } else {
     echo '
     <a href="/Login/Default.aspx">
      <input type="image" class="ImageButton" src="/images/VisitOnline.png" alt="Visit Online">
      
       </a>
      
      ';

  }
  ?>
  
      </div>
        <div style="clear: both;"></div>
   </div>
    <div style="margin: 10px; width: 703px;">
 
     <div class="ajax__tab_xp ajax__tab_container ajax__tab_default" id="TabbedInfo">
        <div id="TabbedInfo_header" class="ajax__tab_header" style="height:21px;">
            <span id="tab1" class=" jax__tab ajax__tab_active">
                 <span class="ajax__tab_outer">
                    <span class="ajax__tab_inner">
                        <a class="ajax__tab_tab ajax__tab" id="__tab_TabbedInfo_GamesTab" href="javascript:void(0)" onclick="activateTab('tab1', 'tab2'); getServers(<?=$id?>);" style="text-decoration:none;">
                            <h3>Games</h3>
                        </a>
                    </span>
                </span>
            </span>
            <span id="tab2" class="ajax__tab_hover">
                <span class="ajax__tab_outer">
                    <span class="ajax__tab_inner">
                        <a class="ajax__tab_tab ajax__tab" id="__tab_TabbedInfo_CommentaryTab" href="javascript:void(0)" onclick="activateTab('tab2', 'tab1'); getComments(1, <?=$id?>);" style="text-decoration:none;">
                            <h3>Commentary</h3>
                        </a>
                    </span>
                </span>
            </span>
        </div>
        <div id="TabbedInfo_body" class="ajax__tab_body">
            <div id="TabbedInfo_CommentaryTab" class="ajax__tab_panel">
                <div id="TabbedInfo_CommentaryTab_CommentsPane_CommentsUpdatePanel">
                    <div class="CommentsContainer" id="CommentsContainer"></div>
            </div>
          </div>
                  </div>
      </div>
    </div>
      </div>
	  
 <div class="Ads_WideSkyscraper">
    
      
      
</div>
  <div style="clear: both;"></div>
  
  
<style>.ajax__tab_default .ajax__tab {
  
     margin-top: 0px !important
}</style>
 
                          
   
                      
   <?php require_once ("api/web/footer.php"); 
exit;
}
?>
<?php 
switch ($row['type']) {
    case 'tshirt':
        $type = 'T-Shirt';
        break;
    case 'asset':
        header('Location: /Error/Default.aspx');
        exit;
    case 'pants':
        $commentText = 'these ';
        break;
}

$name = $row['name'];
$description = $row['description'];

 $pricebux = (int)$row['price2'];
        $pricetix = (int)$row['price'];

$creatorstmt = $con->prepare('SELECT * FROM users WHERE id = :creatorid');
$creatorstmt->execute(['creatorid' => $row['creatorid']]);
$creator = $creatorstmt->fetch(PDO::FETCH_ASSOC);

$owneditemsstmt = $con->prepare('SELECT * FROM owned_items WHERE itemid = :itemid AND ownerid = :ownerid');
$owneditemsstmt->execute(['itemid' => $row['id'], 'ownerid' => $_USER['id']]);
$owneditems = $owneditemsstmt->fetch(PDO::FETCH_ASSOC);

if ($owneditems) {
    $owned = true;
} else {
    $owned = false;
}

$price = $row['price'];
$buywith = $row['buywith'];
$itemname = ($row['name']);
$itemcreator = htmlspecialchars($creator['username']);

?>




      <title><?php echo $row['name']; ?> By <?php echo $creator['username']; ?>  - GOLDBLOX <?php echo $type; ?><?php if($row['type'] != 'pants') { ?>s<?php  } ?>
      </title>  
  <div id="Body">

  <div id="ItemContainer">

 
   <div id="Item">
  <h2><?=($itemname); ?></h2>
  <div id="Details">
  <div id="Thumbnail">
    <a title="<?= $row['name']; ?>" style="display:inline-block;height:250px;width:250px;"><img src="/Thumbs/Asset.ashx?assetId=<?=$row['id'];?>" border="0" id="img" alt="<?= $row['name']; ?>" style="display:inline-block;height:250px;width:250px;"></a>
  </div>
  
   <div id="Summary">
      <h3>GOLDBLOX <?=$type ?></h3>
	<?php if($row['isoffsale'] == 0 && $owned == false)	{ ?>
	<?php if($row['price2'] > 0 || $row['price'] > 0){ ?>
          <?php if($row['buywith2'] == 'GOLDBUX') { ?>
           <div id="GOLDBUXPurchase">
          <div id="PriceInGOLDBUX">G$: <?=number_format((int)$row['price2'])?></div>
          <div id="BuyWithGOLDBUX">
            <a <?php if($auth == false) { ?> href="/Login/Default.aspx"<?php } else { ?>  onclick="showPurchaseDiag(1);"<?php } ?> class="Button">Buy with G$</a>
          </div>
        </div>
        <br><br>
        <?php } ?>
          <?php if($row['buywith'] == 'tix') { ?>
           <div id="TicketsPurchase">
          <div id="PriceInTickets">Tx: <?=number_format((int)$row['price'])?></div>
          <div id="BuyWithTickets">
            <a <?php if($auth == false) { ?> href="/Login/Default.aspx"<?php } else { ?>  onclick="showPurchaseDiag(0);"<?php } ?> class="Button">Buy with Tx</a>
          </div>
        </div>
        <br><br>
        <?php } ?>
        <?php }else{ ?><div id="PublicDomainPurchase">
          <div id="PricePublicDomain">Free</div>
          <div id="BuyForFree">
            <a <?php if($auth == false) { ?> href="/Login/Default.aspx"<?php } else { ?>  onclick="showPurchaseDiag(0);"<?php } ?> class="Button">Take One!</a>
          </div>
        </div>
        <?php } ?>
        <?php } ?>
            <div id="Creator"><a href="/User.aspx?ID=<?php echo $creator['id']; ?>"><img src="/Thumbs/Avatar.ashx?assetId=<?php echo $creator['id']; ?>&IsSmall"></a><br><span style="color:#555;">Creator: </span><a href="/User.aspx?ID=<?php echo $creator['id']; ?>"><?php echo $creator['username']; ?></a></div>
                <?php
	  $updated = context::updated($row['creation_date']);
                                ?>
      <div id="LastUpdate">Updated: <?php echo $updated ?></div>
    <?php
  $q = $con->prepare("SELECT * FROM favorites WHERE itemid = :itemid");

    $q->bindParam(':itemid', $row['id'], PDO::PARAM_INT);

    $q->execute();

    $q = $q->rowCount();
    ?>
    <div id="Favourites">Favorited: <?php echo $q; ?> time<?php if($q > 1 || $q < 1) { echo "s"; } ?></div>
      <div>
        <?php if(!empty($row['description'])) { ?>
   
    <div id="DescriptionLabel">Description:</div>
    <div id="Description"><?=nl2br($row['description']); ?></div><?php } ?>
    </div>
      <center><div id="ReportAbuse">
    
      <span class="AbuseIcon">
          
          <a <?php if($row['creatorid'] != $_USER['id'] && $row['creatorid'] != 1 && $auth) { ?>
          href="/report/?id=<?php echo (int)$row['id']; ?>&type=2"
          <?php } else { ?>style="color:gray"<?php } ?>><img src="/images/abuse.gif" alt="Report Abuse" style="border-width:0px;"></span>
      <span class="AbuseButton">
      Report Abuse</span>
     </a>
        
      
    </div></center>
    </div>
    
    <div id="Actions">
  <?php if($favorited == false) { echo '<a onclick="favorite()" href="#" id="favorit" ">Favorite</a>';}else{echo '<a disabled="disabled">Favorite</a>';}?>
                </div>
        
<?php if($_USER['id'] == $row['creatorid'] && $row['moderation'] == 'accepted' || $_USER['USER_PERMISSIONS'] == 'Administrator') { ?>

      <div id="Configuration">
    <a href="/My/Item.aspx?ID=<?=$id;?>">Configure <?=$commentText?> <?=$type?></a>
    </div><div style="clear: both;"></div>

<?php } ?>        
                

                
                
 
    



<?php if($owned == true) { ?>

    <div id="Ownership">
      
      You own this item
    
    </div>
    
    <div style="clear: both;"></div>
    
<?php } ?>

   
    
</div>

<?php
$buxafterpurchase = $_USER['GOLDBUX'] - $row['price'];
$tixafterpurchase = $_USER['tix'] - $row['price'];
  
$buxbeforepurchase = $_USER['GOLDBUX'] + $row['price'];
$tixbeforepurchase = $_USER['tix'] + $row['price'];
?>
            <div style="clear: both;"></div>
    <div style="margin: 10px; width: 703px;">
 
      <div class="ajax__tab_xp ajax__tab_container ajax__tab_default" id="TabbedInfo">
        <div id="TabbedInfo_header" class="ajax__tab_header">
                    <span id="__tab_TabbedInfo_CommentaryTab" class="ajax__tab ajax__tab_active">
            <span class="ajax__tab_outer"><span class="ajax__tab_inner"><a class="ajax__tab_tab ajax__tab" id="__tab_TabbedInfo_CommentaryTab" href="#" style="text-decoration:none;"><h3>Commentary</h3></a></span></span>
          </span>
                  </div>
        <div id="TabbedInfo_body" class="ajax__tab_body">
                    <div id="TabbedInfo_CommentaryTab" class="ajax__tab_panel">
            <div id="TabbedInfo_CommentaryTab_CommentsPane_CommentsUpdatePanel">
              <div class="CommentsContainer" id="CommentsContainer">
            </div>
          </div>
                  </div>
      </div>
   
    </div>
      </div>
	 
	  </div>


 <div class="Ads_WideSkyscraper">
    
      
      
</div>
   <?php if($auth == true && $owned == false){ 
  
  ?>
    <div id="itemPurchaseFade" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(100,100,100,0.25);">
    <div id="itemPurchase" class="anim" style="width: 27em; position: absolute; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);">
      <div style="background-color: #ffd; border:3px solid gray; box-shadow: black 5px 5px;">
	 <?php if($row['buywith2'] == 'GOLDBUX')  { ?>
	  <div id="VerifyPurchaseBux" style="margin: 1.5em; display:none;">
          <h3>Purchase Item:</h3>
        
          <p>Would you like to purchase <?=($type)?> "<?=$ep->remove($row['name'])?>" from <?=$ep->remove($creator['username'])?> for G$: <?=number_format($pricebux)?>?</p>
         
          <p>Your balance after this purchase will be G$: <?=number_format($_USER['GOLDBUX'] - $pricebux) ?>.</p>
       <p>
               <input type='submit' value='Buy Now!' name='buybux' onclick='buyitem(<?= $row['id']; ?>,1);' class='MediumButton' style='width:100%;'>
    </p>
        <p>
          <input type="button" value="Cancel" onclick="$('#itemPurchaseFade').hide();" class="MediumButton" style="width:100%;" />
      </p>
        </div>
	 <div id="VerifyPurchaseBuxIn" style="margin: 1.5em;">
          <h3>Insufficient Funds</h3>
          <p>You need <?= number_format($pricebux - $_USER['GOLDBUX'])?> more G$ to purchase this item.</p>
          <p>                    <input type="button" value="Cancel" onclick="$('#itemPurchaseFade').hide();" class="MediumButton" style="width:100%;" /></p>
        </div>
	<div id='PurchaseMessageBux' style='margin: 1.5em; display: none;'>
    <h3>Purchase Complete:</h3>
    <p>You have successfully purchased <?=$type ?> "<?= $row['name']; ?>" from <?= $creator['username']; ?> for <?php if($row['price2'] == '0') { ?>
    Free.
<?php } else { ?>
    <?php if($row['buywith2'] == 'tix') {
         echo 'G$';
    } else {
        echo 'G$';
    } ?>: <?= number_format($row['price2']); ?>
.
<?php } ?></p>
<p>
<a href="/Catalog.aspx">
   <input type="button" value="Continue Shopping"  href="/Catalog.aspx"  class="MediumButton" style="width:100%;" />
 </a>
</p> 
<p>
    <a href="/My/Character.aspx">
        
        <input type="button" value="Customize Character"  href="/My/Character.aspx" class="MediumButton" style="width:100%;" />   
    </a> 
    </p> 
    
    </div>
		<?php } ?>
              <?php if($row['buywith'] == 'tix')  { ?>
                <div id="VerifyPurchaseTix" style="margin: 1.5em;">
          <h3>Purchase Item:</h3>
       
          <p>Would you like to purchase <?=$type?> "<?=$ep->remove($row['name'])?>" from <?=$ep->remove($creator['username'])?> for <?php if ($row['price'] == '0') { ?>
   <?php echo ('Free') ?>
<?php } else { ?>
    <?php if ($row['buywith'] == 'tix') {
        echo 'Tx';
    } else {
        echo 'Tx';
    } ?>: <?= number_format($row['price']); ?>
<?php } ?>?
</p>

          <p>Your balance after this purchase will be Tx: <?=number_format($_USER['tix'] - $pricetix)?>.</p>
          <p>     
          <input type='submit' value='Buy Now!' name='buytix' onclick='buyitem(<?= $row['id']; ?>);' class='MediumButton' style='width:100%;'>
        </p> 
         <p>
          <input type="button" value="Cancel" onclick="$('#itemPurchaseFade').hide();" class="MediumButton" style="width:100%;" /></p>
        </div>
       <div id="VerifyPurchaseTixIn" style="margin: 1.5em; display:none;">
          <h3>Insufficient Funds</h3>
          <p>You need <?=number_format($pricetix - $_USER['tix'])?> more Tx to purchase this item.</p>
        <p>
          <input type="button" value="Cancel" onclick="$('#itemPurchaseFade').hide();" class="MediumButton" style="width:100%;" />
       </p>
        </div>
       <div id='PurchaseMessageTix' style='margin: 1.5em; display: none;'>
    <h3>Purchase Complete:</h3>
    <p>You have successfully purchased <?=$type ?> "<?= $row['name']; ?>" from <?= $creator['username']; ?> for <?php if($row['price'] == '0') { ?>
<?php echo ('Free') ?>
<?php } else { ?>
    <?php if($row['buywith'] == 'tix') {
        echo 'Tx';
    } else {
        echo 'Tx';
    } ?>: <?= number_format($row['price']); ?>
.
<?php } ?>
</p>
 <p>
<a href="/Catalog.aspx">
   <input type="button" value="Continue Shopping"  href="/Catalog.aspx"  class="MediumButton" style="width:100%;" />
 </a>
</p> 
<p>
    <a href="/My/Character.aspx">
        
        <input type="button" value="Customize Character"  href="/My/Character.aspx" class="MediumButton" style="width:100%;" />   
    </a> 
    </p> 
    </div>
       <?php } ?>
      
 
    <div id="ProcessPurchase" style="margin: 2.5em auto; display: none;">
							<div id="Processing_Tickets" style="margin: 0 auto; text-align: center; vertical-align: middle;">
								<img id="ctl00_cphRoblox_ProcessingTicketsPurchaseIconImage" src="/images/ProgressIndicator2.gif" style="border-width:0px;" align="middle">&nbsp;&nbsp;
								Processing transaction ...
							</div>
						</div>
     <div id="ErrorMessage" style="padding-left:50px; padding-right:50px; margin: 2.5em auto; display: none;">
          <div id="Processing" style="margin: 0 auto; text-align: center; vertical-align: middle;">
          
         An Error occured and we weren't able to process your transaction. We're sorry.
          </div>
        </div>
     </div>
    </div>
  </div>

<?php } ?>
  <div style="clear: both;">
  
<script>
function getComments(page, item) 
    {
		if (page == undefined){ page = 1; }
        $.post("/api/items/getCommentsItem.php", {page:page,item:item}, function(data) 
        {
        	$("#CommentsContainer").html("");
        	$("#CommentsContainer").html(data);
        })
        .fail(function() 
        {
        	$("#CommentsContainer").html("");
        	$("#CommentsContainer").html("Failed to get comments");
        });
    }
	<?php if($auth == true){ ?>
	function Comment(item)
	{
		var content = document.getElementById("comment").value;
        $.post("/api/items/CommentItem.php", {content:content,item:item}, function(data) 
        {
        	if(data == "Success")
			{
				getComments(1, <?=$id?>)
			}
			else{
				$("#CommentsContainer").html("");
				$("#CommentsContainer").html(data);
			}
        })
        .fail(function() 
        {
        	$("#CommentsContainer").html("");
        	$("#CommentsContainer").html("Failed to comment");
        });
	}
 
	<?php } ?>
 
 function favorite() {
    var id = '<?php echo $id ?>';
    var link = document.getElementById("favorit");
    
    fetch(`/api/favorite.php?id=${id}`, {
        method: 'POST',
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('hehe.');
        }
        console.log('favorited');
        const newlink = document.createElement("p");
        newlink.textContent = "Favorite";
        newlink.style.marginTop = "0px";
        newlink.style.marginBottom = "0px";

        link.parentNode.replaceChild(newlink, link);
    })
    .catch(error => {
        console.error('grrr:', error);
    });
    }
   getComments(1,<?=$id?>);
</script>

<script>
function buyitem(id, bux) {
    var skibidi = { id: <?php echo $id ?> };

    if (bux) {
        skibidi.buybux = true;
    }
  $("#VerifyPurchaseTix").hide();
   $("#VerifyPurchaseBux").hide();
   
    $("#ProcessPurchase").show();

    $.post("/buyitem.php", skibidi, function(data) {
        $("#ProcessPurchase").hide();

        if (data === 'ItemAlreadyOwned') {
            $("#AlreadyOwned").show();
        } else if (data === 'Success') {
            if (bux) {
                   $("#ProcessPurchase").hide();
                $("#PurchaseMessageBux").show();
            } else {
                   $("#ProcessPurchase").hide();
                $("#PurchaseMessageTix").show();
            }
        } else {
            throw new Error('Unexpected response');
       $("#ProcessPurchase").hide();
        $("#ErrorMessage").show();
        }
    })
    .fail(function(error) {
        console.error('Error:', error);
        $("#ProcessPurchase").hide();
        $("#ErrorMessage").show();
    });
}
</script>


    </div>
<script>
  var currency;
var suffBux = <?php echo ($_USER['GOLDBUX'] >= $pricebux) ? 'true' : 'false'; ?>;
        var suffTix = <?php echo ($_USER['tix'] >= $pricetix) ? 'true' : 'false'; ?>;
  function showPurchaseDiag(currencyA) {
    $("#VerifyPurchaseTix").hide();
    $("#VerifyPurchaseBux").hide();
    $("#VerifyPurchaseTixIn").hide();
    $("#VerifyPurchaseBuxIn").hide();
    $("#ProcessPurchase").hide();
    $("#PurchaseMessage").hide();
    currency = currencyA
    $("#itemPurchaseFade").show();
    if(currency == 0) {
      if (suffTix) {
        $("#VerifyPurchaseTix").show();
      } else {
        $("#VerifyPurchaseTixIn").show();
      }
    } else {
      if (suffBux) {
        $("#VerifyPurchaseBux").show();
      } else {
        $("#VerifyPurchaseBuxIn").show();
      }
    }
  }
</script>


 </div>
  <?php include 'api/web/footer.php'; ?>


     
     
     
     


    