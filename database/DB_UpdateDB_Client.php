#!/usr/bin/php
<?php
require_once('logger.inc');

$logger = new LoggerClient(__FILE__);
set_error_handler(array($logger, 'onError'));

echo basename(__FILE__)." BEGIN".PHP_EOL;
ini_set("allow_url_fopen", 1);

$hostname = 'localhost';
$user = 'testuser';
$password = '12345';
$db_name = 'it490';
$table_name = 'users';

$logger->logg("Creating connection");
// Create connection
$conn = new mysqli($hostname,$user,$password, $db_name);
// Check connection.
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM users"; 

$result = $conn->query($query); // Get response from using query
$logger->logg("Looping through users database");

while ($row = $result->fetch_assoc()){// Turn response into array
    echo 'Updating: '.$row['username'].' using: '.$row['bnet'].PHP_EOL;

    $client = new rabbitMQClient('testRabbitMQ.ini','API_Client');
      
    $request['bnet'] = $row['bnet'];
    $request['platform'] = 'pc';
    $request['region'] = 'us';
    print_r($request);
    $response = $client->send_request($request);

    // Skip if account is private
    if ($response['private']){
        echo $row['bnet']." is private, skipping: ".$row['username']."\n".PHP_EOL;
        continue;
    }
    // Skip if "rating" doesn't exist
    if (!$response['rating']){
        echo "Bad response from DMZ, skipping: ".$row['username']."\n".PHP_EOL;
        continue;
    }

    $sql = "UPDATE ".$table_name." SET skillRating='".$response['rating']."' WHERE bnet='".$row['bnet']."'";
    echo "Query: ".$sql.PHP_EOL;

    if ($conn->query($sql)) {
        echo "Record updated successfully";
     } else {
        echo "Error updating record: " . mysqli_error($conn);
     }
    $result2 = $conn->query($sql); // Run query that inserts data

    echo PHP_EOL;
}    

echo PHP_EOL;
$conn->close();
$logger->logg("Connection closed");

?>
