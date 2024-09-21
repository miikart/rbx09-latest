<?php
require ("api/web/header.php");

?><title>GOLDBLOX - Badges</title>

	
<style>#BadgesContainer {
	border: solid 1px #000;
}
	</style>
<div id="Body">
<div id="BadgesContainer">
  <div id="BadgesAndRankings">
		<input type="hidden" name="AccordionExtender_ClientState" id="AccordionExtender_ClientState" value="0">
<?php
try {
   

    $fanumtax = "SELECT * FROM badgecategories";
    $stmt = $con->query($fanumtax);
    $rizz = $stmt->fetchAll();
} catch (PDOException $e) {
    die('it broken... ' . $e->getMessage());
}
?>

<?php 
foreach ($rizz as $row) { 
    $top = ($row['id'] == 1) ? "Top" : ""; 
?>
    <div>
        <h4 class="<?= $top; ?>AccordionHeader"><?= htmlspecialchars($row['name']); ?> Badges</h4>
    </div>
    <div style="display:block;">
        <div id="CommunityBadges">
            <div class="Legend">
                <ul class="BadgesList">
                    <?php
                    $fanumtax2 = "SELECT * FROM badges WHERE category = :category AND featured = '0'";
                    $stmt2 = $con->prepare($fanumtax2);
                    $stmt2->execute(['category' => $row['id']]);
                    $badges = $stmt2->fetchAll();
                    
                    foreach ($badges as $row2) { 
                    ?>
                        <li style="background-image: url('<?= htmlspecialchars($row2['image']); ?>'); min-height: 75px;">
                            <h4><?= htmlspecialchars($row2['name']); ?> Badge</h4>
                            <div><?= htmlspecialchars($row2['description']); ?></div>
                        </li>
                    <?php 
                    } 
                    ?>
                </ul>
            </div>
            <?php
            $fanumtax3 = "SELECT * FROM badges WHERE category = :category AND featured = '1'";
            $stmt3 = $con->prepare($fanumtax3);
            $stmt3->execute(['category' => $row['id']]);
            $fanumtaxedbadges = $stmt3->fetchAll();
            
            foreach ($fanumtaxedbadges as $row3) { 
            ?>
                <div id="FeaturedBadge_Community">
                    <h4><?= htmlspecialchars($row3['name']); ?></h4>
                    <div class="FeaturedBadgeContent">
                        <div class="FeaturedBadgeIcon"><img src="<?= htmlspecialchars($row3['image']); ?>" height="125" width="125" border="0"/></div>
                        <p><?= htmlspecialchars($row3['description']); ?></p>
                    </div>
                </div>
            <?php 
            } 
            ?>
            <div style="clear:both;"></div>
        </div>
    </div>
<?php 
} 
?>


								
									
					</div>
					
				</div>
					</div>
					
				<script>
Sys.Application.add_init(function() {
	$create(Sys.Extended.UI.AccordionBehavior, {"ClientStateFieldID":"AccordionExtender_ClientState","FramesPerSecond":40,"id":"AccordionExtender"}, null, null, $get("BadgesAndRankings"));
});
</script>
								</div>
<?php
require ("api/web/footer.php");
?>