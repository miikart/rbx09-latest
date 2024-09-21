<?php
require_once '../api/web/header.php';
 if($auth == false){
  die(header("Location: /Login/Default.aspx"));
}
?>

<div id="Body">
					
    <div id="MyAccountBalanceContainer">
		<h2>My Account Balance</h2>
		<div id="AboutGOLDBUX">
			<h3>What are GOLDBUX?</h3>
			<p>GOLDBUX are the principle currency of Goldbloxia. Citizens in the Builders Club receive a daily allowance of GOLDBUX to help them live a comfortable life of leisure. For this and other benefits, consider joining the Builders Club!</p>
			<h3>What are Tickets?</h3>
			<p>Goldbloxian Tickets are similar to tickets you win in an arcade. You play the game, get tickets, and are rewarded with fabulous prizes. Tickets are granted to citizens who are helping to expand and improve Goldbloxia. The primary way to get tickets is to make a cool place, and then get people to visit it. You can also get the daily login bonus, just by showing up!</p>
			<h3>Where do I buy things?</h3>
			<p>Browse the <a id="ctl00_cphRoblox_CatalogHyperLink" href="../Catalog.aspx">ROBLOX Catalog</a></p>
		</div>
		<div id="Earnings">
			<h3>Earnings</h3>
			<div>
                <div class="Label"></div>
				<div class="Field"><img id="ctl00_cphRoblox_GoldbuxIcon" src="../images/GOLDBUX.png" alt="Goldbux" style="border-width:0px;"></div>
				<div class="Field"><img id="ctl00_cphRoblox_TicketsIcon" src="../images/Tickets.png" alt="Tickets" style="border-width:0px;"></div>
			</div>
			<div class="Earnings_Period">
				<h4>Past Day</h4>
				<div class="Earnings_LoginAward">
					<div class="Label">Login Award</div>
					<div class="Field"></div>
					<div class="Field">10</div>
				</div>
				<div id="ctl00_cphRoblox_Earnings_PastDay_PlaceTrafficAward" class="Earnings_PlaceTrafficAward">
					<div class="Label">Place Traffic Award</div>
					<div class="Field"></div>
					<div class="Field">186</div>
				</div>
				<div id="ctl00_cphRoblox_Earnings_PastDay_SaleOfGoods" class="Earnings_SaleOfGoods">
					<div class="Label">Sale of Goods</div>
					<div class="Field"></div>
					<div class="Field">10</div>
				</div>
				<div class="Earnings_PeriodTotal">
					<div class="Label">Total:</div>
					<div class="Field">15</div>
					<div class="Field">206</div>
				</div>
			</div>
			<div class="Earnings_Period">
				<h4>Past Week</h4>
				<div class="Earnings_LoginAward">
					<div class="Label">Login Award</div>
					<div class="Field"></div>
					<div class="Field">60</div>
				</div>
				<div id="ctl00_cphRoblox_Earnings_PastWeek_PlaceTrafficAward" class="Earnings_PlaceTrafficAward">
					<div class="Label">Place Traffic Award</div>
					<div class="Field"></div>
					<div class="Field">2,017</div>
				</div>
				<div id="ctl00_cphRoblox_Earnings_PastWeek_SaleOfGoods" class="Earnings_SaleOfGoods">
					<div class="Label">Sale of Goods</div>
					<div class="Field"></div>
					<div class="Field">20</div>
				</div>
				<div class="Earnings_PeriodTotal">
					<div class="Label">Total:</div>
					<div class="Field">90</div>
					<div class="Field">2,097</div>
				</div>
			</div>
			<div class="Earnings_Period">
				<h4>Past Month</h4>
				<div class="Earnings_LoginAward">
					<div class="Label">Login Award</div>
					<div class="Field"></div>
					<div class="Field">260</div>
				</div>
				<div id="ctl00_cphRoblox_Earnings_PastMonth_PlaceTrafficAward" class="Earnings_PlaceTrafficAward">
					<div class="Label">Place Traffic Award</div>
					<div class="Field"></div>
					<div class="Field">10,033</div>
				</div>
				<div id="ctl00_cphRoblox_Earnings_PastMonth_SaleOfGoods" class="Earnings_SaleOfGoods">
					<div class="Label">Sale of Goods</div>
					<div class="Field">1</div>
					<div class="Field">59</div>
				</div>
				<div class="Earnings_PeriodTotal">
					<div class="Label">Total:</div>
					<div class="Field">391</div>
					<div class="Field">10,352</div>
				</div>
			</div>
			<div class="Earnings_Period">
				<h4>All Time</h4>
				<div class="Earnings_LoginAward">
					<div class="Label">Login Award</div>
					<div class="Field">290</div>
					<div class="Field">1,780</div>
				</div>
				<div id="ctl00_cphRoblox_Earnings_AllTime_PlaceTrafficAward" class="Earnings_PlaceTrafficAward">
					<div class="Label">Place Traffic Award</div>
					<div class="Field">6,328</div>
					<div class="Field">43,163</div>
				</div>
				<div id="ctl00_cphRoblox_Earnings_AllTime_SaleOfGoods" class="Earnings_SaleOfGoods">
					<div class="Label">Sale of Goods</div>
					<div class="Field">7</div>
					<div class="Field">361</div>
				</div>
				<div class="Earnings_PeriodTotal">
					<div class="Label">Total:</div>
					<div class="Field"><?php echo number_format($_USER['GOLDBUX']) ?></div>
					<div class="Field"><?php echo number_format($_USER['tix']) ?></div>
				</div>
			</div>
		</div>
	</div>

				</div>
<div style="clear: both;">
</div>
<?php require_once '../api/web/footer.php'; ?>

