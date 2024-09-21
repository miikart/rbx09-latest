    <?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/header.php");
    ?>
    <style>
        #EditProfileContainer {
            background-color: #eeeeee;
            border: 1px solid #000;
            color: #555;
            margin: 0 auto;
            width: 620px;
            text-align: center;
        }
        #EditProfileContainer #AgeGroup, #EditProfileContainer #ChatMode, #EditProfileContainer #PrivacyMode, #EditProfileContainer #EnterEmail, #EditProfileContainer #ResetPassword, #EditProfileContainer #Blurb {
            margin: 0 auto;
            width: 60%;
        }
        #link {
            font-size: 16px;
            margin: 5px;
            display: block;
        }
        #Catagory {
            width: 580px;
            border: 1px solid #000;
            margin: 20px auto;
        }
        #title {
            font-size: 23px;
            font-weight: bold;
            margin:5px;
            color: #333;
        }
    </style>
            </div>
          <title>Alt Identifier - Administration | GOLDBLOX</title>
            <center>
            <div id="Body">
                <div id="EditProfileContainer">
                    <h2>Alt Identifier</h2>
                    <form method="POST">
                        <br>
                        <input class="Text" type="number" placeholder="User ID" name="id"<?php if(isset($_REQUEST["id"])) echo " value='".(int)$_REQUEST["id"]."'"; ?>>
                        <button class="Button" type="submit">Identify Alts</button>
                     <?php
                   if($_SERVER["REQUEST_METHOD"] != "POST") { ?>
                   <br>   <br>
                <?php } ?>
                    </form>
                    <?php
                    if($_SERVER["REQUEST_METHOD"] === "POST") {
                        if(!isset($_REQUEST["id"]))
                            echo "<span style='color: red;'>Please enter an ID.</span>";
                        else {
                            $q = $con->prepare("SELECT * FROM users WHERE id = :id");
                            $q->bindParam(':id', $_REQUEST["id"], PDO::PARAM_INT);
                            $q->execute();
                            $usr = $q->fetch();
                            if(!$usr)
                                echo "<span style='color: red;'>User does not exist</span>";
                            else {
                                $q = $con->prepare("SELECT * FROM ips WHERE user = :id");
                                $q->bindParam(':id', $_REQUEST["id"], PDO::PARAM_INT);
                                $q->execute();
                                $ips = $q->fetchAll();
                                if(count($ips) < 1)
                                    echo "<span style='color: red;'>User's IP hasn't been logged or never logged in</span>";
                                else {
                                    $q = $con->prepare("SELECT * FROM ips WHERE ip = :ip");
                                    $q->bindParam(':ip', $ips[0]["ip"], PDO::PARAM_STR);
                                    $q->execute();
                                    $ips = $q->fetchAll();
                                    
                                    $alts = [];
                                    foreach($ips as $iplog) {
                                        $q = $con->prepare("SELECT * FROM users WHERE id = :id");
                                        $q->bindParam(':id', $iplog["user"], PDO::PARAM_INT);
                                        $q->execute();
                                        $iplogusr = $q->fetch();
                                        if($iplogusr)
                                            if((int)$iplogusr["id"] !== (int)$usr["id"] && !in_array($iplogusr, $alts))
                                                array_push($alts, $iplogusr);
                                    }
                                    
                                    echo "<div id='Catagory'><h2>".htmlspecialchars($usr["username"])."'s alts</h2>";
                                    if(count($alts) < 1)
                                        echo "<h4 style='color: red;'>This user does not have any alts</h4>";
                                    else
                                        foreach($alts as $alt) {
                                        ?>
                                            <a href="/User.aspx?ID=<?php echo (int)$alt["id"]; ?>"><h4><?php echo htmlspecialchars($alt["username"]); ?> (ID <?php echo (int)$alt["id"]; ?>)</h4></a>
                                        <?php
                                        }
                                    echo "</div>";
                                    $q = $con->prepare("SELECT * FROM ips WHERE user = :id");
                                    $q->bindParam(':id', $usr["id"], PDO::PARAM_INT);
                                    $q->execute();
                                    $ips = $q->fetchAll();
                                    
                                    $iplogs = [];
                                    foreach($ips as $iplog)
                                        if(!in_array($iplog["ip"], $iplogs))
                                            array_push($iplogs, $iplog["ip"]);
                                    
                                    echo "<div id='Catagory'><h2>".htmlspecialchars($usr["username"])."'s IPs</h2>";
                                    foreach($iplogs as $ip) {
                                    ?>
                                        <h4><?php echo htmlspecialchars($ip); ?></h4>
                                    <?php
                                    }
                                    echo "</div>";
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div id="Footer">
                <?php require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"); ?>
            </div>
        </div>
    </div>