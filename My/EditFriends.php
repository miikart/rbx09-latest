<?php
    require_once ("../api/web/header.php");
if (!$auth) {
  header('Location: /Login/Default.aspx');
  exit();
}

$id = (int)$_USER['id'];

try {
 
    $stmt = $con->prepare("SELECT * FROM users WHERE id = :id AND bantype = 'None'");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      
        exit();
    }
    



    $resultsperpage = 30;
    
    $stmt = $con->prepare("SELECT * FROM friends WHERE (user_from = :id AND arefriends = '1') OR (user_to = :id AND arefriends = '1')");
    $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
    $stmt->execute();
    
    $usercount = $stmt->rowCount();
    $numberofpages = ceil($usercount / $resultsperpage);

    if(!isset($_GET['Page'])) {
        $page = 1;
    } else {
        $page = (int)$_GET['Page'];
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
<script>
function getfriend() {
    $.post("/api/fetchdeletefriend.php", {})
        .done(function(data) {
            $("#shit").html(data);
        })
        .fail(function() {
            $("#shit").html("Failed to get friends! report to copy.aspx");
        });
}
function deletefriend(id) {
    $.post("/api/Unfriend.php", {id: id})
        .done(function(data) {
            getfriend(1);
        })
        .fail(function() {
            $("#shit").html("Failed to delete friend! report to copy.aspx");
        });
}
getfriend(1);
</script>
<div id="FriendsContainer">
  <div id="Friends">
  <h4>My Friends (<?=$usercount;?>)</h4>
 <div id ='shit'>
     
     
 </div>
<?php include("../api/web/footer.php"); ?>
