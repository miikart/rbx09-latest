<?php
include $_SERVER["DOCUMENT_ROOT"]."/api/web/header.php";

if($auth == false) {
     header("Location: /Error/Default.aspx");
    exit();
}

if($_USER['USER_PERMISSIONS'] !== "Administrator") {
    header("Location: /Error/Default.aspx");
    exit();
}

try {
  
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$banners = "SELECT * FROM banners";
$real = $con->query($banners);

if (isset($_POST['upload'])) {
    $uploader = $_USER['id'];
    $text = htmlspecialchars($_POST['text']);
    if(!empty($text)) {
        $color = htmlspecialchars($_POST['color']);
        $textcolor = htmlspecialchars($_POST['textcolor']);
        
        $textcolorint = ($textcolor == "black") ? 1 : 0;
        
        echo htmlspecialchars($text);
        echo htmlspecialchars($color);
        echo htmlspecialchars($textcolor);
        echo $textcolorint;

        $stmt = $con->prepare("INSERT INTO banners (text, color, textcolor, uploader) VALUES (:text, :color, :textcolor, :uploader)");
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':textcolor', $textcolorint);
        $stmt->bindParam(':uploader', $uploader);

        if ($stmt->execute()) {
            header("Location: /Admi/ManageBanners.aspx");
        } else {
            echo 'Error: Unable to upload banner.';
        }
    } else {
        echo 'Please provide the banner text.';
    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    echo $id;
    
    $stmt = $con->prepare("DELETE FROM banners WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: /Admi/ManageBanners.aspx");
    } else {
        echo 'Error: Unable to delete banner.';
    }
    exit;
}

if (isset($_POST['clear'])) {
    $con->beginTransaction();

    $deleteBanners = $con->prepare("DELETE FROM banners");
    $resetAutoIncrement = $con->prepare("ALTER TABLE banners AUTO_INCREMENT = 1");

    if ($deleteBanners->execute() && $resetAutoIncrement->execute()) {
        $con->commit();
        header("Location: /Admi/ManageBanners.aspx");
    } else {
        $con->rollBack();
        echo 'Error: Unable to clear banners.';
    }
    exit;
}
?>

<style>
    #EditProfileContainer {
        background-color: #eeeeee;
        border: 1px solid #000;
        color: #555;
        margin: 0 auto;
        width: 620px;
    }
    #EditProfileContainer #AgeGroup, #EditProfileContainer #ChatMode, #EditProfileContainer #PrivacyMode, #EditProfileContainer #EnterEmail, #EditProfileContainer #ResetPassword, #EditProfileContainer #Blurb {
        margin: 0 auto;
        width: 60%;
    }
</style>

<div id="Body">
    <div id="EditProfileContainer">
        <h2>Manage Banners</h2>
        <!--<center><p>no assets found</p></center>-->
        <?php
        if($real) {
            while($row = $real->fetch(PDO::FETCH_ASSOC)) {
                $textcolor = ($row['textcolor'] == 1) ? "Black" : "White";
                
                echo '<form method="POST" action enctype="multipart/form-data">
                <div id="Blurb">
                    <fieldset>
                        <input type="hidden" name="id" value="'. htmlspecialchars($row['id']) .'">
                        <legend>Banner '. htmlspecialchars($row['id']) .'</legend>
                        <h4>'. htmlspecialchars($row['text']) .'</h4>
                        <h4>Color: '. htmlspecialchars($row['color']) .'<div style="height:20px; width:100%; background-color: '. htmlspecialchars($row['color']) .';"></div></h4>
                        <h4>Text Color: '. htmlspecialchars($textcolor) .'</h4> 
                        <center><input name="delete" id="delete" tabindex="4" style="width:25%;" class="Button" type="submit" value="Delete"></center>
                    </fieldset>
                </div></form>';
            }
        }
        ?>
        <form method="POST" action enctype="multipart/form-data">
        <div id="Blurb">
            <fieldset>
                <legend>NEW BANNER</legend>
                <textarea name="text" rows="1" cols="1" id="Text" placeholder="Text" style="font-size: 1em; !important width:100%" class="MultilineTextBox"></textarea>
                <h4>Color: <input name="color" placeholder="color" type="color" style="width: 100%; border: dashed 2px Gray;"></h4>
                <fieldset style="width:92.5%; border: dashed 2px Gray;">
                    <legend>Text Color:</legend>
                    <label>
                      <input type="radio" name="textcolor" value="white" checked>
                      White
                    </label>
                    <label>
                      <input type="radio" name="textcolor" value="black">
                      Black
                    </label>
                </fieldset>
            </fieldset>
        </div>
        <div class="Buttons">
            <input name="upload" id="upload" tabindex="4" class="Button" type="submit" value="Create">&nbsp;<input name="clear" id="clear" tabindex="4" class="Button" type="submit" value="Clear All">
        </div></form>
    </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>
