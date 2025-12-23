<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get form data from session (for repopulating after errors)
$formData = isset($_SESSION['contact_form_data']) ? $_SESSION['contact_form_data'] : [];
$errors = isset($_SESSION['contact_errors']) ? $_SESSION['contact_errors'] : [];

// Clear the session data
unset($_SESSION['contact_form_data']);
unset($_SESSION['contact_errors']);

require_once '../src/views/header.php';
require_once '../config/database.php';

// Fetch users for Assigned To dropdown
$users = [];
try {
    $stmt = $conn->query("SELECT id, firstname, lastname FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error loading users: " . $e->getMessage();
}
?>

<div class="container mt-4">
    <h3>New Contact</h3>
    
    <!-- Display success/error messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="../src/controllers/ContactController.php" class="bg-white p-4 rounded shadow-sm">
        <!-- Title -->
        <div class="mb-3">
            <label class="form-label">Title</label>
            <select name="title" class="form-select" required>
                <option value="">Select Title</option>
                <option value="Mr" <?php echo (isset($formData['title']) && $formData['title'] == 'Mr') ? 'selected' : ''; ?>>Mr</option>
                <option value="Ms" <?php echo (isset($formData['title']) && $formData['title'] == 'Ms') ? 'selected' : ''; ?>>Ms</option>
                <option value="Mrs" <?php echo (isset($formData['title']) && $formData['title'] == 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
                <option value="Dr" <?php echo (isset($formData['title']) && $formData['title'] == 'Dr') ? 'selected' : ''; ?>>Dr</option>
                <option value="Prof" <?php echo (isset($formData['title']) && $formData['title'] == 'Prof') ? 'selected' : ''; ?>>Prof</option>
            </select>
        </div>

        <!-- Names -->
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">First Name</label>
                <input name="firstname" class="form-control" required 
                       value="<?php echo isset($formData['firstname']) ? htmlspecialchars($formData['firstname']) : ''; ?>">
            </div>
            <div class="col">
                <label class="form-label">Last Name</label>
                <input name="lastname" class="form-control" required 
                       value="<?php echo isset($formData['lastname']) ? htmlspecialchars($formData['lastname']) : ''; ?>">
            </div>
        </div>

        <!-- Email & Telephone -->
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control" required 
                       value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>">
            </div>
            <div class="col">
                <label class="form-label">Telephone</label>
                <input name="telephone" class="form-control" 
                       value="<?php echo isset($formData['telephone']) ? htmlspecialchars($formData['telephone']) : ''; ?>">
            </div>
        </div>

        <!-- Company & Type -->
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Company</label>
                <input name="company" class="form-control" 
                       value="<?php echo isset($formData['company']) ? htmlspecialchars($formData['company']) : ''; ?>">
            </div>
            <div class="col">
                <label class="form-label">Type</label>
                <select name="type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="Sales Lead" <?php echo (isset($formData['type']) && $formData['type'] == 'Sales Lead') ? 'selected' : ''; ?>>Sales Lead</option>
                    <option value="Support" <?php echo (isset($formData['type']) && $formData['type'] == 'Support') ? 'selected' : ''; ?>>Support</option>
                </select>
            </div>
        </div>

        <!-- Assigned To -->
        <div class="mb-4">
            <label class="form-label">Assigned To</label>
            <select name="assigned_to" class="form-select" required>
                <option value="">Select User</option>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>" 
                            <?php echo (isset($formData['assigned_to']) && $formData['assigned_to'] == $user['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No users available</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="text-end">
            <a href="dashboard.php" class="btn btn-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm">Save</button>
        </div>
    </form>
</div>

<?php require_once '../src/views/footer.php'; ?>