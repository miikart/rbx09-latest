<?php
include $_SERVER["DOCUMENT_ROOT"]."/api/web/config.php";
if(!$auth == true){
header('Location: /Error/Default.aspx');
	exit();
}
if(isset($_POST['delMsgs'])) {
    $selected = json_decode($_POST['delMsgs']);
    if($selected) {
        foreach($selected as $id) {
	$checkq = $con->prepare("SELECT * FROM messages WHERE user_to = :uid AND id = :id");
    $checkq->execute([":uid" => $_USER['id'] , ":id" => (int)$id]);
 
	
			if($checkq->rowCount() > 0){
			$sigma2 = $con->prepare("UPDATE messages SET deleteto = 1 WHERE id = :id");
    $sigma2->execute([':id' => (int)$id]);
			
			}else{
				exit("This message not yours.");
			}
        }
		exit("success");
         
    }else{
		exit("no post");
	}
}else{
	exit("no post");
}
?>