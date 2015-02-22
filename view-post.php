<?php 
require_once 'lib/common.php';
require_once 'lib/view-post.php';

//Get the post ID
if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];
}
else {
    // So we always have a post ID variable defined
    $postId = 0;
}

//Connect to the database, run a query, handle errors
$pdo = getPDO();
$row = getPostRow($pdo, $postId);

//If the post does not exist, let's deal with that here
if (!$row) {
    redirectAndExit('index.php?not-found=1');
}

//Swap carriage returns for paragraph breaks
$bodyText = htmlEscape($row['body']);
$paraText = str_replace("\n", "</p><p>", $bodyText);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
       Spencer's blog |
       <?php echo htmlEscape($row['title']) ?> 
    </title>
</head>
<body>
    <?php require 'templates/title.php' ?>
    
    <h2>
        <?php echo htmlEscape($row['title']) ?>
    </h2>
    <div>
        <?php echo convertSqlDate($row['created_at']) ?>
    </div>
    <p>
        <?php echo $paraText ?>
    </p>
    
    <h3><?php echo countCommentsForPost($postId) ?> comments</h3>
    
    <?php foreach (getCommentsForPost($postId) as $comment): ?>
        <?php //horizontal rules for comment division ?>
        <hr>
        <div class="comment">
            <div class="comment-meta">
                Comment from
                <?php echo htmlEscape($comment['name']) ?>
                on
                <?php echo convertSqlDate($comment['created_at']) ?>
            </div>
            <div class="comment-body">
                <?php echo htmlEscape($comment['text']) ?>
            </div>
        </div>
    <?php endforeach ?>
    
</body>
</html>