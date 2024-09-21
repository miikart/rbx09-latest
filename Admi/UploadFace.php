<?php

include $_SERVER["DOCUMENT_ROOT"]."/api/web/header.php";
include $_SERVER["DOCUMENT_ROOT"]."/api/web/db.php";

if($_USER['USER_PERMISSIONS'] !== "Administrator"){
	header("Location: /Error/Default.aspx");
	exit();
}

if (isset($_POST['upload'])) {
    $myid = $_USER['id'];
  
    // Handle Decal
     $decalq = mysqli_query($link, "INSERT INTO catalog (name, description, creatorid, buywith, price, type, isoffsale, moderation) VALUES ('Decal', 'Decal', '1', 'tix', '0', 'asset', '1', 'accepted')") or die(mysqli_error($link));
    $decal = mysqli_insert_id($link);
	
	
	// Your XML content
$xmlContent = '<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.roblox.com/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="Decal" referent="RBX0">
		<Properties>
			<token name="Face">5</token>
			<string name="Name">face</string>
			<float name="Shiny">20</float>
			<float name="Specular">0</float>
			<Content name="Texture"><url>thetextureid</url></Content>
			<bool name="archivable">true</bool>
		</Properties>
	</Item>
</roblox>';

// Load XML content into a DOMDocument
$doc = new DOMDocument;
$doc->preserveWhiteSpace = false;
$doc->loadXML($xmlContent);
$doc->formatOutput = true;

// Output the formatted XML
$xmlhehe = $doc->saveXML();
	$xmlhehe = str_replace("thetextureid","http://placeholder.com/asset/?id=$decal", $xmlhehe);

    if (isset($_FILES['decal']['tmp_name']) && !empty($_FILES['decal']['tmp_name'])) {
        move_uploaded_file($_FILES['decal']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/asset/realassetfrfr/" . $decal . ".php");
    }

    // Handle Hat
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $description = mysqli_real_escape_string($link, $_POST['description']);
    $price = mysqli_real_escape_string($link, $_POST['price']);
    $type = mysqli_real_escape_string($link, $_POST['type']);
	$sale = (int)mysqli_real_escape_string($link, $_POST['sale']);
    $ctype = "face";
   $time = time() -3600;
      $itemq = mysqli_query($link, "INSERT INTO catalog (name, description, creatorid, buywith, price, type, isoffsale, creation_date,moderation) VALUES ('$name', '$description', '1', '$type', '$price', 'face', '$sale', '$time', 'accepted')") or die(mysqli_error($link));

    $item = mysqli_insert_id($link);

    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/asset/realassetfrfr/" . $item . ".php", $xmlhehe);
	$source = $_SERVER['DOCUMENT_ROOT'] . "/asset/realassetfrfr/" . $decal . ".php";
    $destination = $_SERVER['DOCUMENT_ROOT'] . "/Thumbs/CATALOG/" . $item . ".png";
    $destination2 = $_SERVER['DOCUMENT_ROOT'] . "/Thumbs/CATALOG/" . $item . "-small.png";
    copy($source, $destination);
    copy($source, $destination2);


    header("Location: /Item.aspx?ID=$item");
}


?>
<form method="POST" action enctype="multipart/form-data">
<p>Decal: </p><input type="file" name="decal"><br>
<input type="text" name="name" placeholder="name">
<input type="text" name="description" placeholder="description">
<input type="text" name="price" placeholder="price">
<input type="text" name="type" placeholder="tix or robux">
<input type="text" name="sale" placeholder="sale">
<input type="submit" value="upload" name="upload">
</form>
<?php include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>