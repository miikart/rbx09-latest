<?php
// AMAZING site works! (setup)
require_once($_SERVER["DOCUMENT_ROOT"] . "/api/web/dbpdo.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/api/web/shit.php");
$copymaintenance = 0;
if ($copymaintenance == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}
function setSessionCookie($cookie) {
    setcookie('GOLDBLOSECURITY', $cookie, time() + 31536000, '/');
    setcookie('GOLDBLOSECURITY', $cookie, time() + 31536000, '.');
}
function generateSessionCookie() {
    return bin2hex(random_bytes(100));
}
$domain = "www.w.rccs.lol";
$local = false;
$stmt = $con->query("SELECT * FROM sitesettings");
$sitesettings = $stmt->fetch(PDO::FETCH_ASSOC);
$current_page =  basename(dirname($_SERVER["PHP_SELF"]));
if ($sitesettings['offline'] == 'true' && (!isset($_COOKIE['skibidi']) || $_COOKIE['skibidi'] != 'bussy')) {
   if (!in_array($current_page, ['asset', 'Thumbs', 'IDE', 'Info', 'Error'])) {
$lol = array("europapa2", "tacos2", "holiday", "slimshady", "vacation", "zeus", "website", "faint", "thebadtouch", "rickandmorty", "redroubles");
$lolran = $lol[array_rand($lol)];
switch($lolran) {
case "tacos2":
$width = "534";
break;
case "europapa2":
$width = "430";    
break;
case "holiday":
$width = "400";  
break;
case "vacation":
$width = "534"; 
break;
case "zeus":
$width = "300"; 
break;
case "website":
$width = "534"; 
break;
case "thebadtouch":
$width = "534"; 
break;
case "redroubles":
$width = "534"; 
break;
default:
$width = "400";     
break;
}
echo '

<html id="GOLDBLOX" lang="en"><head>

<link href="/CSS/AllCSS.ashx?v=2" rel="stylesheet">
<link id="ctl00_Favicon" rel="Shortcut Icon" type="image/png" href="/images/favicon.png">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="author" content="GOLDBLOX: A FREE Virtual World-Building Game with Avatar Chat, 3D Environments, and Physics">
<meta name="keywords" content="game, video game, building game, construction game, online game, LEGO game, LEGO, MMO, MMORPG, virtual world, avatar chat">
<meta name="description" content="GOLDBLOX is SAFE for kids! GOLDBLOX is a FREE casual virtual world with fully constructible/desctructible environments and immersive physics. Build, battle, chat, or just hang out.">
<script src="/JS/JQuery.js"></script>
<script src="/JS/ajax.js" type="text/javascript"></script>
<script src="/JS/ajaxcommon.js" type="text/javascript"></script>
<script src="/JS/ajaxtimer.js" type="text/javascript"></script>
<script src="/JS/ajaxanimations.js" type="text/javascript"></script>
<script src="/JS/ajaxextenderbase.js" type="text/javascript"></script>
<script src="/JS/accordian.js" type="text/javascript"></script>
<script src="https://unpkg.com/@ruffle-rs/ruffle@0.1.0-nightly.2024.7.9/ruffle.js"></script>
</head>
<body><div id="Container">
<div id="Header">
<div id="Banner">
<div id="Options">
</div>
<script> $(document).ready(function() {
            $("#bypassf").submit(function(event) {
                event.preventDefault();
                var key = $("#bypass").val();
         $("#Processing").show();
             $("#fail").hide();
                $.post("/api/maintenance", { key: key }, function(data) {
                    if (data === "Success") {
                      $("#fail").hide();
                        location.reload(); 
                    } else {
                       $("#Processing").hide();
                        $("#fail").show();
                    }
                }).fail(function() {
                
                    $("#fail").show();
                });
            });
        });</script>
<div id="Logo" style="margin-top:8px;

margin-left:13px;

">
                <a id="ctl00_rbxImage_Logo" href="/" style="display:inline-block;cursor:pointer;" title="GOLDBLOX">
                    <img src="/images/GOLDBLOX.png" id="img" style="height: 57px;" blankurl="http://t2.GOLDBLOX.com:80/blank-267x70.gif" alt="GOLDBLOX" border="0">
                </a>
            </div>
</div>

</div>
<div class="SystemAlert">
                    <div id="ctl00_SystemAlertTextColor" class="SystemAlertText" style="background-color: #ff0000">
                        <div class="Exclamation">
                        </div>
                        <div id="ctl00_LabelAnnouncement" style="color: white">this site does NOT use a gpt backend anymore as ive said multiple times before</div>
                    </div>
                </div>
<title>GOLDBLOX Maintenance</title>
 <div id="Body">
 <center>      
 <div id="ErrorPane" style="width:400px">
  
    <div id="ErrorContainer">
  <h5>Uh oh!</h5><p></p>
  <div>
   <a>  
  <img id="img" src="/images/Error.png" border="0">
    </a>  
    <h1>GOLDBLOX is currently offline.</h1>      
</div>
  <span>Please check back soon!</span>    
  <p>
                  
                    <div id="Processing"  style="display: none;">
							<p>
							<div style="margin: 0 auto;">
								<img id="ctl00_cphRoblox_ProcessingTicketsPurchaseIconImage" src="/images/ProgressIndicator2.gif" style="height:20px;border-width:0px;vertical-align: middle;" >&nbsp;&nbsp;
validating...
							</div>
					</p>
						</div>
                        <div id="fail" style="display:none"><p>invaild bypass key</p></div>  
            <form id="bypassf" action="" method="POST">
                <input type="password" id="bypass" name="bypass">&nbsp;
                <input type="submit" name="G" value="G">
                <input type="submit" name="O" value="O">
                <input type="submit" name="L" value="L">
                <input type="submit" name="D" value="D">
                <input type="submit" name="B" value="B">
                <input type="submit" name="L" value="L">
                <input type="submit" name="O" value="O">
                <input type="submit" name="X" value="X">
            </form>  
                    </p>
<p></p>
</div>

</div>
</center>    

</div></div>

';
require_once($_SERVER["DOCUMENT_ROOT"] . "/api/web/footer.php");
exit;
    
}
}
$auth = false;
require_once($_SERVER["DOCUMENT_ROOT"] . "/api/web/CSRF.php");
if (isset($_COOKIE["GOLDBLOSECURITY"])) {
    $sessKey = $_COOKIE["GOLDBLOSECURITY"];
    $q = $con->prepare("SELECT * FROM sessions WHERE sessKey = :sessKey");
    $q->bindParam(':sessKey', $sessKey, PDO::PARAM_STR);
    $q->execute();
    if ($q->rowCount() > 0) {
        $sessiondata = $q->fetch(PDO::FETCH_ASSOC);
        $currenttime = time();
        $expiretime = strtotime($sessiondata['expiretime']);
        if ($currenttime > $expiretime) {
            $q = $con->prepare("DELETE FROM sessions WHERE sessKey = :sessKey");
            $q->bindParam(':sessKey', $sessKey, PDO::PARAM_STR);
            $q->execute();
            setcookie("GOLDBLOSECURITY", null, -1, '/');
        } else {
            $q = $con->prepare("SELECT * FROM users WHERE id = :userId");
            $q->bindParam(':userId', $sessiondata['userId'], PDO::PARAM_INT);
            $q->execute();
            if ($q->rowCount() > 0) {
                $_USER = $q->fetch(PDO::FETCH_ASSOC);
                $auth = true;
        } else {
            setcookie("GOLDBLOSECURITY", null, -1, '/');
          }
        }
    }
}
if($_USER['filter'] == 0 && $auth == true) {
 $filterenabled = false;   
} else { 
  $filterenabled = true;     
}
 // just get rid of this if you dont use cloudflare or else it would just 500 on login - mii 9/21/2024 //
