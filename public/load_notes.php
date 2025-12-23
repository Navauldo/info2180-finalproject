<?php
require_once '../config/database.php';

$contactId = $_GET['contact_id'];  // Get contact ID from the URL

// Prepare SQL query to fetch notes for the contact
$stmt = $conn->prepare("
  SELECT n.*, u.firstname, u.lastname
  FROM notes n
  JOIN users u ON n.created_by = u.id
  WHERE n.contact_id = ?
  ORDER BY n.created_at DESC
");
$stmt->execute([$contactId]);

// Output each note as HTML
while ($note = $stmt->fetch(PDO::FETCH_ASSOC)) {
  echo "
  <div class='card mb-2'>
    <div class='card-body'>
      <strong>{$note['firstname']} {$note['lastname']}</strong>
      <p>{$note['comment']}</p>
      <small class='text-muted'>" . date('F j, Y g:i a', strtotime($note['created_at'])) . "</small>
    </div>
  </div>
  ";
}
?>

