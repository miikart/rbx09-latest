<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/config.php");
$stmt = $link->prepare("SELECT * FROM users WHERE accountcode = ?");
$stmt->bind_param('s', $_GET['accountcode']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt = $link->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param('i', (int)$_GET['placeid']);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();
$sql = "SELECT * FROM wearing WHERE userid = ? AND type = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param('is', $user['id'], "tshirt");
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$echothing = '';
if (count($result) > 0 || $row['type'] == 'tshirt') {
	foreach ($result as $row) {
		$itemq = $link->prepare("SELECT * FROM catalog WHERE id = ? AND type = ?");
		$itemq->bind_param('is', $row['itemid'], $row['type']);
		$itemq->execute();
		$item = $itemq->get_result()->fetch_assoc();
		if ($row['type'] == 'tshirt') {
			$echothing .= $item['filename'];
		}
	}
}
$sql1 = "SELECT * FROM wearing WHERE userid = ? AND type = ?";
$stmt1 = $link->prepare($sql1);
$stmt1->bind_param('is', $user['id'], "shirt");
$stmt1->execute();
$result1 = $stmt1->get_result()->fetch_all(MYSQLI_ASSOC);
$echothing1 = '';
if (count($result1) > 0 || $row1['type'] == 'shirt') {
	foreach ($result1 as $row1) {
		$itemq1 = $conn->prepare("SELECT * FROM catalog WHERE id = ? AND type = ?");
		$itemq1->bind_param('is', $row1['itemid'], $row1['type']);
		$itemq1->execute();
		$item1 = $itemq1->get_result()->fetch_assoc();
		if ($row1['type'] == 'shirt') {
			$echothing1 .= $item1['filename'];
		}
	}
}
$sql2 = "SELECT * FROM wearing WHERE userid = ? AND type = ?";
$stmt2 = $link->prepare($sql2);
$stmt2->bind_param('is', $user['id'], "pants");
$stmt2->execute();
$result2 = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$echothing2 = '';
if (count($result2) > 0 || $row2['type'] == 'pants') {
	foreach ($result2 as $row2) {
		$itemq2 = $link->prepare("SELECT * FROM catalog WHERE id = ? AND type = ?");
		$itemq2->bind_param('is', $row2['itemid'], $row2['type']);
		$itemq2->execute();
		$item2 = $itemq2->get_result()->fetch_assoc();
		if ($row2['type'] == 'pants') {
			$echothing2 .= $item2['filename'];
		}
	}
}
$sql3 = "SELECT * FROM wearing WHERE userid = ? AND type = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param('is', $user['id'], "face");
$stmt3->execute();
$result3 = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
$echothing3 = '';
if (count($result3) > 0 || $row3['type'] == 'face') {
	foreach ($result3 as $row3) {
		$itemq3 = $conn->prepare("SELECT * FROM catalog WHERE id = ? AND type = ?");
		$itemq3->bind_param('is', $row3['itemid'], $row3['type']);
		$itemq3->execute();
		$item3 = $itemq3->get_result()->fetch_assoc();
		if ($row3['type'] == 'face') {
			$echothing3 .= $item3['filename'];
		}
	}
}
if ($echothing3 == '') {
	$echothing3 = "rbxasset://textures/face.png";
}
$sql4 = "SELECT * FROM wearing WHERE userid = ? AND type = ?";
$stmt4 = $link->prepare($sql4);
$stmt4->bind_param('is', $user['id'], "hat");
$stmt4->execute();
$result4 = $stmt4->get_result()->fetch_all(MYSQLI_ASSOC);
$echothing4 = '';
if (count($result4) > 0) {
	$row4 = $result4[0];
	$itemq4 = $conn->prepare("SELECT * FROM catalog WHERE id = ? AND type = ?");
	$itemq4->bind_param('is', $row4['itemid'], $row4['type']);
	$itemq4->execute();
	$item4 = $itemq4->get_result()->fetch_assoc();
	if ($row4['type'] == 'hat') {
		$echothing4 = $item4['assetlink'];
	}
}
$sql45 = "SELECT * FROM wearing WHERE userid = ? AND type = ?";
$stmt45 = $conn->prepare($sql45);
$stmt45->bind_param('is', $user['id'], "hat");
$stmt45->execute();
$result45 = $stmt45->get_result()->fetch_all(MYSQLI_ASSOC);
$echothing45 = '';
if (count($result45) > 1) {
	$row45 = $result45[1];
	$itemq45 = $conn->prepare("SELECT * FROM catalog WHERE id = ? AND type = ?");
	$itemq45->bind_param('is', $row45['itemid'], $row45['type']);
	$itemq45->execute();
	$item45 = $itemq45->get_result()->fetch_assoc();
	if ($row45['type'] == 'hat') {
		$echothing45 = $item45['assetlink'];
	}
}
$sql455 = "SELECT * FROM wearing WHERE userid = ? AND type = ?";
$stmt455 = $conn->prepare($sql455);
$stmt455->bind_param('is', $user['id'], "hat");
$stmt455->execute();
$result455 = $stmt455->get_result()->fetch_all(MYSQLI_ASSOC);
$echothing455 = '';
if (count($result455) > 2) {
	$row455 = $result455[2];
	$itemq455 = $conn->prepare("SELECT * FROM catalog WHERE id = ? AND type = ?");
	$itemq455->bind_param('is', $row455['itemid'], $row455['type']);
	$itemq455->execute();
	$item455 = $itemq455->get_result()->fetch_assoc();
	if ($row455['type'] == 'hat') {
		$echothing455 = $item455['assetlink'];
	}
}
$sql4555 = "SELECT * FROM wearing WHERE userid = ? AND type = ?";
$stmt4555 = $conn->prepare($sql4555);
$stmt4555->bind_param('is', $user['id'], "head");
$stmt4555->execute();
$result4555 = $stmt4555->get_result()->fetch_all(MYSQLI_ASSOC);
$echothing4555 = '';
if (count($result4555) > 0) {
	$row4555 = $result4555[0];
	$itemq4555 = $conn->prepare("SELECT * FROM catalog WHERE id = ? AND type = ?");
	$itemq4555->bind_param('is', $row4555['itemid'], $row4555['type']);
	$itemq4555->execute();
	$item4555 = $itemq4555->get_result()->fetch_assoc();
	if ($row4555['type'] == 'head') {
		$echothing4555 = $item4555['assetlink'];
	}
}
$sql45555 = "SELECT * FROM wearing WHERE userid = ? AND type = ?";
$stmt45555 = $conn->prepare($sql45555);
$stmt45555->bind_param('is', $user['id'], "torso");
$stmt45555->execute();
$result45555 = $stmt45555->get_result()->fetch_all(MYSQLI_ASSOC);
$echothing45555 = '';
if (count($result45555) > 0) {
	$row45555 = $result45555[0];
	$itemq45555 = $conn->prepare("SELECT * FROM catalog WHERE id = ? AND type = ?");
	$itemq45555->bind_param('is', $row45555['itemid'], $row45555['type']);
	$itemq45555->execute();
	$item45555 = $itemq45555->get_result()->fetch_assoc();
	if ($row45555['type'] == 'torso') {
		$echothing45555 = $item45555['assetlink'];
	}
}
$hatthing = '';
if (count($result4) > 0) {
	$hatthing = '
	local Hat = game:GetObjects("' . $echothing4 . '")[1]
	Hat.Parent = player
	';
}
$hatthing2 = '';
if (count($result45) > 0) {
	$hatthing2 = '
	local Hat2 = game:GetObjects("' . $echothing45 . '")[1]
	Hat2.Parent = player
	';
}
$hatthing3 = '';
if (count($result455) > 0) {
	$hatthing3 = '
	local Hat3 = game:GetObjects("' . $echothing455 . '")[1]
	Hat3.Parent = player
	';
}
$headthing = '';
if (count($result4555) > 0) {
	$headthing = '
	player.Head["Mesh"]:Remove()
	
	local Head = game:GetObjects("' . $echothing4555 . '")[1]
	Head.Parent = player.Head
	';
}
$torsothing = '';
if (count($result45555) > 0) {
	$torsothing = '
	local Hat4 = game:GetObjects("' . $echothing45555 . '")[1]
	Hat4.Parent = player
	';
}
$shirtthing = '';
if (count($result1) > 0) {
	$shirtthing = '
	local Shirt = Instance.new("Shirt", game.Players.LocalPlayer.Character)
	Shirt.ShirtTemplate = "' . $echothing1 . '"';
}
$facething = '';
if (count($result3) > 0) {
	$facething = '
	local Face = player.Head.face
	Face.Texture = "' . $echothing3 . '"';
}
$pantthing = '';
if (count($result2) > 0) {
	$pantthing = '
	local Pant = Instance.new("Pants", game.Players.LocalPlayer.Character)
	Pant.PantsTemplate = "' . $echothing2 . '"';
}
?>
local hasLoaded = false
function character()
	local player = game.Workspace:FindFirstChild("<?= $user['username']; ?>")
	if player ~= nil and hasLoaded == false then
		wait(1)
		player.Head.BrickColor = BrickColor.new("<?= $user['headcolor']; ?>")
		player.Torso.BrickColor = BrickColor.new("<?= $user['torsocolor']; ?>")
		player["Right Leg"].BrickColor = BrickColor.new("<?= $user['rightlegcolor']; ?>")
		player["Right Arm"].BrickColor = BrickColor.new("<?= $user['rightarmcolor']; ?>")
		player["Left Leg"].BrickColor = BrickColor.new("<?= $user['leftlegcolor']; ?>")
		player["Left Arm"].BrickColor = BrickColor.new("<?= $user['leftarmcolor']; ?>")
		<?= $facething; ?>
		<?= $shirtthing; ?>
		<?= $pantthing; ?>
		local TShirt = Instance.new("Decal")
		TShirt.Parent = player.Torso
		TShirt.Texture = "<?= $echothing; ?>"
		<?= $headthing; ?>
		<?= $hatthing; ?>
		<?= $hatthing2; ?>
		<?= $hatthing3; ?>
		player.Humanoid.Died:connect(function()
			if hasLoaded == true then
				wait(10)
				hasLoaded = false
			end
		end)
		hasLoaded = false
	end
end
workspace.ChildAdded:connect(character);
