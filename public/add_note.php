<?php
session_start();
require_once '../config/database.php';

$contactId = $_POST['contact_id'];
$comment = trim($_POST['comment']);

if ($comment === '') exit;

$stmt = $conn->prepare("
  INSERT INTO notes (contact_id, comment, created_by)
  VALUES (?, ?, ?)
");
$stmt->execute([
  $contactId,
  htmlspecialchars($comment),
  $_SESSION['user_id']
]);

$conn->prepare("
  UPDATE contacts SET updated_at = NOW() WHERE id = ?
")->execute([$contactId]);
