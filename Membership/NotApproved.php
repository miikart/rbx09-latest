
<?php
$onlybanner = true;
require_once '../api/web/header.php'; 
if ($auth && $_USER['bantype'] === 'None' ) {
        header("Location: /Default.aspx");
        exit;
    }
if($auth == false ){
  
  header("Location: /Login/Default.aspx");
exit;
}
 ?>
<title>GOLDBLOX | Disabled Account</title>
   <div style="margin: 150px auto 150px auto; width: 500px; border: black thin solid; padding: 22px;">
        <h2>
          <?php if($_USER['bantype'] == 'Reminder') {echo 'Reminder';} elseif($_USER['bantype'] == 'Warning') {echo 'Warning';} elseif($_USER['bantype'] == 'Ban') {echo 'Account Deleted';} ?>
        </h2>
        <p>
            Our content monitors have determined that your behavior at GOLDBLOX has been in violation of our Terms of Service. We will terminate your account if you do not abide by the rules.</p>
        <p>
            Reason:<span style="font-weight: bold">
                <?php if(!empty($_USER['banreason'])) { ?>
               <?= ($_USER['banreason']); ?>
               <?php } else {?>
             No reason given.
             <?php } ?></span>
            <br />
            </span>
            Source:<span style="font-weight: bold">
                <?php if(!empty($_USER['bansource'])) { ?>
               <?= ($_USER['bansource']); ?>
               <?php } else {?>
             No source given.
             <?php } ?>
            <br />
            </span>
            Reported:<span style="font-weight: bold">
                <?php if(!empty($_USER['bantime']) || $_USER['bantime'] != 0) { ?>
             <?= date("n/j/Y h:i:s A",$_USER['bantime']); ?>
               <?php } else {?>
             No report time given.
             <?php } ?>
               </span>
        
        </p>
        <p>
            <span style="font-weight: bold">
             <?php if(!empty($_USER['banthingy'])) { ?>
               <?= ($_USER['banthingy']); ?>
               <?php } else {?>
             No description given.
             <?php } ?>
               </span>
        
        </p>
        <p>
            Please abide by the <a href="#">GOLDBLOX Community Guidelines</a> so that GOLDBLOX can be fun for users of all ages.
        </p>
  <p><?php if($_USER['bantype'] == 'Ban') {?>Your account has been terminated.<?php } ?><br>
  <?php if($_USER['bantype'] !== 'Ban') { ?><a  href="/Reactivate.ashx">Reactivate account</a>
      
    </div> <?php }?>  </p>
</div>
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"); die();  ?>