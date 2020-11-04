
<?php
	session_start();
	
	if(is_null($_SESSION["user"])) {
		header("Location: DB_Server.php");
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>Overwatch Forum</title>
	</head>

	<body>
		<p>Welcome <b><?php echo $_SESSION["user"] ?></b>!</p>
		<a href="create_post.php">Creat a New Post</a>
	</body>
</html>
