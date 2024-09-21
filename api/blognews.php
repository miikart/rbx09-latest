<?php
include $_SERVER['DOCUMENT_ROOT'] . '/api/web/config.php';
try {
 

   
    $sql = "SELECT ID, post_title, post_name 
            FROM wp_posts 
            WHERE post_status = 'publish' 
            AND post_type = 'post' 
            ORDER BY post_date DESC 
           LIMIT 5 "; 
    $stmt = $con->prepare($sql);
    $stmt->execute();

  
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

  
    foreach ($posts as $post) {
        $post_id = $post['ID'];
        $post_title = htmlspecialchars($post['post_title']);
        $post_name = htmlspecialchars($post['post_name']);
        $post_url = "http://www.rccs.lol/blog/index.php/$post_id/$post_name/";

        echo "<p><a href=\"$post_url\">$post_title</a></p>";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
