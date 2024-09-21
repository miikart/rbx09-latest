<?php include '../api/web/config.php'; ?>
<script>document.title = "<?=$sitename;?> Studio"</script>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link id="ctl00_Imports" rel="stylesheet" type="text/css" href="/AllCSSnew.css"/><link id="ctl00_Favicon" rel="Shortcut Icon" type="image/ico" href="/favicon.ico"/><meta name="author" content="<?=$sitename?> Corporation"/><meta name="keywords" content="game, video game, building game, construction game, online game, LEGO game, LEGO, MMO, MMORPG, virtual world, avatar chat"/><meta name="robots" content="all"/></head>
<center><br><br><br><br><div id="LoginView">
		<?php if($auth == 'yes') {echo '<h5>Logged In</h5>
  <div id="AlreadySignedIn">
		  <a title="'.$_USER['username'].'" href="/User.aspx" style="display:inline-block;height:190px;width:152px;cursor:pointer;"><img src="/api/avatar/getthumb.php?id='.$_USER['id'].'" style="display:inline-block;margin-top:15px;" border="0" id="img" height="150px" alt="'.$_USER['username'].'"></a>
		';} else echo '<h5>Member Login</h5>
		
		<div class="AspNet-Login">
			<div class="AspNet-Login"><form method="POST" action="/IDE/Login.aspx">
			  <div class="AspNet-Login-UserPanel">
				<label for="ctl00_cphGoldblox_rbxLoginView_lvLoginView_lSignIn_UserName" id="ctl00_cphGoldblox_rbxLoginView_lvLoginView_lSignIn_UserNameLabel" class="Label">Character Name</label>
				<input name="username" type="text" id="ctl00_cphGoldblox_rbxLoginView_lvLoginView_lSignIn_UserName" tabindex="1" class="Text">
			  </div>
			  <div class="AspNet-Login-PasswordPanel">
				<label for="ctl00_cphGoldblox_rbxLoginView_lvLoginView_lSignIn_Password" id="ctl00_cphGoldblox_rbxLoginView_lvLoginView_lSignIn_PasswordLabel" class="Label">Password</label>
				<input name="password" type="password" id="ctl00_cphGoldblox_rbxLoginView_lvLoginView_lSignIn_Password" tabindex="2" class="Text">
			  </div>
			  <!--div class="AspNet-Login-RememberMePanel"-->
				
			  <!--/div-->
			  <div class="AspNet-Login-SubmitPanel">
<div class="AspNet-Login-SubmitPanel" style="padding-bottom: 12px!important;">
			  <button tabindex="3" class="Button" type="submit" name="Login">Login</button>
			</div>
			  </div>
			</div>'; ?><br>Welcome to <?=$sitename?> Studio, where you can build things. You also can host here. It's Better loading an RBXL from your computer, but if you want to create a place in the studio, feel free to do it by inserting a part!
  </center>
  