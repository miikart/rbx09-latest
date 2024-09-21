<?php header('Content-Type:text/plain');
require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/config.php");

$userId = $auth ? htmlspecialchars((int)$_USER['id']) : 0;
$username = $auth ? htmlspecialchars($_USER['username']) : 'Guest';
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
-- Created by nolanwhy, 2024
-- Steal if gay
game.Players:CreateLocalPlayer(0)
game.Players.LocalPlayer:LoadCharacter()
game:GetService("RunService"):Run()
game.Players.LocalPlayer.Name = "<?php echo htmlspecialchars ($username); ?>"
game.Players.LocalPlayer.CharacterAppearance = "http://www.rccs.lol/asset/CharacterFetch.ashx?userId=<?php echo htmlspecialchars ($userId); ?>"
while wait() do
	if game.Players.LocalPlayer.Character.Humanoid.Health == 0 or game.Players.LocalPlayer.Character.Parent == nil then
		wait(5)
		game.Players.LocalPlayer:LoadCharacter()
	end
end