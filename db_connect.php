<?php
$servername = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default (no password)
$database = "Discover_Nepal_Inquiry";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
