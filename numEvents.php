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
$stmt = $mysqli->prepare("select eventName, startTime from events where user=? AND eventDate=STR_TO_DATE(?,'%m/%d/%Y')");
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
		"message" => "Failed to count events"
	));
	exit;
}
$stmt->close();
$numEvents="";
if (count($events)==0){
    $numEvents="white";
}
else if(count($events)>=1 && count($events)<=3){
    $numEvents="#90EE90";
}
else if(count($events)>=4 &&count($events)<=6){
    $numEvents="yellow";
}
else{
    $numEvents="red";
}
echo json_encode(array(
    "success"=>true,
    "numEvents"=>$numEvents
));
?>