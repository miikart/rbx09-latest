<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/header.php';
if($auth == false){
header("Location: /Login/Default.aspx");
exit;
}
$error = null;
if ($_SERVER["REQUEST_METHOD"] == 'POST' && $auth == true && $_REQUEST["csrf_token"]) {
$blurb = $ep->remove(htmlspecialchars(trim($_REQUEST["Blurb"]), ENT_QUOTES, 'UTF-8'));
  if(CSRF::check(trim($_REQUEST["csrf_token"]))) {
  } else {
  $error ='<span style="color:red;">Invaild CSRF Token! Changes were not saved.</span>';  
  }
  if (strlen($blurb) > 1000) {
        $error = '<span style="color:red;">Your blurb must be below 1000 characters.</span>';
  }
  if(!$error) {
  try {
        $q = "UPDATE `users` SET `blurb` = :blurb WHERE `id` = :id";
        $q = $con->prepare($q);
        $q->bindParam(':blurb', $blurb, PDO::PARAM_STR);
        $q->bindParam(':id', $_USER["id"], PDO::PARAM_INT);
        $q->execute();
       context::userlog("{$_USER['username']} (id {$_USER['id']}) changed their blurb to \"$blurb\"");
        if($_REQUEST['SiteFilter']) { 
        $q = "UPDATE `users` SET `filter` = 1 WHERE `id` = :id";
        $q  = $con->prepare($q);
        $q->bindParam(':id', $_USER["id"], PDO::PARAM_INT);
        $q ->execute();
        } else {
        $q = "UPDATE `users` SET `filter` = 0 WHERE `id` = :id";
        $q  = $con->prepare($q);
        $q->bindParam(':id', $_USER["id"], PDO::PARAM_INT);
        $q ->execute();
        }
       
    } catch (PDOException $e) {
        error_log($e->getMessage());
        die("sum broke?? report to copy.aspx". $e->getMessage());
    }
    $success = true;
    $_USER['blurb'] = $blurb;
        if($_REQUEST['SiteFilter']) { 
        $filterenabled = true;
        } else {
        $filterenabled = false;
        }  
  }
}
?>
<form method="POST" action="">

 <div id="Body">
 <div id="EditProfileContainer">
  <h2>Edit Profile</h2>
  
  <?php $csrf = CSRF::generate();  if($csrf) { echo '<input type="hidden" name="csrf_token" value="'. $csrf .'">';  } else {  echo '<div id="Confirmation">CSRF token failed to generate. Changes will not be saved.</div>'; } ?>
  <?php if(!empty($error)) { ?>
 <div id="Confirmation"><?php echo $error ?></div>
        	    <?php } ?>
   <?php if($success) { ?>
 <div id="Confirmation">The changes to your profile have been saved. (<?php echo date("g:i:s A", strtotime("-13 hour")); ?>)</div>
 <?php } ?>
  <div id="ChatMode">
                			<fieldset title="Update your chat mode">
                				<legend>Update your chat mode</legend>
                				<div class="Suggestion">All in-game chat is subject to profanity filtering and moderation. For enhanced safety, choose Menu Chat Mode; only chat from pre-approved menus will be shown to you.</div>
                				<div class="ChatModeRow">
                					<input type="radio" name="ChatMode" id="FilteredChat" value="FilteredChat" checked="true">
                				  	<label for="Filtered">Filtered Chat</label>
                				  	<br>
                				  	<input type="radio" name="ChatMode" id="MenuChat" value="MenuChat">
                				  	<label for="MenuChat">Menu Chat</label>
                				</div>
                			</fieldset>
                		</div>
 <div id="ChatMode">
                			<fieldset title="Update your site filter preferences">
                				<legend>Update your site filter preferences</legend>
                				<div class="Suggestion">User generated on-site text is subject to profanity filtering and moderation. This replaces anything it filters with "<i>####</i>". This is not the same as chat/safety mode.</div>
                				<div class="ChatModeRow">
                					<input type="radio" name="SiteFilter" id="1" value="1" <?php if($filterenabled == true) { ?>checked="true"<?php } ?>>
                				  	<label for="Filtered">On</label>
                				  	<br>
                				  	<input type="radio" name="SiteFilter" id="0" value="0" <?php if($filterenabled == false) { ?>checked="true"<?php } ?>>
                				  	<label for="MenuChat">Off</label>
                				</div>
                			</fieldset>
                		</div>
  <div id="Blurb">
    <fieldset title="Change your password">
      <legend>Change your password</legend>
      <div class="Suggestion">
         Click the button below to change your password.</div>
      <div class="Validators">
      </div><br><center>
        <a href="Password.aspx" >Change Password</a>
    </center><br></fieldset>
  </div>
  <div id="EnterEmail">
		    <fieldset title="Update Email Address">
			    <legend>Update Email Address</legend>
			    <div class="Validators">
				    <div><span id="ctl00_cphRoblox_RegularExpressionValidator2" style="color:Red;display:none;">Please enter a valid email address.</span></div>
				    <div><span id="ctl00_cphRoblox_RequiredFieldValidator1" style="color:Red;display:none;">Email is required.</span></div>
				    <div><span id="ctl00_cphRoblox_CustomValidatorEmail" style="color:Red;display:none;">An account with this email address already exists.</span></div>
			    </div>
			    <div class="EmailRow">
				    <label for="ctl00_cphRoblox_TextBoxEMail" id="ctl00_cphRoblox_LabelEmail" class="Label">Email:</label>&nbsp;<input name="ctl00$cphRoblox$TextBoxEMail" type="text" value="" id="ctl00_cphRoblox_TextBoxEMail" tabindex="4" class="TextBox">
			    </div>
		    </fieldset>
		</div>
  <div id="Blurb">
    <fieldset title="Update your personal blurb">
      <legend>Update your personal blurb</legend>

      <div class="Suggestion">
        Describe yourself here (max. 1000 characters).  Make sure not to provide any details that can be used to identify you outside GOLDBLOX.
      </div>
      <div class="Validators">
      
    
      </div>
      <div class="BlurbRow">
        	<textarea name="Blurb" rows="12" cols="20" id="Blurb" class="MultilineTextBox" style="max-width: 211px;"><?php if(!empty($_USER['blurb'])) { ?><?php echo $ep->remove($_USER['blurb']); ?><?php } ?></textarea>
      </div>
    </fieldset>
 <div class="Buttons">
    <input id="Submit" tabindex="4" class="Button" type="submit" name="descupd" value="Update">&nbsp;<a id="Cancel" tabindex="5" class="Button" href="/User.aspx">Cancel</a>
  </div>
  </form>
  </div>
</div>
</form>
</div>
<?php require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/footer.php'; ?>