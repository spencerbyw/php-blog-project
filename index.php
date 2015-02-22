<?php 

require_once 'lib/common.php';

//Connect to the database, run a query, handle errors
$pdo = getPDO();    //Creates a new PDO object
$stmt = $pdo->query(
    'SELECT
        id, title, created_at, body
    FROM
        post
    ORDER BY
        created_at DESC'
);
if ($stmt === false) {
    throw new Exception('There was a problem running this query');
}
$notFound = isset($_GET['not-found']);

?> 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;utf-8">
	<title>Blogwater</title>
</head>
<body>
	<?php require 'templates/title.php' ?>
	
	<?php if ($notFound): ?>
	    <div style="border: 1px solid #ff6666; padding: 6px;">
	        Error: cannot find the requested blog post
	    </div>
    <?php endif ?>

	<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
		<h2>
		    <?php echo htmlEscape($row['title']) ?>
		</h2>
		<div>
		    <?php echo convertSqlDate($row['created_at']) ?>
           (<?php echo countCommentsForPost($row['id']) ?> comments)
        </div>
		<p>
		    <?php echo htmlEscape($row['body']) ?>
        </p>
		<p>
			<a href="view-post.php?post_id=<?php echo $row['id'] ?>">Read more.</a>
		</p>
	<?php endwhile ?>

	<!--
	<h2>Article 1 title</h2>
	<div>dd Mon YYYY</div>
	<p>A paragraph summarizing article 1.</p>
	<p>
		<a href="#">Read more.</a>
	</p>

	<h2>Article 2 title</h2>
	<div>dd Mon YYYY</div>
	<p>A paragraph summarizing article 2.</p>
	<p>
		<a href="#">Read more.</a>
	</p>
	-->
</body>
</html>