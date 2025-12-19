<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "discover_nepal-1";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo "ERROR";
    exit;
}

$name    = $_POST['name'] ?? '';
$email   = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo "ERROR";
    exit;
}

$stmt = $conn->prepare("INSERT INTO inquiry_contact (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    echo "SUCCESS"; // must be exactly SUCCESS
} else {
    echo "ERROR";
}

$stmt->close();
$conn->close();
?>
