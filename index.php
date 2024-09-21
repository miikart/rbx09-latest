<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/config.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/header.php");
$errorMsg = null;
if($_SERVER["REQUEST_METHOD"] === "POST"  && $auth == false && isset($_REQUEST["csrf_token"])) {
    if(CSRF::check($_REQUEST["csrf_token"])) {
    $username = htmlspecialchars($_REQUEST['text']);
    $password = htmlspecialchars($_REQUEST['password']);

    if (empty($username) || empty($password)) {
      $errorMsg = "<div style='color:red; text-align:center;'>Username or password incorrect</div>";
    } else {
      $sql = "SELECT id, username, password FROM users WHERE username = :username";
$stmt = $con->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $sessKey = generateSessionCookie();
    $id = $user["id"];
                     $time = time();
$expiretime = date('Y-m-d H:i:s', $time + (7 * 24 * 60 * 60));
                            
                            $q = $con->prepare("INSERT INTO sessions (sessKey, userId, created, expiretime) VALUES (:sessKey, :userId, :created, :expiretime)");
                            $q->bindParam(":sessKey", $sessKey, PDO::PARAM_STR);
                            $q->bindParam(":userId", $id, PDO::PARAM_INT);
                            $q->bindParam(":created", $time, PDO::PARAM_INT);
                         $q->bindParam(":expiretime", $expiretime, PDO::PARAM_STR);
                            $q->execute();
    setSessionCookie($sessKey);
    header("Location: /");
    exit();
            exit;
        } else {
           $errorMsg = "<div style='color:red; text-align:center;'>Username or password incorrect</div>";
      }
    }
    } else {
     $errorMsg = "<div style='color:red; text-align:center;'>Invaild CSRF Token! please refresh.</div>";   
    }
}
?>
<title>GOLDBLOX: A FREE Virtual World-Building Game with Avatar Chat, 3D Environments, and Physics</title>
  <div id="Body">
      
  <div id="SplashContainer">
  <div id="SignInPane">
    
<div id="LoginViewContainer">
  
    <div id="LoginView">
    <?php if($auth == true) {?><h5>Logged In</h5>
  <div id="AlreadySignedIn">
   <a href="/User.aspx">  
  <img src="/Thumbs/Avatar.ashx?assetId=<?= $_USER['id'] ?>" border="0" id="img" alt="<?= $_USER['username'] ?>">
    </a>  
  <?php } else { ?><h5>Member Login</h5>
    
    <div class="AspNet-Login">
      <div class="AspNet-Login"><?php error_reporting(0);

 

?>   <form method="POST" action="">
           <?php if(!empty($errorMsg)) { ?>
            <?php echo $errorMsg ?> 
     <?php } ?>
      <input type="hidden" name="csrf_token" value="<?php echo CSRF::generate(); ?>">
      <div class="AspNet-Login">
						<div class="AspNet-Login">
							<div class="AspNet-Login-UserPanel">
							<label for="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_UserName" id="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_UserNameLabel" class="Label">Character Name</label>
                <input name="text" type="text" id="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_UserName" tabindex="1"  value='<?php if(!empty($errorMsg)) { ?><?php echo $username ?><?php } ?>'class="Text">
              </div>
             
              <div class="AspNet-Login-PasswordPanel">
                <label for="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_Password" id="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_PasswordLabel" class="Label">Password</label>
                <input name="password" type="password" id="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_Password" tabindex="2" class="Text">
              </div>
							<!--div class="AspNet-Login-RememberMePanel"-->
								
							<!--/div-->
							<div class="AspNet-Login-SubmitPanel">
								<button id="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_Login" tabindex="4" class="Button" href="javascript:__doPostBack('ctl00$cphGOLDBLOX$rbxLoginView$lvLoginView$lSignIn$Login','')">Login</button>
							</div>
							
							<div id="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_RegisterDiv" align="center">
								<br/>
								<a id="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_Register" tabindex="5" class="Button" href="/Login/NewAge.aspx">Register</a>
							</div>
							
							<div align="center">
							    <br/>
							    <a id="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_ParentLogin" tabindex="6" class="Button" href="javascript:__doPostBack('ctl00$cphGOLDBLOX$rbxLoginView$lvLoginView$lSignIn$ParentLogin','')">Parent Login</a>
							</div>
							                         
							<div class="AspNet-Login-PasswordRecoveryPanel">
								<a id="ctl00_cphGOLDBLOX_rbxLoginView_lvLoginView_lSignIn_hlPasswordRecovery" tabindex="6" href="Login/ResetPasswordRequest.aspx">Forgot your password?</a>
							</div>
</form>
</div>
   </div></div>
    
<?php } ?>
      
</div>
    </div>
  
