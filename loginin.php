<?php
session_start();
header("Content-Type: application/json");

/* ---------- DATABASE CONNECTION ---------- */
$conn = new mysqli("localhost", "root", "", "discover_nepal-1");
if ($conn->connect_error) {
    echo json_encode(["success"=>false, "message"=>"Database connection failed"]);
    exit;
}

$action = $_POST['action'] ?? '';

/* ---------- SIGNUP ---------- */
if ($action === "signup") {

    $name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        echo json_encode(["success"=>false, "message"=>"All fields are required"]);
        exit;
    }

    // Check if email exists
    $check = $conn->prepare("SELECT id FROM signup_users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(["success"=>false, "message"=>"Email already registered"]);
        exit;
    }

    // Insert user
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare(
        "INSERT INTO signup_users (fullname, email, password) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $name, $email, $hashed);
    $stmt->execute();

    // Auto-login after signup
    $_SESSION['user_email'] = $email;

    echo json_encode([
        "success" => true,
        "message" => "Signup successful",
        "name" => $name,
        "email" => $email
    ]);
    exit;
}



/* ---------- LOGIN ---------- */
if ($action === "login") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        echo json_encode(["success"=>false, "message"=>"Email and password required"]);
        exit;
    }

    $stmt = $conn->prepare(
        "SELECT fullname, password FROM signup_users WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        logLogin($conn, $email, "failed");
        echo json_encode(["success"=>false, "message"=>"Account not found"]);
        exit;
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        logLogin($conn, $email, "failed");
        echo json_encode(["success"=>false, "message"=>"Incorrect password"]);
        exit;
    }

    $_SESSION['user_email'] = $email;
    logLogin($conn, $email, "success");

    // Send back name and email for JS sessionStorage
    echo json_encode([
        "success" => true,
        "message" => "Login successful",
        "name" => $user['fullname'],
        "email" => $email
    ]);
    exit;
}

/* ---------- LOGIN LOGGER FUNCTION ---------- */
function logLogin($conn, $email, $status) {
    $log = $conn->prepare(
        "INSERT INTO login_logs (user_email, status) VALUES (?, ?)"
    );
    $log->bind_param("ss", $email, $status);
    $log->execute();
}
