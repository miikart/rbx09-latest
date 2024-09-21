<?php
require_once("../api/web/header.php");

if ($auth == false) {
  
    die(header("Location: /Login/Default.aspx"));
}

if (isset($_GET['RecipientID']) || isset($_GET['replyto'])) {
{
    $uid = intval($_GET['RecipientID'] ?? 0);
    $replyto = intval($_GET['replyto'] ?? 0);

    try {
        $stmt = $con->prepare("SELECT * FROM users WHERE id = :uid");
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->execute();

        if (!isset($_GET['replyto'])) {
            if (($stmt->rowCount() < 1) || ($uid == $_USER['id'])) {
                header("Location: /Error/Default.aspx");
                exit;
            }
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $reply = false;

        if ($replyto != 0) {
            $stmt = $con->prepare("SELECT * FROM messages WHERE user_to = :user_to AND  deleteto = '0' AND id = :replyto");
            $stmt->bindParam(':user_to', $_USER['id'], PDO::PARAM_INT);
            $stmt->bindParam(':replyto', $replyto, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() != 0) {
            
                $reply = true;
                $reply_msg = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt = $con->prepare("SELECT * FROM users WHERE id = :user_from");
                $stmt->bindParam(':user_from', $reply_msg['user_from'], PDO::PARAM_INT);
                $stmt->execute();

                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $uid = $user['id'];
                $lolok = "RE: " . $reply_msg['subject'];
                $append = "\n\n------------------------------\nOn " . date("m/d/Y", $reply_msg['datesent']) . " at " . date('n/j/Y g:i:s A', $reply_msg['datesent']) . " " . htmlspecialchars($user['username']) . " wrote:\n" . $reply_msg['content'];
            }
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
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
                $stmt = $con->prepare("INSERT INTO `messages` (`user_from`, `user_to`, `subject`, `content`, `datesent`) VALUES (:user_from, :user_to, :subject, :content, :datesent)");
                $stmt->execute([
                    ':user_from' => $_USER['id'],
                    ':user_to' => $uid,
                    ':subject' => $subject,
                    ':content' => $message,
                    ':datesent' => time()
                ]);

                if ($replyto != 0) {
                     header("Location: /My/Inbox.aspx");
                exit;
                    
                } else {
              ?>
             
			<div id="ctl00_cphGoldblox_pConfirmation">
				<div id="Confirmation">
					<h3>Message Sent</h3>
					<div id="Message"><span id="ctl00_cphGoldblox_lConfirmationMessage">Your message has been sent to <?=$user['username']?>.</span></div>
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
	
	}
	

?>

            <form method="post" id='msgform'>
                <h3>Your Message</h3>
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
                                                <input name="subject" type="text" id="MsgSubject" class="TextBox" style="width:100%;" value="<?= $lolok ?? ''; ?>">
                                            </div>
                                        </div>
                                        <div class="Body">
                                            <div class="Label">
                                                <label id="MsgBodyTitle">Message:</label>
                                            </div>
                                            <textarea name="message" rows="2" cols="20" id="MsgBody" class="MultilineTextBox" style="width:100%;"><?= $append ?? ''; ?></textarea>
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
{
if (isset($_GET['MessageID'])) {
    $msql = $con->prepare("SELECT * FROM messages  WHERE `id` = :id  AND isrequest = 0");
    $msql->execute([':id' => (int)$_GET['MessageID']]);
    $msg = $msql->fetch();
if (!$msg) {
        header("Location: /Error/Default.aspx");
        exit();
    }
    $userq = $con->prepare("SELECT * FROM users WHERE id = :id");
    $userq->execute([':id' => $msg['user_from']]);
    $user = $userq->fetch();

    if ($msg['user_to'] != $_USER['id'] && $_USER['USER_PERMISSIONS'] !== 'Administrator') {
        header("Location: /My/Inbox.aspx");
        exit;
    } else {
        if ($msg['user_to'] == $_USER['id']) {
            $yeet = $con->prepare("UPDATE messages SET `readto` = '1' WHERE `id` = :id");
            $yeet->execute([':id' => (int)$msg['id']]);

            if (isset($_POST['delete'])) {
                $yeet = $con->prepare("UPDATE  messages SET  `deleteto` = '1'  WHERE `id` = :id");
                $yeet->execute([':id' => (int)$msg['id']]);
                header("Location: /My/Inbox.aspx");
                exit;
            }
        }
    }
?>

<div id="Body">
      
  <div class="MessageContainer">
    <div id="MessagePane">
    <div id="ctl00_cphGoldblox_pPrivateMessage">
  
    <div id="ctl00_cphGoldblox_pPrivateMessageReader">
  
      <h3>Private Message</h3>
      <div class="MessageReaderContainer">
        
<div id="Message">
  <table width="100%">
    <tr valign="top">
      <td style="width: 10em">
        <div id="DateSent"><?=date("n/j/Y g:i:s A",$msg['datesent']);?></div>
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
         <?php if($msg['isrequest']){ ?>
            <div class="Buttons">
                <a id="ctl00_cphGoldblox_lbCancel" class="Button" href="/My/Inbox.aspx">Cancel</a>
                <a id="ctl00_cphGoldblox_lbDecline" class="Button" href="/My/DeclineInvitation.aspx?ID=<?=$user['id']?>">Decline</a>
                <a id="ctl00_cphGoldblox_lbAccept" class="Button" href="/My/AcceptInvitation.aspx?ID=<?=$user['id']?>">Accept</a>
            </div>
            <?php }else{ ?>
            <div class="Buttons">
                <a id="Cancel" class="Button" href="/My/Inbox.aspx">Cancel</a>
                <form method="post">
                    <input name="delete" value="Delete" class="Button" type="submit">
              
                <a href="/My/PrivateMessage.aspx?RecipientID=<?=$msg['user_from']?>&replyto=<?=$msg['id']?>" id="reply" class="Button">Reply</a>
            </div>
            <?php } ?>
      <div style="clear:both"></div>
    
  </div> 
    
    
</div>
    
  </div>

<?}}?>
  <div style="clear: both;"></div>
  </div>
    </div>
<?php
require ("../api/web/footer.php");
?>