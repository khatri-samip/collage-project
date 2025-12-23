<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "discover_nepal-1";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Test if table exists by running a simple SELECT query
$result = $conn->query("SELECT * FROM inquiry_contact LIMIT 1");

if ($result) {
    echo "Table 'inquiry_contact' exists and is accessible!";
} else {
    echo "Table 'inquiry_contact' not found or not accessible.";
}

$conn->close();
?>
