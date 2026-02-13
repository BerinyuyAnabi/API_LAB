<?php
// Error checking 
error_reporting(E_ALL);
ini_set('display_errors', 1);

//JSON header 
header('Content-Type: application/json');

// connection to database 
include("../config.php");

// check for put method 
if($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    echo json_encode(["success" => false,
    "error" => "Only the put method is allowed!!"]);
    exit;
}
// Get the input data 
parse_str(file_get_contents("php://input"), $input);
$id = isset($input['id']) ? $input['id'] : null;
$name = isset($input['name']) ? $input['name'] : null;
$studentID = isset($input['student_id']) ? $input['student_id'] : null;
$email = isset($input['email']) ? $input['email'] : null;
$major = isset($input['major']) ? $input['major'] : null;

//Validate the input 
if(!$id ) {
    echo json_encode(["success" => false,
    "error" => "ID is required"]);
    exit;
}

if(!$name && !$studentID && !$email && !$major) {
    echo json_encode(["success" => false,
    "error" => "At least one field is required"]);
    exit;
}

// Build the query dynamically
$fields = [];
$types = "";
$values = [];

if ($name) { $fields[] = "name = ?"; $types .= "s"; $values[] = $name; }
if ($studentID) { $fields[] = "student_id = ?"; $types .= "s"; $values[] = $studentID; }
if ($email) { $fields[] = "email = ?"; $types .= "s"; $values[] = $email; }
if ($major) { $fields[] = "major = ?"; $types .= "s"; $values[] = $major; }

// Add id at the end for WHERE clause
$types .= "i";
$values[] = $id;

$sql = "UPDATE students SET " . implode(", ", $fields) . " WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$values);

// Execute and return result
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Student updated successfully"]);
    } else {
        echo json_encode(["success" => false, "error" => "No student found with that id or no changes made"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Failed to update student"]);
}

// Clean up
$stmt->close();
$conn->close();

?>