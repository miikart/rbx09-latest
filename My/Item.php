<?php
include('../api/web/header.php');

if (!$auth) {
    header("Location: /Login/Default.aspx");
    exit;
}

$id = intval($_REQUEST['ID']);

$itemq = $con->prepare("SELECT * FROM catalog WHERE id=? AND type !='place'");
$itemq->execute([$id]);
$itemCount = $itemq->rowCount();
$item = $itemq->fetch(PDO::FETCH_ASSOC);

if ($itemCount < 1 || $item['moderation'] === "pending" || $item['moderation'] === "declined") {
    header("Location: /error.php");
    exit;
}

$editable = ($item['creatorid'] == $_USER['id'] || $_USER['USER_PERMISSIONS'] == 'Administrator');

if (!$editable) {
    header("Location: /error.php");
    exit;
}

$typea = ucfirst($item['type']);
$chekc = "";
$trueorfalseccb = 'false';
$ishidden = 'hidden';
$sellitemthing = 'style="display: none;"';
if ($item['isoffsale'] == 0) {
    $sellitemthing = '';
}
$checkc = "";
if ($item['isoffsale'] == 0) {
    $checkc = 'checked="checked"';
}
?>

<?php
if (isset($_POST['updatend'])) {
  $desc = $ep->remove(htmlspecialchars(trim($_POST["iDesc"]), ENT_QUOTES, 'UTF-8'));
$name = $ep->remove(htmlspecialchars(trim($_POST["iName"]), ENT_QUOTES, 'UTF-8'));

    $uca5 = $con->prepare("UPDATE catalog SET name=?, description=? WHERE id=?");
    $uca5->execute([$name, $desc, $id]);
    header("Location: /Item.aspx?ID=".$id);
    exit;
}

if (isset($_POST['updateall'])) {
    if (!$_POST['commentschk']) {
$uca35 = $con->prepare("UPDATE catalog SET commentsenabled = 0 WHERE id=?");
$uca35->execute([$id]);
} else {
$uca35 = $con->prepare("UPDATE catalog SET commentsenabled = 1 WHERE id=?");
$uca35->execute([$id]);
}
    
    $isOffSale = ($_POST['sellItem']) ? '0' : '1';
    $GOLDBUX = (int)$_POST['GOLDBUXN'];
    $tix = (int)$_POST['tixN'];

    if (isset($_POST['sellItem'])) {
        if ($GOLDBUX <= 0) {
            $sellGOLDBUX = false;
        } else {
            $sellGOLDBUX = true;
        }

        if ($tix <= 0) {
            $selltix = false;
        } else {
            $selltix = true;
        }

        if ($sellGOLDBUX && $selltix) {
            // Update for both GOLDBUX and Tix
            $uca2 = $con->prepare("UPDATE catalog SET isoffsale=?, price=?, buywith='tix', price2=?, buywith2='GOLDBUX' WHERE id=?");
            $uca2->execute([$isOffSale, $tix, $GOLDBUX, $id]);
        } elseif ($sellGOLDBUX && !$selltix) {
            // Update for GOLDBUX only
            $uca2 = $con->prepare("UPDATE catalog SET isoffsale=?, price2=?, buywith2='GOLDBUX', price=NULL, buywith=NULL WHERE id=?");
            $uca2->execute([$isOffSale, $GOLDBUX, $id]);
        } elseif ($selltix && !$sellGOLDBUX) {
            // Update for Tix only
            $uca2 = $con->prepare("UPDATE catalog SET isoffsale=?, price=?, buywith='tix', price2=NULL, buywith2=NULL WHERE id=?");
            $uca2->execute([$isOffSale, $tix, $id]);
        }
    } else {
      
        $uca2 = $con->prepare("UPDATE catalog SET isoffsale=1, price=NULL, buywith=NULL, price2=NULL, buywith2=NULL WHERE id=?");
        $uca2->execute([$id]);
    }

    header("Location: /Item.aspx?ID=".$id);
    exit;
}


?>


  <script>
