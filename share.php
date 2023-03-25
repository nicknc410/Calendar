<?php

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
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);


$eventId=$json_obj['eventId'];
$user=$json_obj['user'];
$stmt = $mysqli->prepare("SELECT * from events where eventId=?");
$stmt->bind_param('s', $eventId);
$stmt->execute();
$res=$stmt->get_result();
$row=$res->fetch_assoc();

//check for user
$mysqliF= new mysqli('localhost', 'mod5','Kelly38538a!','module5');
$userFind=$mysqliF->prepare("SELECT COUNT(*) from accounts where username=?");
$userFind->bind_param('s',$user);
$userFind->execute();
$userFind->bind_result($cnt);
$userFind->fetch();
if ($cnt==0){
    echo json_encode(array(
        "success"=>false,
        "message"=>"Could not find user"
    ));
    exit;
}
session_start();
$owner= new mysqli('localhost', 'mod5','Kelly38538a!','module5');
$isOwner=$owner->prepare("select user,owner from events where eventId=?");
$isOwner->bind_param('s',$eventId);
$isOwner->execute();
$res2=$isOwner->get_result();
$own=$res2->fetch_assoc();
if ($own['owner']==false){
    echo json_encode(array(
        "success"=>false,
        "message"=>"You are not the owner."
    ));
    exit;
}
//make a copy of the event except add it to the other user
$mysqli2= new mysqli('localhost', 'mod5','Kelly38538a!','module5');
$stmt2=$mysqli2->prepare("insert into events (eventName, user, owner, startTime,eventDate,tag) values (?,?,false,?,?,?);");

$stmt2->bind_param('sssss',$row['eventName'],$user,$row['startTime'],$row['eventDate'],$row['tag']);
$stmt2->execute();

if(!$stmt2){
	echo json_encode(array(
		"success" => false,
		"message" => "Failed to share event"
	));
	exit;
}
$stmt->close();
$stmt2->close();
$userFind->close();
echo json_encode(array(
    "success"=>true
));
?>