
<?php
include $_SERVER["DOCUMENT_ROOT"]."/api/web/header.php";

if($auth == false) {
    header("Location: /Error/Default.aspx");
    exit();
}

if($_USER['USER_PERMISSIONS'] !== "Administrator"){
    header("Location: /Error/Default.aspx");
    exit();
}
if (isset($_POST['confirm'])) {
    $id = intval($_POST['id']);
    
    try {
        $con->beginTransaction();

        $stmtsigma = $con->prepare("DELETE FROM catalog WHERE id = ?");
        $stmtsigma->execute([$id]);
     

        $owned = $con->prepare("DELETE FROM owned_items WHERE itemid = ?");
        $owned->execute([$id]);
       

        $wear = $con->prepare("DELETE FROM wearing WHERE itemid = ?");
        $wear->execute([$id]);
     


        $fav = $con->prepare("DELETE FROM favorites WHERE itemid = ?");
        $fav->execute([$id]);
          echo "item $id deleted from catalog. ";

        $con->commit();
    } catch (PDOException $e) {
        $con->rollBack();
        echo "error deleting item $id: " . $e->getMessage();
    }
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
    #assetid {
        text-align: center;
        margin-top: 5px;
    }
    #renderImage {
        width:450px;
        border: 1px solid black;
        margin-top: 10px;
    }
    #render {
        width:500px;
        height: 40px;
    }
    .renderContainer {
        text-align: center;
    }
</style>
<div  id='Body'>
<div id='EditProfileContainer'>
		<h2>delete asset</h2>
<center>
    <form method="post" style="padding:20px;">
        
            <b>id to delete</b>
            <input type="number" name="id" id="id" required>
       <div style="margin-top:20px">
</div>
     
            <input type="submit" tabindex="4" name="confirm" value="Confirm" class="Button">
        </div>
    </form>
</center>
</div>
</div></div>
<?php include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>
