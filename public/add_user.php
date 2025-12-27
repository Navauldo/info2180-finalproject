<?php
// Start session at the very top
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    $_SESSION['error'] = "Access denied. Admin only.";
    header('Location: users.php');
    exit;
}

// Get form data from session if it exists (for repopulating after validation errors)
$formData = isset($_SESSION['user_form_data']) ? $_SESSION['user_form_data'] : [];
$errors = isset($_SESSION['user_errors']) ? $_SESSION['user_errors'] : [];

// Clear session data
unset($_SESSION['user_form_data']);
unset($_SESSION['user_errors']);

// Include header
require_once '../src/views/header.php';
?>

<div class="container mt-4">
    <h3>New User</h3>
    
    <!-- Display any error messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <!-- Display validation errors -->
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
    
    <!-- Display success message if redirected from successful user creation -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="POST" action="../src/controllers/UserController.php" class="bg-white p-4 rounded shadow-sm">
        <input type="hidden" name="add_user" value="1">
        
        <!-- Names -->
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">First Name</label>
                <input name="firstname" class="form-control" required maxlength="50" 
                       value="<?php echo isset($formData['firstname']) ? htmlspecialchars($formData['firstname']) : ''; ?>">
            </div>
            <div class="col">
                <label class="form-label">Last Name</label>
                <input name="lastname" class="form-control" required maxlength="50" 
                       value="<?php echo isset($formData['lastname']) ? htmlspecialchars($formData['lastname']) : ''; ?>">
            </div>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required maxlength="100" 
                   value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>">
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required id="password">
            <small class="form-text text-muted">
                Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, and one number.
            </small>
        </div>

        <!-- Role -->
        <div class="mb-4">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="">Select Role</option>
                <option value="Admin" <?php echo (isset($formData['role']) && $formData['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="Member" <?php echo (isset($formData['role']) && $formData['role'] == 'Member') ? 'selected' : ''; ?>>Member</option>
            </select>
        </div>

        <div class="text-end">
            <button type="button" onclick="window.location.href='users.php'" class="btn btn-secondary me-2">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<script>
// Client-side password validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    
    if (!passwordRegex.test(password)) {
        e.preventDefault();
        alert('Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, and one number.');
        return false;
    }
    return true;
});
</script>

<?php require_once '../src/views/footer.php'; ?>