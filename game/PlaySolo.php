<?php header('Content-Type:text/plain');
require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/config.php");
$ticket = $_GET['ticket'];
$usrq = mysqli_query($link, "SELECT * FROM users WHERE accountcode='".$_GET['ticket']."'") or die(mysqli_error($link));
$user = mysqli_fetch_assoc($usrq);
$stmt = $link->prepare("SELECT * FROM users WHERE accountcode=?");
$stmt->bind_param("s", htmlspecialchars($_GET['ticket']));
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$placeId = $_GET['placeid'];
  $userId = htmlspecialchars((int)$user['id']);
$username = htmlspecialchars($user['username']);
$userticket = $_GET['ticket']; 
  if(!$user) {
exit();
}  
if(strlen($_GET['ticket']) <= 0) {
exit();
}
$sql = "SELECT * FROM wearing WHERE userid=?";
$stmt = $link->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
    $echothing = "";

while ($row = $result->fetch_assoc()) {
    $itemq = $link->prepare("SELECT * FROM catalog WHERE id=?");
    $itemq->bind_param('i', $row['itemid']);
    $itemq->execute();
    $item = $itemq->get_result()->fetch_assoc();
     if ($item['type'] == 'tshirt' || $item['type'] == 'shirt' || $item['type'] == 'hat' || $item['type'] == 'pants' || $item['type'] == 'face' || $item['type'] == 'gear' || $item['type'] == 'head') {
        $echothing .= "http://rccs.lol/asset/?id=" . $item['id'] . ";"; // Concatenate URLs
    }
}

?>
game:load("http://rccs.lol/PlaceAsset/?id=<?php echo htmlspecialchars ($placeId); ?>&ticket=<?php echo htmlspecialchars ($ticket); ?>")
local plr = game.Players:CreateLocalPlayer(0)
plr.Name = "<?php echo htmlspecialchars ($username); ?>"
plr.userId = "<?php echo htmlspecialchars ($userId); ?>"
plr.CharacterAppearance = "http://www.rccs.lol/asset/CharacterFetch.ashx?userId=<?php echo htmlspecialchars ($userId); ?>"

function loadCharacter()
    -- load the character and listen for death
    plr:loadCharacter()
    local humanoid = plr.Character.Humanoid
    humanoid.Died:connect(function() wait(5) loadCharacter() end)
end



loadCharacter()
game:service("RunService"):Run()
game.Players:CreateLocalPlayer(0)
game:GetService("RunService"):Run()