<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration for local and remote servers
$use_remote_db = true; // Set to false if using local database

if ($use_remote_db) {
    $servername = "sql12.freesqldatabase.com";
    $username = "sql12765093";
    $password = "cwLbYsARir";
    $dbname = "sql12765093";
    $port = 3306; // Default MySQL port
} else {
    $servername = "localhost";
    $username = "first_hope";
    $password = "firsthope@123";
    $dbname = "voter_registration";
    $port = 3306;
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Get filter parameters
$name = isset($_GET['name']) ? trim($_GET['name']) : '';
$organization = isset($_GET['organization']) ? trim($_GET['organization']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';

// Base SQL query
$sql = "SELECT name, organization, location, mobile FROM serach_detail WHERE 1=1";
$params = [];
$types = "";

// Add conditions dynamically
if (!empty($name)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%" . $name . "%";
    $types .= "s";
}
if (!empty($organization)) {
    $sql .= " AND organization LIKE ?";
    $params[] = "%" . $organization . "%";
    $types .= "s";
}
if (!empty($location)) {
    $sql .= " AND location LIKE ?";
    $params[] = "%" . $location . "%";
    $types .= "s";
}

// Prepare and execute query
$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return JSON response
echo json_encode($data);

// Close resources
$stmt->close();
$conn->close();
?>
