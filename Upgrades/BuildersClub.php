<?php
include '../api/web/header.php';
?>
<title>GOLDBLOX - Builders Club</title>
<audio id="music" loop>
  <source src="/images/believer.mp3" type="audio/mpeg">
  sonb
</audio>
<script>
    var clicked = false;
    function playcoolstuff() {
        if(clicked == false) {
            clicked = true;
            var music = document.getElementById("music");
            music.play();
}}
</script>
 <style>
 #WhyJoin
{
    display: inline;
    float: left;
    margin: 30px 10px 20px 90px;
    padding: 0;
    width: 375px;
}

#WhyJoin h3
{
    font-size: 15pt;
    font-weight: normal;
    letter-spacing: 0.2em;
    margin: 0 0 15px 0;
    padding: 0;
}

#WhyJoin #MembershipBenefits
{
    list-style: none;
    margin: 0;
  padding: 0;
}

#WhyJoin #MembershipBenefits li 
{
  background-position: 0 0;
  background-repeat: no-repeat;
  font-family: Verdana, Sans-Serif;
  font-size: 11pt;
  margin: 0 0 10px 10px;
  min-height: 32px;
  padding: 0 0 0 40px;
}

#WhyJoin #MembershipBenefits #Benefit_MultiplePlaces
{  
  background-image: url(/images/MultiplePlacesBullet.png);
}

#WhyJoin #MembershipBenefits #Benefit_GOLDBUXAllowance
{  
  background-image: url(/images/AllowanceBullet.png);
}

#WhyJoin #MembershipBenefits #Benefit_SellContent
{  
  background-image: url(/images/SellBullet.png);
}

#WhyJoin #MembershipBenefits #Benefit_SuppressAds
{  
  background-image: url(/images/AdSuppressionBullet.png);
}

#WhyJoin #MembershipBenefits #Benefit_ExclusiveHat
{  
  background-image: url(/images/HardHatBullet.png);
}
 </style>
<div id="Body">
<font face="Verdana">
  <div id="BuildersClubContainer" style="border:1px solid black;">
  <div id="JoinBuildersClubNow"><img src="/images/JoinBuildersClubNow.png" alt="Join Builders Club Now!" style="margin-bottom:-2px;" /></div>
  <div id="MembershipOptions">
   <div id="OneMonth">
      <div class="BuildersClubButton"><a href="#" onclick="playcoolstuff()"><img src="/images/BuyBC/BuyBCMonthly.png" style="border-width:0px;" /></a></div>
      <div class="Label"><a href="#" onclick="playcoolstuff()">Join Monthly</a></div>
    </div>
    <div id="SixMonths">
      <div class="BuildersClubButton"><a href="#" onclick="playcoolstuff()"><img src="/images/BuyBC/BuyBC6Months.png" style="border-width:0px;" /></a></div>
      <div class="Label"><a href="#" onclick="playcoolstuff()">Join for 6 Months</a></div>
    </div>
    <div id="TwelveMonths">
      <div class="BuildersClubButton"><a href="#" onclick="playcoolstuff()"><img src="/images/BuyBC/BuyBC12Months.png" style="border-width:0px;" /></a></div>
      <div class="Label"><a href="#" onclick="playcoolstuff()">Join for 12 Months</a></div>
    </div>
  </div>
  <div id="WhyJoin">
    <h3>Why Join Builders Club?</h3>
    <ul id="MembershipBenefits">
      <li id="Benefit_MultiplePlaces">Create up to 10 places on a single account</li>
      <li id="Benefit_GOLDBUXAllowance">Earn a daily income of 15 GOLDBUX</li>
     
      <li id="Benefit_ExclusiveHat">Receive the exclusive Builders Club construction hard hat</li>
    </ul>
    <p>Product is Windows-only. For more information, read our <a href="../Parents/BuildersClub.aspx">Builders Club FAQs</a>.</p>
    <h3>Not Ready Yet?</h3>
    <ul id="MembershipBenefits">
    <li id="Benefit_GOLDBUXAllowance">You can also <a href="GOLDBUX.aspx">grab GOLDBUX</a> by donating us. We will offer you some as our way of saying thank you. </li>
    <ul>
  </div>
  <div style="clear:both;"></div>
</font>
				</div>
                                <?php include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>