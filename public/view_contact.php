<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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

$stmt = $conn->prepare("
    SELECT c.*, u.firstname AS creator_first, u.lastname AS creator_last
    FROM contacts c
    JOIN users u ON c.created_by = u.id
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

    <!-- MAIN CARD -->
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
                    <div><?= htmlspecialchars($contact['assigned_to']) ?></div>
                </div>
            </div>

            <!-- NOTES LIST -->
            <div class="mb-4">
                <h6 class="fw-semibold mb-3">Notes</h6>
                <div id="notes"></div>
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

<script src="../assets/js/app.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadNotes(<?= $contactId ?>);
    });
</script>

<?php require_once '../src/views/footer.php'; ?>
