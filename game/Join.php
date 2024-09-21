<?php header('Content-Type:text/plain');
require_once($_SERVER["DOCUMENT_ROOT"]."/api/web/config.php");
$allowedUserAgent = 'Goldblox';

$userAgent = $_SERVER['HTTP_USER_AGENT'];

if ($userAgent === $allowedUserAgent) {
$usrq = mysqli_query($link, "SELECT * FROM users WHERE accountcode='".$_GET['ticket']."'") or die(mysqli_error($link));
$user = mysqli_fetch_assoc($usrq);
$stmt = $link->prepare("SELECT * FROM users WHERE accountcode=?");
$stmt->bind_param("s", htmlspecialchars($_GET['ticket']));
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$gameq = mysqli_query($link, "SELECT * FROM catalog WHERE id='".$_GET['placeid']."'AND type ='place'") or die(mysqli_error($link));
$game = mysqli_fetch_assoc($gameq);
mysqli_query($link, "INSERT INTO `gamesvisits` (`id`, `gameid`, `visitorid`) VALUES (NULL, '".$game['id']."', '".$user['id']."');") or die(mysqli_error($link));
$placeid = htmlspecialchars($game['id']);
$ip = htmlspecialchars($game['ip']);
$port = htmlspecialchars($game['port']);
$userId = htmlspecialchars((int)$user['id']);
$username = htmlspecialchars($user['username']);
$bc = htmlspecialchars($user['BC']);  
    if(!$user) {
exit();
}  
if(strlen($_GET['ticket']) <= 0) {
exit();
}
 } else {
echo ('nah');
exit;
}
?>

-- functions --------------------------
function onPlayerAdded(player)
  -- override
end
-- arguments ---------------------------------------
local threadSleepTime = ...
if threadSleepTime==nil then
  threadSleepTime = 15
end
local test = true
print("Joining place <?php echo htmlspecialchars ($placeid); ?> at GOLDBLOX")
game:GetService("ChangeHistoryService"):SetEnabled(false)
game:GetService("ContentProvider"):SetThreadPool(16)
game:GetService("InsertService"):SetBaseCategoryUrl("rccs.lol/Game/Tools/InsertAsset.php?nsets=10&type=base")
game:GetService("InsertService"):SetUserCategoryUrl("rccs.lol/Game/Tools/InsertAsset.php?nsets=20&type=user&userid=%d")
game:GetService("InsertService"):SetCollectionUrl("rccs.lol/Game/Tools/InsertAsset.php?sid=%d")
game:GetService("InsertService"):SetAssetUrl("rccs.lol/asset/?id=%d")
game:GetService("InsertService"):SetAssetVersionUrl("rccs.lol/asset/?assetversionid=%d")
-- game:GetService("InsertService"):SetTrustLevel(0)
game:GetService("InsertService"):SetAdvancedResults(true)
-- Bubble chat.  This is all-encapsulated to allow us to turn it off with a config setting
pcall(function() game:GetService("Players"):SetChatStyle(Enum.ChatStyle.ClassicAndBubble) end)
local waitingForCharacter = false
pcall( function()
  if settings().Network.MtuOverride == 0 then
    settings().Network.MtuOverride = 1400
  end
end)
-- globals -----------------------------------------
client = game:GetService("NetworkClient")
visit = game:GetService("Visit")
-- functions ---------------------------------------
function setMessage(message)
  -- todo: animated "..."
  if not false then
    game:SetMessage(message)
  else
    -- hack, good enought for now
    game:SetMessage("Teleporting ...")
  end
end
function showErrorWindow(message)
  game:SetMessage(message)
end
function reportError(err)
  print("***ERROR*** " .. err)
  if not test then visit:SetUploadUrl("") end
  client:Disconnect()
  wait(4)
  showErrorWindow("Error: " .. err)
end
-- called when the client connection closes
function onDisconnection(peer, lostConnection)
  if lostConnection then
    showErrorWindow("You have lost the connection to the game")

  else
   showErrorWindow("This game has shut down")

local thething = "http://rccs.lol/api/v1/Status0?apikey=dunno&userId=<?php echo htmlspecialchars ($userId); ?>"
        game:httpGet(thething)

