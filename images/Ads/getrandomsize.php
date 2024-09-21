
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/api/web/config.php');

if (isset($_USER) && $_USER !== 'BC') {
$q = $link->prepare("SELECT * FROM ads2 WHERE `status` = 'running' ORDER BY RAND() LIMIT 1");
$q->execute();
$result = $q->get_result();
$ad = $result->fetch_assoc();
?>

<div id="fart" style="position: relative; " >
    <div  style="position: relative; display: inline-block;">
        <a href="<?php echo htmlspecialchars($ad["adurl"]); ?>" title="<?php echo htmlspecialchars($ad["adtitle"]); ?>">
            <img style="border:solid 1px #000;width:160px;height:600px;" src="<?php echo htmlspecialchars($ad["adimg"]); ?>">
        </a>
        <a href="javascript:alert('nolan allowed me to use this ad system');" style="position:absolute; background-color:#EEE; border:solid 1px #000; color:blue; font-family:Verdana; font-size:10px; font-weight:lighter; bottom:0; right:0; padding-bottom:1px;">
            [ info ]
        </a>
    </div>
</div>
<?php
}
?>




