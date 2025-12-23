<?php
session_start();
require_once '../config/database.php';

$type = $_GET['type'] ?? 'all';
$userId = $_SESSION['user_id'];

$sql = "SELECT * FROM contacts";
$params = [];

if ($type === 'Sales Lead' || $type === 'Support') {
    $sql .= " WHERE type = ?";
    $params[] = $type;
} elseif ($type === 'assigned') {
    $sql .= " WHERE assigned_to = ?";
    $params[] = $userId;
}

$sql .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
?>

<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Type</th>
            <th class="text-end"></th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>

            <tr>
                <td class="fw-semibold">
                    <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>
                </td>

                <td class="text-muted">
                    <?= htmlspecialchars($row['email']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['company']) ?>
                </td>

                <td>
                    <?php if ($row['type'] === 'Sales Lead'): ?>
                        <span class="badge bg-warning text-dark px-3 py-1">
                            Sales Lead
                        </span>
                    <?php else: ?>
                        <span class="badge bg-indigo px-3 py-1">
                            Support
                        </span>
                    <?php endif; ?>
                </td>

                <td class="text-end">
                    <a href="view_contact.php?id=<?= $row['id'] ?>"
                       class="text-primary fw-semibold text-decoration-none">
                        View
                    </a>
                </td>
            </tr>

        <?php endwhile; ?>
    </tbody>
</table>