end
local thething2 = "http://rccs.lol/api/v1/playercount?placeId=<?php echo htmlspecialchars ($placeid); ?>&action=PlayerRemoving&PlayerCount=0"
        game:httpGet(thething2)
end

function requestCharacter(replicator)
  
  -- prepare code for when the Character appears
  local connection
  connection = player.Changed:connect(function (property)
    if property=="Character" then
      game:ClearMessage()
      waitingForCharacter = false
      
      connection:disconnect()
    end
  end)
  
  setMessage("Requesting character")
  local success, err = pcall(function()  
    replicator:RequestCharacter()
    setMessage("Requesting character")
    waitingForCharacter = true
  end)
  if not success then
    reportError(err)
    return
  end
end
-- called when the client connection is established
function onConnectionAccepted(url, replicator)
  local waitingForMarker = true
  
  local success, err = pcall(function()  
    if not test then 
        visit:SetPing("", 300) 
    end
    
    if not false then
      game:SetMessageBrickCount()
    else
      setMessage("Teleporting ...")
    end
    replicator.Disconnection:connect(onDisconnection)
    
    -- Wait for a marker to return before creating the Player
    local marker = replicator:SendMarker()
    
    marker.Received:connect(function()
      waitingForMarker = false
      requestCharacter(replicator)
    end)
  end)
  
  if not success then
    reportError(err)
    return
  end
  
  -- TODO: report marker progress
  
  while waitingForMarker do
    workspace:ZoomToExtents()
    wait(0.5)
  end
end
-- called when the client connection fails
function onConnectionFailed(_, error)
  showErrorWindow("This game is not available. Please try another")
end
-- called when the client connection is rejected
function onConnectionRejected()
  connectionFailed:disconnect()
  showErrorWindow("This game is not available. Please try another")
end
idled = false
function onPlayerIdled(time)
  if time > 20*60 then
    showErrorWindow(string.format("You were disconnected for being idle for %d minutes", time/60))
    client:Disconnect()  
    if not idled then
      idled = true
    end
  end
end
-- main ------------------------------------------------------------
pcall(function() settings().Diagnostics:LegacyScriptMode() end)
local success, err = pcall(function()  
  game:SetRemoteBuildMode(true)
  
  setMessage("Connecting to Server")
  client.ConnectionAccepted:connect(onConnectionAccepted)
  client.ConnectionRejected:connect(onConnectionRejected)
  connectionFailed = client.ConnectionFailed:connect(onConnectionFailed)
  client.Ticket = "YourRizzHasBeenFanumTaxed"
  
  playerConnectSucces, player = pcall(function() return client:PlayerConnect(<?php echo htmlspecialchars ($userId); ?>, "<?php echo htmlspecialchars ($ip); ?>", "<?php echo htmlspecialchars ($port); ?>", 0, threadSleepTime) end)
  if not playerConnectSucces then
    --Old player connection scheme
    player = game:GetService("Players"):CreateLocalPlayer(<?php echo htmlspecialchars ($userId); ?>)
    client:Connect("<?php echo htmlspecialchars ($ip); ?>", "<?php echo htmlspecialchars ($port); ?>", 0, threadSleepTime)
  end
  player:SetSuperSafeChat(false)
  pcall(function() player:SetMembershipType(Enum.MembershipType.<?php echo htmlspecialchars ($bc); ?>) end)
  pcall(function() player:SetAccountAge(300) end)
  player.Idled:connect(onPlayerIdled)
  
  -- Overriden
  onPlayerAdded(player)
  
  pcall(function() player.Name = [========[<?php echo htmlspecialchars ($username); ?>]========] end)
  game.Players.LocalPlayer.CharacterAppearance = "http://rccs.lol/asset/CharacterFetch.ashx?userId=<?php echo htmlspecialchars ($userId); ?>"
  if not test then visit:SetUploadUrl("") end
end)
if not success then
  reportError(err)
end
if not test then
  -- TODO: Async get?
  loadfile("")("", -1, 0)
end
pcall(function() game:SetScreenshotInfo("") end)
pcall(function() game:SetVideoInfo("") end)
-- use single quotes here because the video info string may have unescaped double quotes







