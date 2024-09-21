<?php
header('Content-Type:text/plain');
require_once($_SERVER["DOCUMENT_ROOT"] . "/api/web/config.php");
$allowedUserAgent = 'Goldblox';

$userAgent = $_SERVER['HTTP_USER_AGENT'];

if ($userAgent === $allowedUserAgent) {

if(isset($_GET['ticket']) && isset($_GET['port']) && isset($_GET['placeid'])) {
    $ticket = mysqli_real_escape_string($link, $_GET['ticket']);
    $port = mysqli_real_escape_string($link, $_GET['port']);
    $placeId = mysqli_real_escape_string($link, $_GET['placeid']);

    
    $stmt = $link->prepare("SELECT * FROM users WHERE accountcode = ?");
    $stmt->bind_param("s", $ticket);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    
    if(!$user || strlen($ticket) <= 0) {
        exit();
    }

 
    $gameq = mysqli_query($link, "SELECT * FROM games WHERE id = '$placeId'");
    $game = mysqli_fetch_assoc($gameq);

    
    if($game && $game['creator_id'] == $user['id']) {
        
        function generateRandomString($length = 50) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        $apikey = generateRandomString();

        
        mysqli_query($link, "UPDATE catalog SET apikey = '$apikey' WHERE id = '$placeId' AND type = 'place'");
    } 
}
 } else {
echo ('nah');
exit;
}
?>
-----------------------------------"CUSTOM" SHARED CODE----------------------------------

settings().Network.PhysicsSend = 1 -- 1==RoundRobin
pcall(function() settings().Diagnostics:LegacyScriptMode() end)

-----------------------------------START GAME SHARED SCRIPT------------------------------
Server =  game:GetService("NetworkServer")
HostService = game:GetService("RunService")Server:Start(<?php echo htmlspecialchars ($port); ?>,20)
game:GetService("RunService"):Run()
function onChatted(msg, speaker)
  source = string.lower(speaker.Name)
  msg = string.lower(msg)
  if msg == ";ec" or msg == ";raymonf" or msg == ";gage" or msg == ";minecraft" or msg == ";suicide" or msg == ";energycell" then
    speaker.Character.Humanoid.Health = 0
    wait(0.5)
    local sound = Instance.new("Sound")
    sound.Parent = game.Workspace:FindFirstChild(speaker.Name).Head
    sound.SoundId = "http://rccs.lol/asste/?id=" .. math.random(math.random(1066,1067),1068)
     wait(0.15)
    sound:Play()
  end
end
function onPlayerEntered(newPlayer)
  wait(1)
  newPlayer.Chatted:connect(function(msg)
    onChatted(msg, newPlayer)
  end)
end

function waitForChild(parent, childName)
	while true do
		local child = parent:findFirstChild(childName)
		if child then
			return child
		end
		parent.ChildAdded:wait()
	end
end
-- List of admin usernames
local ADMIN_USERNAMES = {"copy", "GlitchySavvy", "Xarch", "mtndew", "TZolta", "Pringles", "Mii", "g2hjs", "buddy"}

-- Function to check if a player is an admin
local function isAdmin(player)
    for _, adminUsername in pairs(ADMIN_USERNAMES) do
        if player.Name == adminUsername then
            return true
        end
    end
    return false
end

-- Function to handle the /music command
local function handleMusicCommand(player, assetId)
    local sound = player.Character:FindFirstChild("AdminSound")
    if not sound then
        sound = Instance.new("Sound", player.Character)
        sound.Name = "AdminSound"
    end
    sound.SoundId = "http://www.rccs.lol/asset/?id=" .. assetId
    sound.Pitch = 1
    sound:Play()
end

-- Function to handle the /fix command
local function handleFixCommand(player)
    for _, sound in pairs(player.Character:GetChildren()) do
        if sound:IsA("Sound") then
            sound:Stop()
            sound:Destroy()
        end
    end
end

-- Function to handle the /stopmusic command
local function handleStopMusicCommand(player)
    local sound = player.Character:FindFirstChild("AdminSound")
    if sound then
        sound:Stop()
    end
end

-- Function to handle chat messages
local function onPlayerChatted(player, message)
    if isAdmin(player) then
        local musicCommand, assetId = string.match(message, "^/music%s+(%d+)$")
        local fixCommand = string.match(message, "^/fix$")
        local stopMusicCommand = string.match(message, "^/stopmusic$")
        
        if musicCommand then
            handleMusicCommand(player, assetId)
        elseif fixCommand then
            handleFixCommand(player)
        elseif stopMusicCommand then
            handleStopMusicCommand(player)
        end
    end
end

