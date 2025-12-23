<?php
session_start();

// FORCE error visibility while debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ CORRECT absolute path
require_once __DIR__ . '/../../config/database.php';

// SAFETY CHECK (temporary but useful)
if (!isset($conn)) {
    die("Database connection variable \$conn is not set");
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    header("Location: ../../public/dashboard.php");
    exit;
} else {
    header("Location: ../../public/login.php");
    exit;
}
