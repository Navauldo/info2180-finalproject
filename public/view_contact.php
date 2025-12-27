<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once '../config/database.php';
require_once '../src/views/header.php';

$contactId = $_GET['id'] ?? null;
if (!$contactId) {
    echo "<p>Contact not found.</p>";
    require_once '../src/views/footer.php';
    exit;
}

// Fetch contact with creator and assigned user
$stmt = $conn->prepare("
    SELECT c.*, 
           u.firstname AS creator_first, u.lastname AS creator_last,
           a.firstname AS assigned_first, a.lastname AS assigned_last
    FROM contacts c
    JOIN users u ON c.created_by = u.id
    LEFT JOIN users a ON c.assigned_to = a.id
    WHERE c.id = ?
");
$stmt->execute([$contactId]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contact) {
    echo "<p>Contact not found.</p>";
    require_once '../src/views/footer.php';
    exit;
}
?>

<div class="content">

    <!-- Notification div -->
    <div id="notification" style="
        position: fixed;
        top: 20px;
        right: 20px;
        background: #4caf50;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        display: none;
        z-index: 9999;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    "></div>

    <div class="card">
        <div class="card-body">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h4 class="fw-semibold mb-1">
                        <?= htmlspecialchars($contact['title'].' '.$contact['firstname'].' '.$contact['lastname']) ?>
                    </h4>
                    <div class="text-muted small">
                        Created on <?= date('F j, Y', strtotime($contact['created_at'])) ?>
                        by <?= htmlspecialchars($contact['creator_first'].' '.$contact['creator_last']) ?>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-success btn-sm px-3"
                            onclick="assignToMe(<?= $contactId ?>)">
                        Assign to me
                    </button>

                    <?php if ($contact['type'] === 'Sales Lead'): ?>
                        <button class="btn btn-warning btn-sm px-3"
                                onclick="switchType(<?= $contactId ?>,'Support')">
                            Switch to Support
                        </button>
                    <?php else: ?>
                        <button class="btn btn-warning btn-sm px-3"
                                onclick="switchType(<?= $contactId ?>,'Sales Lead')">
                            Switch to Sales Lead
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CONTACT DETAILS -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="text-muted small">Email</div>
                    <div><?= htmlspecialchars($contact['email']) ?></div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="text-muted small">Telephone</div>
                    <div><?= htmlspecialchars($contact['telephone']) ?></div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="text-muted small">Company</div>
                    <div><?= htmlspecialchars($contact['company']) ?></div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="text-muted small">Assigned To</div>
                    <div>
                        <?= $contact['assigned_to'] ? 
                            htmlspecialchars($contact['assigned_first'].' '.$contact['assigned_last']) : 'Unassigned' ?>
                    </div>
                </div>
            </div>

            <!-- NOTES -->
            <div class="mb-4">
                <h6 class="fw-semibold mb-3">Notes</h6>
                <div id="notes" data-contact-id="<?= $contactId ?>"></div>
            </div>

            <!-- ADD NOTE -->
            <div class="bg-light rounded p-3">
                <div class="text-muted small mb-2">
                    Add a note about <?= htmlspecialchars($contact['firstname']) ?>
                </div>

                <textarea
                    id="noteText"
                    class="form-control mb-3"
                    rows="4"
                    placeholder="Enter details here">
                </textarea>

                <div class="text-end">
                    <button class="btn btn-indigo btn-sm px-4"
                            onclick="addNote(<?= $contactId ?>)">
                        Add Note
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
// Show notifications
function showNotification(message, color = '#4caf50') {
    const notif = document.getElementById('notification');
    notif.textContent = message;
    notif.style.background = color;
    notif.style.display = 'block';
    setTimeout(() => { notif.style.display = 'none'; }, 3000);
}

// ASSIGN CONTACT
function assignToMe(contactId) {
    fetch('assign_contact.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `contact_id=${contactId}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification('Contact assigned to you successfully!');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Failed to assign contact.', '#f44336');
        }
    })
    .catch(() => showNotification('Error assigning contact.', '#f44336'));
}

// SWITCH CONTACT TYPE
function switchType(contactId, type) {
    fetch('switch_type.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `contact_id=${contactId}&type=${encodeURIComponent(type)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification(`Contact type switched to "${type}" successfully!`);
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Failed to switch type.', '#f44336');
        }
    })
    .catch(() => showNotification('Error switching type.', '#f44336'));
}

// NOTES FUNCTIONS
function loadNotes(contactId) {
    fetch(`load_notes.php?contact_id=${contactId}`)
        .then(res => res.text())
        .then(data => { document.getElementById('notes').innerHTML = data; })
        .catch(error => console.error('Error loading notes:', error));
}

function addNote(contactId) {
    const text = document.getElementById('noteText').value;
    if (!text.trim()) { showNotification("Please enter a note.", '#f44336'); return; }

    fetch('add_note.php', {
        method: 'POST',
        headers: { 'Content-Type':'application/x-www-form-urlencoded' },
        body: `contact_id=${contactId}&comment=${encodeURIComponent(text)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('noteText').value = '';
            loadNotes(contactId);
        } else showNotification(data.message, '#f44336');
    })
    .catch(() => showNotification('Error adding note.', '#f44336'));
}

// AUTO-LOAD NOTES
document.addEventListener('DOMContentLoaded', () => {
    const notesDiv = document.getElementById('notes');
    if (notesDiv && notesDiv.dataset.contactId) {
        loadNotes(notesDiv.dataset.contactId);
    }
});
</script>

<?php require_once '../src/views/footer.php'; ?>
