<?php
include $_SERVER["DOCUMENT_ROOT"]."/api/web/header.php";


if(!$auth) {
    header("Location: /Login/Default.aspx");
    exit;
}

if(!$_REQUEST['id'] || !$_REQUEST['type']) {
    header("Location: /Error/Default.aspx");
    exit;
}
try {

    $stmt = $con->prepare("SELECT * FROM reports WHERE userid = :userid ORDER BY id DESC LIMIT 1");
    $stmt->execute([':userid' => $_USER['id']]);
    $ratelimit = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($ratelimit && time() <= $ratelimit['timeout']) {
        $ratelimited = true;
    } else {
        $ratelimited = false;
    }
    
    if ($ratelimited && !$_USER['USER_PERMISSIONS']) {
        echo("You are being rate limited.");
        exit;
    }

    $otheruser = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT);

    if ($type == 3) {
        $stmt = $con->prepare("SELECT * FROM users WHERE id = :otheruser");
        $stmt->execute([':otheruser' => $otheruser]);
        $otheruser2 = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$otheruser2 || $otheruser == $_USER['id']) {
            echo 'no';
            exit();
        }

        $username2 = htmlspecialchars($otheruser2['username']);
        $link2 = "http://$domain/User.aspx?ID=" . $otheruser2['id'];
        $goofystring = "If you feel the profile created by ".$username2." contains profanity or other forms of abuse then click the button below.";
        
    } elseif ($type == 2) {
        $stmt = $con->prepare("SELECT * FROM catalog WHERE id = :otheruser");
        $stmt->execute([':otheruser' => $otheruser]);
        $otheruser2 = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($otheruser2) {
            $stmt = $con->prepare("SELECT * FROM users WHERE id = :creatorid");
            $stmt->execute([':creatorid' => $otheruser2['creatorid']]);
            $otheruser3 = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$otheruser2 || $otheruser == $otheruser2['creatorid'] || $otheruser3['id'] == "1") {
                   header("Location: /Error/Default.aspx");
                exit();
                exit();
            }

            $username2 = htmlspecialchars($otheruser2['name']);
            $username3 = htmlspecialchars($otheruser3['username']);
            $link2 = "http://$domain/Item.aspx?ID=" . $otheruser2['id'];
            $goofystring = "If you feel the item created by ".$username3." contains profanity or other forms of abuse then click the button below.";
        }

    } elseif ($type == 0) {
        $stmt = $con->prepare("SELECT * FROM forum WHERE id = :otheruser");
        $stmt->execute([':otheruser' => $otheruser]);
        $otheruser2 = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($otheruser2) {
            $stmt = $con->prepare("SELECT * FROM users WHERE id = :author");
            $stmt->execute([':author' => $otheruser2['author']]);
            $otheruser3 = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$otheruser2 || $otheruser3['id'] == $_USER['id']) {
              header("Location: /Error/Default.aspx");
                exit();
            }

            $username2 = htmlspecialchars($otheruser2['title']);
            $username3 = htmlspecialchars($otheruser3['username']);
            $link2 = $otheruser2['reply_to'] == 0 ? "https://$domain/Forum/ShowPost.aspx?ID=" . $otheruser2['id'] : "https://$domain/Forum/ShowPost.aspx?ID=" . $otheruser2['reply_to'];
            $goofystring = "If you feel the forum post created by ".$username3." contains profanity or other forms of abuse then click the button below.";
        }
    } else {
           header("Location: /Error/Default.aspx");
                exit();
      
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        $user = htmlspecialchars($_USER['username']);
        $time = time() + 15;

        $stmt = $con->prepare("INSERT INTO reports (userid, itemid, content, timeout) VALUES (:userid, :itemid, :content, :timeout)");
        $stmt->execute([
            ':userid' => $_USER['id'],
            ':itemid' => $otheruser,
            ':content' => $comment,
            ':timeout' => $time
        ]);

        // webhook logic
        $webhookUrl = "https://discord.com/api/webhooks/1272164173761216574/qNgk5AC5_MgEuLr10JeOjqHokKx2xU5GVNExDvNNqM8nlB-_gp4DgPx-tS-jCYSpICrQ";
        $reason = $comment;
        $embed = ["title" => "Report", "description" => "User [$user](http://www.rccs.lol/User.aspx?ID={$_USER['id']}) reported [$username2]($link2) for: \n$reason", "color" => hexdec("FF0000")];
        $data = ["embeds" => [$embed]];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $webhookUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        header("Location: ../Default.aspx");
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<title>Report Abuse</title>
<div id="Body">
<div style="margin: 150px auto 150px auto; width: 500px; border: black thin solid; padding: 22px;">
    <?=$goofystring?>
    <br>
    <div style="margin: 12px">
        <div>
            <p style="color:red;"></p>
            <label style="width: 4em; float: left; text-align: right; margin-right: 0.5em">Comment:</label>
            <form method="post" style="width:20.5em">
                <textarea name="comment" rows="4" cols="20" style="width:15em;"></textarea>
                <div style="float: right; padding: 0.5em">
                    <input type="submit" name="ok" value="OK" style="width:5em;">
                    <input type="button" value="Cancel" onclick="location.href='../'" style="width:5em;">
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>
