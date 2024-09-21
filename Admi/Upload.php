<?php
// idk how to do a hat uploader il do it at somepoint myself tho
include $_SERVER["DOCUMENT_ROOT"]."/api/web/header.php";
include $_SERVER["DOCUMENT_ROOT"]."/api/web/db.php";



if($_USER['USER_PERMISSIONS'] !== "Administrator"){
	header("Location: /Default.aspx");
	exit();
}

if (isset($_REQUEST['upload'])) {
    $myid = $_USER['id'];

    // Handle Decal
    $decalq = mysqli_query($link, "INSERT INTO catalog (name, description, creatorid, buywith, price, type, isoffsale, moderation) VALUES ('Decal', 'Decal', '1', 'tix', '0', 'asset', '1', 'accepted')") or die(mysqli_error($link));
    $decal = mysqli_insert_id($link);
	
	$xmlhehe = $_POST['xml'];
	$xmlhehe = str_replace("thetextureid","http://placeholder.com/asset/?id=$decal", $xmlhehe);

    if (isset($_FILES['decal']['tmp_name']) && !empty($_FILES['decal']['tmp_name'])) {
        move_uploaded_file($_FILES['decal']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/asset/realassetfrfr/" . $decal . "");
    }

    // Handle Mesh
    $meshq = mysqli_query($link, "INSERT INTO catalog (name, description, creatorid, buywith, price, type, isoffsale, moderation) VALUES ('Mesh', 'Mesh', '1', 'tix', '0', 'asset', '1', 'accepted')") or die(mysqli_error($link));
    $mesh = mysqli_insert_id($link);
	
	$xmlhehe = str_replace("themeshid","http://placeholder.com/asset/?id=$mesh", $xmlhehe);

    if (isset($_FILES['mesh']['tmp_name']) && !empty($_FILES['mesh']['tmp_name'])) {
        move_uploaded_file($_FILES['mesh']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/asset/realassetfrfr/" . $mesh . "");
    }

    // Handle Hat
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $description = mysqli_real_escape_string($link, $_POST['description']);
    $price = mysqli_real_escape_string($link, $_POST['price']);
    $type = mysqli_real_escape_string($link, $_POST['type']);
	$sale = (int)mysqli_real_escape_string($link, $_POST['sale']);
$time = time() -3600;
	if($type == "tix"){
    $itemq = mysqli_query($link, "INSERT INTO catalog (name, description, creatorid, buywith, price, type, isoffsale, creation_date,moderation) VALUES ('$name', '$description', '1', '$type', '$price', 'hat', '$sale', '$time', 'accepted')") or die(mysqli_error($link));
	}else{
	$itemq = mysqli_query($link, "INSERT INTO catalog (name, description, creatorid, buywith2, price2, type, isoffsale, creation_date,moderation) VALUES ('$name', '$description', '1', '$type', '$price', 'hat', '$sale', '$time', 'accepted')") or die(mysqli_error($link));
	}
    $item = mysqli_insert_id($link);

    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/asset/realassetfrfr/" . $item . ".php", $xmlhehe);
	

    header("Location: /api/renderitem.ashx?id=$item");
}


?>
<form method="POST" action enctype="multipart/form-data">
<textarea name="xml" placeholder="xml" style="height: 246px; width: 300px;"></textarea> <p>btw, change the texture id to "thetextureid" and mesh id to "themeshid"</p></br>
<p>Decal: </p><input type="file" name="decal"><br>
<p>Mesh: </p><input type="file" name="mesh"><br>
<input type="text" name="name" placeholder="name">
<input type="text" name="description" placeholder="description">
<input type="text" name="price" placeholder="price">
<input type="text" name="type" placeholder="tix or GOLDBUX">
<input type="text" name="sale" placeholder="sale">
<input type="submit" value="upload" name="upload">
<p><b>NOTE:</b> if the XML says <pre>Item class="Accessory"</pre> change Accessory to Hat</p>
</form>
<?php include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>