</div>

      
  <script>       
    function getnews() {
       
    $.post("/api/blognews.php", {
        })
        .done(function(data) {
            $("#NEWSPANEL").html(data);
        })
        .fail(function() {
            $("#NEWSPANEL").html("failed to fetch contact copy.floppy");
        });
}     
 getnews(0);
 </script> 
  <?php if($auth == false) {?>
<div id="ctl00_cphGOLDBLOX_LoginView1_pFigure">
<div id="Figure"><a id="ctl00_cphGOLDBLOX_LoginView1_ImageFigure" disabled="disabled" title="Figure" onclick="return false" style="display:inline-block;"><img src="/images/NewFrontPageGuy.png" alt="Figure" blankurl="http://t1.GOLDBLOX.com:80/blank-115x130.gif" border="0"></a></div><br>
<?php }else{ ?>
<br>
<div style="text-align:center; background-color:#eeeeee; border:1px solid black; width:148px; height:246px;">
  <br>
  <h3 style="color: gray;">GOLDBLOX News</h3>
<div id='NEWSPANEL'>
Loading news..
    
</div>

</div>

<?php }?>   
 
  <?php if($auth == false) {?>      
</div>  
    <?php } ?>  
  </div>
 <div id="GOLDBLOXAtAGlance">
	<h2>GOLDBLOX Virtual Playworld</h2>
	<h3>GOLDBLOX is Free!</h3>
	<ul id="ThingsToDo">
		<li id="Point1">
			<h3>Build your personal Place</h3>
			<div>Create buildings, vehicles, scenery, and traps with thousands of virtual bricks.</div>
		</li>
		<li id="Point2">
			<h3>Meet new friends online</h3>
			<div>Visit your friend's place, chat in 3D, and build together.</div>
		</li>
		<li id="Point3">
			<h3>Battle in the Brick Arenas</h3>
			<div>Play with the slingshot, rocket, or other brick battle tools.  Be careful not to get "bloxxed".</div>
		</li>
	</ul>
	<div id="Showcase" onload="MM_CheckFlashVersion('8,0,0,0','Content on this page is awesome and requires a newer version of Macromedia Flash Player. Do you want to download it now?');">

        <div   id="ctl00_cphGOLDBLOX_GOLDBLOXAtAGlanceLoginView_GOLDBLOXAtAGlance_Anonymous_NewPlayer">
<iframe  width="395" height="326" src="https://www.youtube.com/embed/5D3crqpClPY?si=I0xZgcZpCAg7GVQT" allowfullscreen></iframe>
</div>
    </div>
	<div id="Install">
	
		<div id="DownloadAndPlay"><a id="ctl00_cphGOLDBLOX_GOLDBLOXAtAGlanceLoginView_GOLDBLOXAtAGlance_Anonymous_hlDownloadAndPlay" href="Games.aspx"><img src="/images/PlayNowRedBlinker.gif" alt="FREE - Download and Play!" border="0"/></a></div>
	</div>
	
	<div id="ForParents">
		<a id="ctl00_cphGOLDBLOX_GOLDBLOXAtAGlanceLoginView_GOLDBLOXAtAGlance_Anonymous_hlKidSafe" title="GOLDBLOX isn't kid-safe!" href="Parents.aspx" style="display:inline-block;"><img title="GOLDBLOX isn't kid-safe!" style="height:125px;" src="/images/realcoppaseal.png" border="0"/></a>  
	</div>
	<div id="PrivPolicy" style="font-size:large;">
	        <a id="ctl00_cphGOLDBLOX_GOLDBLOXAtAGlanceLoginView_GOLDBLOXAtAGlance_Anonymous_hlPrivacyPolicy" href="Info/Privacy.aspx" style="display:inline-block;">Privacy Policy</a>
	        <a id="ctl00_cphGOLDBLOX_GOLDBLOXAtAGlanceLoginView_GOLDBLOXAtAGlance_Anonymous_hlTruste" href="http://www.truste.org/ivalidate.php?url=www.GOLDBLOX.com&amp;sealid=105" style="display:inline-block;"><img src="/images/truste_seal_kids.gif" border="0"/></a>
	    </div>
</div>
		    
		
<?php
    $sql = "SELECT f.itemid AS ID, COUNT(*) AS favorites_count, c.name AS game_name
            FROM favorites AS f
            JOIN catalog AS c ON f.itemid = c.ID
            WHERE f.type = 'place' AND c.type = 'place'
            GROUP BY f.itemid
            ORDER BY favorites_count DESC
            LIMIT 5";

    $stmt = $con->prepare($sql);
    $stmt->execute();

    $sigmaids333 = [];

    if ($stmt->rowCount() > 0) {
        while ($skibiditoiletismgabog = $stmt->fetch()) {
            
            $sigmaids333[] = htmlspecialchars($skibiditoiletismgabog["ID"]);
        }
    } else {
    
        $sigmaids333[] = 33243432;
    }

    $con = null;
?>

<div id="ctl00_cphGOLDBLOX_CoolPlaces_FlashContent">
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
            codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0"
            width="900" height="100"  autoplay="true" id="CoolPlaces" align="middle">
        <param name="movie" value="/images/CoolPlaces.swf?place1=<?php echo $sigmaids333[0]; ?>&amp;place2=<?php echo $sigmaids333[1]; ?>&amp;place3=<?php echo $sigmaids333[2]; ?>&amp;place4=<?php echo $sigmaids333[3]; ?>&amp;place5=<?php echo $sigmaids333[4]; ?>&amp;bounce=true&amp;subdomain=/"/>
       
    </object>
</div></div></div>
<?php
include 'api/web/footer.php';
?>

