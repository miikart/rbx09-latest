<?php
header("Content-type: text/plain");
include "../api/web/config.php";

if ($auth == false) {
    $ReturnUrl = htmlspecialchars("//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", ENT_QUOTES, 'UTF-8');
    die(header("Location: /Login/Default.aspx?ReturnUrl=".$ReturnUrl));
}

$user_from = $_USER['id'];
$user_to = intval($_POST['id']);
if ($user_to < 1) {
    die("Invalid ID.");
}

try {
   

    $stmt1 = $con->prepare("DELETE FROM friends WHERE user_to = :user_to AND user_from = :user_from AND arefriends = '1'");
    $stmt1->bindParam(':user_to', $_USER['id'], PDO::PARAM_INT);
    $stmt1->bindParam(':user_from', $user_to, PDO::PARAM_INT);
    $stmt1->execute();

    $stmt2 = $con->prepare("DELETE FROM friends WHERE user_from = :user_from AND user_to = :user_to AND arefriends = '1'");
    $stmt2->bindParam(':user_from', $_USER['id'], PDO::PARAM_INT);
    $stmt2->bindParam(':user_to', $user_to, PDO::PARAM_INT);
    $stmt2->execute();

    if (isset($_GET['from_rq_page']) && $_GET['from_rq_page']) {
        header("Location: /User.aspx");
    } else {
        header("Location: /My/EditFriends.aspx");
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// header("Location: " . $_SERVER["HTTP_REFERER"]);
?>
