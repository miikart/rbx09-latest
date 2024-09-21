<?php require_once($_SERVER['DOCUMENT_ROOT']."/api/web/config.php"); ?>
<center>
 <div id="Footer">
    <hr>
   <div class="FooterNav">
        <a id="ctl00_rbxFooter_hlPrivacyPolicy" href="/Info/Privacy.aspx"><b>Privacy Policy</b></a>
        &nbsp;|&nbsp; 
        <a id="ctl00_rbxFooter_hlAdvertise" href="http://sales.roblox.com/">Advertise with Us</a>
        &nbsp;|&nbsp; 
        <a href="mailto:info@roblox.com">Contact Us</a> 
        &nbsp;|&nbsp;
        <a id="ctl00_rbxFooter_hlAboutGoldblox" href="/Info/About.aspx">About Us</a>
        &nbsp;|&nbsp;
        <a id="ctl00_rbxFooter_HyperLink1" href="http://jobs.roblox.com/">Jobs</a>
          &nbsp;|&nbsp;
        <a id="ctl00_rbxFooter_HyperLink1" href="/Info/Credits.aspx">Credits</a>
    </div>
    <hr>
     
      <p class="Legalese">
        GOLDBLOX, "Online Building Toy", characters, logos, names, and all related indicia are trademarks of
        <a id="ctl00_rbxFooter_hlGoldbloxCorporation" href="/Info/About.aspx">ROBLOX Corporation</a>,
        ©2009. Patents pending.
        <br>
        GOLDBLOX. is not affiliated with the LEGO Group, MegaBloks, Pokemon, Nintendo,
        Lincoln Logs, Yu Gi Oh, K'nex, Tinkertoys, Erector Set, or the Pirates of the Caribbean.
        ARrrr! The LEGO Group’s position with regard to intellectual property is stated
        <a id="ctl00_rbxFooter_HyperLink2" href="/Info/Disclaimer.aspx">here</a>
        .<br>
        Use of this site signifies your acceptance of the
        <a id="ctl00_rbxFooter_hlTermsOfService" href="/Info/TermsOfService.aspx">Terms and Conditions</a>.
        <br>
    </p>
</div>
</center>
<title>GOLDBLOX: A FREE Virtual World-Building Game with Avatar Chat, 3D Environments, and Physics</title>
<?php if( !isset($_COOKIE["consent"]) || empty($_COOKIE["consent"]) || $_COOKIE['consent'] !== '1' ) { ?>
<style>#consentdialog {
 position:fixed;
 text-align:center;
 font-size:1.5em;
 bottom:-100%;
 left:0;
background:white;
 border-top:1px solid #000;
 transition:all .75s;
 width:100%;
 color:#000
}</style>
<div id="consentblock" style="display: block;transition:all 0.5s;position: fixed;z-index: 1;left: 0;top: 0;width: 100%;height: 100%;overflow: auto;background-color: rgba(0,0,0,0.5);opacity:0;">
    <script>
        $(function() 
        {
            $("#consentblock").css("opacity", "1");
            setTimeout(function() { $("#consentdialog").css("bottom", "0"); }, 750);
        });
        function accept() 
        {
            document.cookie = "consent=1; expires=<?php echo date('D, d M Y H:i:s \U\T\C', time() + 31536000); ?>; path=/";
            $("#consentdialog").css("bottom", "-100%");
            setTimeout(function() { $("#consentblock").remove(); }, 750);
        }
    </script>
    <div id="consentdialog">
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;"></th>
                    <th style="width:auto;"></th>
                    <th style="width: 128px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                     <img src="/images/realcoppaseal.png" style="width: 100%;height: 161px;">
                    </td>
                    <td id="consentinfo">
                         On GOLDBLOX, our mission is to provide the most authentic old ROBLOX experience as possible, recreated from the archived pages from January 2009. To have access to the website, you need to agree to the following:<br>
                        <ul style="text-align:left;font-size:0.75em;">
                         <li>You understand that we are not affiliated with ROBLOX in any way, shape or form. This is NOT a ROBLOX private server, but only as a working "recreation" of the 2009 website.</li>
                    <li>To have an account, we require your IP address, username, password (which is hashed using BCRYPT). We store this information for account verification purposes and for other features.</li>
                    <li>You are thirteen (13) years old or over.</li>
                     <li>Most assets are from ROBLOX.</li>
                   
                    </ul>
                    </td>
                    <td id="consentbtns">
                        Do you agree?
                        <br><br>
                        <a href="http://google.com">
                        <button class="Button" style="display:inline;">No</button>
                        </a>
                        <br><br><button class="Button" name="agree" onclick="accept()">Yes</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>