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
$feedbackMessage = '';
$feedbackType = '';

// Fetch all contact submissions to display
$submissions = [];
try {
    // We already have a contact_submissions table from the original setup
    $query = "SELECT id, name, email, phone, project_type, message, submission_date, ip_address FROM contact_submissions ORDER BY submission_date DESC";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $submissions[] = $row;
    }
} catch (Exception $e) {
    error_log("Contact submissions fetch error: " . $e->getMessage());
    $feedbackMessage = "Error fetching contact submissions.";
    $feedbackType = 'error';
}


include 'admin_header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-primary-black mb-6">Contact Form Submissions</h1>

    <?php if ($feedbackMessage): ?>
    <div class="mb-6 p-4 rounded-md bg-red-100 text-red-800" role="alert">
        <?php echo htmlspecialchars($feedbackMessage); ?>
    </div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <?php if (empty($submissions) && $feedbackType !== 'error'): ?>
            <p class="text-center text-gray-500 py-8">No contact submissions have been received yet.</p>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($submissions as $sub): ?>
                <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-lg text-primary-black"><?php echo htmlspecialchars($sub['name']); ?></p>
                            <p class="text-sm text-gray-600">
                                <a href="mailto:<?php echo htmlspecialchars($sub['email']); ?>" class="text-blue-600 hover:underline"><?php echo htmlspecialchars($sub['email']); ?></a>
                                <?php if (!empty($sub['phone'])): ?>
                                    | <a href="tel:<?php echo htmlspecialchars($sub['phone']); ?>" class="text-blue-600 hover:underline"><?php echo htmlspecialchars($sub['phone']); ?></a>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="text-right text-xs text-gray-500">
                            <p><?php echo date("M j, Y, g:i a", strtotime($sub['submission_date'])); ?></p>
                            <p>IP: <?php echo htmlspecialchars($sub['ip_address']); ?></p>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 mt-2">Project Type: <span class="font-normal"><?php echo htmlspecialchars($sub['project_type']); ?></span></p>
                    <div class="mt-2 pt-2 border-t">
                        <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($sub['message']); ?></p>
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
