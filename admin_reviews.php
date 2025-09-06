<?php
// admin_reviews.php
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

$pageTitle = "Manage Reviews - Admin Dashboard";
$feedbackMessage = $_SESSION['feedback_message'] ?? '';
$feedbackType = $_SESSION['feedback_type'] ?? '';
unset($_SESSION['feedback_message'], $_SESSION['feedback_type']);

// Handle visibility toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_visibility'])) {
    $reviewId = (int)$_POST['review_id'];
    $currentVisibility = (int)$_POST['current_visibility'];
    $newVisibility = $currentVisibility ? 0 : 1;

    try {
        $stmt = $db->prepare("UPDATE reviews SET is_visible = ? WHERE id = ?");
        $stmt->bind_param("ii", $newVisibility, $reviewId);
        $stmt->execute();
        $_SESSION['feedback_message'] = "Review visibility updated successfully.";
        $_SESSION['feedback_type'] = 'success';
    } catch (Exception $e) {
        error_log("Review visibility toggle error: " . $e->getMessage());
        $_SESSION['feedback_message'] = "Error updating review visibility.";
        $_SESSION['feedback_type'] = 'error';
    }
    header('Location: admin_reviews.php');
    exit;
}


// Fetch all reviews from the database
$reviews = [];
try {
    $query = "SELECT id, author, project_type, rating, is_visible, display_order FROM reviews ORDER BY display_order ASC, created_at DESC";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
} catch (Exception $e) {
    error_log("Reviews fetch error: " . $e->getMessage());
    $feedbackMessage = "Error fetching reviews from the database.";
    $feedbackType = 'error';
}

include 'admin_header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary-black">Manage Client Reviews</h1>
        <a href="admin_review_edit.php" class="btn bg-accent-orange hover:bg-orange-600 text-primary-black font-bold py-2 px-4 rounded-md shadow-md transition-transform transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i> Add New Review
        </a>
    </div>

    <?php if ($feedbackMessage): ?>
    <div class="mb-6 p-4 rounded-md <?php echo $feedbackType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>" role="alert">
        <?php echo htmlspecialchars($feedbackMessage); ?>
    </div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($reviews)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">No reviews found. Click 'Add New Review' to get started.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($review['author']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($review['project_type'] ?: 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i < $review['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                                <?php endfor; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($review['display_order']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" action="admin_reviews.php" class="inline">
                                    <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                    <input type="hidden" name="current_visibility" value="<?php echo $review['is_visible']; ?>">
                                    <button type="submit" name="toggle_visibility" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $review['is_visible'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $review['is_visible'] ? 'Visible' : 'Hidden'; ?>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="admin_review_edit.php?id=<?php echo $review['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <a href="admin_review_edit.php?delete=<?php echo $review['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this review? This cannot be undone.');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
