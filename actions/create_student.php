<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Setting response type to JSON
header('Content-Type:application/json');

//including database connection
include("../config.php");

// Checking that the request is a post 
if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    echo json_encode(["success" => false,
    "error" => "Only the post method is possible here!!"]);
    exit;
}
// Get the post data 
$name = isset($_POST['name']) ? $_POST['name']:null;
$studentID = isset($_POST['student_id']) ? $_POST['student_id']:null;
$email = isset($_POST['email']) ? $_POST['email']:null;
$major = isset($_POST['major']) ? $_POST['major']:null;


// validate the inputs 
if(!$name || !$studentID || !$email || !$major){
    echo json_encode(['success' => false,
    "error" => "name, student_id, email and major are required to create this student!!"] );
    exit;
}

// Insert into database 
$stmt = $conn->prepare("INSERT INTO students (name, student_id, email, major) values (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $studentID, $email, $major);

// Exeute the statement and return result 

if($stmt->execute()){
    echo json_encode(['success' => true, 
    "message" => ["id" => $stmt->insert_id]]);
}else{
    echo json_encode(["success" => false,
    "error" => "Failed to create student!!"]);
}

// clean up
$stmt->close();
$conn->close();

?>


