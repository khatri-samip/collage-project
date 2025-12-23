<?php
// --- Database connection ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "discover_nepal-1";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("ERROR: Could not connect. " . $conn->connect_error);
}

// --- Receive form data ---
$name        = $_POST['name'] ?? '';
$email       = $_POST['email'] ?? '';
$phone       = $_POST['phone'] ?? '';
$travelers   = $_POST['travelers'] ?? '';
$date        = $_POST['date'] ?? '';
$destination = $_POST['destination'] ?? '';
$info        = $_POST['info'] ?? '';

// --- Determine table based on package/destination ---
$table = '';
switch (strtolower($destination)) {
    case 'essential nepal':
        $table = 'essential_nepal';
        break;
    case 'himalayan adventure':
        $table = 'himalayan_adventure';
        break;
    case 'custom package':
    case 'custom inquiry':
    case 'custom':
        $table = 'custom_package';
        break;
    default:
        // Fallback table
        $table = 'custom_package';
        break;
}

// --- Create table if it doesn't exist ---
$createTableSQL = "CREATE TABLE IF NOT EXISTS `$table` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    travelers INT NOT NULL,
    travel_date DATE NULL,
    destination VARCHAR(255) NOT NULL,
    additional_info TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($createTableSQL);

// --- Insert form data ---
$stmt = $conn->prepare("INSERT INTO `$table` (name,email,phone,travelers,travel_date,destination,additional_info) VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("sssiiss", $name, $email, $phone, $travelers, $date, $destination, $info);

if ($stmt->execute()) {
    echo "SUCCESS";
} else {
    echo "ERROR: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
