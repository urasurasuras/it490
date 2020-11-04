<?php
	session_start();
	
	if(is_null($_SESSION["user"])) {
		header("Location: DB_Server.php");
	}

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$hostname = 'localhost';
		$user = 'testuser';
		$password = '12345';
		$db_name = 'it490';
		$table_name = 'users';

		// Create connection
		$conn = new mysqli($hostname,$user,$password, $db_name);
		// Check connection.
		if ($conn->connect_error) {
		  die("Connection failed: " . $conn->connect_error);
		}
		$errors = "";
		$title = $_POST["title"];
		$post_desc = $POST["desc"];

		if(empty($title) or empty($post_desc)) {
			$errors = "Invalid inputs!";
		} else{
			$query = mysw
		}
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>Create Forum Post</title>
	</head>

	<body>
		<h1>Create a New Post</h1>

		<p style="color: red;"><?php echo $errors; ?></p>
		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			<input type="text" name="title" placeholder="Title"> <br><br>
			<textarea name="desc" rows="25" cols="50" placeholder="Post Description"></textarea> <br><br>
			<input type="submit">
		</form>
	</body>
</html>