if(!$local) {
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP']; 
    function isVPN($ip) {
    try{
        if(in_array($ip, file($_SERVER["DOCUMENT_ROOT"]."/api/web/vpns.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)))
            return true;
        return false;
    } catch(Exception $e) {
        return false;
    }
}
if(isVPN($ip)) {
echo('VPNS are not allowed.');
    exit;
}
    if(!isVPN($ip)) {
    $ipLogged = false;
    $ipUserLogged = false;
    $ipBanned = false;
    $q = $con->prepare("SELECT ip, user FROM ips WHERE ip = :ip");
    $q->bindParam(':ip', $ip, PDO::PARAM_STR);
    $q->execute();
    $ips = $q->fetchAll();
    if(count($ips) >= 1)
        $ipLogged = true;
    foreach($ips as $log) {
        $q = $con->prepare("SELECT id, poisoned, bantype FROM users WHERE id = :id");
        $q->bindParam(':id', $log['user'], PDO::PARAM_INT);
        $q->execute();
        $usr = $q->fetch();
        if($usr) {
            if($usr['poisoned'] === 1)
                $ipBanned = true;
        }
    }
    if($auth) {
        foreach($ips as $log) {
            if($log['ip'] === $ip && (int)$log['user'] === (int)$_USER['id'])
                $ipUserLogged = true;
        }
        if(!$ipUserLogged) {
            $q = $con->prepare("INSERT INTO `ips` (`id`, `ip`, `user`) VALUES (NULL, :ip, :id)");
            $q->bindParam(':ip', $ip, PDO::PARAM_STR);
            $q->bindParam(':id', $_USER['id'], PDO::PARAM_INT);
            $q->execute();
        }
    }
} else
$ipBanned = true;
if($ipBanned) {
   exit;
}
}
if ($auth) {
$q = $con->prepare("SELECT * FROM messages WHERE readto = '0' AND user_to = :userId AND deleteto = '0'");
$q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
$q->execute();
$unreadmsg = $q->rowCount();
    if ($_USER['next_tix_reward'] < time()) {
        $dailyreward = 5;
        $nextrew = time() + 86400;
     // i use bindparm btw
        $q = $con->prepare("UPDATE users SET `tix` = `tix` + :dailyreward, `next_tix_reward` = :nextrew WHERE id = :userId");
        $q->bindParam(':dailyreward', $dailyreward, PDO::PARAM_INT);
        $q->bindParam(':nextrew', $nextrew, PDO::PARAM_INT);
        $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
        $q->execute();
    }
// i made it better -copy 2024 
if ($_USER['BC'] == "BuildersClub" && $_USER['next_bc_reward'] < time()) {
        $bcdailyreward = 15;
        $bcnextrew = time() + 86400;
        $stmt = $con->prepare("UPDATE users SET `GOLDBUX` = `GOLDBUX` + :bcdailyreward, `next_bc_reward` = :bcnextrew WHERE id = :userId");
        $q->bindParam(':bcdailyreward', $bcdailyreward, PDO::PARAM_INT);
        $q->bindParam(':bcnextrew', $bcnextrew, PDO::PARAM_INT);
        $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
        $q->execute();
    }
   
    if ($_USER['bantype'] != 'None') {
        $current_page = basename($_SERVER["PHP_SELF"]);
        if (!in_array($current_page, ['Reactivate.php',  'NotApproved.php'])) {
       header("Location: /Membership/NotApproved.aspx");
       exit;
        }
    }
    $permissions = [
        'Administrator' => 1,
        'SuperModerator' => 2,
        'ForumModerator' => 14,
        'ImageModerator' => 15,
        'BuildersClub' => 3,
    ];
    foreach ($permissions as $key => $badgeId) {
        if ($_USER['USER_PERMISSIONS'] == $key || $_USER['BC'] == $key || $_USER['isImageModerator'] == $key) {
            $q = $con->prepare("SELECT * FROM badgesowned WHERE userid = :userId AND badgeid = :badgeId");
            $q->bindParam(':badgeId', $badgeId, PDO::PARAM_INT);
            $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
            $q->execute();

            if ($stmt->rowCount() == 0) {
                $q = $con->prepare("INSERT INTO badgesowned (userid, badgeid) VALUES (:userId, :badgeId)");
                $q->bindParam(':badgeId', $badgeId, PDO::PARAM_INT);
                $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
                $q->execute();
            }
        } else {
            $q = $con->prepare("DELETE FROM badgesowned WHERE userid = :userId AND badgeid = :badgeId");
            $q->bindParam(':badgeId', $badgeId, PDO::PARAM_INT);
            $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
            $q->execute();
        }
    }
    $q = $con->prepare("SELECT * FROM friends WHERE (user_from = :userId AND arefriends = '1') OR (user_to = :userId AND arefriends = '1')");
    $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
    $q->execute();
    $friendcount = $q->rowCount();

    if ($friendcount > 19) {
        $q = $con->prepare("SELECT * FROM badgesowned WHERE userid = :userId AND badgeid = 6");
        $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
        $q->execute();

        if ($q->rowCount() == 0) {
            $q = $con->prepare("INSERT INTO badgesowned (userid, badgeid) VALUES (:userId, 6)");
            $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
            $q->execute();
        }
    } else {
        $q = $con->prepare("DELETE FROM badgesowned WHERE userid = :userId AND badgeid = 6");
        $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
        $q->execute();
    }
    $joinDate = $_USER['join_date'];
    $q = $con->prepare("SELECT DATEDIFF(NOW(), :joinDate) AS dateDiff");
    $q->bindParam(':joinDate', $joinDate, PDO::PARAM_STR);
    $q->execute();
    $dateDiffRow = $q->fetch(PDO::FETCH_ASSOC);
    $yearsDifference = floor($dateDiffRow['dateDiff'] / 365);
    if ($yearsDifference >= 1) {
        $q = $con->prepare("SELECT * FROM badgesowned WHERE userid = :userId AND badgeid = 13");
        $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
        $q->execute();
        if ($stmt->rowCount() == 0) {
            $q = $con->prepare("INSERT INTO badgesowned (userid, badgeid) VALUES (:userId, 13)");
            $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
            $q->execute();
        }
    } else {
        $q = $con->prepare("DELETE FROM badgesowned WHERE userid = :userId AND badgeid = 13");
        $q->bindParam(':userId', $_USER['id'], PDO::PARAM_INT);
        $q->execute();
    }
require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/RCCAPI.php");
$RCCAPI = new RCCAPI("enteryourapikeyherebro");
function timems() {
return floor(microtime(true) * 1000);
}	     
}
$ep = new ExploitPatch();
$ch = new Check();
?>