<?php
include $_SERVER["DOCUMENT_ROOT"]."/api/web/config.php";
$id = (int)$_POST['item'];
$stmt = $con->prepare("SELECT * FROM catalog WHERE id = :id");
$stmt->execute(['id' => $id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
$resultsperpage = 10;
$sigmastmte = $con->prepare("SELECT COUNT(*) FROM comments WHERE assetid = :id");
$sigmastmte->execute(['id' => $id]);
$usercount = $sigmastmte->fetchColumn();
$numberofpages = ceil($usercount / $resultsperpage);
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$thispagefirstresult = ($page - 1) * $resultsperpage;
if ($numberofpages == 1 || $numberofpages == 0) {
  $haspages = false;
} else {
   $haspages = true;
}
?>
<?php if($item['commentsenabled'] == 1) { ?>
<h3>Comments (<?= number_format($usercount) ?>)</h3>
<?php  if ($haspages) { ?>
<div id="ctl00_cphGoldblox_rbxCatalog_HeaderPagerPanel" class="HeaderPager">
    <?php if ($page > 1) { ?>
        <a id="ctl00_cphGoldblox_rbxCatalog_FooterPagerHyperLink_Next" href="javascript:return false;" onclick="getComments(<?= $page - 1 ?>, <?= $id ?>)">
            <span class="NavigationIndicators">&lt;&lt;</span> Previous
        </a>
    <?php } ?>
    <span id="ctl00_cphGoldblox_rbxCatalog_HeaderPagerLabel">Page <?= $page ?> of <?php  if ($numberofpages == 0) {
    echo '1';
} else {
    echo $numberofpages;
} ?></span>
    <?php if ($page < $numberofpages) { ?>
        <a id="ctl00_cphGoldblox_rbxCatalog_FooterPagerHyperLink_Next" href="javascript:return false;" onclick="getComments(<?= $page + 1 ?>, <?= $id ?>)">
            Next <span class="NavigationIndicators">&gt;&gt;</span>
        </a>
    <?php } ?>
</div>
<?php } ?>
<div class="Comments">
    <?php
    $sql = "SELECT * FROM comments WHERE assetid = :id ORDER BY id DESC LIMIT :start, :limit";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':start', $thispagefirstresult, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $resultsperpage, PDO::PARAM_INT);
    $stmt->execute();

    $resultCheck = $stmt->rowCount();
  $counter = 1;
    if ($resultCheck > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
         
            $userStmt = $con->prepare("SELECT * FROM users WHERE id = :userid");
            $userStmt->execute(['userid' => $row['userid']]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
	  $updated = context::updated($row['time_posted'] -3600) ;
 
                 $counter++
            ?>
                          <div <?php if($counter % 2 == 0) { ?>class="Comment"<?php } else { ?>class="AlternateComment"<?php } ?> >

                <div class="Commenter">
                    <div class="Avatar">
                        <a title="<?= $user['username'] ?>" href="/User.aspx?ID=<?= $user['id'] ?>" style="display:inline-block;height:100px;width:100px;cursor:pointer;">
                            <img style="width:100px;height:100px;" src="/Thumbs/Avatar.ashx?assetId=<?= $user['id'] ?>&IsSmall" border="0" id="img" alt="<?= $user['username'] ?>">
                        </a>
                    </div>
                </div>
                <div class="Post">
                    <div class="Audit">
                        Posted <?php echo $updated ?> by
                        <a href="/User.aspx?ID=<?= $user['id'] ?>"><?= $user['username'] ?></a>
                    </div>
                    <div class="Content"><?= $row['content'] ?></div>
                    <div class="Actions">
                        <div class="ReportAbusePanel">
                            <span class="AbuseIcon"><a href="#"><img src="/images/abuse.gif" alt="Report Abuse" style="border-width:0px;"></a></span>
                            <span class="AbuseButton"><a href="#">Report Abuse</a></span>
                        </div>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <?php
        }
    } else {
        echo "<p>&nbsp;&nbsp;No comments found.</p>";
    }
    ?>
</div>
<?php  if ($haspages) { ?>
<div id="ctl00_cphGoldblox_rbxCatalog_HeaderPagerPanel" class="FooterPager">
  <?php if ($page > 1) { ?>
        <a id="ctl00_cphGoldblox_rbxCatalog_FooterPagerHyperLink_Next" href="javascript:return false;" onclick="getComments(<?= $page - 1 ?>, <?= $id ?>)">
            <span class="NavigationIndicators">&lt;&lt;</span> Previous
        </a>
    <?php } ?>
    <span id="ctl00_cphGoldblox_rbxCatalog_HeaderPagerLabel">Page <?= $page ?> of <?php if ($numberofpages == 0) {
    echo '1';
} else {
    echo $numberofpages;
}?></span>
   
    <?php if ($page < $numberofpages) { ?>
        <a id="ctl00_cphGoldblox_rbxCatalog_FooterPagerHyperLink_Next" href="javascript:return false;" onclick="getComments(<?= $page + 1 ?>, <?= $id ?>)">
            Next <span class="NavigationIndicators">&gt;&gt;</span>
        </a>
    <?php } ?>
</div>
<?php } ?>
<?php if($auth == true) { ?>
<div id="ctl00_cphGoldblox_CommentsPane_PostAComment" class="PostAComment">
    <form method="POST">
        <h3>
        <?php if ($item['type'] == 'pants') {
    echo 'Comment on these';
} else {
    echo 'Comment on this';
} ?>

<?php if ($item['type'] == 'tshirt') {
    echo 'T-Shirt';
} else {
    echo ucfirst($item['type']);
} ?></h3>
        <div class="CommentText">
            <textarea id="comment" rows="5" cols="20" class="MultilineTextBox" style="max-width: 500px;"></textarea>
        </div>
        <div class="Buttons">
            <a id="ctl00_cphGoldblox_CommentsPane_NewCommentButton" class="Button" type="submit" onclick="<?php if (!$auth == true) { ?>location.href='/Login/Default.aspx'<?php } else { ?>Comment(<?= $id ?>)<?php } ?>">Post Comment</a>
        </div>
    </form>
</div>
<?php } ?>
<?php } else { ?>
<p id="ctl00_cphGoldblox_TabbedInfo_CommentaryTab_CommentsNotEnabled" style="text-align: center;">Commentary for this item has been disabled.</p>
<?php } ?>