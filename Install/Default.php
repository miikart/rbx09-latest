<?php
include $_SERVER["DOCUMENT_ROOT"].'/api/web/header.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/config.php';
?>
<?php
header('Location: /Error/Default.aspx');
exit;
if($_POST["ButtonDownload"]){header("Location: ".$clientdownloadlink);}
if($_POST["ButtonDownloadUri"]){header("Location: #");}

?>
<style>.Navigation{display:none!important;}#Alerts{display:none!important;}#Authentication{display:none!important;}#Settings{display:none!important;}</style>
<div id="Body">
      
  
  
  
  <p id="ctl00_cphGoldblox_SystemRequirements1_OS" align="center" style="color: red">Currently, GOLDBLOX is only available on PCs running the Windows&reg; operating system</p>
  <div>
    
 
    
  </div>
  
  <div style="margin-top: 12px; margin-bottom: 12px">
    <div id="AlreadyInstalled" style="display: none">
      <p>GOLDBLOX is already installed on this computer. If you want to try installing it again then follow the instructions below. Otherwise, you can just <a href="javascript:goBack()">continue</a>.</p>
    </div>
    <img id="ctl00_cphGoldblox_Image3" class="Bullet" src="/images/bullet1V2.png" border="0"/>
    <div id="InstallStep1" style="padding-left: 60px">
      <h2>Download GOLDBLOX</h2>
      <p><form action="" method="POST"><input type="submit" name="ButtonDownload" value="Install GOLDBLOX" id="ctl00_cphGoldblox_ButtonDownload" class="BigButton"/>&nbsp;(Total download about 0.5mb)</form></p>
    </div>
    <img id="ctl00_cphGoldblox_Image4" class="Bullet" src="/images/bullet2V2.png" border="0"/>
    <div id="InstallStep2" style="padding-left: 60px">
      <h2>Run the Installer</h2>
      <p>A window will open asking what you want to do with a file called Setup.exe.</p>
      <p>Click 'Run'. You might see a confirmation message, asking if you're sure you want to run this software. Click 'Run' again.</p>
      <p><img id="ctl00_cphGoldblox_Image1" src="" width="300px" border="0"/></p>
    </div>
    <img id="ctl00_cphGoldblox_Image5" class="Bullet" src="/images/bullet3V3.png" border="0"/>
    <div id="InstallStep3" style="padding-left: 60px">
      <h2>Wait for the installer to complete</h2>
      <p>The new GOLDBLOX Bootstrapper will do all of the work for you, just wait for it to complete.</p>
      <p><img id="ctl00_cphGoldblox_Image2" src="" width="300px" border="0"/></p>
    </div>
    <img id="ctl00_cphGoldblox_Image5" class="Bullet" src="http://rccs.lol/api/itemThumb.php?id=76" width="50px" height="50px" border="0"/>
    <div id="InstallStep3" style="padding-left: 60px">
      <h2>Join a game</h2>
      <p>When joining a GOLDBLOX game, you will be asked to allow the program to run (different for each browser), click allow.</p>
      <p><img id="ctl00_cphGoldblox_Image2" src="" width="300px" border="0"/></p>
      <b style="color:red;">Be careful, never show your account code anywhere!</b>
    </div>
    <img id="ctl00_cphGoldblox_Image5" class="Bullet" src="http://rccs.lol/api/itemThumb.php?id=209" width="50px" height="50px" border="0"/>
    <div id="InstallStep3" style="padding-left: 60px">
      <h2>Wait for the client to start</h2>
      <p>The Bootstrapper will tell you, when the client has started, after that just wait for a few seconds (depends on your hardware) and start having fun!.</p>
      <p><img id="ctl00_cphGoldblox_Image2" src="" width="300px" border="0"/></p>
    </div>
  </div>
  <script type="text/javascript">
    function isInstalled()
    {
    try
    { 
      var robloxClient = new ActiveXObject("GoldbloxInstall.Updater"); 
      return true;
    }
    catch (e)
    { 
      return false;
    } 
    }
    function goBack()
    {
     window.history.back();
    }
  function checkInstall() 
  { 
    if (isInstalled())
    { 
    // If we didn't fail, then we can move on
    document.getElementById("ctl00_cphGoldblox_ButtonDownload").disabled = true;
    urchinTracker("InstallSuccess");
        Goldblox.Install.Service.InstallSucceeded();
    goBack();
    }
    else
    {
    // Try again later 
    window.setTimeout("checkInstall()", 2000); 
    } 
  } 
  </script>
  <script type="text/javascript">
  if (isInstalled())
  {
    AlreadyInstalled.style.display="block";
  }
  else
  {
    window.setTimeout("checkInstall()", 1000);
  }
  </script>
    </div>
<?
include '../api/web/footer.php';
?>
