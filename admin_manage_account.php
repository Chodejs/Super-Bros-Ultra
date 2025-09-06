<?php
// admin_manage_account.php
session_start();
require 'db_connect.php';

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_login.php');
    exit;
}

$pageTitle = "Manage Account - Admin Dashboard";
$userId = $_SESSION['user_id'];
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // --- Validation ---
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $errorMessage = "All password fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $errorMessage = "The new password and confirmation password do not match.";
    } elseif (strlen($new_password) < 8) {
        $errorMessage = "The new password must be at least 8 characters long.";
    } else {
        try {
            // Fetch the current password hash from the database
            $stmt = $db->prepare("SELECT password_hash FROM admins WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($current_password, $user['password_hash'])) {
                // Current password is correct, proceed with update
                
                // Hash the new password
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the database with the new password hash and set must_change_password to false (0)
                $update_stmt = $db->prepare("UPDATE admins SET password_hash = ?, must_change_password = 0 WHERE id = ?");
                $update_stmt->bind_param("si", $new_password_hash, $userId);
                
                if ($update_stmt->execute()) {
                    // Update was successful
                    $_SESSION['must_change_password'] = false; // Update the session flag
                    $successMessage = "Your password has been updated successfully!";
                } else {
                    $errorMessage = "There was an error updating your password. Please try again.";
                }
                $update_stmt->close();
            } else {
                // Incorrect current password
                $errorMessage = "The 'Current Password' you entered is incorrect.";
            }
            $stmt->close();

        } catch (Exception $e) {
            error_log("Password change error for user ID {$userId}: " . $e->getMessage());
            $errorMessage = "An unexpected error occurred. Please try again later.";
        }
    }
}

// Minimal admin header for protected pages
include 'admin_header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-2xl font-bold text-primary-black mb-6">Manage Your Account</h1>

        <?php if ($_SESSION['must_change_password']): ?>
        <div class="mb-6 p-4 rounded-md bg-yellow-100 text-yellow-800 border border-yellow-300" role="alert">
            <strong>Security Notice:</strong> You must change your temporary password before you can access the admin dashboard.
        </div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
        <div class="mb-6 p-4 rounded-md bg-green-100 text-green-800" role="alert">
            <?php echo htmlspecialchars($successMessage); ?>
            <p class="mt-2"><a href="admin_dashboard.php" class="font-bold underline">Return to Dashboard</a></p>
        </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
        <div class="mb-6 p-4 rounded-md bg-red-100 text-red-800" role="alert">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
        <?php endif; ?>

        <?php if (!$successMessage): // Hide form on success ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="space-y-6">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
            </div>
             <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" name="new_password" id="new_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters long.</p>
            </div>
             <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
            </div>
            <div>
                <button type="submit" class="w-full btn bg-accent-orange hover:bg-orange-600 text-primary-black font-bold py-3 px-4 rounded-md shadow-md transition">
                    Update Password
                </button>
            </div>
        </form>
        <?php endif; ?>

    </div>
</div>

<?php
// Minimal admin footer
include 'admin_footer.php';
?>
