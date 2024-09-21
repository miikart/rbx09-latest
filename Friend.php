<?php
   require_once("api/web/header.php");
if($auth == false){
    header("Location: /Login/Default.aspx");
    exit;
}

$id = (int)$_GET['UserID'];
if(!$id) {
    header('HTTP/1.1 404 Not Found');
    include("error.php");
    exit;
}

try {
  
    $stmt = $con->prepare("SELECT * FROM users WHERE id = :id AND bantype = 'None'");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row) {
        header('HTTP/1.1 404 Not Found');
        include("error.php");
        exit;
    }

    $title = $sitename . ": " . htmlspecialchars($row['username']) . "'s Friends";
 

    $resultsperpage = 30;

    
    $stmt = $con->prepare("SELECT COUNT(*) FROM friends WHERE (`user_from` = :id AND `arefriends` = 1) OR (`user_to` = :id AND `arefriends` = 1)");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $usercount = $stmt->fetchColumn();

    $numberofpages = ceil($usercount / $resultsperpage);
    $page = isset($_GET['Page']) ? (int)addslashes($_GET['Page']) : 1;

 
?>
<div id="FriendsContainer">
    <div id="Friends">
        <h4><?= htmlspecialchars($row['username']); ?>'s Friends (<?= $usercount; ?>)</h4>
        <div id="ctl00_cphGoldblox_rbxFriendsPane_Pager1_PanelPages" align="center">
            Pages:
            <?php if($numberofpages > 0){ ?>
                <?php if($page > 1 && $numberofpages != 1){ ?>
                    <a id="ctl00_cphGoldblox_rbxFriendsPane_Pager1_LinkButtonPrevious" href="?UserID=<?= $row['id']; ?>&Page=<?= $page-1; ?>"><< Previous</a>
                <?php } ?>
                <?php if($page < $numberofpages){ ?>
                    <a id="ctl00_cphGoldblox_rbxFriendsPane_Pager1_LinkButtonNext" href="?UserID=<?= $row['id']; ?>&Page=<?= $page+1; ?>">Next &gt;&gt;</a>
                <?php } ?>
            <?php } ?>
        </div>
        <div id="Friends">
            <table id="ctl00_cphGoldblox_rbxFriendsPane_dlFriends" cellspacing="0" align="Center" border="0">
                <tr>
                    <?php
                    $thispagefirstresult = ($page-1) * $resultsperpage;
                    $stmt = $con->prepare("SELECT * FROM friends WHERE (`user_from` = :id AND `arefriends` = 1) OR (`user_to` = :id AND `arefriends` = 1) ORDER by id DESC LIMIT :start, :limit");
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':start', $thispagefirstresult, PDO::PARAM_INT);
                    $stmt->bindParam(':limit', $resultsperpage, PDO::PARAM_INT);
                    $stmt->execute();
                    $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if(empty($friends)) {
                        echo "<div class='NoResults'>" . htmlspecialchars($row['username']) . " does not have any Friends.</div>";
                    } else {
                        echo "<div class=\"columns\">";
                        $total = 0;
                        $cinnamonroll = 0;

                        foreach ($friends as $friend) {
                            $friendid = ($friend['user_from'] == $row['id']) ? $friend['user_to'] : $friend['user_from'];
                            $stmt = $con->prepare("SELECT * FROM users WHERE id = :friendid");
                            $stmt->bindParam(':friendid', $friendid, PDO::PARAM_INT);
                            $stmt->execute();
                            $usrlol = $stmt->fetch(PDO::FETCH_ASSOC);

                            if($usrlol['expiretime'] > $now) {
                                $theyare = "nline";
                            } else {
                                $theyare = "ffline";
                                $lastyyy = " (last seen at 12/12/2007 4:56:27 PM)";
                            }
                    ?>
                            <td>
                                <div class="Friend">
                                    <div class="Avatar">
                                        <a id="ctl00_cphGoldblox_rbxFriendsPane_dlFriends_ctl00_hlAvatar" title="<?= htmlspecialchars($usrlol['username']); ?>" href="/User.aspx?ID=<?= $usrlol['id']; ?>" style="display:inline-block;cursor:pointer;">
                                            <img src="/Thumbs/Avatar.ashx?assetId=<?= $usrlol['id']; ?>&IsSmall" border="0" alt="<?= htmlspecialchars($usrlol['username']); ?>" blankurl="http://t6-cf.roblox.com/blank-100x100.gif" height="100"/>
                                        </a>
                                    </div>
                                    <div class="Summary">
                                        <span class="OnlineStatus">
                                            <img id="ctl00_cphGoldblox_rbxFriendsPane_dlFriends_ctl00_iOnlineStatus" src="/images/OnlineStatusIndicator_IsO<?= $theyare; ?>.gif" alt="<?= htmlspecialchars($usrlol['username']); ?> is o<?= $theyare; ?><?= $lastyyy; ?>." border="0"/>
                                        </span>
                                        <span class="Name">
                                            <a id="ctl00_cphGoldblox_rbxFriendsPane_dlFriends_ctl00_hlFriend" href="/User.aspx?ID=<?= $usrlol['id']; ?>"><?= htmlspecialchars($usrlol['username']); ?></a>
                                        </span>
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
                        echo "</div>";
                    }
                    ?>
                </tr>
            </table>
            <style>
                fix {
                    display: table-cell;
                    vertical-align: inherit;
                }
            </style>
            <?php  require_once("api/web/footer.php"); ?>
        </div>
    </div>
</div>
<?php
} catch (PDOException $e) {
    echo 'skibidi: ' . $e->getMessage();
}
?>
