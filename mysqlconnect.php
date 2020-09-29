#!/usr/bin/php
<?php

$mydb = new mysqli('25.6.86.226','root','12345','testdb');

if ($mydb->errno != 0)
{
	echo "failed to connect to database: ". $mydb->error . PHP_EOL;
	exit(0);
}else{
	echo "successfully connected to database".PHP_EOL;
}

$query = "select * from students;";

$response = $mydb->query($query);
if ($mydb->errno != 0)
{
	echo "failed to execute query:".PHP_EOL;
	echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
	exit(0);
}

if ($response->num_rows > 0){
	// output each row
	while($row = $response->fetch_assoc()){
		echo "id: ".$row["id"]." - Name: ".$row["name"].PHP_EOL;
	}
}else {
	echo "no results".PHP_EOL;
}


?>
