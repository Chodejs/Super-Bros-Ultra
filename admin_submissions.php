<?php
// admin_submissions.php
session_start();
require 'db_connect.php';

// Authentication and authorization checks
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_login.php');
    exit;
}
if ($_SESSION['must_change_password']) {
    header('Location: admin_manage_account.php');
    exit;
}

$pageTitle = "View Contact Submissions - Admin Dashboard";
$feedbackMessage = $_SESSION['feedback_message'] ?? '';
$feedbackType = $_SESSION['feedback_type'] ?? '';
unset($_SESSION['feedback_message'], $_SESSION['feedback_type']);

// --- HANDLE ACTIONS (DELETE, UPDATE STATUS) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id'])) {
    $submissionId = (int)$_POST['submission_id'];
    
    try {
        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            // Handle deletion
            $stmt = $db->prepare("DELETE FROM contact_submissions WHERE id = ?");
            $stmt->bind_param("i", $submissionId);
            $stmt->execute();
            $_SESSION['feedback_message'] = "Submission deleted successfully.";
            $_SESSION['feedback_type'] = 'success';
        } 
        elseif (isset($_POST['action']) && $_POST['action'] === 'update_status') {
            // Handle status update
            $newStatus = (int)$_POST['new_status'];
            $stmt = $db->prepare("UPDATE contact_submissions SET status = ? WHERE id = ?");
            $stmt->bind_param("ii", $newStatus, $submissionId);
            $stmt->execute();
            $_SESSION['feedback_message'] = "Submission status updated.";
            $_SESSION['feedback_type'] = 'success';
        }
    } catch (Exception $e) {
        error_log("Contact submission action error: " . $e->getMessage());
        $_SESSION['feedback_message'] = "An error occurred while performing the action.";
        $_SESSION['feedback_type'] = 'error';
    }

    // Redirect to the same page to prevent form resubmission on refresh
    header('Location: admin_submissions.php');
    exit;
}


// Fetch all contact submissions to display
$submissions = [];
try {
    // Added 'status' to the query. 0 = New, 1 = Read
    $query = "SELECT id, name, email, phone, project_type, message, submission_date, ip_address, status FROM contact_submissions ORDER BY status ASC, submission_date DESC";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $submissions[] = $row;
    }
} catch (Exception $e) {
    error_log("Contact submissions fetch error: " . $e->getMessage());
    $feedbackMessage = "Error fetching contact submissions. (Details: " . htmlspecialchars($e->getMessage()) . ")";
    $feedbackType = 'error';
}

include 'admin_header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-primary-black mb-6">Contact Form Submissions</h1>

    <?php if ($feedbackMessage): ?>
    <div class="mb-6 p-4 rounded-md <?php echo $feedbackType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>" role="alert">
        <?php echo htmlspecialchars($feedbackMessage); ?>
    </div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <?php if (empty($submissions) && $feedbackType !== 'error'): ?>
            <p class="text-center text-gray-500 py-8">No contact submissions have been received yet.</p>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($submissions as $sub): ?>
                <div class="border rounded-lg p-4 transition-colors <?php echo $sub['status'] == 0 ? 'bg-orange-50 border-orange-200' : 'bg-white hover:bg-gray-50'; ?>">
                    <div class="flex flex-wrap justify-between items-start gap-4">
                        <div>
                            <p class="font-bold text-lg text-primary-black flex items-center">
                                <?php if($sub['status'] == 0): ?><span class="w-3 h-3 bg-accent-orange rounded-full mr-3" title="New Submission"></span><?php endif; ?>
                                <?php echo htmlspecialchars($sub['name']); ?>
                            </p>
                            <p class="text-sm text-gray-600 pl-6">
                                <a href="mailto:<?php echo htmlspecialchars($sub['email']); ?>" class="text-blue-600 hover:underline"><?php echo htmlspecialchars($sub['email']); ?></a>
                                <?php if (!empty($sub['phone'])): ?>
                                    | <a href="tel:<?php echo htmlspecialchars($sub['phone']); ?>" class="text-blue-600 hover:underline"><?php echo htmlspecialchars($sub['phone']); ?></a>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="text-right text-xs text-gray-500 flex-shrink-0">
                            <p><?php echo date("M j, Y, g:i a", strtotime($sub['submission_date'])); ?></p>
                            <p>IP: <?php echo htmlspecialchars($sub['ip_address']); ?></p>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 mt-2">Project Type: <span class="font-normal"><?php echo htmlspecialchars($sub['project_type']); ?></span></p>
                    <div class="mt-2 pt-2 border-t">
                        <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($sub['message']); ?></p>
                    </div>
                    <!-- ACTION BUTTONS -->
                    <div class="mt-4 pt-3 border-t flex items-center justify-end space-x-3">
                        <form method="POST" action="admin_submissions.php" class="inline">
                            <input type="hidden" name="submission_id" value="<?php echo $sub['id']; ?>">
                            <input type="hidden" name="action" value="update_status">
                            <?php if ($sub['status'] == 0): ?>
                                <input type="hidden" name="new_status" value="1">
                                <button type="submit" class="text-sm bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-3 rounded-md transition-colors">
                                    <i class="fas fa-check mr-1"></i> Mark as Read
                                </button>
                            <?php else: ?>
                                <input type="hidden" name="new_status" value="0">
                                <button type="submit" class="text-sm bg-gray-400 hover:bg-gray-500 text-white font-semibold py-1 px-3 rounded-md transition-colors">
                                    <i class="fas fa-eye-slash mr-1"></i> Mark as Unread
                                </button>
                            <?php endif; ?>
                        </form>
                        <form method="POST" action="admin_submissions.php" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this submission?');">
                            <input type="hidden" name="submission_id" value="<?php echo $sub['id']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="text-sm bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-md transition-colors">
                                <i class="fas fa-trash-alt mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include 'admin_footer.php';
?>
