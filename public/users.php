<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Only admins can view the users list
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: dashboard.php');
    exit;
}

require_once '../src/views/header.php';
require_once '../config/database.php';

// Display success message if user was added
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' 
         . htmlspecialchars($_SESSION['success']) . 
         '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['success']);
}

// Display error message if any
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' 
         . htmlspecialchars($_SESSION['error']) . 
         '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['error']);
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Users</h3>

        <!-- Add User Button - Changed to link to add_user.php -->
        <a href="add_user.php" class="btn btn-indigo btn-sm">
            <i class="bi bi-plus-circle me-2"></i>Add User
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        // Select ALL users including admin
                        $stmt = $conn->prepare("SELECT firstname, lastname, email, role, created_at FROM users ORDER BY created_at DESC");
                        $stmt->execute();

                        if ($stmt->rowCount() === 0) {
                            echo '<tr><td colspan="4" class="text-center">No users found</td></tr>';
                        } else {
                            while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $roleClass = $user['role'] === 'Admin' ? 'bg-primary' : 'bg-secondary';
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($user['firstname']) . " " . htmlspecialchars($user['lastname']) . "</td>";
                                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                                echo "<td><span class='badge {$roleClass}'>" . htmlspecialchars($user['role']) . "</span></td>";
                                echo "<td>" . date('F j, Y', strtotime($user['created_at'])) . "</td>";
                                echo "</tr>";
                            }
                        }
                    } catch (PDOException $e) {
                        echo '<tr><td colspan="4" class="text-center text-danger">Error loading users</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../src/views/footer.php'; ?>