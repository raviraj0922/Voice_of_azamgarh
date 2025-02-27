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
} else {
    $servername = "localhost";
    $username = "first_hope";
    $password = "firsthope@123";
    $dbname = "voter_registration";
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = isset($_GET['name']) ? $_GET['name'] : '';
$organization = isset($_GET['organization']) ? $_GET['organization'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';

$sql = "SELECT * FROM serach_detail WHERE 1=1";

if (!empty($name)) {
    $sql .= " AND name LIKE '%" . $conn->real_escape_string($name) . "%'";
}
if (!empty($organization)) {
    $sql .= " AND organization LIKE '%" . $conn->real_escape_string($organization) . "%'";
}
if (!empty($location)) {
    $sql .= " AND location LIKE '%" . $conn->real_escape_string($location) . "%'";
}

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$conn->close();
?>
