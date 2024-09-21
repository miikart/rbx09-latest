<?php
require_once("../api/web/header.php");
// ik the code sucks i just want this to work for now and rewrite it later -copy
if ($auth == false) {
    die(header("Location: /Login/Default.aspx"));
}
$uid = (int)$_GET['RecipientID'];
$idd = (int)$_GET['InvitationID'];
if(!$uid && !$idd) {
       exit(header("location: /Error/Default.aspx"));
}
if (isset($_REQUEST['RecipientID'])) {

$stmt = $con->prepare("
  
      SELECT * 
    FROM friends 
    WHERE (user_from = :uid AND arefriends = 1 AND user_to = :to) 
       OR (user_to = :uid AND arefriends = 1 AND user_from = :to)
        OR (user_to = :uid AND arefriends = 0 AND user_from = :to)
         OR (user_from = :uid AND arefriends = 0 AND user_to = :to)
");
        $stmt->bindParam(':uid', $_USER['id'], PDO::PARAM_INT);
        $stmt->bindParam(':to', $uid, PDO::PARAM_INT);
        $stmt->execute();
         if($stmt->rowCount() != 0) {
          exit(header("location: /Error/Default.aspx"));
}
        $stmt = $con->prepare("SELECT * FROM users WHERE id = :uid");
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if($user['id'] == $_USER['id'] || $stmt->rowCount() == 0) {
          exit(header("location: /Error/Default.aspx"));
}
?>

<script>
  function SubmitForm(token) {
      document.getElementById("msgform").submit();
  }
</script>

<div id="Body">
    <div class="MessageContainer">
        <div id="AdsPane"></div>
        <div id="MessagePane">
            <?php
           $error = null;
           if (isset($_POST['subject'])) {

                $subject = htmlspecialchars(trim($_POST['subject']));
                $message = htmlspecialchars(trim($_POST['message']));
                
                if(empty($subject)) {
               echo ("message needs an subject (too lazy to make it look nicer)");
               exit;
            }
                $currenttimelol = time();
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
        echo("You are being ratelimited.");
        die();
  
    }
             $timeout = time() + 25;  
             $skibidi = $con->prepare("UPDATE users SET renderedfirsttime = :timeout WHERE id = :userid");

    $skibidi->execute([

        'timeout' => $timeout,

        'userid' => $_USER['id'],

    ]);    
           

             $stmt = $con->prepare("INSERT INTO `friends` (`user_from`, `user_to`, `arefriends`,  `subject`, `content`) VALUES (:user_from, :user_to, :arefriends, :subject, :content)");
                $stmt->execute([
                    ':user_from' => $_USER['id'],
                    ':user_to' => $uid,
                     ':arefriends' => 0,
                      
                ':subject' => $subject,
                        ':content' => $message,
                ]);

       
              ?>
             
			<div id="ctl00_cphGoldblox_pConfirmation">
				<div id="Confirmation">
					<h3>Request Sent</h3>
					<div id="Message"><span id="ctl00_cphGoldblox_lConfirmationMessage">Your friend request has been sent to <?=$user['username']?>.</span></div>
					<div class="Buttons"><a id="ctl00_cphGoldblox_lbContinue" class="Button" href="/User.aspx?ID=<?=$user['id']?>">Continue</a></div>
				</div>
			
</div>
		</div>
		<div style="clear: both;"></div>	
	</div>

				</div>
	<?php
	include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php";
	die();
			
		}
	


?>

            <form method="post" id='msgform'>
                <h3>Your Friend Request</h3>
                <div id="MessageEditorContainer">
                    <div class="MessageEditor">
                        <table width="100%" style="font-size: 12px;">
                            <tbody>
                                <tr valign="top">
                                    <td style="width:12em">
                                        <div id="From">
                                            <span class="Label">
                                                <span id="MsgFrom">From:</span>
                                            </span>
                                            <span class="Field">
                                                <span id="MsgAuthor"><?= htmlspecialchars($_USER['username']); ?></span>
                                            </span>
                                        </div>
                                        <div id="To">
                                            <span class="Label">
                                                <span id="MsgTo">Send To:</span>
                                            </span>
                                            <span class="Field">
                                                <span id="MsgRecipient"><?= htmlspecialchars($user['username']); ?></span>
                                            </span>
                                        </div>
                                    </td>
                                    <td style="padding:0 24px 6px 12px">
                                        <div id="Subject">
                                            <div class="Label">
                                                <label id="MsgSubjectText">Subject:</label>
                                            </div>
                                            <div class="Field">
                                                <input name="subject" type="text" id="MsgSubject" class="TextBox" style="width:100%;" value="">
                                            </div>
                                        </div>
                                        <div class="Body">
                                            <div class="Label">
                                                <label id="MsgBodyTitle">Message:</label>
                                            </div>
                                            <textarea name="message" rows="2" cols="20" id="MsgBody" class="MultilineTextBox" style="width:100%;"></textarea>
                                        </div>
                                      
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div class="Buttons">
                    <input name="sd" data-callback='SubmitForm' value="Send" id="Send" class="Button" type="submit">
                </div>
            </form>
      </div> </div> </div>
       <?}?>
        
    