$(document).ready(function() {
 
 <?php if($item['commentsenabled'] == 1) { ?>
 $(".commentCheckbox").prop("checked", true);
<?php } ?>
    <?php if($item['isoffsale'] == 0){ ?>
    $("#sellItemChk").prop("checked", true);
    $("#PricingPanel").show();
    <?php } else { ?>
    $("#sellItemChk").prop("checked", false);
    $("#PricingPanel").hide();
    <?php } ?>

    <?php if($item['buywith2'] == "GOLDBUX"){ ?>
    $(".GOLDBUXCheckbox").prop("checked", true);
    $("#GOLDBUXTxt").prop("disabled", false);
    $(".buxFee").removeAttr("style");
    $(".buxEarn").removeAttr("style");
    <?php } ?>

    <?php if($item['buywith'] == "tix" && $item['price'] > -1){ ?>
    $(".tixCheckbox").prop("checked", true);
    $("#tixTxt").prop("disabled", false);
    $(".tixFee").removeAttr("style");
    $(".tixEarn").removeAttr("style");
    <?php } ?>
    function cum() {
        if ($(".GOLDBUXCheckbox").is(":checked")) {
            $(".GOLDBUXFee").text(Math.round($("#GOLDBUXTxt").val() * 0.1));
            $(".GOLDBUXEarn").text(Math.round($("#GOLDBUXTxt").val() - $("#GOLDBUXTxt").val() * 0.1));
        } else {
            $(".GOLDBUXFee").text("---");
            $(".GOLDBUXEarn").text("---");
        }

       
        if ($(".tixCheckbox").is(":checked")) {
            $(".ticketFee").text(Math.round($("#tixTxt").val() * 0.1));
            $(".ticketEarn").text(Math.round($("#tixTxt").val() - $("#tixTxt").val() * 0.1));
        } else {
            $(".ticketFee").text("---");
            $(".ticketEarn").text("---");
        }
    }
    cum();
    $("#GOLDBUXTxt, #tixTxt").bind({
        keydown: function(e) {
            if (e.shiftKey === true ) {
                if (e.which == 9) {
                    return true;
                }
                return false;
            }
            if (e.which > 57) {
                return false;
            }
            if (e.which == 32) {
                return false;
            }
            return true;
        }
    });

    $("#sellItemChk").click(function() {
        if($(this).is(':checked')) {
            $('#PricingPanel').show();
        } else {
            $('#PricingPanel').hide();
        }
    });

    $(".GOLDBUXCheckbox").change(function() {
        if(this.checked) {
            $("#GOLDBUXTxt").prop("disabled", false);
            $("#GOLDBUXTxt").val(0);
            $(".buxFee").removeAttr("style");
            $(".buxEarn").removeAttr("style");
            $(".GOLDBUXFee").text("");
            $(".GOLDBUXEarn").text("");
        } else {
            $("#GOLDBUXTxt").prop("disabled", true);
            $("#GOLDBUXTxt").val(0);
            $(".buxFee").css("display", "none");
            $(".buxEarn").css("display", "none");
            $(".GOLDBUXFee").text("---");
            $(".GOLDBUXEarn").text("---");
        }
    });

    $(".tixCheckbox").change(function() {
        if(this.checked) {
            $("#tixTxt").prop("disabled", false);
            $("#tixTxt").val(0);
            $(".tixFee").removeAttr("style");
            $(".tixEarn").removeAttr("style");
            $(".ticketFee").text("");
            $(".ticketEarn").text("");
        } else {
            $("#tixTxt").prop("disabled", true);
            $("#tixTxt").val(0);
            $(".tixFee").css("display", "none");
            $(".tixEarn").css("display", "none");
            $(".ticketFee").text("---");
            $(".ticketEarn").text("---");
        }
    });

    $('#GOLDBUXTxt').on('input propertychange paste', function() {
        $(".GOLDBUXFee").text(Math.round($("#GOLDBUXTxt").val() * 0.1));
        $(".GOLDBUXEarn").text(Math.round($("#GOLDBUXTxt").val() - $("#GOLDBUXTxt").val() * 0.1));
    });

    $('#tixTxt').on('input propertychange paste', function() {
        $(".ticketFee").text(Math.round($("#tixTxt").val() * 0.1));
        $(".ticketEarn").text(Math.round($("#tixTxt").val() - $("#tixTxt").val() * 0.1));
    });
});





