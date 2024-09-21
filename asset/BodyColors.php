<?php
header("Content-Type: text/xml");
require_once $_SERVER['DOCUMENT_ROOT']."/api/web/config.php";
    $userId = isset($_GET['userId']) ? (int) $_GET['userId'] : 0;
    $stmt = $con->prepare("SELECT * FROM users WHERE id = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        ?>
        <roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.roblox.com/roblox.xsd" version="4">
            <External>null</External>
            <External>nil</External>
            <Item class="BodyColors">
                <Properties>
                    <int name="HeadColor"><?= htmlspecialchars($user['HeadColor'], ENT_XML1, 'UTF-8'); ?></int>
                    <int name="LeftArmColor"><?= htmlspecialchars($user['LeftArmColor'], ENT_XML1, 'UTF-8'); ?></int>
                    <int name="LeftLegColor"><?= htmlspecialchars($user['LeftLegColor'], ENT_XML1, 'UTF-8'); ?></int>
                    <string name="Name">Body Colors</string>
                    <int name="RightArmColor"><?= htmlspecialchars($user['RightArmColor'], ENT_XML1, 'UTF-8'); ?></int>
                    <int name="RightLegColor"><?= htmlspecialchars($user['RightLegColor'], ENT_XML1, 'UTF-8'); ?></int>
                    <int name="TorsoColor"><?= htmlspecialchars($user['TorsoColor'], ENT_XML1, 'UTF-8'); ?></int>
                    <bool name="archivable">true</bool>
                </Properties>
            </Item>
        </roblox>
        <?php
    }

?>
