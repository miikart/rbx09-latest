

    <?php 
$onlybanner = true;
          include '../api/web/header.php';

if($auth != false){
header("Location: /Default.aspx");
exit();
}


?>
        
          
         <div id="Registration">
      <div id="ct900_cphGOLDBLOX_upAccountRegistration">
  
          <h2>Create a Free GOLDBLOX Account</h2>
          <h3>How old are you?</h3>
          
             
        <div class="AgeOptions">
                <div id="Under13">
                    <div class="BuildersClubButton"><a id="ctl00_cphGoldblox_HyperLink1" href="/Login/NewNameAndPassword.aspx?Age=Under13"><img src="/images/Under13.png" alt="" style="border-width:0px;"></a></div>
                    <div class="Label"><a id="ctl00_cphGoldblox_HyperLink2" href="/Login/NewNameAndPassword.aspx?Age=Under13">Under 13</a></div>
                </div>    
            </div>
 <div class="AgeOptions">
                <div id="Over12">
                    <div class="BuildersClubButton"><a id="ctl00_cphGoldblox_HyperLink1" href="/Login/NewNameAndPassword.aspx?Age=Over12"><img src="/images/Over12.png" alt="" style="border-width:0px;"></a></div>
                    <div class="Label"><a id="ctl00_cphGoldblox_HyperLink2" href="/Login/NewNameAndPassword.aspx?Age=Over12">13 or Older</a></div>
                </div>    
            </div>
</div>
    </div>
        
    <div id="Sidebars">
      <div id="AlreadyRegistered">
        <h3>Already Registered?</h3>
        <p>If you just need to login, go to the <a id="ctl00_cphGOLDBLOX_HyperLinkLogin" href="/Login/Default.aspx">Login</a> page.</p>
        <p>If you have already registered but you still need to download the game installer, go directly to <a id="ctl00_cphGOLDBLOX_HyperLinkDownload" href="/Install/Default.aspx">download</a>.</p>
      </div>
      <div id="TermsAndConditions">
        <h3>Hey Parents!</h3>
       <p>Are you creating an account for your young child?</p>
			<p>If so, you should select "Under 13"</p>
			<p>GOLDBLOX has a variety of online safety features that help to prevent your child from disclosing personal information to other players. As a parent, you will have the option to override them later.</p>
			<p><a id="ctl00_cphGoldblox_ParentsInfo" href="/Parents.aspx">More information for GOLDBLOX parents</a></p>
      </div>
    </div>
     <div id="Registration">
      <div id="ctl00_cphGOLDBLOX_uptimoAccountRegistration">
  
          <h2>Create a Parent Account</h2>
          <h3>Create an administrative account to control a child's access to GOLDBLOX</h3>
          
        <div class="AgeOptions">
                <div id="ParentAccount">
                    <div class="BuildersClubButton"><a id="ctl00_cphGoldblox_HyperLink1" href="/Parents/Register.aspx"><img src="/images/ParentAccount.png" alt="" style="border-width:0px;"></a></div>
                    <div class="Label"><a id="ctl00_cphGoldblox_HyperLink2" href="/Parents/Register.aspx">Parent Account</a></div>
                </div>    
            </div>
</div>
    </div>
        
    <div id="ct_900_cphGOLDBLOX_ie6_peekaboo" style="clear: both"></div>
        </div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                <?php
include '../api/web/footer.php';
?>
