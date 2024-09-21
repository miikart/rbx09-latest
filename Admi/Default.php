<?php require_once $_SERVER["DOCUMENT_ROOT"]."/api/web/header.php";
if($auth == false) {
    header("Location: /Error/Default.aspx");
    exit();
}

if($_USER['USER_PERMISSIONS'] !== "Administrator"){
    header("Location: /Error/Default.aspx");
    exit();
}


?>
<style>
    #EditProfileContainer {
        background-color: #eeeeee;
        border: 1px solid #000;
        color: #555;
        margin: 0 auto;
        width: 620px;
        text-align: center;
    }
    #EditProfileContainer #AgeGroup, #EditProfileContainer #ChatMode, #EditProfileContainer #PrivacyMode, #EditProfileContainer #EnterEmail, #EditProfileContainer #ResetPassword, #EditProfileContainer #Blurb {
        margin: 0 auto;
        width: 60%;
    }
    #link {
        font-size: 16px;
        margin: 5px;
        display: block;
    }
    #Catagory {
        width:580px;
        border: 1px solid #000;
        margin: 20px auto;
    }
    #title {
        font-size: 23px;
        font-weight: bold;
        margin:5px;
        color: #333;
    }
</style>
<title>GOLDBLOX | Administration</title>
<div id="Body">
    <h2> Disclamer: this admin panel was rushed as fuck and stolen according to copy because he really wanted hat reuploading - mii <h2>
    <h2> I would just recommend making your own. <h2>
    <div id="EditProfileContainer">
        <h2>Administration</h2>
        <div id="Catagory">
            <h2 style="background-color: #CCBBB9;">Moderation</h2>
            <a href="Ban.aspx" id="link">Ban Users</a>
            <a href="Delete.aspx" id="link">Ban Assets</a>
            <a href="AltIdentifier.aspx" id="link">Alt Identifier</a>
        </div>
        <div id="Catagory">
            <h2 style="background-color: #CCC2B9;">Assets</h2>
            <a href="Upload.aspx" id="link">Upload Asset</a>
            <a href="UploadHead.aspx" id="link">Upload Head</a>
            <a href="UploadFace.aspx" id="link">Upload Face</a>
       
            <a href="Approve.aspx" id="link">Approve Assets (<?=$notapproveditemsalready?>)</a>
            <a href="Rerender.aspx" id="link">Rerender Assets</a>
        </div>
        <div id="Catagory">
            <h2 style="background-color: #B9C0CC;">Misc.</h2>
            <a href="ManageBanners.aspx" id="link">Manage Banners</a>
    
        </div>
        <div id="Catagory">
            <h2 style="background-color: #C6B9CC;">Scary Stuff</h2>
            <?php if($sitesettings['offline'] == 'false') { ?>
            <a onclick='document.getElementById("maintenance").hidden = false;' style='color:blue' id="link">Enable Maintenance</a>
          <?php } else { ?>
           <a onclick='document.getElementById("maintenance").hidden = false;' style='color:blue' id="link">Disable Maintenance</a>
          <?php } ?>
            <a href="ClearCacheRCC.aspx" id="link">Clear RCCAPI Cache</a>
        </div>
    </div>
</div>
<div id="maintenance" style="position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(100,100,100,0.25);" hidden>
			<div style="position: absolute; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);">
				<div id="UserBadgesPane" style="width: 440px;box-shadow: gray 5px 5px;">
					<div id="UserBadges">
						<h4><font size="3" face="Comic sans MS">Are you sure that you wanna<?php if($sitesettings['offline'] == 'false') { ?> enable Maintenance?<?php } else { ?> disable Maintenance?<?php } ?>
						</font></h4>
					<?php if($sitesettings['offline'] == 'false') { ?>
					<p style="margin-top: 15px;">Remember! The bypass key is: null</p>
					<?php } else { ?>
						<p style="margin-top: 15px;">This will expose the site to the public.</p>
					<?php } ?>
					
					
						<div style="margin-bottom: 20px;">
						<a  onclick='document.getElementById("maintenance").hidden = true;' class="Button"  href='Maintenance.ashx?enable=<?php if($sitesettings['offline'] == 'false') { ?>1<?php } else { ?>0<?php } ?>'>Yes</a>
							<a class="Button" href="#" onclick='document.getElementById("maintenance").hidden = true;'>No</a>
						
						</div>
						<table cellspacing="0" border="0" align="Center">
						</table>
					</div>
				</div>
			</div>
		</div>
<?php include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>

