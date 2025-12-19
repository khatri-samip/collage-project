<?php
// --- Database connection ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "discover_nepal-1";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// --- Receive form data ---
$name        = $_POST['name'] ?? '';
$email       = $_POST['email'] ?? '';
$phone       = $_POST['phone'] ?? '';
$travelers   = $_POST['travelers'] ?? '';
$date        = $_POST['date'] ?? '';
$destination = $_POST['destination'] ?? '';
$info        = $_POST['info'] ?? '';

// --- Map package name → table name ---
$package_tables = [
    "Essential Nepal"             => "essential_nepal",
    "Himalayan Adventure"         => "himalayan_adventure",
    "Complete Nepal Experience"   => "complete_nepal_experience",
    "Custom Package"              => "custom_inquiry",
    "Custom Inquiry"              => "custom_inquiry"
];

// Validate + pick table (SAFE)
if (array_key_exists($destination, $package_tables)) {
    $table = $package_tables[$destination];
} else {
    $table = "general_inquiries"; // fallback table
}

// --- SAFE insert query ---
$sql = "INSERT INTO `$table` 
        (name, email, phone, travelers, travel_date, destination, additional_info)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param("sssisss",
    $name,
    $email,
    $phone,
    $travelers,
    $date,
    $destination,
    $info
);

// Output result for AJAX
if ($stmt->execute()) {
    echo "SUCCESS";
} else {
    echo "ERROR";
}

$stmt->close();
$conn->close();
?>
