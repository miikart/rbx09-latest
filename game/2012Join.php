game:GetService("CoreGui").DescendantAdded:connect(function(Child)
	if (Child:IsA("BaseScript")) and (Child.Name~="SubMenuBuilder") and (Child.Name~="ToolTipper") and (Child.Name~="MainBotChatScript") then
		Child:Remove()
	end
end)

showServerNotifications = true

coroutine.resume(coroutine.create(function()
	while not game:GetService("CoreGui"):FindFirstChild("GoldbloxGui") do game:GetService("CoreGui").ChildAdded:wait() end
	game:GetService("CoreGui").GoldbloxGui.TopLeftControl:Remove()
end))



coroutine.resume(coroutine.create(function()
	loadstring('\108\111\99\97\108\32\67\111\114\101\71\117\105\32\61\32\103\97\109\101\58\71\101\116\83\101\114\118\105\99\101\40\34\67\111\114\101\71\117\105\34\41\59\10\119\104\105\108\101\32\110\111\116\32\67\111\114\101\71\117\105\58\70\105\110\100\70\105\114\115\116\67\104\105\108\100\40\34\82\111\98\108\111\120\71\117\105\34\41\32\100\111\10\9\67\111\114\101\71\117\105\46\67\104\105\108\100\65\100\100\101\100\58\119\97\105\116\40\41\59\10\101\110\100\10\108\111\99\97\108\32\82\111\98\108\111\120\71\117\105\32\61\32\67\111\114\101\71\117\105\46\82\111\98\108\111\120\71\117\105\59\10\108\111\99\97\108\32\66\111\116\116\111\109\76\101\102\116\67\111\110\116\114\111\108\32\61\32\82\111\98\108\111\120\71\117\105\58\70\105\110\100\70\105\114\115\116\67\104\105\108\100\40\34\66\111\116\116\111\109\76\101\102\116\67\111\110\116\114\111\108\34\41\10\108\111\99\97\108\32\66\111\116\116\111\109\82\105\103\104\116\67\111\110\116\114\111\108\32\61\32\82\111\98\108\111\120\71\117\105\58\70\105\110\100\70\105\114\115\116\67\104\105\108\100\40\34\66\111\116\116\111\109\82\105\103\104\116\67\111\110\116\114\111\108\34\41\10\108\111\99\97\108\32\84\111\112\76\101\102\116\67\111\110\116\114\111\108\32\61\32\82\111\98\108\111\120\71\117\105\58\70\105\110\100\70\105\114\115\116\67\104\105\108\100\40\34\84\111\112\76\101\102\116\67\111\110\116\114\111\108\34\41\10\108\111\99\97\108\32\66\117\105\108\100\84\111\111\108\115\32\61\32\82\111\98\108\111\120\71\117\105\58\70\105\110\100\70\105\114\115\116\67\104\105\108\100\40\34\66\117\105\108\100\84\111\111\108\115\34\41\10\102\117\110\99\116\105\111\110\32\109\97\107\101\89\82\101\108\97\116\105\118\101\40\41\10\66\111\116\116\111\109\76\101\102\116\67\111\110\116\114\111\108\46\83\105\122\101\67\111\110\115\116\114\97\105\110\116\32\61\32\50\10\66\111\116\116\111\109\82\105\103\104\116\67\111\110\116\114\111\108\46\83\105\122\101\67\111\110\115\116\114\97\105\110\116\32\61\32\50\10\105\102\32\84\111\112\76\101\102\116\67\111\110\116\114\111\108\32\116\104\101\110\32\84\111\112\76\101\102\116\67\111\110\116\114\111\108\46\83\105\122\101\67\111\110\115\116\114\97\105\110\116\32\61\32\50\32\101\110\100\10\105\102\32\66\117\105\108\100\84\111\111\108\115\32\116\104\101\110\32\66\117\105\108\100\84\111\111\108\115\46\70\114\97\109\101\46\83\105\122\101\67\111\110\115\116\114\97\105\110\116\32\61\32\50\32\101\110\100\10\66\111\116\116\111\109\76\101\102\116\67\111\110\116\114\111\108\46\80\111\115\105\116\105\111\110\32\61\32\85\68\105\109\50\46\110\101\119\40\48\44\48\44\49\44\45\66\111\116\116\111\109\76\101\102\116\67\111\110\116\114\111\108\46\65\98\115\111\108\117\116\101\83\105\122\101\46\89\41\10\66\111\116\116\111\109\82\105\103\104\116\67\111\110\116\114\111\108\46\80\111\115\105\116\105\111\110\32\61\32\85\68\105\109\50\46\110\101\119\40\49\44\45\66\111\116\116\111\109\82\105\103\104\116\67\111\110\116\114\111\108\46\65\98\115\111\108\117\116\101\83\105\122\101\46\88\44\49\44\45\66\111\116\116\111\109\82\105\103\104\116\67\111\110\116\114\111\108\46\65\98\115\111\108\117\116\101\83\105\122\101\46\89\41\10\101\110\100\10\102\117\110\99\116\105\111\110\32\109\97\107\101\88\82\101\108\97\116\105\118\101\40\41\10\66\111\116\116\111\109\76\101\102\116\67\111\110\116\114\111\108\46\83\105\122\101\67\111\110\115\116\114\97\105\110\116\32\61\32\49\10\66\111\116\116\111\109\82\105\103\104\116\67\111\110\116\114\111\108\46\83\105\122\101\67\111\110\115\116\114\97\105\110\116\32\61\32\49\10\105\102\32\84\111\112\76\101\102\116\67\111\110\116\114\111\108\32\116\104\101\110\32\84\111\112\76\101\102\116\67\111\110\116\114\111\108\46\83\105\122\101\67\111\110\115\116\114\97\105\110\116\32\61\32\49\32\101\110\100\10\105\102\32\66\117\105\108\100\84\111\111\108\115\32\116\104\101\110\32\66\117\105\108\100\84\111\111\108\115\46\70\114\97\109\101\46\83\105\122\101\67\111\110\115\116\114\97\105\110\116\32\61\32\49\32\101\110\100\10\66\111\116\116\111\109\76\101\102\116\67\111\110\116\114\111\108\46\80\111\115\105\116\105\111\110\32\61\32\85\68\105\109\50\46\110\101\119\40\48\44\48\44\49\44\45\66\111\116\116\111\109\76\101\102\116\67\111\110\116\114\111\108\46\65\98\115\111\108\117\116\101\83\105\122\101\46\89\41\10\66\111\116\116\111\109\82\105\103\104\116\67\111\110\116\114\111\108\46\80\111\115\105\116\105\111\110\32\61\32\85\68\105\109\50\46\110\101\119\40\49\44\45\66\111\116\116\111\109\82\105\103\104\116\67\111\110\116\114\111\108\46\65\98\115\111\108\117\116\101\83\105\122\101\46\88\44\49\44\45\66\111\116\116\111\109\82\105\103\104\116\67\111\110\116\114\111\108\46\65\98\115\111\108\117\116\101\83\105\122\101\46\89\41\10\101\110\100\10\108\111\99\97\108\32\102\117\110\99\116\105\111\110\32\114\101\115\105\122\101\40\41\10\105\102\32\82\111\98\108\111\120\71\117\105\46\65\98\115\111\108\117\116\101\83\105\122\101\46\120\32\62\32\82\111\98\108\111\120\71\117\105\46\65\98\115\111\108\117\116\101\83\105\122\101\46\121\32\116\104\101\110\10\109\97\107\101\89\82\101\108\97\116\105\118\101\40\41\10\101\108\115\101\10\109\97\107\101\88\82\101\108\97\116\105\118\101\40\41\10\101\110\100\10\101\110\100\10\82\111\98\108\111\120\71\117\105\46\67\104\97\110\103\101\100\58\99\111\110\110\101\99\116\40\102\117\110\99\116\105\111\110\40\112\114\111\112\101\114\116\121\41\10\105\102\32\112\114\111\112\101\114\116\121\32\61\61\32\34\65\98\115\111\108\117\116\101\83\105\122\101\34\32\116\104\101\110\10\119\97\105\116\40\41\10\114\101\115\105\122\101\40\41\10\101\110\100\10\101\110\100\41\10\119\97\105\116\40\41\10\114\101\115\105\122\101\40\41\10')()
end))



