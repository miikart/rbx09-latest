<?php
include $_SERVER["DOCUMENT_ROOT"]."/api/web/config.php";


if(!$auth == true){
    exit(header("Location: /Login/Default.aspx"));
}

try {
   $q = $con->prepare("SELECT * FROM messages WHERE user_to = :user_to AND isrequest = 0 AND deleteto = 0 ORDER BY id DESC");
    $q->bindParam(':user_to', $_USER['id'], PDO::PARAM_INT);
    $q->execute();
    if($q->rowCount() == 0){
        $emptymessage = "Looks like your inbox is empty.";
    }
    $thingy = $q->fetchAll();
    $resultsper = 20;
    $numberofpages = ceil(count($thingy) / $resultsper);
    if(isset($_REQUEST['page'])) {
    $page = (int)$_REQUEST['page'];
    } else {
      $page = 1;  
    }
    if($page > $numberofpages) {
    $page = $numberofpages; 
    }
    $firstresult = max(0, ($page - 1) * $resultsper);
    $q = $con->prepare("SELECT * FROM messages WHERE user_to = :user_to AND isrequest = 0 AND deleteto = 0 ORDER BY id DESC LIMIT :firstresult, :perpage");
    $q->bindParam(':user_to', $_USER['id'], PDO::PARAM_INT);
    $q->bindParam(':firstresult', $firstresult, PDO::PARAM_INT);
    $q->bindParam(':perpage', $resultsper, PDO::PARAM_INT);
    $q->execute();
    $q = $q->fetchAll();
} catch(PDOException $e) {
    die("fart: " . $e->getMessage());
}
?>

<div>
    <table cellspacing="0" cellpadding="3" border="0" style="border:1px solid black;width:726px;border-collapse:collapse;">
        <tbody>
        <?php if(!$emptymessage) { ?>
            <tr class="InboxHeader">
                <th align="left" scope="col">
                    <input id="SelectAllCheckBox" type="checkbox" name="SelectAllCheckBox">
                </th>
                <th align="left" scope="col">
                    <a>Subject </a>
                </th>
                <th align="left" scope="col">
                    <a>From </a>
                </th>
                <th align="left" scope="col">
                    <a>Date</a>
                </th>
            </tr>
           
           <?php } ?>
            <?php  foreach ($q as $row) {
            $q = $con->prepare("SELECT * FROM users WHERE id = :id");
            $q->bindParam(':id', $row['user_from'], PDO::PARAM_INT);
            $q->execute();
            $user = $q->fetch(PDO::FETCH_ASSOC);
            $time = date('d/m/Y g:i:s A', $row['datesent']);
            ?>
            <tr class="InboxRow">
                <td>
                    <span style="display:inline-block;width:25px;"><input id="<?php echo $row['id'] ?>" type="checkbox" class="DeleteCheckBox"></span>
                </td>
                <td align="left">
                    <a href="/My/PrivateMessage.aspx?MessageID=<?php echo $row['id'] ?>" style="display:inline-block;width:325px;"><?php echo $row['subject']?></a>
                </td>
                <td align="left">
                    <a id="Author" title="Visit <?=$username?>'s Home Page" href="/User.aspx?ID=<?php echo $user['id'] ?>" style="display:inline-block;width:175px;"><?php echo $user['username'] ?> <?php if($row['user_from'] == 1) { ?>[System Message]<?php } ?></a>
                </td>
                <td align="left">
                    <?=$time?>
                </td>
            </tr>
            <?php } ?>
    
    <?php if(!$emptymessage) { ?>
   
       <tr class="InboxPager">
			<td colspan="4"><table border="0">
				<tbody><tr>
				
				<?php 
				 for ($i = 1; $i <= $numberofpages; $i++) {
        if ($i === $page) {
            echo "<td><span>{$i}</span></td>";
        } else {
            echo "<td><a onclick=\"getinbox({$i})\" href=\"javascript:void(0);\">{$i}</a></td>
          ";
        }
				 }
    ?>

  <?php } ?>
				
				</tr>
			</tbody></table></td>
		</tr>
        </tbody>
    </table>
</div>
<?php if(!empty($emptymessage)){ ?>
    <p style="margin-top:0;border:1px solid black;width:724px;border-collapse:collapse;padding-top:5px;padding-bottom:5px"><?=$emptymessage?></p>
<?php } ?>
        <?php if(!$emptymessage) { ?>

<div class="Buttons">
    <input onclick="oof" id="DeleteMsgs" class="Button" value="Delete" type="submit">
    <a class="Button" href="/User.aspx">Cancel</a>
</div>
<?php } ?>
	<script>

					$(document).ready(function() {
						$("#SelectAllCheckBox").change(function() {
							$("input:checkbox").prop('checked', $(this).prop("checked"));
						});
						$("#DeleteMsgs").click(function() {
							var selected = [];
							$('tr input:checked:not("#SelectAllCheckBox")').each(function() {
								selected.push($(this).attr('id'));
							});
							$.post("/api/delinboxmsg.aspx", {delMsgs: JSON.stringify(selected)}, function(data) 
							{
								if (data === "success" || data === "no post"){
									getinbox(1)
								}else{
									$("#Inbox").empty();
									$("#Inbox").html(data);
								}
							})
							.fail(function() 
							{
								$("#Inbox").empty();
								$("#Inbox").html(data);
							});
						});
					});
				</script>
