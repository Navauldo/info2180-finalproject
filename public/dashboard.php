<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
require_once '../src/views/header.php';
?>

<div class="content">

    <!-- Alerts -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']); ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error']); ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Dashboard Card -->
    <div class="card">
        <div class="card-body">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Dashboard</h4>
                <a href="new_contact.php" class="btn btn-indigo btn-sm">
                    + Add Contact
                </a>
            </div>

            <!-- Filters -->
            <div class="filter-links mb-3">
                <strong>Filter By:</strong>
                <a href="#" onclick="loadContacts('all')">All</a>
                <a href="#" onclick="loadContacts('Sales Lead')">Sales Leads</a>
                <a href="#" onclick="loadContacts('Support')">Support</a>
                <a href="#" onclick="loadContacts('assigned')">Assigned to me</a>
            </div>

            <!-- Contacts -->
            <div id="contacts">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => loadContacts('all'));

function loadContacts(type) {
    const el = document.getElementById('contacts');
    el.innerHTML = `<div class="text-center py-4">
        <div class="spinner-border text-primary"></div>
    </div>`;

    fetch(`load_contacts.php?type=${type}`)
        .then(res => res.text())
        .then(html => el.innerHTML = html)
        .catch(() => el.innerHTML =
            `<div class="alert alert-danger">Failed to load contacts</div>`
        );
}
</script>

<?php require_once '../src/views/footer.php'; ?>