pcall(function() settings().Diagnostics:LegacyScriptMode() end)
pcall(function() game:GetService("ScriptContext").ScriptsDisabled = false end)
game.CoreGui.GoldbloxGui.ControlFrame.BottomRightControl.Help:Remove()
	game.CoreGui.GoldbloxGui.ControlFrame.BottomRightControl.ReportAbuse:Remove()
	game.CoreGui.GoldbloxGui.ControlFrame.BottomRightControl.RecordToggle:Remove()
	game.CoreGui.GoldbloxGui.ControlFrame.BottomRightControl.Screenshot:Remove()
	game.CoreGui.GoldbloxGui.ControlFrame.BottomRightControl.ToggleFullScreen:Remove()
	--game.CoreGui.GoldbloxGui.ControlFrame.BottomLeftControl.TogglePlayMode:Remove()
	game.CoreGui.GoldbloxGui.ControlFrame.BottomLeftControl.Exit:Remove()

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
print("Joining place Shitter at GOLDBLOX")
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
pcall(function() game:GetService("Players"):SetChatStyle(Enum.ChatStyle.Classic) end)
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

  
end

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
  client.Ticket = "noticket"
  
  playerConnectSucces, player = pcall(function() return client:PlayerConnect(1, "localhost", "53640", 0, threadSleepTime) end)
  if not playerConnectSucces then
    --Old player connection scheme
    player = game:GetService("Players"):CreateLocalPlayer(0)
    client:Connect("localhost", "53640", 0, threadSleepTime)
  end
  player:SetSuperSafeChat(false)
  pcall(function() player:SetMembershipType(Enum.MembershipType.None) end)
  pcall(function() player:SetAccountAge(300) end)
  player.Idled:connect(onPlayerIdled)
  
  -- Overriden
  onPlayerAdded(player)
  
  pcall(function() player.Name = [========[GOLDBLOX]========] end)
  game.Players.LocalPlayer.CharacterAppearance = "http://rccs.lol/asset/CharacterFetch.ashx?userId=1"
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

