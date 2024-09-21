

        <?php 
     header("Location: /Membership/CreationDisabled.aspx");
exit;
      $onlybanner = true;
          include '../api/web/header.php';
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = $key_err = "";

if($auth == true){
header("Location: /Default.aspx");
exit();
}  


if(isset($_REQUEST['Age'])) {
  if($_REQUEST['Age'] == "Under13")  {
exit(header("location: http://www.google.com"));   
  }
if($_REQUEST['Age'] != "Over12") {   
    exit(header("location: /Error/Default.aspx"));    
}
} else {
    exit(header("location: /Error/Default.aspx"));
}

if($_SERVER["REQUEST_METHOD"] == "POST" ){
    if(empty(trim($_POST["username"]))){
    $username_err = "<div style='color:red; text-align:right;'>Please enter a username.</div>";
} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
    $username_err = "<div style='color:red; text-align:center;'>Username can only contain letters, numbers, and underscores.</div>";
} elseif(strlen(trim($_POST["username"])) < 3){
    $username_err = "<div style='color:red; text-align:right;'>Username must be at least 3 characters.</div>";
} elseif(strlen(trim($_POST["username"])) > 20){
    $username_err = "<div style='color:red; text-align:right;'>Username must be under 20 characters.</div>";
} else {
    $sql = "SELECT id FROM users WHERE LOWER(username) = :username";
    
    if($stmt = $con->prepare($sql)){
        $param_username = strtolower(trim($_POST["username"]));
        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        
        if($stmt->execute()){
            if($stmt->rowCount() != 0){
                $username_err = "<div style='color:red; text-align:right;'>This username is already taken.</div>";
            } else {
                $username = trim($_POST["username"]);
            }
        } else {
            echo "<div style='color:red; text-align:right;'>Oops! Something went wrong. Please try again later.</div>";
        }
        unset($stmt);
    }
}
  
// Validate password
if(empty(trim($_POST["password"]))){
    $password_err = "<div style='color:red; text-align:right;'>Please enter a password.</div>";
} elseif(strlen(trim($_POST["password"])) < 6){
    $password_err = "<div style='color:red; text-align:right;'>Password must have at least 6 characters.</div>";
} elseif(strlen(trim($_POST["password"])) > 20){
    $password_err = "<div style='color:red; text-align:right;'>Password must be under 20 characters.</div>";
    
} else {
    $password = trim($_POST["password"]);
}



require_once $_SERVER["DOCUMENT_ROOT"]."/ReCaptcha.php";
if (isset($_POST["g-recaptcha-response"])) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}

if ($response != null && $response->success) {
    $captcha_completed = true;
} else {
    $captcha_completed = false;
    $captchaerror = "<div style='color:red; text-align:right;'>Invalid Captcha</div>";
}

if(empty(trim($_POST["confirm_password"]))){
    $confirm_password_err = "<div style='color:red; text-align:right;'>Please confirm password.</div>";    
} else {
    $confirm_password = trim($_POST["confirm_password"]);
    if(empty($password_err) && ($password != $confirm_password)){
        $confirm_password_err = "<div style='color:red; text-align:right;'>Password did not match.</div>";
    }
}



