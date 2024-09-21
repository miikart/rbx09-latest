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







if (isset($_GET['accept'])) {
    try {
        $id = $_GET['accept'];

        // Check if the item is already accepted
        $queryCheckAccepted = "SELECT moderation FROM catalog WHERE id = ?";
        $stmtCheckAccepted = $con->prepare($queryCheckAccepted);
        $stmtCheckAccepted->execute([$id]);
        $resultCheckAccepted = $stmtCheckAccepted->fetch(PDO::FETCH_ASSOC);
        if ($resultCheckAccepted) {
            if ($resultCheckAccepted['moderation'] === 'accepted') {
                echo "Item is already accepted!";
                exit; // Exit script if item is already accepted
            }
        } else {
            echo "Error: Item not found!";
            exit; // Exit script if item is not found
        }

        // Proceed with acceptance process if item is not already accepted
        $querySelect = "SELECT creatorid, type FROM catalog WHERE id = ?";
        $stmtSelect = $con->prepare($querySelect);
        $stmtSelect->execute([$id]);
        $rowSelect = $stmtSelect->fetch(PDO::FETCH_ASSOC);
        if ($rowSelect) {
            $creatorId = $rowSelect['creatorid'];
            $assetType = $rowSelect['type'];

          
            if (!$resultCheckOwned) {
                // Update catalog table
                $queryUpdateCatalog = "UPDATE catalog SET moderation = 'accepted', isoffsale = 1, price = 0 WHERE id = ?";
                $stmtUpdateCatalog = $con->prepare($queryUpdateCatalog);
                $stmtUpdateCatalog->execute([$id]);

         
                header("Location: /api/renderitem.php?id=". $id);
                

                exit;
            } else {
                echo "You already own this item!";
                exit; // Exit script if item is already owned by the user
            }
        } else {
            echo "Error: Item not found!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} elseif (isset($_GET['deny'])) {
    try {
        $id = $_GET['deny'];
        $query = "UPDATE catalog SET moderation = 'declined' WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<div id="Body">
    <div id="EditProfileContainer">
		<h2>Asset Approval</h2>
		<center>
        <?php
        $recordsPerPage = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;
        try {
            $query = "SELECT * FROM catalog WHERE moderation = 'pending' AND moderation != 'accepted' LIMIT ?, ?";
            $stmt = $con->prepare($query);
            $stmt->bindValue(1, (int) $offset, PDO::PARAM_INT);
            $stmt->bindValue(2, (int) $recordsPerPage, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                foreach ($result as $row) {
                    ?>
                 <form method="POST">
                
            <p>
        ID: <?php echo ($row['id']); ?> |
        Name: <?php echo ($row['name']); ?> |
       Creator Id: <?php echo ($row['creatorid']); ?> |
        Description: <?php echo ($row['description']); ?> |
        Price: <?php echo ($row['price']); ?> |
        Type: <?php echo ($row['type']); ?>
       <br>
      </p> 
        <img src="http://rccs.lol/asset/?id=<?php echo (int)($row['id']) -1; ?>" style="height: 200px;">
    
               <div class="Buttons">
		<a id="ctl00_cphGoldblox_rbxLoginView_lvLoginView_lSignIn_Register" tabindex="5" class="Button" href="?accept=<?php echo htmlspecialchars($row['id']); ?>">Accept</a>&nbsp;<a id="ctl00_cphGoldblox_rbxLoginView_lvLoginView_lSignIn_Register" tabindex="5" class="Button" href="?deny=<?php echo htmlspecialchars($row['id']); ?>">Deny</a>
		</div>
           
            </form>
                    <?php
                }
            } else {
                echo "
                <br>
                nothing to accept";
            }

            $query = "SELECT COUNT(*) as total FROM catalog WHERE moderation = 'pending' AND moderation != 'accepted'";
            $stmt = $con->query($query);
            $totalRecords = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            $totalPages = ceil($totalRecords / $recordsPerPage);
            echo "<br>Pages: ";
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<a href='?page=$i'>$i</a> ";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>

</center>
	</div>
</div>
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







<?php  include $_SERVER["DOCUMENT_ROOT"]."/api/web/footer.php"; ?>