<?php
session_start();

// Include database connection
require_once '../../config/database.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/users.php');
    exit;
}

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Admin only.";
    header('Location: ../../public/index.php');
    exit;
}

// Sanitize and validate inputs
$firstname = trim($_POST['firstname'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = trim($_POST['role'] ?? '');

// Initialize errors array
$errors = [];

// Validate first name
if (empty($firstname)) {
    $errors[] = "First name is required";
} elseif (strlen($firstname) > 50) {
    $errors[] = "First name must be 50 characters or less";
}

// Validate last name
if (empty($lastname)) {
    $errors[] = "Last name is required";
} elseif (strlen($lastname) > 50) {
    $errors[] = "Last name must be 50 characters or less";
}

// Validate email
if (empty($email)) {
    $errors[] = "Email is required";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
} elseif (strlen($email) > 100) {
    $errors[] = "Email must be 100 characters or less";
}

// Validate password with regex
if (empty($password)) {
    $errors[] = "Password is required";
} elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
    $errors[] = "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, and one number";
}

// Validate role
if (empty($role) || !in_array($role, ['Admin', 'Member'])) {
    $errors[] = "Valid role is required";
}

// Check if email already exists
if (empty($errors)) {
    try {
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->execute([$email]);
        if ($checkStmt->fetch()) {
            $errors[] = "Email already exists in the system";
        }
    } catch (PDOException $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }
}

// If there are errors, store them in session and redirect back
if (!empty($errors)) {
    $_SESSION['user_errors'] = $errors;
    $_SESSION['user_form_data'] = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'role' => $role
    ];
    header('Location: ../../public/add_user.php');
    exit;
}

// Insert new user into database
try {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
    
    // Execute the statement
    if ($stmt->execute([$firstname, $lastname, $email, $hashedPassword, $role])) {
        $_SESSION['success'] = "User '$firstname $lastname' added successfully!";
        header('Location: ../../public/users.php');
        exit;
    } else {
        throw new Exception("Failed to execute query");
    }
    
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header('Location: ../../public/add_user.php');
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header('Location: ../../public/add_user.php');
    exit;
}