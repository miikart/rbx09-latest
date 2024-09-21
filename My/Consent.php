<?php
$onlybanner = true;
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/header.php';
if (isset($_COOKIE['consent']) && $_COOKIE['consent'] === 'true') {
    header('Location: /');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agree'])) {
setcookie('consent', 'true', time() + (10 * 365 * 24 * 60 * 60), '/');
    header('Location: /');
    exit();
}
?>
<title>GOLDBLOX - Consent</title>
        <div id="Body">
            <div style="text-align:center; font-size:1.5em;">
               
                <br/>
                On GOLDBLOX, our mission is to provide the most authentic old ROBLOX experience as possible, recreated from the archived pages from January 2009. To have access to the website, you need to agree to the following:<br/>
                <ul style="text-align:left;font-size:0.75em;">
                    <li>You understand that we are not affiliated with ROBLOX in any way, shape or form. This is NOT a ROBLOX private server, but only as a working "recreation" of the 2009 website.</li>
                    <li>To have an account, we require your IP address, username, password (which is hashed using BCRYPT). We store this information for account verification purposes and for other features.</li>
                    <li>You are thirteen (13) years old or over.</li>
                  <li>Most assets are from ROBLOX.</li>
                </ul>
                <br/>
                Do you agree?
                <br/><br/>
                <form method="post" style="display:inline;">
                    <button type="submit" class="Button" name="agree">Yes</button>
                </form>&nbsp;
               <a href='http://google.com'>
                <button class="Button"  style="display:inline;">No</button>
               </a>
            </div>
        </div>
    </div>
</div>
<br>
<?php require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/footer.php'; ?>