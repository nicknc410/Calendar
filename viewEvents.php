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

$date=$json_obj['date'];
$owner=true;
$user=$_SESSION['username'];
$stmt = $mysqli->prepare("select eventName, startTime,owner, eventId,tag from events where user=? AND eventDate=STR_TO_DATE(?,'%m/%d/%Y') ORDER BY startTime asc");
$stmt->bind_param('ss', $_SESSION['username'],$date);
$stmt->execute();
$res=$stmt->get_result();
$events=array();
while ($row1=$res->fetch_assoc()){
    array_push($events,$row1);
}
if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "Failed to add event"
	));
	exit;
}
$stmt->close();
echo json_encode(array(
    "success"=>true,
    "events"=>$events
));
?>