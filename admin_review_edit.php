<?php
// admin_review_edit.php
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

$pageTitle = "Add/Edit Review";
$feedbackMessage = '';
$feedbackType = '';
$formAction = 'create';

// Initialize form data array
$review = [
    'id' => null, 'author' => '', 'project_type' => '',
    'review_text' => '', 'rating' => 5, 'is_visible' => 1, 'display_order' => 0
];

// --- Handle Deletion ---
if (isset($_GET['delete'])) {
    $reviewIdToDelete = (int)$_GET['delete'];
    try {
        $stmt = $db->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $reviewIdToDelete);
        $stmt->execute();
        $_SESSION['feedback_message'] = 'Review deleted successfully.';
        $_SESSION['feedback_type'] = 'success';
    } catch (Exception $e) {
        error_log("Review delete error: " . $e->getMessage());
        $_SESSION['feedback_message'] = 'Error deleting review.';
        $_SESSION['feedback_type'] = 'error';
    }
    header('Location: admin_reviews.php');
    exit;
}

// --- Handle Form Submission (Create or Update) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review['id'] = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $review['author'] = trim($_POST['author']);
    $review['project_type'] = trim($_POST['project_type']);
    $review['review_text'] = trim($_POST['review_text']);
    $review['rating'] = (int)$_POST['rating'];
    $review['is_visible'] = isset($_POST['is_visible']) ? 1 : 0;
    $review['display_order'] = (int)$_POST['display_order'];

    // Basic validation
    if (empty($review['author']) || empty($review['review_text'])) {
        $feedbackMessage = 'Author and Review Text are required fields.';
        $feedbackType = 'error';
    } else {
        try {
            if ($review['id']) { // Update existing review
                $stmt = $db->prepare("UPDATE reviews SET author = ?, project_type = ?, review_text = ?, rating = ?, is_visible = ?, display_order = ? WHERE id = ?");
                $stmt->bind_param("sssiiii", $review['author'], $review['project_type'], $review['review_text'], $review['rating'], $review['is_visible'], $review['display_order'], $review['id']);
                $_SESSION['feedback_message'] = 'Review updated successfully!';
            } else { // Create new review
                $stmt = $db->prepare("INSERT INTO reviews (author, project_type, review_text, rating, is_visible, display_order) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssiii", $review['author'], $review['project_type'], $review['review_text'], $review['rating'], $review['is_visible'], $review['display_order']);
                $_SESSION['feedback_message'] = 'Review added successfully!';
            }
            $stmt->execute();
            $_SESSION['feedback_type'] = 'success';
            header('Location: admin_reviews.php');
            exit;
        } catch (Exception $e) {
            error_log("Review save/update error: " . $e->getMessage());
            $feedbackMessage = 'An error occurred while saving the review.';
            $feedbackType = 'error';
        }
    }
}

// --- Fetch Existing Review for Editing ---
if (isset($_GET['id'])) {
    $formAction = 'update';
    $reviewId = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $reviewId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $review = $result->fetch_assoc();
        } else {
            $feedbackMessage = 'Review not found.';
            $feedbackType = 'error';
        }
    } catch (Exception $e) {
        error_log("Fetch review for edit error: " . $e->getMessage());
        $feedbackMessage = 'Error fetching review data.';
        $feedbackType = 'error';
    }
}

$pageTitle = ($formAction === 'update' ? "Edit Review" : "Add New Review");

include 'admin_header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-primary-black mb-6"><?php echo $pageTitle; ?></h1>

    <?php if ($feedbackMessage): ?>
    <div class="mb-6 p-4 rounded-md <?php echo $feedbackType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>" role="alert">
        <?php echo htmlspecialchars($feedbackMessage); ?>
    </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-xl shadow-lg">
        <form action="admin_review_edit.php" method="POST">
            <?php if ($review['id']): ?>
                <input type="hidden" name="id" value="<?php echo $review['id']; ?>">
            <?php endif; ?>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Author -->
                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700">Author <span class="text-red-500">*</span></label>
                    <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($review['author']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                </div>
                <!-- Project Type -->
                <div>
                    <label for="project_type" class="block text-sm font-medium text-gray-700">Project Type (e.g., Residential Painting)</label>
                    <input type="text" name="project_type" id="project_type" value="<?php echo htmlspecialchars($review['project_type']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                </div>
            </div>

            <!-- Review Text -->
            <div class="mt-6">
                <label for="review_text" class="block text-sm font-medium text-gray-700">Review Text <span class="text-red-500">*</span></label>
                <textarea name="review_text" id="review_text" rows="5" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange"><?php echo htmlspecialchars($review['review_text']); ?></textarea>
            </div>

            <div class="grid md:grid-cols-3 gap-6 mt-6">
                <!-- Rating -->
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                    <select name="rating" id="rating" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo ($review['rating'] == $i) ? 'selected' : ''; ?>>
                                <?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <!-- Display Order -->
                <div>
                    <label for="display_order" class="block text-sm font-medium text-gray-700">Display Order</label>
                    <input type="number" name="display_order" id="display_order" value="<?php echo (int)$review['display_order']; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                </div>
                <!-- Visibility -->
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="is_visible" id="is_visible" value="1" <?php echo ($review['is_visible'] == 1) ? 'checked' : ''; ?> class="h-4 w-4 text-accent-orange focus:ring-accent-orange border-gray-300 rounded">
                    <label for="is_visible" class="ml-2 block text-sm text-gray-900">Visible on website</label>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="admin_reviews.php" class="btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</a>
                <button type="submit" class="btn bg-accent-orange hover:bg-orange-600 text-primary-black font-bold py-2 px-4 rounded-md">
                    <?php echo $formAction === 'update' ? 'Save Changes' : 'Add Review'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
