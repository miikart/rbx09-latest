<?php
require_once 'api/web/header.php';
$search = isset($_REQUEST['search']) ? htmlspecialchars($_REQUEST['search'], ENT_QUOTES, 'UTF-8') : '';
$sort = isset($_REQUEST['Sort']) ? htmlspecialchars($_REQUEST['Sort'], ENT_QUOTES, 'UTF-8') : '';
$username = '';

?>
 <div id="Body">
            

    <div id="ctl00_cphRoblox_ContainerPanel">
	
	    <div id="BrowseContainer" style="font-family: Verdana, Sans-Serif; text-align: center">
 <div>
            <form action="" method="POST">
                <div id="SearchBar" class="SearchBar">
                    <span class="SearchBox">
                        <input name="search" type="text" maxlength="100" id="ctl00_cphGoldblox_SearchTextBox" class="TextBox" value="<?= $search; ?>">
                    </span>
                    <span class="SearchButton">
                        <input type="submit" name="ctl00$cphGoldblox$SearchButton" value="Search" id="ctl00_cphGoldblox_SearchButton">
                    </span>
                    <span class="SearchLinks">
                        <sup><a id="ctl00_cphGoldblox_ResetSearchButton" href="/Browse.aspx">Reset</a>&nbsp;|</sup>
                        <a href="#" class="tips"><sup>Tips</sup>
                            <span>
                                Exact Phrase: "red brick"<br>
                                Find ALL Terms: red and brick =OR= red + brick<br>
                                Find ANY Term: red or brick =OR= red | brick<br>
                                Wildcard Suffix: tel* (Finds teleport, telamon, telephone, etc.)<br>
                                Terms Near each other: red near brick =OR= red ~ brick<br>
                                Excluding Terms: red and not brick =OR= red - brick<br>
                                Grouping operations: brick and (red or blue) =OR= brick + (red | blue)<br>
                                Combinations: "red brick" and not (tele* or tower) =OR= "red brick" - (tele* | tower)<br>
                                Wildcard Prefix is NOT supported: *port will not find teleport, airport, etc.
                            </span>
                        </a>
                    </span>
                </div>
            </form>
            <div class="SearchError"></div>
		    <br/><br/>
		    
				    
				    <div>
		<table class="Grid" style="margin: 0 auto;" cellspacing="0" cellpadding="4" border="0" id="ctl00_cphRoblox_gvUsersBrowsed">
			<tr class="GridHeader">
				<th scope="col">Avatar</th><th scope="col"><a href="/Browse.aspx?Sort=Name">Name</a></th><th scope="col">Status</th><th scope="col">Location / Last Seen</th>
			</tr>              <?php
                if ($sort == "Name") {
                    $sortthing = "username";
                } else {
                    $sortthing = "visittick DESC";
                }
                $resultsperpage = 10;

                $q = $con->prepare("SELECT * FROM users WHERE LOWER(username) LIKE LOWER(:search) AND bantype = 'None' AND poisoned != '1'");
                $q->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
                $q->execute();
                $usercount = $q->rowCount();
                $numberofpages = ceil($usercount / $resultsperpage);
                $page = isset($_REQUEST['Page']) ? (int)$_REQUEST['Page'] : 1;
                if($page > $numberofpages) {
                $page = $numberofpages; 
                }
             $thispagefirstresult = ($page - 1) * $resultsperpage;

                $q = $con->prepare("SELECT * FROM users WHERE LOWER(username) LIKE LOWER(:search) AND bantype = 'None' AND poisoned != '1' ORDER BY $sortthing LIMIT :start, :limit");
               $q->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);  
                $q->bindParam(':start', $thispagefirstresult, PDO::PARAM_INT);
                $q->bindParam(':limit', $resultsperpage, PDO::PARAM_INT);
                $q->execute();
                $q = $q->fetchAll();

                $now = time();
                foreach ($q as $row) {
                  
                    $id = ($row['id']);
                    $name = ($row['username']);
                    $blurbington = $row['blurb'];
                  if(!empty($row['blurb'])) {
                    if($filterenabled) {
                    $description = $ch->obfuscateIfProfane($row['blurb']);
                    } else {
                      $description = ($row['blurb']);    
                    }
                  }
                    ?>
                    <tr class='GridItem'>
                        <td>
                          <a id="ctl00_cphRoblox_gvUsersBrowsed_ctl03_hlAvatar" title="<?php echo ($name); ?>" href="/User.aspx?ID=<?php echo $id ?>" style="display:inline-block;cursor:pointer"><img src="/Thumbs/Avatar.ashx?assetId=<?php echo $id ?>&IsSmall" alt="<?php echo ($name); ?>" style="width:48px;height:48px;background-color:white"border="0"></a>
                           
                        </td>
                        <td>
                           <a id="ctl00_cphRoblox_gvUsersBrowsed_ctl02_hlName" href="/User.aspx?ID=<?php echo $id ?>"><?php echo $name; ?></a><br/>
                            <span id="ctl00_cphRoblox_gvUsersBrowsed_ctl02_lBlurb"><?php if(!empty($row['blurb'])) { ?><?php echo $description; ?><?php } ?></span>
                        </td>
                        <td><span>
                           <?php 
                            if ($row['expiretime'] < $now) {
                                echo "Offline";
                                $lastseen = date("n/j/Y g:i A", $row['visittick']);
                            } else {
                                echo "Online";
                                $lastseen = "Website"; 
                                $q = $con->prepare("SELECT playing FROM users WHERE id = :id");
                                $q->execute(['id' => $id]);
                                $gameRow = $q->fetch();
                                $gameshit = $gameRow['playing'];

                                if ($gameshit != 0) {
                                    $q = $con->prepare("SELECT name FROM catalog WHERE id = :gameid AND type ='place'");
                                    $q->execute(['gameid' => $gameshit]);
                                    $gameRow = $q->fetch();
                                    $lastseen = $gameRow['name']; 
                                }
                            }

                            echo "</span><br></td>
                            <td><span id='ctl00_cphGoldblox_gvUsersBrowsed_ctl02_lblUserLocationOrLastSeen'>".$lastseen."</span></td>
                            </tr>";
                }
                echo "
                <tr class='GridPager'>
                  <td colspan='4'>
                    <table border='0'>
                      <tbody>
                ";

                $pagefix = $page + 9;
                if ($pagefix > $numberofpages) {
                    $pagefix = $numberofpages;
                }

                $page_back_steps = [6, 5, 4, 3, 2, 1];
                foreach ($page_back_steps as $step) {
                    $page_back = $page - $step;
                    if ($page_back > 0) {
                        echo "
                        <td>
                          <a href='Browse.aspx?Page=" . $page_back . "'>" . $page_back . " </a>
                        </td>
                        ";
                    }
                }

                echo "
                <td>
                  <a href='Browse.aspx?Page=" . $page . "' style='color: white;'>" . $page . " </a>
                </td>
                ";

                for ($i = $page + 1; $i <= $pagefix; $i++) {
                    echo "
                    <td>
                      <a href='Browse.aspx?Page=" . $i . "'>" . $i . " </a>
                    </td>
                    ";
                }

                echo "
                <td><a href='Browse.aspx?Page=$numberofpages'>...</a></td>
                      </tbody>
                    </table>
                  </td>
                </tr>
                ";
                ?>
					</tr>
				</table></td>
			</tr>
		</table>
	</div>
			    
	    </div>
	
</div>

        </div>
  
        <?php require_once 'api/web/footer.php'; ?>