</script>
<div id="Body">
	<form method="post">
	<div id="EditItemContainer">
		<div id="EditItem">
			<h2>Configure <?php if($item['type'] == "tshirt") {echo 'T-Shirt';} else { echo ucfirst($item['type']);} ?></h2>
			
			<div id="ItemName">
				<span style="font-weight: bold;">Name:</span><br>
				<input name="iName" type="text" value="<?=$item['name']?>" maxlength="64" class="TextBox">
		<?php echo $errorname ?>
			</div>
			<div id="ItemDescription">
				<span style="font-weight: bold;">Description:</span><br>
				<textarea name="iDesc" maxlength="1000" rows="2" cols="20" class="TextBox" style="height:150px;width: 410px;max-width: 400px; padding: 5px;"><?=($item['description'])?></textarea>
			</div>
			
			<div class="Buttons">
				<input name="updatend" id="Submit" tabindex="4" class="Button" type="submit" value="Update">&nbsp;
				<a id="Cancel" tabindex="5" class="Button" href="/Item.aspx?ID=<?=$item['id']?>">Cancel</a>
			</div>
			
			<div id="Comments">
				<fieldset title="Turn comments on/off">
					<legend>Turn comments on/off</legend>
					<div class="Suggestion">
						Choose whether or not this item is open for comments.
					</div>
					<div class="EnableCommentsRow">
						<input class="commentCheckbox" type="checkbox" name="commentschk" ><label>Allow Comments</label>
					</div>
				</fieldset>
			</div> 
			 
		
			
			<?php if($type != "model" && $type != "decal") { ?><div id="SellThisItem">
				<fieldset title="Sell this Item">
					<legend>Sell this Item</legend>
					<div class="Suggestion">
						Check the box below and enter a price if you want to sell this item in the GOLDBLOX
						catalog. Uncheck the box to remove the item from the catalog.
					</div>
					<div class="SellThisItemRow">
						<input id="sellItemChk" class="sellCheckbox" type="checkbox" name="sellItem"><label>Sell this Item</label>
						<div id="PricingPanel" class="PricingPanel" style="display: none;">
							<div id="Pricing" style="
    background-color: #fff;
    border: dashed 1px #000;
    margin: 15px 5px 5px 5px;
    padding: 5px;
">
								<div id="Currency" style="margin-left: 151px;margin-left: 141px;">
									<div class="PricingLabel">
									</div>
									<div class="PricingField_GOLDBUX">
										<input class="GOLDBUXCheckbox" type="checkbox"><label>for GOLDBUX</label>
									</div>
									<div class="PricingField_Tickets">
										<input class="tixCheckbox" type="checkbox"><label>for Tickets</label>
									</div>
									<div style="clear: both;">
									</div>
								</div>
								<div id="Price">
									<div class="PricingLabel" style="width: 135px;">
										Price:
									</div>
									<div class="PricingField_GOLDBUX">
										<input id="GOLDBUXTxt" name="GOLDBUXN" type="number" maxlength="9" class="TextBox" disabled="" value="<?php if(!$item['buywith2'] == "GOLDBUX"){ ?>0<?php }else{ echo $item['price2']; } ?>">
									</div>
									<div class="PricingField_Tickets">
											<input id="tixTxt" name="tixN" type="number" maxlength="9" class="TextBox" disabled="" value="<?php if(!$item['buywith'] == "tix"){ ?>0<?php }else{ echo $item['price']; } ?>">
										
									</div>
							
									<?php echo 	$pricerrorlow ?>
									<?php echo $pricerrormax ?>
									<div style="clear: both;"></div>
								</div>
								<div id="Fee" style="margin-top: 18px;">
									<div class="PricingLabel" style="
    width: 135px;
">
											Marketplace Fee @ <br> 10%:
											<br><span class="Suggestion">(minimum 1)</span>
									</div>
									<div class="PricingField_GOLDBUX">
										<span class="buxFee" style="display: none;">G$&nbsp;</span>
										<span class="GOLDBUXFee">---</span>
									</div>
									<div class="PricingField_Tickets">
										<span class="tixFee" style="display: none;">Tx&nbsp;</span>
										<span class="ticketFee">---</span>
									</div>
									<div style="clear: both;">
									</div>
								</div>
								<div id="Profit" style="margin-top:10px">
									<div class="PricingLabel" style="
    width: 135px;
">
										You Earn:</div>
										<div class="PricingField_GOLDBUX">
											<span class="buxEarn" style="display: none;">G$&nbsp;</span>
											<span class="GOLDBUXEarn">---</span>
										</div>
										<div class="PricingField_Tickets">
											<span class="tixEarn" style="display: none;">Tx&nbsp;</span>
											<span class="ticketEarn">---</span>
										</div>
									<div style="clear: both;">
									</div>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			</div><?php } ?>
			<div class="Buttons">
				<input name="updateall" tabindex="4" class="Button" type="submit" value="Update">&nbsp;
				<a id="Cancel" tabindex="5" class="Button" href="/Item.aspx?ID=<?=$item['id']?>">Cancel</a>
			</div>
		</div>
		<div class="Ads_WideSkyscraper">
			
  		</div>
		<div style="clear: both;"></div>
	</div>
</form>
</div>
<?php include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>
