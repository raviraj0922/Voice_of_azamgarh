<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "sql12.freesqldatabase.com";
$username = "sql12765093";
$password = "cwLbYsARir";
$dbname = "sql12765093";
$port = 3306; // Default MySQL port

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Set parameters for pagination
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

// Prepare SQL statement with LIMIT and OFFSET
$sql = "SELECT name, organization, location, mobile FROM serach_detail LIMIT ?, ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(['error' => "SQL prepare failed: " . $conn->error]));
}

$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$stmt->bind_result($name, $organization, $location, $mobile);

// Fetch results into an array
$contacts = [];
while ($stmt->fetch()) {
    $contacts[] = [
        'name' => $name,
        'organization' => $organization,
        'location' => $location,
        'mobile' => $mobile
    ];
}

// Total count for pagination
$totalQuery = "SELECT COUNT(*) as total FROM serach_detail";
$totalResult = $conn->query($totalQuery);

if (!$totalResult) {
    die(json_encode(['error' => "SQL total query failed: " . $conn->error]));
}

$totalRow = $totalResult->fetch_assoc();
$totalContacts = $totalRow['total'] ?? 0;

// Close resources
$stmt->close();
$conn->close();

// Return JSON response
echo json_encode([
    'contacts' => $contacts,
    'total' => $totalContacts
]);
?>
