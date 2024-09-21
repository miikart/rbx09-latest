
<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/header.php';
$type = (int)$_REQUEST['ContentType'];

if($type === false || !in_array($type, [0, 1, 2, 3])) {
    header("Location: /Error/Default.aspx"); 
    exit;
}

$what = $whata = $typelol = $explanation = $price = "";
if($type == 0){ 
  $what = "T-Shirt";
  $whata = "tshirt";
  $typelol = "tshirts";
  $typexml = "ShirtGraphic";
  $typexml2 = "Graphic";
  $explanation = '
          <p>On GOLDBLOX, a T-Shirt is a transparent torso adornment with a decal applied to the front surface. To create a T-Shirt:</p>
          <ol>
            <li>Click the "Browse" button below.</li>
            <li>Use the File Explorer that pops up to browse your computer.</li>
            <li>Find and select the picture that you want to use as the shirt\'s decal. Most standard images (.png, .bmp, .gif) will work.</li>
            <li>Finally, click the "Create T-Shirt" button.</li>
          </ol>
          <p>The image you selected will be uploaded to GOLDBLOX, where we will create a T-Shirt and add it to your inventory. To wear this T-Shirt, simply go to the <a href="/My/Character">Change Character</a> page, find them in your wardrobe, and click to wear them.</p>
  ';
}elseif($type == 1){
  $what = "Shirt";
  $typexml = "Shirt";
  $whata = "shirt";
  $typelol = "shirts";
  $typexml2 = "ShirtTemplate"; 
  $explanation = '
    <p>On GOLDBLOX, a Shirt is a textured character adornment that is applied to all surfaces of the character\'s arms and torso. To create Shirts:</p>
    <ol>
      <li>Open the <a href="Template1.png">Shirt Template</a> in the image editor of your choice.</li>
      <li>Modify the template to suit your tastes.</li>
      <li>Save the customized Shirt texture to your computer.</li>
      <li>Click the "Browse" button below.</li>
      <li>Use the File Explorer that pops up to browse your computer.</li>
      <li>Find and select the newly created Shirt Texture.</li>
      <li>Finally, click the "Create Shirt" button.</li>
    </ol>
    <p>The texture you created will be uploaded to GOLDBLOX, where we will add your Shirt and add it to your inventory. To wear this Shirt, simply go to the <a href="/My/Character">Change Character</a> page, find them in your wardrobe, and click to wear them.</p>
    <p style="display:none;">For more information, read the tutorial: <a href="#">How to Make Shirts and Pants</a>.</p>
  ';
}elseif($type == 2){
  $what = "Pants";
  $whata = "pants";
  $typelol = "pants";
  $typexml = "Pants";
  $typexml2 = "PantsTemplate"; 

  $explanation = '
    <p>On GOLDBLOX, Pants are a textured character adornment that is applied to all surfaces of the character\'s legs and torso. To create Pants:</p>
    <ol>
      <li>Open the <a href="Template2.png">Pants Template</a> in the image editor of your choice.</li>
      <li>Modify the template to suit your tastes.</li>
      <li>Save the customized Pants texture to your computer.</li>
      <li>Click the "Browse" button below.</li>
      <li>Use the File Explorer that pops up to browse your computer.</li>
      <li>Find and select the newly created Pants Texture.</li>
      <li>Finally, click the "Create Pants" button.</li>
    </ol>
    <p>The texture you created will be uploaded to GOLDBLOX, where we will add your Pants and add it to your inventory. To wear this Pants, simply go to the <a href="/My/Character">Change Character</a> page, find them in your wardrobe, and click to wear them.</p>
    <p style="display:none;">For more information, read the tutorial: <a href="#">How to Make Shirts and Pants</a>.</p>
  ';
}elseif($type == 3){
  $what = "Decal";
  $whata = "decal";
  $typelol = "decals";
  $explanation = '
    <p>On GOLDBLOX, a Decal is an image that can be applied to one of a part\'s faces. To create a Decal:</p>
    <ol>
      <li>Click the "Browse" button below.</li>
      <li>Use the File Explorer that pops up to browse your computer.</li>
      <li>Find and select the picture that you want to use as your decal. Only (.png) will work temporarily.</li>
      <li>Finally, click the "Create Decal" button.</li>
    </ol>
    <p>The image you selected will be uploaded to GOLDBLOX, where we will add your Decal and add it to your inventory. To use this Decal, simply open the <strong>Insert</strong> menu in GOLDBLOX, choose My Decals, and click the Decal you wish to insert. You can drag the Decal onto the part you wish to decorate.</p>
  ';
}
if (!$auth) {
    header("Location: /Login/Default.aspx");
    exit;
}

