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
		<title></title>
	</head>

	<body>
		<h1>Create a New Post</h1>
		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			<input type="text" name="title" placeholder="Title"> <br><br>
			<textarea rows="25" cols="50" placeholder="Post Description"></textarea> <br><br>
			<input type="submit">
		</form>
	</body>
</html>




