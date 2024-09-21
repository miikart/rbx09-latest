<?php
$onlybanner = true;
require_once '../api/web/header.php';
$errorMsg = null;
if ($auth) {

    header("Location: /Default.aspx"); 
  
exit;
}
 if($_SERVER["REQUEST_METHOD"] === "POST"  && $auth == false && isset($_REQUEST["csrf_token"])) {
    if(CSRF::check($_REQUEST["csrf_token"])) {
    $username = htmlspecialchars($_REQUEST['UserName']);
    $password = htmlspecialchars($_REQUEST['Password']);

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
<div id="Body">
<div id="FrameLogin" style="margin: 50px auto 150px auto; width: 500px; border: black thin solid; padding: 21px; z-index: 8; background-color: white;">
    <div id="PaneNewUser">
      <h3>New User?</h3>
      <p>You need an account to play GOLDBLOX.</p>
      <p>If you aren't a GOLDBLOX member then <a id="ctl00_cphGoldblox_HyperLink1" href="/Login/NewAge.aspx">register</a>. It's easy and we do <em>not</em> share your personal information with anybody.</p>
    </div>
    <div id="PaneLogin">
   
      <h3>Log In</h3>
   
      <?= $errorMsg; ?>
<div class="AspNet-Login"><div style="color:red; text-align:center;"></div><form method="POST" action="<?php $_SERVER["PHP_SELF"]; ?>">
       <input type="hidden" name="csrf_token" value="<?php echo CSRF::generate(); ?>">
  <div class="AspNet-Login-UserPanel">
    <label for="ctl00_cphGoldblox_lGoldbloxLogin_UserName" class="TextboxLabel"><em>U</em>ser Name:</label>
    <input type="text" id="ctl00_cphGoldblox_lGoldbloxLogin_UserName" name="UserName" value="<?php if(!empty($username)) {  ?>
<?php echo $username ?>
<?php } ?>
" accesskey="u">&nbsp;
  </div>
  <div class="AspNet-Login-PasswordPanel">
    <label for="ctl00_cphGoldblox_lGoldbloxLogin_Password" class="TextboxLabel"><em>P</em>assword:</label>
    <input type="password" id="ctl00_cphGoldblox_lGoldbloxLogin_Password" name="Password" value="" accesskey="p">&nbsp;
  </div>
  <div class="AspNet-Login-SubmitPanel">
    <input type="submit" value="Log In" id="ctl00_cphGoldblox_lGoldbloxLogin_LoginButton" name="ctl00$cphGoldblox$lGoldbloxLogin$LoginButton" onclick="WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl00$cphGoldblox$lGoldbloxLogin$LoginButton&quot;, &quot;&quot;, true, &quot;ctl00$cphGoldblox$lGoldbloxLogin&quot;, &quot;&quot;, false, false))">
  </div>
  <div class="AspNet-Login-PasswordRecoveryPanel">
    <a href="ResetPasswordRequest.aspx" title="Password recovery">Forgot your password?</a>
  </div>
  </form>
</div>
    </div>
  </div>
                               
<?php
require_once($_SERVER['DOCUMENT_ROOT']."/api/web/footer.php");
?>
</div>
