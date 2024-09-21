<?php
/* if you want your site fucked, keep the file name as this... 
99% sure this doesnnt work.... - mii 9/21/2024 /* 
    require_once $_SERVER["DOCUMENT_ROOT"]."/api/web/config.php";
    ignore_user_abort(true);     
    if($auth == false){
     exit('kys faggot <br>
     <iframe width="560" height="315" src="/vacation.mp4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>');
    }
    
    $assetId = (int)$_REQUEST['assetId'];
    if(!isset($assetId) || empty($assetId)){
        exit(
            'kys faggot <br>
            <iframe width="560" height="315" src="/vacation.mp4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>');
    }
    if($_USER['USER_PERMISSIONS'] != "Administrator") {
     exit('<iframe width="560" height="315" src="/vacation.mp4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>');
    }
    $stmt = $con->prepare("SELECT * FROM users WHERE id = :assetId");
    $stmt->bindParam(':assetId', $assetId, PDO::PARAM_INT);
    $stmt->execute();
    if($stmt->rowCount() < 1){
      exit('user no no existe');
    }
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $head = $user['HeadColor'];
    $ra = $user['RightArmColor'];
    $torso = $user['TorsoColor'];
    $la = $user['LeftArmColor'];
    $rl = $user['RightLegColor'];
    $ll = $user['LeftLegColor'];
    $items = "";
    $face = "";
    $newhead = "";
    $a = array();
    $stmt = $con->prepare("SELECT * FROM wearing WHERE userid = :assetId");
    $stmt->bindParam(':assetId', $assetId, PDO::PARAM_INT);
    $stmt->execute();
     $fart = []; 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stmt2 = $con->prepare("SELECT * FROM owned_items WHERE itemid = :itemId");
        $stmt2->bindParam(':itemId', $row['itemid'], PDO::PARAM_INT);
        $stmt2->execute();
    
        if ($stmt2->rowCount() < 1) {
            exit('hi');
        }
    
        $item = $stmt2->fetch(PDO::FETCH_ASSOC);
        $stmt3 = $con->prepare("SELECT * FROM catalog WHERE id = :catalogId AND moderation ='accepted'");
        $stmt3->bindParam(':catalogId', $item['itemid'], PDO::PARAM_INT);
        $stmt3->execute();
        $catalogItem = $stmt3->fetch(PDO::FETCH_ASSOC);
        if ($catalogItem && $catalogItem['moderation'] === 'accepted') {
            if ($catalogItem['type'] == "face") {
                $face = $item['itemid'];
            } elseif ($catalogItem['type'] == "head") {
                $newhead = $item['itemid'];
            } else {
                $fart[] = $item['itemid'];
            }
        }
    }
    $items = '';
    $lastItem = end( $fart);
    foreach ($fart as $key => $item) {
        $items .= ";http://$domain/asset/?id=" . $item;
     
    }
    $script1 = '
    local plr = game.Players:CreateLocalPlayer('.$assetId.')
    plr:LoadCharacter()
    plr.CharacterAppearance = "http://'. $domain .'/asset/BodyColors.ashx?userId='.$assetId.'&amp;a='.rand(1,getrandmax()).''.$items.'"
    print("rccapi by nolanwhy pookie bear :3")
    ';
    if($face != "") {
        $script1 .= '  FakeFace = game.Players.LocalPlayer.Character.FakeFace
                      FakeFace.Mesh.TextureId = "http://'. $domain .'/Thumbs/Asset.ashx?assetId=' . ($face) . '";';
    }
    if($newhead != "") {
        $script1 .= '
        plr.Character.Head.Mesh:remove()
        local item = game:GetObjects("http://'. $domain .'/asset/?id='.($newhead - 2).'&amp;a='.rand(1,getrandmax()).'")
        item[1].Parent = plr.Character.Head
        plr.Character.FakeFace.Mesh:remove()
        plr.Character.FakeFace.Transparency = 0
        local item2 = game:GetObjects("http://'. $domain .'/asset/?id='.($newhead - 1).'&amp;a='.rand(1,getrandmax()).'")
        item2[1].Parent = plr.Character.FakeFace
        plr.Character.FakeFace.Mesh.Scale = Vector3.new(1.05,1.05,1.05)';
        if($face != "") {
            $script1 .= '  FakeFace = game.Players.LocalPlayer.Character.FakeFace
                      FakeFace.Mesh.TextureId = "http://'. $domain .'/Thumbs/Asset.ashx?assetId=' . ($face) . '" ';
        } else {
            $script1 .= ' plr.Character.FakeFace.Mesh.TextureId = "http://'. $domain .'/asset/?id=1185" ';
        }
    }

    $timems = timems();
    $render = $RCCAPI->render($script1, 480, 640);
    $rendersmall = $RCCAPI->render($script1, 495, 505);
    if(!empty($render)){
    $location = $_SERVER["DOCUMENT_ROOT"]."/Thumbs/USERS/".$assetId.".png";
    $location2 = $_SERVER["DOCUMENT_ROOT"]."/Thumbs/USERS/".$assetId."-small.png";
    $location3 = $_SERVER["DOCUMENT_ROOT"]."/Thumbs/USERS/".$assetId."-square.png";
    $RCCAPI->img($location, $render, 150, 200);
    $RCCAPI->img($location2, $rendersmall, 100, 100);
    $RCCAPI->img($location3, $rendersmall, 200, 200);
    $timetook = timems() - $timems;
   
    
     
  echo('<script >
            window.location.href = "/api/rerenderal32432432432432984329849328492384932l.php?assetId=' . ($assetId + 1) . '";
          </script>');

} else {
   
    
    echo '<script >
            window.location.href = "/api/rerenderal32432432432432984329849328492384932l.php?assetId=' . ($assetId + 1) . '";
          </script>';
}

?>