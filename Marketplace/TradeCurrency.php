<?php
include $_SERVER["DOCUMENT_ROOT"].'/api/web/header.php';
?>

<?php
if ($auth == false) {
    header("Location: /Login/Default.aspx");
    exit;
}

if (isset($_POST['submit'])) {
   $checkStmt = $con->prepare("SELECT * FROM users WHERE id = :userid ORDER BY id DESC LIMIT 1");
    $checkStmt->execute(['userid' => $_USER['id']]);

    $ratelimited = false;
    if ($checkStmt->rowCount() == 1) {
        $ratelimit = $checkStmt->fetch(PDO::FETCH_ASSOC);
        if (time() <= $ratelimit['renderedfirsttime']) {
            $ratelimited = true;
        }
    }

    if ($ratelimited) {
        context::boughtitem("# " . $_USER['username'] . " (id " . $_USER['id'] . ") MIGHT be a faggot that tried to spam the site");
        echo("You are being ratelimited");
        die();
  
    }

  
      $timeout = time() + 25;
    $userId = $_USER['id'];
    $conversionType = htmlspecialchars($_POST['mtype']);
    $amount = htmlspecialchars($_POST['amount']);

    if (!is_numeric($amount) || $amount <= 0) {
        die("Invalid amount");
    }

    if ($conversionType == 'gx') {
       
        if ($amount < 10) {
            context::boughtitem("# " . $_USER['username'] . " (id " . $_USER['id'] . ") tried to exploit currency exchage");
            die("no thanks");
        }
        $columnToUpdate = 'GOLDBUX';
        $newAmount = $_USER['GOLDBUX'] + $amount / 10;
        $columnToUpdates = 'tix';
        $newAmounts = $_USER['tix'] - $amount;
        if ($_USER['tix'] < $amount) {
            die("Insufficient Tickets");
        }
    } else {
        $columnToUpdate = 'tix';
        $newAmount = $_USER['tix'] + $amount * 10;
        $columnToUpdates = 'GOLDBUX';
        $newAmounts = $_USER['GOLDBUX'] - $amount;
        if ($_USER['GOLDBUX'] < $amount) {
            die("Insufficient GOLDBUX");
        }
    }
   $skibidi = $con->prepare("UPDATE users SET renderedfirsttime = :timeout WHERE id = :userid");      $skibidi->execute([          'timeout' => $timeout,          'userid' => $_USER['id'],      ]); 
    $sql = $con->prepare("UPDATE users SET $columnToUpdate = ?, $columnToUpdates = ? WHERE id = ?");
    $sql->execute([$newAmount, $newAmounts, $userId]);

    header("location: /Marketplace/TradeCurrency.aspx");
}
?>
<br>
<div id="TradeCurrencyContainer">
  <h2>Basic Currency Exchange</h2>
  <font face="Verdana">
    <div style="margin-bottom:5px; text-align:center;"><a href="#" onclick="location.reload()">Refresh</a></div>
  </font>
  <div class="LeftColumn">
    <div id="CurrencyBidsPane">
      <div class="CurrencyBids">
        <h4>Available Tickets</h4>
        <div class="CurrencyBid">
          1 @ 10
        </div>
      </div>
    </div>
  </div>
  <div class="CenterColumn">
    <!--<div id="CurrencyQuotePane">
      <div class="CurrencyQuote">
          <div class="TableHeader">
              <div class="Pair">Pair</div>
              <div class="Rate">Rate</div>
              <div class="Spread">Spread</div>
              <div class="HighLow">High/Low</div>
              <div style="clear: both;"></div>
          </div>
          <div class="TableRow">
              <div class="Pair">BUX/TIX</div>
              <div class="Rate">3.8220/3.9023</div>
              <div class="Spread">80</div>
              <div class="HighLow">459/0.0018</div>
              <div style="clear: both;"></div>
          </div>
      </div>
      </div>-->
    <div id="ctl00_cphGoldblox_CurrencyTradePane">
      <div class="CurrencyTrade">
        <h4>Trade</h4>
        <!--
          <div class="CurrencyTradeDetails">
              <div class="CurrencyTradeDetail">
                  <span title="A market order is a buy or sell order to be executed immediately at current market prices. As long as there are willing sellers and buyers, a market order will be filled."><input id="ctl00_cphGoldblox_MarketOrderRadioButton" type="radio" name="ctl00$cphGoldblox$OrderType" value="MarketOrderRadioButton" checked="checked" onclick="if (document.getElementById(&#39;ctl00_cphGoldblox_MarketOrderRadioButton&#39;).checked) { document.getElementById(&#39;LimitOrder&#39;).style.display=&#39;none&#39;; document.getElementById(&#39;SplitTrades&#39;).style.display=&#39;none&#39;; document.getElementById(&#39;MarketOrder&#39;).style.display=&#39;&#39;; } else { document.getElementById(&#39;LimitOrder&#39;).style.display=&#39;&#39;; document.getElementById(&#39;SplitTrades&#39;).style.display=&#39;&#39;; document.getElementById(&#39;MarketOrder&#39;).style.display=&#39;none&#39;; };"><label for="ctl00_cphGoldblox_MarketOrderRadioButton">Market Order</label></span>&nbsp;
                  <span title="A limit order is an order to buy at no more (or sell at no less) than a specific price. This gives you some control over the price at which the trade is executed, but may prevent the order from being executed."><input id="ctl00_cphGoldblox_LimitOrderRadioButton" type="radio" name="ctl00$cphGoldblox$OrderType" value="LimitOrderRadioButton" onclick="if (document.getElementById(&#39;ctl00_cphGoldblox_LimitOrderRadioButton&#39;).checked) { document.getElementById(&#39;LimitOrder&#39;).style.display=&#39;&#39;; document.getElementById(&#39;SplitTrades&#39;).style.display=&#39;&#39;; document.getElementById(&#39;MarketOrder&#39;).style.display=&#39;none&#39;; } else { document.getElementById(&#39;LimitOrder&#39;).style.display=&#39;none&#39;; document.getElementById(&#39;SplitTrades&#39;).style.display=&#39;none&#39;; document.getElementById(&#39;MarketOrder&#39;).style.display=&#39;&#39;; };"><label for="ctl00_cphGoldblox_LimitOrderRadioButton">Limit Order</label></span>
              </div>
              <div class="CurrencyTradeDetail">
                  <div>What I'll give:</div>
                  <input name="ctl00$cphGoldblox$HaveAmountTextBox" type="text" maxlength="9" id="ctl00_cphGoldblox_HaveAmountTextBox" tabindex="1" class="TradeBox" autocomplete="off" onkeyup="EstimateTrade()" onblur="if (document.getElementById(&#39;ctl00_cphGoldblox_MarketOrderRadioButton&#39;).checked) { if (document.getElementById(&#39;ctl00_cphGoldblox_HaveCurrencyDropDownList&#39;).selectedIndex == 0) { var haveBox = document.getElementById(&#39;ctl00_cphGoldblox_HaveAmountTextBox&#39;); if (parseInt(haveBox.value) < 20) { alert(&#39;Market Orders must be at least 20 Tickets.&#39;); haveBox.value = &#39;&#39;; haveBox.focus(); } } }">
                  
                  
                  &nbsp;&nbsp;
                  <select name="ctl00$cphGoldblox$HaveCurrencyDropDownList" id="ctl00_cphGoldblox_HaveCurrencyDropDownList" onchange="ctl00_cphGoldblox_WantCurrencyDropDownList.selectedIndex = ctl00_cphGoldblox_HaveCurrencyDropDownList.selectedIndex; EstimateTrade()">
          <option value="Tickets">Tickets</option>
          <option value="GOLDBUX">GoodBux</option>
          
          </select>
              </div>
              <div id="LimitOrder" class="CurrencyTradeDetail" style="display: none;">
                  <div>What I want:</div>
                  <input name="ctl00$cphGoldblox$WantAmountTextBox" type="text" maxlength="9" id="ctl00_cphGoldblox_WantAmountTextBox" tabindex="2" class="TradeBox" autocomplete="off">
                  
                  
                  &nbsp;
                  <select name="ctl00$cphGoldblox$WantCurrencyDropDownList" id="ctl00_cphGoldblox_WantCurrencyDropDownList" onchange="ctl00_cphGoldblox_HaveCurrencyDropDownList.selectedIndex = ctl00_cphGoldblox_WantCurrencyDropDownList.selectedIndex; EstimateTrade()">
          <option value="GOLDBUX">GoodBux</option>
          <option value="Tickets">Tickets</option>
          
          </select>
                  <p style="color: Red;">* NOTE: Your money will be held for safe-keeping until either the trade executes or you cancel your position.</p>
                  <p style="font-size: smaller; margin: 15px; text-align: left;">A limit order is an order to buy at no more (or sell at no less) than a specific price. This gives you some control over the price at which the trade is executed, but may prevent the order from being executed.</p>
              </div>
              <div id="SplitTrades" class="CurrencyTradeDetail" style="display: none;">
                  <input id="ctl00_cphGoldblox_AllowSplitTradesCheckBox" type="checkbox" name="ctl00$cphGoldblox$AllowSplitTradesCheckBox" checked="checked" tabindex="3"><label for="ctl00_cphGoldblox_AllowSplitTradesCheckBox">Allow split trades</label>
              </div>
              <div id="MarketOrder" class="CurrencyTradeDetail" style="">
                  <div>What I'll get:</div>
                  <p id="EstimatedTrade" style="color: Red;">Estimated Trade: ?</p>
                  <p style="color: Red;">* NOTE: Your money will be held for safe-keeping until either the trade executes or you cancel your position.</p>
                  <p style="font-size: smaller; margin: 15px; text-align: left;">A market order is a buy or sell order to be executed immediately at current market prices. As long as there are willing sellers and buyers, a market order will be filled.</p>
              </div>
              <div class="CurrencyTradeDetail">
                  <input type="submit" name="ctl00$cphGoldblox$SubmitTradeButton" value="Submit Trade" onclick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl00$cphGoldblox$SubmitTradeButton&quot;, &quot;&quot;, true, &quot;&quot;, &quot;&quot;, false, false))" id="ctl00_cphGoldblox_SubmitTradeButton" tabindex="4">
              </div>
          </div>-->
        <form method="post" style="padding:25px;">
          <ul>
            <li>10 Tickets = 1 GOLDBUX</li>
            <li>1 GOLDBUX = 10 Tickets</li>
          </ul>
          <input id="radioGX" type="radio" name="mtype" value="gx" checked="checked"><label for="radioGX">To GOLDBUX</label>
          <input id="radioTX" type="radio"  name="mtype" value="tx"><label for="radioTX">To Tickets</label>
          <br><br>
          <input id="amounttxt" name="amount" min="1" type="number" maxlength="9" tabindex="2" value="10" class="TradeBox" autocomplete="off" step="1">
          <br><br>
          I will get <span id="amounts">1</span> <span id="type" style="color:green;">GX</span>
          <script>
            $("#amounttxt").on("input", function() {
                if($("input[name=mtype]:checked").val() == "gx") {
                    $("#amounts").text(Math.floor($(this).val() / 10));
                    $("#type").css("color", "green").html("GX");
                } else {
                    $("#amounts").text(Math.floor($(this).val() * 10));
                    $("#type").css("color", "goldenrod").html("TX");
                }
            });
            $("input[name=mtype]").on("change", function() {
                if($("input[name=mtype]:checked").val() == "gx") {
                    $("#amounts").text(Math.floor($("#amounttxt").val() / 10));
                    $("#type").css("color", "green").html("GX");
                } else {
                    $("#amounts").text(Math.floor($("#amounttxt").val() * 10));
                    $("#type").css("color", "goldenrod").html("TX");
                }
            });
          </script>
          <br><br>
          <input type="submit" name="submit" value="Submit Trade">
        </form>
      </div>
    </div>
    <div class="TradingDashboard">
    </div>
  </div>
  <div class="RightColumn">
    <div id="CurrencyOffersPane">
      <div class="CurrencyOffers">
        <h4>Available GOLDBUX</h4>
        <div class="CurrencyOffer">
          10 @ 1
        </div>
      </div>
    </div>
  </div>
  <div style="clear: both;"></div>
</div>
<?php
include $_SERVER["DOCUMENT_ROOT"].'/api/web/footer.php';
?>