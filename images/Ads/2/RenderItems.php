<?php 
include("../inc/config.php");
$id = htmlspecialchars($_GET['id']);
$itemq = $conn->prepare("SELECT * FROM catalog WHERE id=:itemid");
		$itemq->execute([':itemid' => $id]);
		$item = $itemq->fetch(PDO::FETCH_ASSOC);
if($item['type'] == 'hat'){
$hatshit = "http://".$sitedomain."/asste/".$item['assetid'].".txt";
$xml = "
<?xml version='1.0' encoding='UTF-8'?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:ns2='http://roblox.com/RCCServiceSoap' xmlns:ns1='http://roblox.com/' xmlns:ns3='http://roblox.com/RCCServiceSoap12'>
	<SOAP-ENV:Body>
		<ns1:OpenJob>
			<ns1:job>
				<ns1:id>3</ns1:id>
				<ns1:expirationInSeconds>1</ns1:expirationInSeconds>
				<ns1:category>1</ns1:category>
				<ns1:cores>321</ns1:cores>
			</ns1:job>
			<ns1:script>
				<ns1:name>Script</ns1:name>
				<ns1:script>
			  hat = game:GetObjects('".$hatshit."')[1]
			  hat.Parent = game.Workspace
			  
  
b64 = game:GetService('ThumbnailGenerator'):Click('PNG', 420, 420, true)									 
return b64
		</ns1:script>
			</ns1:script>
		</ns1:OpenJob>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
";
//The URL that you want to send your XML to.
$url = $gotoUrl;
//Initiate cURL
$curl = curl_init($url);
//Set CURLOPT_POST to true to send a POST request.
curl_setopt($curl, CURLOPT_POST, true);
//Attach the XML string to the body of our request.
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
//Tell cURL that we want the response to be returned as
//a string instead of being dumped to the output.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//Execute the POST request and send our XML.
$result = curl_exec($curl);
//Do some basic error checking.
if(curl_errno($curl)){
	throw new Exception(curl_error($curl));
}
//Close the cURL handle.
curl_close($curl);
$funnybase  = $result;
$luashit = array('LUA_TTABLE', "LUA_TSTRING");
$data = str_replace($luashit, "", $funnybase);
//echo $data;
$almost = strstr($data, '<ns1:value>');
$luashit = array('<ns1:value>', "</ns1:value></ns1:OpenJobResult><ns1:OpenJobResult><ns1:type></ns1:type><ns1:table></ns1:table></ns1:OpenJobResult></ns1:OpenJobResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>");
$yeab = str_replace($luashit, "", $almost);
$yeas = str_replace("</ns1:value></ns1:OpenJobResult></ns1:OpenJobResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>","","$yeab");
$yeah = "data:image/jpeg;base64,".$yeas;
$update = $conn->prepare("UPDATE catalog SET thumbnail = :thumb WHERE id=:id");
$update->execute([
':thumb' => $yeah,
':id' => $item['id']
]);
}
if($item['type'] == 'shirt'){
$xml = "
<?xml version='1.0' encoding='UTF-8'?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:ns2='http://roblox.com/RCCServiceSoap' xmlns:ns1='http://roblox.com/' xmlns:ns3='http://roblox.com/RCCServiceSoap12'>
	<SOAP-ENV:Body>
		<ns1:OpenJob>
			<ns1:job>
				<ns1:id>3</ns1:id>
				<ns1:expirationInSeconds>1</ns1:expirationInSeconds>
				<ns1:category>1</ns1:category>
				<ns1:cores>321</ns1:cores>
			</ns1:job>
			<ns1:script>
				<ns1:name>Script</ns1:name>
				<ns1:script>
				game.Players:CreateLocalPlayer(0)
			 game.Players.LocalPlayer:LoadCharacter()
  
  local char = game.Players.LocalPlayer.Character or game.Players.LocalPlayer.Character.CharacterAdded:Wait()
  
  char.Head.BrickColor = BrickColor.new('White')
  char.Torso.BrickColor = BrickColor.new('White')
  char['Right Arm'].BrickColor = BrickColor.new('White')
  char['Left Arm'].BrickColor = BrickColor.new('White')
  char['Right Leg'].BrickColor = BrickColor.new('White')
  char['Left Leg'].BrickColor = BrickColor.new('White')
  
  shirt = Instance.new('Shirt')
  shirt.ShirtTemplate = '".$item['filename']."'
  shirt.Parent = char
			  
  
b64 = game:GetService('ThumbnailGenerator'):Click('PNG', 420, 420, true)
return b64
		</ns1:script>
			</ns1:script>
		</ns1:OpenJob>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
";
//The URL that you want to send your XML to.
$url = $gotoUrl;
//Initiate cURL
$curl = curl_init($url);
//Set CURLOPT_POST to true to send a POST request.
curl_setopt($curl, CURLOPT_POST, true);
//Attach the XML string to the body of our request.
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
//Tell cURL that we want the response to be returned as
//a string instead of being dumped to the output.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//Execute the POST request and send our XML.
$result = curl_exec($curl);
//Do some basic error checking.
if(curl_errno($curl)){
	throw new Exception(curl_error($curl));
}
//Close the cURL handle.
curl_close($curl);
$funnybase  = $result;
$luashit = array('LUA_TTABLE', "LUA_TSTRING");
$data = str_replace($luashit, "", $funnybase);
//echo $data;
$almost = strstr($data, '<ns1:value>');
$luashit = array('<ns1:value>', "</ns1:value></ns1:OpenJobResult><ns1:OpenJobResult><ns1:type></ns1:type><ns1:table></ns1:table></ns1:OpenJobResult></ns1:OpenJobResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>");
$yeab = str_replace($luashit, "", $almost);
$yeas = str_replace("</ns1:value></ns1:OpenJobResult></ns1:OpenJobResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>","","$yeab");
$yeah = "data:image/jpeg;base64,".$yeas;
$update = $conn->prepare("UPDATE catalog SET thumbnail = :thumb WHERE id=:id");
$update->execute([
':thumb' => $yeah,
':id' => $item['id']
]);
}
  