<?php
if (isset($_GET['InvitationID'])) {

$msql = $con->prepare("SELECT * FROM friends WHERE `id` = :id AND `user_to` = :uid AND `arefriends` = 0");
   
   $msql->bindParam(':id', $_REQUEST['InvitationID'], PDO::PARAM_INT);
       $msql->bindParam(':uid', $_USER['id'], PDO::PARAM_INT);
    $msql->execute();
    $msg = $msql->fetch();
if (!$msg) {
        header("Location: /Error/Default.aspx");
        exit();
    }
    $userq = $con->prepare("SELECT * FROM users WHERE id = :id");
    $userq->execute([':id' => $msg['user_from']]);
    $user = $userq->fetch();
$accept = "". $_USER['username'] ." accepted your friend request.";
$decline = "". $_USER['username'] ." declined your friend request.";
if ($_REQUEST['Accept']) {
  $stmt = $con->prepare("INSERT INTO `messages` (`user_from`, `user_to`, `subject`, `content`, `datesent`) VALUES (:user_from, :user_to, :subject, :content, :datesent)");
                $stmt->execute([
                    ':user_from' => 1,
                    ':user_to' => $msg['user_from'],
                    ':subject' => "Friend request accepted.",
                    ':content' =>$accept,
                    ':datesent' => time()
                ]);
$q = $con->prepare("UPDATE friends SET `arefriends` = 1 WHERE `id` = :id");
$q->bindParam(':id', $_REQUEST['InvitationID'], PDO::PARAM_INT);
 $q->execute(); 
 header("Location: /User.aspx");
        exit();
    
}
if ($_REQUEST['Decline']) {
$stmt = $con->prepare("INSERT INTO `messages` (`user_from`, `user_to`, `subject`, `content`, `datesent`) VALUES (:user_from, :user_to, :subject, :content, :datesent)");
                $stmt->execute([
                    ':user_from' => 1,
                    ':user_to' => $msg['user_from'],
                    ':subject' => "Friend request declined.",
                    ':content' =>$decline,
                    ':datesent' => time()
                ]);
$q = $con->prepare("DELETE FROM friends WHERE `id` = :id");
$q->bindParam(':id',  $_REQUEST['InvitationID'], PDO::PARAM_INT);
$q->execute(); 
 header("Location: /User.aspx");
        exit();
    
}
?>

<div id="Body">
      
  <div class="MessageContainer">
    <div id="MessagePane">
    <div id="ctl00_cphGoldblox_pPrivateMessage">
  
    <div id="ctl00_cphGoldblox_pPrivateMessageReader">
  
      <h3>Friend Request</h3>
      <div class="MessageReaderContainer">
        
<div id="Message">
  <table width="100%">
    <tr valign="top">
      <td style="width: 10em">
        <div id="DateSent"><?php echo date('n/j/Y g:i:s A', $msg['datesent']); ?></div>
        <div id="Author">
          
          <a id="ctl00_cphGoldblox_rbxMessageReader_Avatar" disabled="disabled" title="<?=htmlspecialchars($user['username']);?>" onclick="return false" style="display:inline-block;height:64px;width:64px;"><img src="/Thumbs/Avatar.ashx?assetId=<?=$user['id'];?>&IsSmall" border="0" id="img" alt="<?=htmlspecialchars($user['username']);?>" height="64px"/></a><br />
          <a id="ctl00_cphGoldblox_rbxMessageReader_AuthorHyperLink" title="Visit <?=htmlspecialchars($user['username']);?>'s Home Page" href="/User.aspx?ID=<?=$user['id'];?>"><?=htmlspecialchars($user['username']);?></a>
        </div>
        <div id="Subject">
          <?=nl2br($msg['subject']);?><br />
          <br />
          <div id="ctl00_cphGoldblox_rbxMessageReader_AbuseReportButton_AbuseReportPanel" class="ReportAbusePanel">
    
  <span class="AbuseIcon"><a id="ctl00_cphGoldblox_rbxMessageReader_AbuseReportButton_ReportAbuseIconHyperLink" href="../AbuseReport/Message.aspx?ID=2274781&amp;ReturnUrl=http%3a%2f%2fwww.roblox.com%2fMy%2fPrivateMessage.aspx%3fMessageID%3d2274781"><img src="/images/abuse.gif" alt="Report Abuse" style="border-width:0px;" /></a></span>
  <span class="AbuseButton"><a id="ctl00_cphGoldblox_rbxMessageReader_AbuseReportButton_ReportAbuseTextHyperLink" href="../AbuseReport/Message.aspx?ID=2274781&amp;ReturnUrl=http%3a%2f%2fwww.roblox.com%2fMy%2fPrivateMessage.aspx%3fMessageID%3d2274781">Report Abuse</a></span>
  </div>
        </div>
      </td>
      <td style="padding: 0 10px 0 10px">
        <div class="Body">
          <div id="ctl00_cphGoldblox_rbxMessageReader_pBody" class="MultilineTextBox" style="height:250px;overflow-y:scroll;width:455px;">
    
            <?=nl2br($msg['content']);?>
          
  </div>
       
        </div>
         <p style="color:red;">
                                            <b>Remember, GOLDBLOX staff will never ask you for your <br>password.<br>
                                                People who ask for your password are trying to steal <br>your account.
                                            </b>
                                        </p> 
      </td>
    </tr>
  </table>
</div>
      
        <div style="clear:both"></div>

   
      </div>
            <div class="Buttons">
                <a id="ctl00_cphGoldblox_lbCancel" class="Button" href="/User.aspx">Cancel</a>
                 <form method="post">
               <input name="Decline" value="Decline" class="Button" type="submit">
               </form>
 <form method="post" >
 <input name="Accept" value="Accept" class="Button" type="submit">
               
           </form>
            </div>
        
      <div style="clear:both"></div>
    
  </div> 
    
    
</div>
    
  </div>

<?} else {
  
} ?>
  <div style="clear: both;"></div>
  </div>
    </div>
<?php
require ("../api/web/footer.php");
?>