<?php
// login_ajax.php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$eventId=$json_obj['eventId'];
$mysqli= new mysqli('localhost', 'mod5','Kelly38538a!','module5');
if ($mysqli->connect_errno){
    printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
session_start();
$stmt = $mysqli->prepare("delete from events WHERE eventId=? AND user=?");
$stmt->bind_param('ss', $eventId, $_SESSION['username']);
$stmt->execute();
if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "Failed to delete event"
	));
	exit;
}
$stmt->close();
echo json_encode(array(
    "success"=>true
));
?>
