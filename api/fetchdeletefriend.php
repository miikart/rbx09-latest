<?php
require ("../api/web/config.php");
if (!$auth) {
  header('Location: /Login/Default.aspx');
  exit();
}

$id = (int)$_USER['id'];

try {
 
    $stmt = $con->prepare("SELECT * FROM users WHERE id = :id AND bantype = 'None'");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      
        exit();
    }
    


    $resultsperpage = 30;
    
    $stmt = $con->prepare("SELECT * FROM friends WHERE (user_from = :id AND arefriends = '1') OR (user_to = :id AND arefriends = '1')");
    $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
    $stmt->execute();
    
    $usercount = $stmt->rowCount();
    $numberofpages = ceil($usercount / $resultsperpage);

    if(!isset($_GET['Page'])) {
        $page = 1;
    } else {
        $page = (int)$_GET['Page'];
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

  <div id="ctl00_cphGoldblox_rbxFriendsPane_Pager1_PanelPages" align="center">
    Pages:
<?php if($numberofpages > 0){?>
<?php if($page > 1 && $numberofpages != 1){$gage=$page-1;$mage=$page+1;?>
<a id="ctl00_cphGoldblox_rbxFriendsPane_Pager1_LinkButtonPrevious" href="?Page=<?=$page-1;?>"><< Previous</a>
<?php }?>
<?php if($page < $numberofpages){?>
<a id="ctl00_cphGoldblox_rbxFriendsPane_Pager1_LinkButtonNext" href="?Page=<?=$page+1;?>">Next &gt;&gt;</a>  
<?php }?>  
<?php }?>
</div>
  <table id="ctl00_cphGoldblox_rbxFriendsPane_dlFriends" cellspacing="0" align="Center" border="0">
  <tr>
<?php
$thispagefirstresult = ($page-1) * $resultsperpage;

try {
    $stmt = $con->prepare("SELECT * FROM friends WHERE (user_from = :id AND arefriends = '1') OR (user_to = :id AND arefriends = '1') ORDER by id DESC LIMIT :start, :limit");
    $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
    $stmt->bindParam(':start', $thispagefirstresult, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $resultsperpage, PDO::PARAM_INT);
    $stmt->execute();

    $friendcount = $stmt->rowCount();
    
    if ($friendcount < 1) {
        echo "<div class='NoResults'>You don't have any GOLDBLOX friends.</div>";
    } else {
        echo "<div class=\"columns\">";
        $total = 0;
        $cinnamonroll = 0;
        
        while ($friend = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($total == $total) {
                $friendid = 0;
                if ($friend['user_from'] == $row['id']) {
                    $friendid = $friend['user_to'];
                } else {
                    $friendid = $friend['user_from'];
                }

                $stmt2 = $con->prepare("SELECT * FROM users WHERE id = :friendid");
                $stmt2->bindParam(':friendid', $friendid, PDO::PARAM_INT);
                $stmt2->execute();
                
                $finfo = $stmt2->fetch(PDO::FETCH_ASSOC);

                $usrlol = $finfo;
                
                $now = time(); 
                $theyare = $usrlol['expiretime'] > $now ? "nline" : "ffline";
              
?>
<td>
    <div class="Friend" onmouseover="this.style.borderStyle='outset';this.style.margin='6px'" onmouseout="this.style.borderStyle='none';this.style.margin='10px'" style="border-style: none; margin: 10px;">
    <div class="Avatar"><a id="ctl00_cphGoldblox_rbxFriendsPane_dlFriends_ctl00_hlAvatar" title="<?=$usrlol['username'];?>" href="/User.aspx?ID=<?=$usrlol['id'];?>" style="display:inline-block;cursor:pointer;"><img src="/Thumbs/Avatar.ashx?assetId=<?=$usrlol['id'];?>&IsSmall" border="0" alt="<?=$usrlol['username'];?>" blankurl="http://t6-cf.roblox.com/blank-100x100.gif" height="100"/></a></div>
    <div class="Summary">
     
      <span class="OnlineStatus"><img id="ctl00_cphGoldblox_rbxFriendsPane_dlFriends_ctl00_iOnlineStatus" src="/images/OnlineStatusIndicator_IsO<?=$theyare;?>.gif" alt="<?=$usrlol['username'];?> is o<?=$theyare;?>." border="0"/></span>
      <span class="Name"><a id="ctl00_cphGoldblox_rbxFriendsPane_dlFriends_ctl00_hlFriend" href="/User.aspx?ID=<?=$usrlol['id'];?>"><?=$usrlol['username'];?></a></span><br><br>
      <button id="ctl00_cphGoldblox_rbxLoginView_lvLoginView_lSignIn_Login" onclick="deletefriend(<?=$usrlol['id'];?>);">Delete</button>
    </div>
    </div>
  </td>
<?php
                $total++;
                $cinnamonroll++;
                if ($cinnamonroll >= 6) {
                    echo "</tr></div><tr><div class=\"columns\">";
                    $cinnamonroll = 0;
                }
            }
        }
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
</tr></tbody></table>
<style>
fix {
  display: table-cell;
  vertical-align: inherit;
}
</style>

