<?php

// error reporting 
error_reporting(E_ALL);
ini_set('display_errors', 1);

//JSON header 
header('Content-Type: application/json');

// include database connection
include ('../config.php');

// Check for delete 
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(["success"=> false,
    "error" => "Only delete method is allowed"]);
}
// Get Student ID 
parse_str(file_get_contents("php://input"), $input);
$id = isset($input['id']) ? $input['id'] : null;

// Validate input 
if(!$id){
    echo json_encode(["success" => false,
    "error" => "id is needed to delete a student"]);
    exit();
};
// Delete from database 
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
// Execute and return result 

if($stmt->execute()){
    echo json_encode(["success" => true,
    "message" => "Student deleted successfully"]);
} else {
    echo json_encode(["success" => false,
    "error" => "Failed to delete student"]);
}
//Clean up 
$stmt->close();
$conn->close();

?>