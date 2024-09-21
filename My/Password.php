<?php
include $_SERVER["DOCUMENT_ROOT"].'/api/web/config.php';
include $_SERVER["DOCUMENT_ROOT"].'/api/web/header.php';
include $_SERVER["DOCUMENT_ROOT"].'/api/web/nav.php';

if($auth == false){
    $ReturnUrl = htmlspecialchars("//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", ENT_QUOTES, 'UTF-8');
    die(header("Location: /Login/Default.aspx?ReturnUrl=".$ReturnUrl));
}

$message = "";

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(
        isset($_POST["oldPass"]) &&
        isset($_POST["newPass"]) &&
        isset($_POST["newPassConfirm"])
    ) {
        $old = $_POST["oldPass"];
        $new = $_POST["newPass"];
        $newConfirm = $_POST["newPassConfirm"];
        
        // Check if new password is the same as the old password
        if($new === $old) {
            $message = "New password cannot be the same as the old password.";
        } elseif($newConfirm === $new) {
            $id = $_USER["id"];
            $oldPass = mysqli_real_escape_string($link, $old);
            $oldPassQuery = mysqli_query($link, "SELECT password FROM users WHERE id = $id");
            if($oldPassQuery) {
                $userData = mysqli_fetch_assoc($oldPassQuery);
                $hash = $userData["password"];
                if(password_verify($old, $hash)) {
                    $hashPass = password_hash($new, PASSWORD_BCRYPT, [ "cost" => 12 ]);
                    $updateQuery = mysqli_query($link, "UPDATE users SET password = '$hashPass' WHERE id = $id");
                    if($updateQuery) {
                        $message = "You have successfully updated your password.";
                        echo "<div id=\"Body\">
      
  <div class=\"MessageContainer\">
    <div id=\"MessagePane\">
    <div id=\"ctl00_cphGoldblox_pConfirmation\">
    <div id=\"Confirmation\">
      <h3>System</h3>
      <div id=\"Message\"><span id=\"ctl00_cphGoldblox_lConfirmationMessage\">".$message."</span></div>
      <div class=\"Buttons\"><a id=\"ctl00_cphGoldblox_lbContinue\" class=\"Button\" href=\"/my/settings.aspx\">Continue</a></div>
    </div>
    
</div>
  </div>
  <div style=\"clear: both;\"></div>
  </div>
    </div>  <div id=\"Footer\">
<hr>

  <p class=\"Legalese\">   GOLDBLOX, characters, logos, names, and all related indicia are trademarks of <a id=\"ctl00_rbxFooter_hlGoldbloxCorporation\" href=\"/info/About.aspx\">GOLDBLOX Corporation</a>, ©2009. Patents pending.
    <br>GOLDBLOX is not affiliated with Lego, MegaBloks, Bionicle, Pokemon, Nintendo, Lincoln Logs, Yu Gi Oh, K'nex, Tinkertoys, Erector Set, or the Pirates of the Caribbean.
    <br>Use of this site signifies your acceptance of the <a id=\"ctl00_rbxFooter_hlTermsOfService\" href=\"/info/TermsOfService.aspx\">Terms and Conditions</a>.
    <br><a id=\"ctl00_rbxFooter_hlPrivacyPolicy\" href=\"/info/Privacy.aspx\"><b>Privacy Policy</b></a>
    &nbsp;|&nbsp; <a href=\"https://web.archive.org/web/20081016212043/mailto:info@roblox.com\">Contact Us</a>
    &nbsp;|&nbsp; <a id=\"ctl00_rbxFooter_hlAboutGoldblox\" href=\"/info/About.aspx\">About Us</a>
    &nbsp;|&nbsp; <a id=\"ctl00_rbxFooter_HyperLink1\" href=\"http://jobs.roblox.com/\">Jobs</a>
     &nbsp;|&nbsp; <a id=\"ctl00_rbxFooter_HyperLink1\" href=\"http://status.rccs.lol/\">Status</a>
    </p></div>";
exit();
                    } else {
                        $message = "(please report to copy) error updating password: " . mysqli_error($link);
                    }
                } else {
                    $message = "old password is not correct";
                }
            } else {
                $message = "(please report to copy) error fetching old password: " . mysqli_error($link);
         
            }
        } else {
            $message = "confirm new password is not correct";

        }
    }
}

  ?>

<br>

<style>
#EditPasswordContainer {
    background-color: #eeeeee;
    border: 1px solid #000;
    color: #555;
    margin: 0 auto;
    width: 620px;
}

#EditPasswordContainer h2
{
  background-color: #ccc;
  border-bottom: solid 1px #000;
  color: #333;
  font-size: x-large;
  margin: 0;
  text-align: center;
}

#EditPasswordContainer h3
{
  text-align: center;
}

#EditPasswordContainer fieldset
{
  font-size: 1.2em;
  margin: 15px 0 0 0;
}

#EditPasswordContainer .MultilineTextBox
{
     width: 340px; 
}

#EditPasswordContainer .TextBox
{
  vertical-align: middle;
  width: 150px;
}

#EditPasswordContainer .Label
{
  vertical-align: middle;
}

#EditPasswordContainer #AgeGroup,
#EditPasswordContainer #ChatMode,
#EditPasswordContainer #ResetPassword,
#EditPasswordContainer #Blurb
{
    margin: 0 auto;
    width: 60%;
}

#EditPasswordContainer .Buttons
{
    margin: 20px 0 20px 0;
    text-align: center;
}

#EditPasswordContainer .AgeGroupRow,
#EditPasswordContainer .ChatModeRow
{
  font-size: .9em;
  margin: 10px 0 10px 100px;
}

#EditPasswordContainer .ResetPasswordRow
{
  margin: 10px 0;
  text-align: center;
}

#EditPasswordContainer .BlurbRow
{
    padding: 10px 4px 10px 4px;
    text-align: right;
}

#EditPasswordContainer .Legend
{
  color: Blue;
  margin-left: 9px;
}

#EditPasswordContainer .Suggestion
{
  font: normal .8em/normal Verdana, sans-serif;
  padding-left: 9px;
}

#EditPasswordContainer .Validators
{
  margin-left: 9px;
}
</style>



<div id="EditPasswordContainer">
    <h2>Change Password</h2>
    <form method="post" action="">
        <div id="Blurb">

          <fieldset title="Update your password">

           
            <legend>Update your account password</legend>
                <div class="Suggestion">
                    Make sure to use a hard and secure password.
                </div>
                <div class="Validators"></div>
                                      <?php
        if (!empty($message)) {
            echo '<div style="color:red; text-align:right;">'.$message.'</div>';
        }
    ?>
              <div class="BlurbRow">
                    <textarea rows="1" name="oldPass" id="OldPass" tabindex="3" class="MultilineTextBox" placeholder="Enter your old Password."></textarea>
                    <br><br>
                    <textarea rows="1" name="newPass" id="newPass" tabindex="4" class="MultilineTextBox" placeholder="Enter your new Password."></textarea>
                    <br><br>
                    <textarea rows="1" name="newPassConfirm" id="newPassConfirm" tabindex="5" class="MultilineTextBox" placeholder="Confirm your new Password."></textarea>
                </div>
            </fieldset>
        </div>
        <div class="Buttons">
            <input id="Submit" tabindex="6" class="Button" type="submit" name="descupd" value="Update">&nbsp;<a id="Cancel" tabindex="7" class="Button" href="/my/settings.aspx">Cancel</a>
        </div>
    </form>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"].'/api/web/footer.php'; ?>
