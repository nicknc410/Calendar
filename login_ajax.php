<?php
// login_ajax.php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $json_obj['username'];
$password = $json_obj['password'];


$mysqli= new mysqli('localhost', 'mod5','Kelly38538a!','module5');
if ($mysqli->connect_errno){
    printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

function findUser($use, $pw){
    $mysqli= new mysqli('localhost', 'mod5','Kelly38538a!','module5');
    $stmt = $mysqli->prepare("SELECT COUNT(*), username, passwords FROM accounts WHERE username=?");

    // Bind the parameter
    $stmt->bind_param('s', $use);
    $stmt->execute();

    // Bind the results
    $stmt->bind_result($cnt, $user_id, $pwd_hash);
    $stmt->fetch();

    // Compare the submitted password to the actual password hash

    if($cnt == 1 && password_verify($pw, $pwd_hash)){
        // Login succeeded!
        $_SESSION['user_id'] = $user_id;
        return true;
    } else{
        return false;
    }
}

if(findUser($username,$password)){
	session_start();
	$_SESSION['username'] = $username;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 

	echo json_encode(array(
		"success" => true
	));

	exit;
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}
?>