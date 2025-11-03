<?php
// Connect to database
include("db_connect.php");

// Collect form data
$name        = $_POST['name'] ?? '';
$email       = $_POST['email'] ?? '';
$phone       = $_POST['phone'] ?? '';
$travelers   = $_POST['travelers'] ?? '';
$date        = $_POST['date'] ?? NULL;
$destination = $_POST['destination'] ?? '';
$info        = $_POST['info'] ?? '';

// Clean input (basic sanitization)
$name        = mysqli_real_escape_string($conn, $name);
$email       = mysqli_real_escape_string($conn, $email);
$phone       = mysqli_real_escape_string($conn, $phone);
$travelers   = (int)$travelers;
$destination = mysqli_real_escape_string($conn, $destination);
$info        = mysqli_real_escape_string($conn, $info);

// --- Determine which table to insert into ---
$table = "";

switch (strtolower($destination)) {
  case "essential nepal":
    $table = "essential_nepal";
    break;
  case "himalayan adventure":
    $table = "himalayan_adventure";
    break;
  case "complete nepal experience":
    $table = "complete_nepal_experience";
    break;
  default:
    // Anything else (including dropdown selections)
    $table = "custom_inquiry";
    break;
}

// --- Insert into database ---
$sql = "INSERT INTO $table (name, email, phone, travelers, travel_date, destination, info)
        VALUES ('$name', '$email', '$phone', $travelers, " .
        ($date ? "'$date'" : "NULL") . ", '$destination', '$info')";

if ($conn->query($sql) === TRUE) {
  echo "
    <div style='
      display:flex;
      flex-direction:column;
      justify-content:center;
      align-items:center;
      height:100vh;
      font-family:Poppins, sans-serif;
      background:#f9f9f9;
      text-align:center;
    '>
      <h2 style='color:#111;'>Thank you, $name!</h2>
      <p>Your inquiry for <strong>$destination</strong> has been received.</p>
      <a href='index.html' style='
        display:inline-block;
        margin-top:20px;
        background:#000;
        color:#fff;
        padding:10px 20px;
        border-radius:6px;
        text-decoration:none;
      '>Back to Home</a>
    </div>
  ";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
