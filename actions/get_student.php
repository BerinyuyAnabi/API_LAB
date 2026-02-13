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

// Get the id from the URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Validate
if (!$id) {
    echo json_encode(["success" => false,
    "error" => "id is required"]);
    exit;
}

// Query the database with prepared statement
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Return the result
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    echo json_encode(["success" => true, "data" => $student]);
} else {
    echo json_encode(["success" => false, "error" => "No student found with that id"]);
}

// Clean up
$stmt->close();
$conn->close();

?>