if($item['type'] == 'pants'){
$xml = "
<?xml version='1.0' encoding='UTF-8'?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:ns2='http://roblox.com/RCCServiceSoap' xmlns:ns1='http://roblox.com/' xmlns:ns3='http://roblox.com/RCCServiceSoap12'>
	<SOAP-ENV:Body>
		<ns1:OpenJob>
			<ns1:job>
				<ns1:id>3</ns1:id>
				<ns1:expirationInSeconds>1</ns1:expirationInSeconds>
				<ns1:category>1</ns1:category>
				<ns1:cores>321</ns1:cores>
			</ns1:job>
			<ns1:script>
				<ns1:name>Script</ns1:name>
				<ns1:script>
				game.Players:CreateLocalPlayer(0)
			 game.Players.LocalPlayer:LoadCharacter()
  
  local char = game.Players.LocalPlayer.Character or game.Players.LocalPlayer.Character.CharacterAdded:Wait()
  
  char.Head.BrickColor = BrickColor.new('White')
  char.Torso.BrickColor = BrickColor.new('White')
  char['Right Arm'].BrickColor = BrickColor.new('White')
  char['Left Arm'].BrickColor = BrickColor.new('White')
  char['Right Leg'].BrickColor = BrickColor.new('White')
  char['Left Leg'].BrickColor = BrickColor.new('White')
  
  pant = Instance.new('Pants')
  pant.PantsTemplate = '".$item['filename']."'
  pant.Parent = char
			  
  
b64 = game:GetService('ThumbnailGenerator'):Click('PNG', 420, 420, true)
return b64
		</ns1:script>
			</ns1:script>
		</ns1:OpenJob>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
";
//The URL that you want to send your XML to.
$url = $gotoUrl;
//Initiate cURL
$curl = curl_init($url);
//Set CURLOPT_POST to true to send a POST request.
curl_setopt($curl, CURLOPT_POST, true);
//Attach the XML string to the body of our request.
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
//Tell cURL that we want the response to be returned as
//a string instead of being dumped to the output.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//Execute the POST request and send our XML.
$result = curl_exec($curl);
//Do some basic error checking.
if(curl_errno($curl)){
	throw new Exception(curl_error($curl));
}
//Close the cURL handle.
curl_close($curl);
$funnybase  = $result;
$luashit = array('LUA_TTABLE', "LUA_TSTRING");
$data = str_replace($luashit, "", $funnybase);
//echo $data;
$almost = strstr($data, '<ns1:value>');
$luashit = array('<ns1:value>', "</ns1:value></ns1:OpenJobResult><ns1:OpenJobResult><ns1:type></ns1:type><ns1:table></ns1:table></ns1:OpenJobResult></ns1:OpenJobResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>");
$yeab = str_replace($luashit, "", $almost);
$yeas = str_replace("</ns1:value></ns1:OpenJobResult></ns1:OpenJobResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>","","$yeab");
$yeah = "data:image/jpeg;base64,".$yeas;
$update = $conn->prepare("UPDATE catalog SET thumbnail = :thumb WHERE id=:id");
$update->execute([
':thumb' => $yeah,
':id' => $item['id']
]);
}
$returnUrl = $_GET["r"] ?? "/admin/pendingassets.php";
header('location: '.$returnUrl);