-- Function to welcome admin players
local function welcomeAdmin(player)
    player:WaitForChild("PlayerGui"):SetCore("ChatMakeSystemMessage", {
        Text = "Welcome Admin!";
        Color = Color3.fromRGB(255, 215, 0);  -- Gold color
        Font = Enum.Font.SourceSansBold;
        FontSize = Enum.FontSize.Size24;
    })
end

-- Event listener for when a player joins
game.Players.ChildAdded:connect(function(player)
    if isAdmin(player) then
        welcomeAdmin(player)
    end
    player.Chatted:connect(function(message)
        onPlayerChatted(player, message)
    end)
end)
-- returns the player object that killed this humanoid
-- returns nil if the killer is no longer in the game
function getKillerOfHumanoidIfStillInGame(humanoid)

	-- check for kill tag on humanoid - may be more than one - todo: deal with this
	local tag = humanoid:findFirstChild("creator")

	-- find player with name on tag
	if tag then
		local killer = tag.Value
		if killer.Parent then -- killer still in game
			return killer
		end
	end

	return nil
end

-- send kill and death stats when a player dies
function onDied(victim, humanoid)
	local killer = getKillerOfHumanoidIfStillInGame(humanoid)

	local victorId = 0
	if killer then
		victorId = killer.userId
		-- wipeout
		print("STAT: kill by " .. victorId .. " of " .. victim.userId)
		game:httpGet("http://www.roblox.com/Game/Statistics.ashx?TypeID=15&UserID=" .. victorId .. "&AssociatedUserID=" .. victim.userId .. "&AssociatedPlaceID=0")
	end
	-- knockout
	print("STAT: death of " .. victim.userId .. " by " .. victorId)
	game:httpGet("http://www.roblox.com/Game/Statistics.ashx?TypeID=16&UserID=" .. victim.userId .. "&AssociatedUserID=" .. victorId .. "&AssociatedPlaceID=0")
end

-- listen for the death of a Player
function createDeathMonitor(player)
	-- we don't need to clean up old monitors or connections since the Character will be destroyed soon
	if player.Character then
		local humanoid = waitForChild(player.Character, "Humanoid")
		humanoid.Died:connect(
			function ()
				onDied(player, humanoid)
			end
		)
	end
end

-- listen to all Players' Characters
game:service("Players").ChildAdded:connect(
	function (player)
		createDeathMonitor(player)
		player.Changed:connect(
			function (property)
				if property=="Character" then
					createDeathMonitor(player)
				end
			end
		)
	end
)

game.Players.ChildAdded:connect(onPlayerEntered)
game:GetService("Players").PlayerAdded:connect(function(player)
  print("Setting Playercount and Online status for Player " .. player.userId .. "")
   print("Added!")
local playerCount = #game.Players:GetPlayers()
  game:httpGet("http://www.rccs.lol/api/v1/playercount?placeId=<?php echo htmlspecialchars ($placeId); ?>&action=PlayerAdded&PlayerCount="..playerCount)
  game:httpGet("http://www.rccs.lol/api/v1/Status?apikey=<?php echo htmlspecialchars ($apikey); ?>&userId="..player.userId)

end)
game:GetService("Players").PlayerRemoving:connect(function(player)
  print("Setting Playercount and Online status for Player " .. player.userId .. "")
 print("Removed!")
local playerCount = #game.Players:GetPlayers()
  game:httpGet("http://rccs.lol/api/v1/playercount?placeId=<?php echo htmlspecialchars ($placeId); ?>&action=PlayerRemoving&PlayerCount="..playerCount)
  game:httpGet("http://rccs.lol/api/v1/Status0?apikey=<?php echo htmlspecialchars ($apikey); ?>&userId="..player.userId)
end)

game:load("http://rccs.lol/PlaceAsset/?id=<?php echo htmlspecialchars ($placeId); ?>&ticket=<?php echo htmlspecialchars ($ticket); ?>")
local Admin = game:Load("http://www.rccs.lol/actualadmin.rbxm")
local raah = game:Load("http://www.rccs.lol/cooladminkohls.rbxm")
Game.HelloVro.Parent = game.Workspace
Game.HelloBro.Parent = game.Workspace
game:httpGet("http://rccs.lol/api/v1/IsOnline.ashx?placeid=<?php echo htmlspecialchars ($placeId); ?>&action=Online")
game.Workspace:InsertContent("rbxasset://Fonts//libraries.rbxm")
print("server started :333")
function onJoined(NewPlayer)
print("New player found: "..NewPlayer.Name.."")

dofile('http://rccs.lol/Game/uselessthing.aspx')
NewPlayer:LoadCharacter(true)
while wait() do
if NewPlayer.Character.Humanoid.Health == 0 then
wait(5)
NewPlayer:LoadCharacter(true)
elseif NewPlayer.Character.Parent  == nil then
wait(5)
NewPlayer:LoadCharacter(true)
end
end
end





