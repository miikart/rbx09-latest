<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/api/web/config.php";
if(!$auth){
    exit(header("Location: /Login/Default.aspx"));
}
if($_REQUEST['get']) {
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
                    From 
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
            $time = date('n/j/Y g:i:s A', $row['datesent']);
            ?>
          <?php if($row['readto'] == 1) { ?>
            <tr class="InboxRow">
               <?php } else { ?>
               <tr class="InboxRow_Unread">
               <?php } ?>
                <td>
                    <span style="display:inline-block;width:25px;"><input id="<?php echo $row['id'] ?>" type="checkbox" class="DeleteCheckBox"></span>
                </td>
                <td align="left">
                <a href="/My/PrivateMessage.aspx?MessageID=<?php echo $row['id'] ?>" style="display:inline-block;width:325px;"><?php echo $row['subject']?></a>
                </td>
                <td align="left">
                    <a id="Author" title="Visit <?php echo $user['username'] ?>'s Home Page" href="/User.aspx?ID=<?php echo $user['id'] ?>" style="display:inline-block;width:175px;"><?php echo $user['username'] ?> <?php if($row['user_from'] == 1) { ?>[System Message]<?php } ?></a>
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
			    $pagefix = $page + 9;
                if ($pagefix > $numberofpages) {
                    $pagefix = $numberofpages;
                }
                $steps = [9,8,7,6, 5, 4, 3, 2, 1];
                foreach ($steps as $step) {
                    $prevviuzs = $page - $step;
                    if ($prevviuzs > 0) {
                        echo "
                      <td><a onclick=\"getinbox({$prevviuzs})\" href=\"javascript:void(0);\">{$prevviuzs}</a></td>
                        ";
                    }
                }

                echo "
               <td><span>{$page}</span></td>
                ";

                for ($i = $page + 1; $i <= $pagefix; $i++) {
                    echo "
                   <td><a onclick=\"getinbox({$i})\" href=\"javascript:void(0);\">{$i}</a></td>
                    ";
                }
if($page == $numberofpages && $page != 1) {
     echo "
                  <td><a onclick=\"getinbox(1)\" href=\"javascript:void(0);\"> ...<<</a></td>
               
                ";	   
} else {
                echo "
                  <td><a onclick=\"getinbox({$numberofpages})\" href=\"javascript:void(0);\"> ...>></a></td>
               
                ";			
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
<p style="margin:0;border:1px solid black;width:724px;border-collapse:collapse;padding-top:5px;padding-bottom:5px"><?=$emptymessage?></p>
<?php } ?>
<?php if(!$emptymessage) { ?>
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
 if (data === "success"){
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
<div class="Buttons">
    <input id="DeleteMsgs" class="Button" value="Delete" type="submit">
    <a class="Button" href="/User.aspx">Cancel</a>
</div>
<?php } ?>
<?php exit; } ?>
<?php require_once $_SERVER["DOCUMENT_ROOT"]."/api/web/header.php"; ?>
<title>GOLDBLOX - Inbox</title> 
<script>
 function getinbox(page,event) 
 {
 if(page == undefined){ page = 1; }
 if(event != undefined){ event.preventDefault(); }
 $.post("/My/Inbox.aspx?get=true", {page:page}, function(data) 
 {
 $("#Inbox").empty();
 $("#Inbox").html(data);
 })
 .fail(function() 
 {
 $("#Inbox").html("Failed to get inbox");
 });
 }
 getinbox(1)
</script>
<div id="Body">
    <div id="InboxContainer">
     <div class="Ads_WideSkyscraper"></div> 
        <div id="InboxPane">
            <h2>Inbox</h2>
            <div id="Inbox" style="border: 0px;">
                
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
<div style="clear: both;">
</div>
</div>
<?php require_once $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>