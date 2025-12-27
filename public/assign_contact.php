<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$contactId = $_POST['contact_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$contactId) {
    http_response_code(400);
    exit;
}

$stmt = $conn->prepare("
    UPDATE contacts
    SET assigned_to = ?, updated_at = NOW()
    WHERE id = ?
");
$stmt->execute([$userId, $contactId]);

echo json_encode(['success' => true]);
