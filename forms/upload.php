<?php
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

if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = array('csv');
    if (in_array($fileExtension, $allowedfileExtensions)) {
        // Read the CSV file
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
            fgetcsv($handle); // Skip the first row if it contains column headers
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Assuming CSV columns are in the order: name, organization, location, mobile
                $name = $conn->real_escape_string($data[0]);
                $organization = $conn->real_escape_string($data[1]);
                $location = $conn->real_escape_string($data[2]);
                $mobile = $conn->real_escape_string($data[3]);

                $sql = "INSERT INTO serach_detail (name, organization, location, mobile) VALUES ('$name', '$organization', '$location', '$mobile')";
                if (!$conn->query($sql)) {
                    echo "Error: " . $conn->error;
                }
            }
            fclose($handle);
            echo "File successfully uploaded and data inserted.";
        } else {
            echo "Error opening the file.";
        }
    } else {
        echo "Upload failed. Allowed file types: " . implode(',', $allowedfileExtensions);
    }
} else {
    echo "There was an error uploading the file.";
}

$conn->close();
?>
