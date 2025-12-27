<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$contactId = $_POST['contact_id'] ?? null;
$type = $_POST['type'] ?? null;

if (!$contactId || !$type) {
    http_response_code(400);
    exit;
}

$stmt = $conn->prepare("
    UPDATE contacts
    SET type = ?, updated_at = NOW()
    WHERE id = ?
");
$stmt->execute([$type, $contactId]);

echo json_encode(['success' => true]);
