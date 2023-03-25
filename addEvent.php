<?php
header("Content-Type: application/json");
//This will store the data into an associative array
$mysqli= new mysqli('localhost', 'mod5','Kelly38538a!','module5');
if ($mysqli->connect_errno){
     printf("Connection Failed: %s\n", $mysqli->connect_error);
 	exit;
 }
session_start();
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$start=$json_obj['startTime'];
$date=$json_obj['date'];
$eventName=$json_obj['eventName'];
$owner=true;
$tag=$json_obj['tag'];
$stmt = $mysqli->prepare("insert into events (eventName, user, owner, startTime, eventDate,tag) values (?, ?,?,STR_TO_DATE(?,'%h:%i%p'),STR_TO_DATE(?, '%m/%d/%Y'),?)");
$stmt->bind_param('ssssss', $eventName, $_SESSION['username'],$owner,$start,$date,$tag);
$stmt->execute();
if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "Failed to add event"
	));
	exit;
}
$stmt->close();
echo json_encode(array(
    "success"=>true
));
?>