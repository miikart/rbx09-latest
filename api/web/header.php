<?php
ob_start();
require_once($_SERVER['DOCUMENT_ROOT']."/api/web/config.php");
function errorhandler() {
    $err = error_get_last();  
    if ($err !== NULL && ($err['type'] === E_ERROR || $err['type'] === E_PARSE || $err['type'] === E_CORE_ERROR || $err['type'] === E_COMPILE_ERROR)) {
        echo '
 <center>      
 <div id="errorthing" style="position: fixed; z-index: 1; left: 0px; top: 0px; width: 100%; height: 100%; overflow: auto; background-color: rgba(100, 100, 100, 0.25);">
    <div  style="width: 27em; position: absolute; top: 50%; left: 46%; transform: translateX(-50%) translateY(-50%);">

<div id="ErrorPane">

  <h5>Uh oh!</h5>
  <div>
   <p>
   <a>  
  <img id="img" src="/images/Error.png" border="0">
    </a>  
    </p>
    <h1>An Error occurred!</h1>      
</div>
 <p>
  <span>Fatal error: ' . $err["message"] . '  in ' . $err["file"] . ' on line ' . $err["line"] . '</span>    
  </p>
                  
                  
  <p>
          <input type="button" value="Ok!" onclick="$(\'#errorthing\').hide();"/>
       </p>
</div>
</div>
</div>
</center>    
  ';
       

        exit();
    }
    }
register_shutdown_function('errorhandler');
$date = date("Y-m-d");
$now = time();
$timeout = 5;
$xp = 60;
$expires = $now + $timeout * $xp;
$stmt = $con->prepare("UPDATE users SET visittick = :now, expiretime = :expires WHERE id = :userId");
$stmt->execute(['now' => $now, 'expires' => $expires, 'userId' => $_USER['id']]);
$stmt = $con->prepare("SELECT COUNT(*) FROM catalog WHERE moderation = 'pending'");
$stmt->execute();
$notapproveditemsalready = $stmt->fetchColumn();
?>
<!DOCTYPE HTML>
<head>
<html id="GOLDBLOX" lang="en">
<link href="/CSS/AllCSS.ashx?v=2" rel="stylesheet">
<link id="ctl00_Favicon" rel="Shortcut Icon" type="image/png" href="/images/favicon.png"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="author" content="GOLDBLOX: A FREE Virtual World-Building Game with Avatar Chat, 3D Environments, and Physics"/>
<meta name="keywords" content="game, video game, building game, construction game, online game, LEGO game, LEGO, MMO, MMORPG, virtual world, avatar chat"/>
<meta name="description" content="GOLDBLOX is SAFE for kids! GOLDBLOX is a FREE casual virtual world with fully constructible/desctructible environments and immersive physics. Build, battle, chat, or just hang out."/>
<script src="/JS/JQuery.js"></script>
<script src="/JS/ajax.js" type="text/javascript"></script>
<script src="/JS/ajaxcommon.js" type="text/javascript"></script>
<script src="/JS/ajaxtimer.js" type="text/javascript"></script>
<script src="/JS/ajaxanimations.js" type="text/javascript"></script>
<script src="/JS/ajaxextenderbase.js" type="text/javascript"></script>
<script src="/JS/accordian.js" type="text/javascript"></script>
<script src="https://unpkg.com/@ruffle-rs/ruffle@0.1.0-nightly.2024.7.9/ruffle.js"></script>
</head>
<div id="Container">
<div id="Header">
<div id="Banner">
<div id="Options">
<?php if(!isset($onlybanner)) { ?>
<div id="Authentication">
<?php if ($auth == false) { ?><a href="/Login/Default.aspx">Login</a><?php } ?>
<?php if ($auth == true) { ?>Logged in as <?= htmlspecialchars($_USER['username']) ?>&nbsp;<strong>|</strong>&nbsp;<a href="/Logout.ashx">Logout</a><?php } ?>
</div>
<div id="Settings">
<?php if ($auth == true && $_USER['USER_PERMISSIONS'] === 'Administrator') {
echo 'Age ';
echo $_USER['age'] == 1 ? '< 13' : '13+';
echo ', Safety Mode: ';
echo $_USER['chatMode'] == true ? 'Safe' : 'Filter';
echo ' |</strong>&nbsp;<a href="/Admi/Default.aspx" style="color: red;">Admin (' . $notapproveditemsalready . ')';
} else {
if ($auth == true) {
echo 'Age ';
echo $_USER['age'] == 1 ? '< 13' : '13+';
echo ', Safety Mode: ';
echo $_USER['chatMode'] == true ? 'Safe' : 'Filter';
}
}
?>
</div>
<?php } ?>
</div>

<div id="Logo" style="margin-top:8px;

margin-left:13px;

">
                <a id="ctl00_rbxImage_Logo" href="/" style="display:inline-block;cursor:pointer;" title="GOLDBLOX">
                    <img src="/images/GOLDBLOX.png" id="img" style="height: 57px;" blankurl="http://t2.GOLDBLOX.com:80/blank-267x70.gif" alt="GOLDBLOX" border="0">
                </a>
            </div>