if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($captchaerror)){

    $accountcode = generateRandomString();

    $sql = "INSERT INTO users (username, password, accountcode) VALUES (:username, :password,  :accountcode)";
    if($stmt = $con->prepare($sql)){
        $param_username = $username;
        $param_password =  password_hash($password, PASSWORD_BCRYPT, [ "cost" => 12 ]);
       
     
        $param_accountcode = $accountcode;

        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
       
        $stmt->bindParam(":accountcode", $param_accountcode, PDO::PARAM_STR);

        if($stmt->execute()){
         
            $newUserId = $con->lastInsertId();

    
          $source2 = $_SERVER["DOCUMENT_ROOT"] . "/images/prerendersmall.png"; 
$destination2 = $_SERVER["DOCUMENT_ROOT"] . "/Thumbs/USERS/" . $newUserId . "-small.png";
$source = $_SERVER["DOCUMENT_ROOT"] . "/images/prerender.png"; 
$destination = $_SERVER["DOCUMENT_ROOT"] . "/Thumbs/USERS/" . $newUserId . ".png";
$source3 = $_SERVER["DOCUMENT_ROOT"] . "/images/prerendersquare.png"; 
$destination3 = $_SERVER["DOCUMENT_ROOT"] . "/Thumbs/USERS/" . $newUserId . "-square.png";
$copySuccess1 = copy($source, $destination);
$copySuccess2 = copy($source2, $destination2);

if (!$copySuccess1 || !$copySuccess2 || !$copySuccess2) {
    echo "<div style='color:red; text-align:right;'>Oops! Something went wrong. Please try again later.</div>";
            } else {
              
                $sql = "SELECT id, username, password FROM users WHERE username = :username";
                if($stmt = $con->prepare($sql)){
                    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                    $stmt->execute();
                    if($user = $stmt->fetch(PDO::FETCH_ASSOC)){
                        if(password_verify($password, $user['password'])){
                            $sessKey = generateRandomString();
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
                           context::userlog("{$username} has registered on GOLDBLOX.");

                            header('Location: /Games.aspx');
                        exit;
                            
                        }
                    }
                }
            }
        } else {
            echo "<div style='color:red; text-align:right;'>Oops! Something went wrong. Please try again later.</div>";
        }
        unset($stmt);
    }
}}
unset($con);
?>
         
          <form method="POST" action="<?php $_SERVER["PHP_SELF"]; ?>">
         <div id="Registration">
      <div id="ctl00_cphGOLDBLOX_upAccountRegistration">
  
          <h2>Create a Free GOLDBLOX Account</h2>
          <h3>Welcome to our really quick signup</h3>
           
          <div id="EnterUsername">
       
            <fieldset title="Choose a name for your GOLDBLOX character">
             
              <legend>Choose a name for your GOLDBLOX character</legend>
              <div class="Suggestion">
                Use 3-20 alphanumeric characters: A-Z, a-z, 0-9, no spaces. Please do not use your name or any other information that identifies you in real life.
              </div><?php if(!empty($username_err)){
   echo "<div class='Suggestion'>".$username_err."</div>";
} ?>    <br>    <br>
              <div class="Validators">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
              </div>
              <div class="PasswordRow">
                <label for="ctl00_cphGOLDBLOX_UserName" id="ctl00_cphGOLDBLOX_UserNameLabel" class="Label">Character Name:</label>&nbsp;<input name="username" type="text" id="ctl00_cphGOLDBLOX_UserName" tabindex="1" class="TextBox"/>
              </div>
            </fieldset>
          </div>
          <div id="EnterPassword">
            <fieldset title="Choose your GOLDBLOX password">
              <legend>Choose your GOLDBLOX password</legend>
              <div class="Suggestion">
           6-20 characters, no spaces. At least 4 letters and 2 numbers. This is the KEY to your account. Don't pick something obvious like "password", "asdf", or "qwerty".
              </div><?php if(!empty($password_err)){      echo "<div class='Suggestion'>".$password_err."</div>";    } ?><?php if(!empty($confirm_password_err)){      echo "<div class='Suggestion'>".$confirm_password_err."</div>";    } ?>
              <div class="Validators">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
              </div>
              <div class="PasswordRow">
                <label for="ctl00_cphGOLDBLOX_Password" id="ctl00_cphGOLDBLOX_LabelPassword" class="Label">Password:</label>&nbsp;<input name="password" type="password" id="ctl00_cphGOLDBLOX_Password" tabindex="2" class="TextBox"/>
              </div>
              <div class="ConfirmPasswordRow">
                <label for="ctl00_cphGOLDBLOX_TextBoxPasswordConfirm" id="ctl00_cphGOLDBLOX_LabelPasswordConfirm" class="Label">Confirm Password:</label>&nbsp;<input name="confirm_password" type="password" id="ctl00_cphGOLDBLOX_TextimotBoxPasswordConfirm" tabindex="3" class="TextBox"/>
              </div>
            </fieldset>
          </div>
        
            
         
         
          <div id="EnterPassword">
            <fieldset title="reCAPTCHA">
              <legend>reCAPTCHA</legend>
              <div class="Suggestion">
               Robots cant play GOLDBLOX!
              </div><?php if(!empty($captchaerror)){      echo "<div class='Suggestion'>".$captchaerror."</div>";    } ?>
              <div class="Validators">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
              </div>
              <div class="g-recaptcha" data-sitekey="6LfMYiQqAAAAAJV_8iDCPpfFoXJk8oXwK6p8dm-i"></div>
            </fieldset>
          </div>
          <div class="Confirm">
            <input type="submit" name="ctl00$cphGOLDBLOX$ButtonCreateAccount" value="Sign Up!" id="ctl00_cphGOLDBLOX_ButtonCreateAccount" tabindex="5" class="BigButton"/>
          </div></form>
        
</div>
    </div>
    <div id="Sidebars">
      <div id="AlreadyRegistered">
        <h3>Already Registered?</h3>
        <p>If you just need to login, go to the <a id="ctl00_cphGOLDBLOX_HyperLinkLogin" href="/Login/Default.aspx">Login</a> page.</p>
        <p>If you have already registered but you still need to download the game installer, go directly to <a id="ctl00_cphGOLDBLOX_HyperLinkDownload" href="/Install/Default.aspx">download</a>.</p>
      </div>
      <div id="TermsAndConditions">
        <h3>Terms &amp; Conditions</h3>
        <p>Registration does not provide any guarantees of service. See our <a id="ctl00_cphGOLDBLOX_HyperLinkToS" href="/info/TermsOfService.aspx" target="_blank">Terms of Service</a> and <a id="ctl00_cphGOLDBLOX_HyperLinkEULA" href="/info/EULA.htm" target="_blank">Licensing Agreement</a> for details.</p>
        <p>GOLDBLOX will not share your email address with 3rd parties. See our <a id="ctl00_cphGOLDBLOX_HyperLinkPrivacy" href="/info/Privacy.aspx" target="_blank">Privacy Policy</a> for details.</p>
    <a id="ctl00_cphGoldblox_hlTruste" href="http://www.truste.org/ivalidate.php?url=www.roblox.com&amp;sealid=105" style="display:inline-block;width:140px;"><img src="/images/truste_seal_kids.gif" alt="" style="border-width:0px;"></a>
    
      </div>
    </div>
    <div id="ctl00_cphGOLDBLOX_ie6_peekaboo" style="clear: both"></div>
        </div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                <?php
include '../api/web/footer.php';
?>