use SoftCreatR\MimeDetector\MimeDetector;

$allowedExtensions = ['png', 'gif', 'jpeg', 'jpg', 'bmp'];
$error = null;
$uploadSuccess = false;
if (isset($_REQUEST['submit'])) {
   if(CSRF::check(trim($_REQUEST["csrf_token"]))) {
  } else {
  $error = "vro the csrf is invaild";  
      
  }  
     
 $checkStmt = $con->prepare("SELECT * FROM users WHERE id = :userid ORDER BY id DESC LIMIT 1");
    $checkStmt->execute(['userid' => $_USER['id']]);

    $ratelimited = false;
    if ($checkStmt->rowCount() == 1) {
        $ratelimit = $checkStmt->fetch(PDO::FETCH_ASSOC);
        if (time() <= $ratelimit['renderedfirsttime']) {
            $ratelimited = true;
        context::boughtitem("# " . $_USER['username'] . " (id " . $_USER['id'] . ") MIGHT be a small weiner dickhead that tried to spam the site");
        $error ="you are being ratelimited vro wait 25 seconds";
            
        }
    }

   
  if(!$error) {
    if (!empty($_FILES['texture']['tmp_name'])) {
      
        $fileName = basename($_FILES['texture']['name']);
    if (strlen($fileName) > 64) {
    $fileName = substr($fileName, 0, 64);
}
     
  
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));



  
      $timeout = time() + 25;
    $time = time();
        if (!in_array($fileType, $allowedExtensions)) {
            $error = "grrr invaild file format";
        } else {
         
            $mimeDetector = new MimeDetector();
            $fileMimeType = $mimeDetector->getMimeType($_FILES['texture']['tmp_name']);

            if ($fileMimeType !== 'image/png') {
                switch ($fileType) {
                    case 'bmp':
                        $image = imagecreatefrombmp($_FILES['texture']['tmp_name']);
                        break;
                    case 'gif':
                        $gif = imagecreatefromgif($_FILES['texture']['tmp_name']);
                        $image = imagecreatetruecolor(imagesx($gif), imagesy($gif));
                        imagecopy($image, $gif, 0, 0, 0, 0, imagesx($gif), imagesy($gif));
                        imagedestroy($gif);
                        break;
                    case 'png':
                        $image = imagecreatefrompng($_FILES['texture']['tmp_name']);
                        break;
                   case 'jpeg':
                    
                        $image = imagecreatefromjpeg($_FILES['texture']['tmp_name']);
                        break;

                    case 'jpg':
                        $image = imagecreatefromjpeg()($_FILES['texture']['tmp_name']);
                        break;
                    default:
                        $error = "grrr invaild file format";
                }

                if (isset($image)) {
                   
                }
            }

            if (!isset($error)) {
              $gex = "INSERT INTO catalog (name, description, creatorid, type ,isoffsale, moderation) VALUES (:name, :description, :creatorid, :type, :isoffsale ,:pending)";
$stmt = $con->prepare($gex);
$stmt->execute([
    ':name' => 'thingy',
    ':description' => 'thingy by me :)',
    ':creatorid' => $_USER['id'],
    ':type' => 'asset',
    ':isoffsale' => 1,
    ':pending' => 'accepted'
]);
$decal = $con->lastInsertId();

if($type != 3) {
$xmlContent = '<?xml version="1.0" encoding="utf-8"?>
<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.roblox.com/roblox.xsd" version="4">
    <External>null</External>
    <External>nil</External>
    <Item class="'. $typexml .'" referent="RBX0">
        <Properties>
            <Content name="'. $typexml2 .'">
                <url>thetextureid</url>
            </Content>
            <string name="Name">'. $whata .'</string>
            <bool name="archivable">true</bool>
        </Properties>
    </Item>
</roblox>';
} else {
 $xmlContent = '<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.roblox.com/roblox.xsd" version="4">
  <External>null</External>
  <External>nil</External>
  <Item class="Decal" referent="RBX0">
    <Properties>
      <token name="Face">5</token>
      <string name="Name">Decal</string>
      <float name="Shiny">20</float>
      <float name="Specular">0</float>
      <Content name="Texture">
        <url>thetextureid</url>
      </Content>
      <bool name="archivable">true</bool>
    </Properties>
  </Item>
</roblox>';   
}
$doc = new DOMDocument;
$doc->preserveWhiteSpace = false;
$doc->loadXML($xmlContent);
$doc->formatOutput = true;
$xmlhehe = $doc->saveXML();
$xmlhehe = str_replace("thetextureid", "http://placeholder.com/asset/?id=$decal", $xmlhehe);

if (isset($_FILES['texture']['tmp_name']) && !empty($_FILES['texture']['tmp_name'])) {
    move_uploaded_file($_FILES['texture']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/asset/realassetfrfr/" . $decal . "");

                  
   $skibidi = $con->prepare("UPDATE users SET renderedfirsttime = :timeout WHERE id = :userid");

    $skibidi->execute([

        'timeout' => $timeout,

        'userid' => $_USER['id'],

    ]); 
            $what = "$what Image";
                    try {
                         
                        $stmt = $con->prepare("INSERT IGNORE INTO catalog (name, description, creatorid, creation_date, type, isoffsale, moderation) VALUES (:name, :description,  :creatorid, :time, :type, :isoffsale ,'pending')");
                        $stmt->execute([
                            'name' => $fileName,
                            'description' => $what,
                            'creatorid' => $_USER['id'],
                             'time' => time() -3600,
                            'type' => $whata,
                              ':isoffsale' => 1,
                    
                        ]);
                       $lastInsertId = $con->lastInsertId();
                        $stmt = $con->prepare("INSERT INTO owned_items (itemid,ownerid,type) VALUES (:id, :ownerid  , :type)");
                        $stmt->execute([
                            'id' => $lastInsertId,
                          'ownerid' => $_USER['id'],
                     'type' => $whata,
                        ]);
                       
                        
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/asset/realassetfrfr/" . $lastInsertId . "", $xmlhehe);
                 
                 
                        context::boughtitem("# " . $_USER['username'] . " (id " . $_USER['id'] . ") uploaded\n**Link:** http://www.rccs.lol/Item.aspx?ID=" . $lastInsertId . "\n**item file:** " . $filename);
                        
                        header("Location: /Item.aspx?ID=" . $lastInsertId);
                        exit();
                  
                    } catch (PDOException $e) {
                        echo "AAAAAAAAAAAAAAAAAAAA $e";
                    }
                } else {
                    $error = "shit went wrong please report to copy.aspx";
                }
            }
        }  
    } else {
        $error = "select a file vro";
    }  
 } }
    
?>

<div id="Body">
    <form action="" method="POST" enctype="multipart/form-data">
     
        <div id="ContentBuilderContainer">
            <h2><?= $what; ?> Builder</h2><br>

            <div class="InstructionsPanel">
                <h3>Instructions</h3>
                <?= $explanation; ?>
            </div>

            <div id="upload" class="UploaderPanel">
                <h3>Upload Texture</h3>
                <br>
                <input id="filename" type="text" name="filename" disabled value="">
                <input id="files" type="file" name="texture">
                <br>
                <br>
                <input type="submit" name="submit" value="Create <?= $what; ?>">
                 <span id="warning" class="Attention">
                               <?php echo $error; ?>
                 </span>
                <br>
                <div style='padding:10px 1px 1px 1px'></div>
      <?php $csrf = CSRF::generate();  if($csrf) { echo '<input type="hidden" name="csrf_token" value="'. $csrf .'">';  } else {  $error= 'CSRF token failed to generate. Uploads will not work.'; } ?>
                <script>
                    $("#files").change(function() {
                        filename = this.files[0].name;
                        $("#filename").val(filename);
                    }); 
                </script>
            </div>
        </div>
    </form>
</div>
<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/api/web/footer.php';
?>