<?php if(!isset($onlybanner)) { ?>
<div id="Alerts">
<?php if ($auth == false) { ?>
<table style="width:100%;height:105%">
<tbody>
<tr>
<td valign="middle">
<a id="ctl00_BannerAlertsLoginView_BannerAlerts_Anonymous_rbxAlerts_SignupAndPlayHyperLink" class="SignUpAndPlay" text="Sign-up and Play!" href="/Games.aspx" style="display:inline-block;cursor:pointer;"><img src="/images/Holiday3Button.png" border="0" onerror="return GOLDBLOX.Controls.Image.OnError(this)"></a>
</td>
</tr>
</tbody>
</table>
<?php } ?>
<?php if ($auth == true) {
if ($_USER['GOLDBUX'] > 0 || $_USER['tix'] > 0 || $unreadmsg > 0) { ?>
<table style="width:100%;height:100%">
<tbody>
<tr>
<td valign="middle">
<div>
<div id="AlertSpace">
<?php if ($unreadmsg > 0) { ?>
<div id="MessageAlert">
<a class="MessageAlertIcon"><img src="/images/Message.gif" style="border-width:0px;"></a>&nbsp;<a href="/My/Inbox.aspx" class="MessageAlertCaption"><?= number_format($unreadmsg) ?> new messages!</a>
</div>
<?php } ?>
<?php if ($_USER['GOLDBUX'] > 0) { ?>
<div id="GOLDBUXAlert">
<a class="GOLDBUXAlertIcon"><img src="/images/GOLDBUX.png" style="border-width:0px;"></a>&nbsp;<a href="/Marketplace/TradeCurrency.aspx" class="GOLDBUXAlertCaption"><?= number_format($_USER['GOLDBUX']) ?> GOLDBUX</a>
</div>
<?php } ?>
<?php if ($_USER['tix'] > 0) { ?>
<div id="TicketsAlert">
<a class="TicketsAlertIcon"><img src="/images/Tickets.png" style="border-width:0px;"></a>&nbsp;<a href="/Marketplace/TradeCurrency.aspx" class="TicketsAlertCaption"><?= number_format($_USER['tix']) ?> Tickets</a>
</div>
<?php } ?>
</div>
</div>
</td>
</tr>
</tbody>
</table>
<?php } ?>
<?php } ?>
</div></div>
<div class="Navigation">
   <span><a id="ctl00_Menu_hlMyGOLDBLOXLink_hlMyGOLDBLOX" class="MenuItem" href="/User.aspx">My GOLDBLOX</a></span>
	    
	<span class="Separator">&nbsp;|&nbsp;</span>
	<span><a id="ctl00_Menu_hlGames" class="MenuItem" href="/Games.aspx">Games</a></span>
	<span class="Separator">&nbsp;|&nbsp;</span>
	<span><a id="ctl00_Menu_hlCatalog" class="MenuItem" href="/Catalog.aspx">Catalog</a></span>
	<span class="Separator">&nbsp;|&nbsp;</span>
	<span><a id="ctl00_Menu_hlBrowse" class="MenuItem" href="/Browse.aspx">People</a></span>
	<span class="Separator">&nbsp;|&nbsp;</span>
    <span><a id="ctl00_Menu_hlBuildersClub" class="MenuItem" href="/Upgrades/BuildersClub.aspx">Builders Club</a></span>
	<span class="Separator">&nbsp;|&nbsp;</span>
	<span><a id="ctl00_Menu_hlForum" class="MenuItem" href="/Forum/Default.aspx">Forum</a></span>
	<span class="Separator">&nbsp;|&nbsp;</span>
	<span><a id="ctl00_Menu_hlNews" class="MenuItem" href="/blog/" target="_blank">News</a>&nbsp;<a id="ctl00_Menu_hlNewsFeed" href="/blog/?feed=rss"><img src="/images/feed-icons/feed-icon-14x14.png" alt="RSS" border="0"/></a></span>
	<span class="Separator">&nbsp;|&nbsp;</span>
	<span><a id="ctl00_Menu_hlParents" class="MenuItem" href="/Parents.aspx">Parents</a></span>
	<span class="Separator">&nbsp;|&nbsp;</span>
	<span><a id="ctl00_Menu_hlHelp" class="MenuItem" href="/Help/Builderman.aspx">Help</a></span>
<?php } ?>
</div>

</div>
<?php if(!isset($onlybanner)) { ?>
<?php
try {
$coolpeople = $con->prepare("SELECT * FROM banners");
$coolpeople->execute();
$sigmas = $coolpeople->fetchAll();
} catch (PDOException $e) {
echo "Connection failed: " . $e->getMessage();
exit;
}
if ($sigmas) {
foreach ($sigmas as $awesomeguy) {
$browncolor = $awesomeguy['textcolor'] == 1 ? "black" : "white";
?>
<div class="SystemAlert">
                    <div id="ctl00_SystemAlertTextColor" class="SystemAlertText"  style="background-color: <?= htmlspecialchars($awesomeguy['color']) ?>">
                        <div class="Exclamation">
                        </div>
                        <div id="ctl00_LabelAnnouncement" style="color: <?= htmlspecialchars($browncolor) ?>"><?= htmlspecialchars($awesomeguy['text']) ?></div>
                    </div>
                </div>
<?php
}
}
?>
<?php } ?>