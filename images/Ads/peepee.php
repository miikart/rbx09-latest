<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/api/web/config.php');

if (isset($_USER)) {
    $userId = $_USER['id'];
    
    // Check if the user's bantype is anything other than "None"
    $checkBan = $link->prepare("SELECT * FROM users WHERE id = ? AND bantype <> 'None'");
    $checkBan->bind_param("i", $userId);
    $checkBan->execute();
    $resultBan = $checkBan->get_result();
    

    if ($resultBan->num_rows === 0) {
        // Fetch a random ad from the database
        $q = $link->prepare("SELECT * FROM ads WHERE `status` = 'running' ORDER BY RAND() LIMIT 1");
        $q->execute();
        $result = $q->get_result();
        $ad = $result->fetch_assoc();
?>
 
<div id="AdvertisingLeaderboard" style="position:relative; text-align:center;">
    <div style="position: relative; display: inline-block;">
        <a href="<?php echo htmlspecialchars($ad["adurl"]); ?>" title="<?php echo htmlspecialchars($ad["adtitle"]); ?>">
            <img style="border:solid 1px #000;width:728px;height:90px;" src="/<?php echo htmlspecialchars($ad["adimg"]); ?>">
        </a>
        <a href="javascript:alert('nolan allowed me to use this ad system');" style="position:absolute; background-color:#EEE; border:solid 1px #000; color:blue; font-family:Verdana; font-size:10px; font-weight:lighter; bottom:0; right:0; padding-bottom:1px;">
            [ info ]
        </a>
    </div>
</div>

<?php
    }
}
?>



      
