<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/api/web/config.php');

$q = $link->prepare("SELECT * FROM ads2 WHERE `status` = 'running' ORDER BY RAND() LIMIT 1");
$q->execute();
$result = $q->get_result();
$ad = $result->fetch_assoc();

if ($auth == true) {
    echo '<div style="float: right; width: 162px;">
        <br style="line-height: 1px;">
        <div id="fart" style="position: relative; margin-top: -4.8px;">
            <a href="' . htmlspecialchars($ad["adurl"]) . '" title="' . htmlspecialchars($ad["adtitle"]) . '">
                <img style="border: solid 1px #000; width: 160px; height: 600px;" src="' . htmlspecialchars($ad["adimg"]) . '">
            </a>
            <a href="javascript:alert(\'ad report soon\');" style="position: absolute; background-color: #EEE; border: solid 1px #000; color: blue; font-family: Verdana; font-size: 10px; font-weight: lighter; bottom: 3px; right: 1px; padding-bottom: 1px;">
                [ report ]
            </a>
        </div>
    </div>';
}
?>
