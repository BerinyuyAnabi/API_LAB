<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// JSON header
header('Content-Type: application/json');

// Connection to database
include('../config.php');

// Check for GET method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["success" => false,
    "error" => "Only GET method is allowed"]);
    exit;
}

// Query all students
$result = $conn->query("SELECT * FROM students");

// Check results and build response
if ($result->num_rows > 0) {
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    echo json_encode(["success" => true, "data" => $students]);
} else {
    echo json_encode(["success" => true, "data" => []]);
}

// Clean up
$conn->close();

?>
