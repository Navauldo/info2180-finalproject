<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../../public/dashboard.php');
  exit;
}

$data = [
  'title' => trim($_POST['title']),
  'firstname' => trim($_POST['firstname']),
  'lastname' => trim($_POST['lastname']),
  'email' => trim($_POST['email']),
  'telephone' => trim($_POST['telephone']),
  'company' => trim($_POST['company']),
  'type' => $_POST['type'],
  'assigned_to' => $_POST['assigned_to'],
  'created_by' => $_SESSION['user_id']
];

$stmt = $conn->prepare("
  INSERT INTO contacts 
  (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
  $data['title'],
  $data['firstname'],
  $data['lastname'],
  $data['email'],
  $data['telephone'],
  $data['company'],
  $data['type'],
  $data['assigned_to'],
  $data['created_by']
]);

header('Location: ../../public/dashboard.php');
exit;
