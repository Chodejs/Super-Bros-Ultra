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

// --- CONFIGURATION ---
define("UPLOAD_DIR", "uploads/");

$pageTitle = "Add/Edit Review";
$feedbackMessage = '';
$feedbackType = '';
$formAction = 'create';

// Initialize form data array
$review = [
    'id' => null, 'author' => '', 'project_type' => '', 'title' => '',
    'review_text' => '', 'rating' => 5, 'is_visible' => 1, 'display_order' => 0,
    'author_image_url' => '', 'project_image_url' => '', 'google_review_url' => ''
];

// --- Handle Deletion ---
if (isset($_GET['delete'])) {
    $reviewIdToDelete = (int)$_GET['delete'];
    try {
        // First, get the project image URL to delete the file
        $stmt_select = $db->prepare("SELECT project_image_url FROM reviews WHERE id = ?");
        $stmt_select->bind_param("i", $reviewIdToDelete);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        if ($row = $result->fetch_assoc()) {
            if (!empty($row['project_image_url']) && file_exists($row['project_image_url'])) {
                unlink($row['project_image_url']);
            }
        }
        $stmt_select->close();

        // Then, delete the database record
        $stmt_delete = $db->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt_delete->bind_param("i", $reviewIdToDelete);
        $stmt_delete->execute();
        $stmt_delete->close();
        
        $_SESSION['feedback_message'] = 'Review and associated image deleted successfully.';
        $_SESSION['feedback_type'] = 'success';
    } catch (Exception $e) {
        error_log("Review delete error: " . $e->getMessage());
        $_SESSION['feedback_message'] = 'Error deleting review.';
        $_SESSION['feedback_type'] = 'error';
    }
    header('Location: admin_reviews.php');
    exit;
}

// --- Handle Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Populate review array from POST data
    $review['id'] = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $review['author'] = trim($_POST['author']);
    $review['project_type'] = trim($_POST['project_type']);
    $review['title'] = trim($_POST['title']);
    $review['review_text'] = trim($_POST['review_text']);
    $review['rating'] = (int)$_POST['rating'];
    $review['is_visible'] = isset($_POST['is_visible']) ? 1 : 0;
    $review['display_order'] = (int)$_POST['display_order'];
    $review['author_image_url'] = trim($_POST['author_image_url']);
    $review['google_review_url'] = trim($_POST['google_review_url']);
    $review['project_image_url'] = $_POST['existing_project_image_url'] ?? ''; // Keep existing if no new upload

    // Basic validation
    if (empty($review['author']) || empty($review['review_text'])) {
        $feedbackMessage = 'Author and Review Text are required fields.';
        $feedbackType = 'error';
    } else {
        // --- Handle File Upload ---
        if (isset($_FILES['project_image']) && $_FILES['project_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['project_image']['tmp_name'];
            $fileName = $_FILES['project_image']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = UPLOAD_DIR . $newFileName;
            
            $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileExtension, $allowedfileExtensions)) {
                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Delete old image if it exists and is different
                    if (!empty($review['project_image_url']) && $review['project_image_url'] !== $dest_path && file_exists($review['project_image_url'])) {
                        unlink($review['project_image_url']);
                    }
                    $review['project_image_url'] = $dest_path;
                } else {
                    $feedbackMessage = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                    $feedbackType = 'error';
                }
            } else {
                $feedbackMessage = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
                $feedbackType = 'error';
            }
        }

        // Proceed with DB operation only if there were no upload errors
        if ($feedbackType !== 'error') {
            try {
                if ($review['id']) { // Update
                    $stmt = $db->prepare("UPDATE reviews SET author = ?, project_type = ?, title = ?, review_text = ?, rating = ?, is_visible = ?, display_order = ?, author_image_url = ?, project_image_url = ?, google_review_url = ? WHERE id = ?");
                    $stmt->bind_param("ssssiiisssi", $review['author'], $review['project_type'], $review['title'], $review['review_text'], $review['rating'], $review['is_visible'], $review['display_order'], $review['author_image_url'], $review['project_image_url'], $review['google_review_url'], $review['id']);
                    $_SESSION['feedback_message'] = 'Review updated successfully!';
                } else { // Create
                    $stmt = $db->prepare("INSERT INTO reviews (author, project_type, title, review_text, rating, is_visible, display_order, author_image_url, project_image_url, google_review_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssiiisss", $review['author'], $review['project_type'], $review['title'], $review['review_text'], $review['rating'], $review['is_visible'], $review['display_order'], $review['author_image_url'], $review['project_image_url'], $review['google_review_url']);
                    $_SESSION['feedback_message'] = 'Review added successfully!';
                }
                $stmt->execute();
                $_SESSION['feedback_type'] = 'success';
                header('Location: admin_reviews.php');
                exit;
            } catch (Exception $e) {
                error_log("Review save/update error: " . $e->getMessage());
                $feedbackMessage = 'An error occurred while saving the review to the database.';
                $feedbackType = 'error';
            }
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
            $_SESSION['feedback_message'] = 'Review not found.';
            $_SESSION['feedback_type'] = 'error';
            header('Location: admin_reviews.php');
            exit;
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
    <div class="mb-6 p-4 rounded-md <?php echo $feedbackType === 'error' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>" role="alert">
        <?php echo htmlspecialchars($feedbackMessage); ?>
    </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-xl shadow-lg">
        <form action="admin_review_edit.php<?php echo $review['id'] ? '?id='.$review['id'] : ''; ?>" method="POST" enctype="multipart/form-data">
            <?php if ($review['id']): ?>
                <input type="hidden" name="id" value="<?php echo $review['id']; ?>">
                <input type="hidden" name="existing_project_image_url" value="<?php echo htmlspecialchars($review['project_image_url']); ?>">
            <?php endif; ?>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700">Author <span class="text-red-500">*</span></label>
                    <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($review['author']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                </div>
                <div>
                    <label for="project_type" class="block text-sm font-medium text-gray-700">Project Type</label>
                    <input type="text" name="project_type" id="project_type" value="<?php echo htmlspecialchars($review['project_type']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                </div>
            </div>
            
            <div class="mt-6">
                <label for="title" class="block text-sm font-medium text-gray-700">Review Title</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($review['title']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                <p class="text-xs text-gray-500 mt-1">Optional. A short, catchy headline for the review.</p>
            </div>

            <div class="mt-6">
                <label for="author_image_url" class="block text-sm font-medium text-gray-700">Author Image URL</label>
                <input type="text" name="author_image_url" id="author_image_url" value="<?php echo htmlspecialchars($review['author_image_url']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                <p class="text-xs text-gray-500 mt-1">Optional. Paste a URL for the client's profile picture.</p>
            </div>
            
            <div class="mt-6">
                <label for="project_image" class="block text-sm font-medium text-gray-700">Project Image</label>
                <?php if (!empty($review['project_image_url'])): ?>
                <div class="my-2">
                    <img src="<?php echo htmlspecialchars($review['project_image_url']); ?>" alt="Current project image" class="h-24 w-auto rounded-md border">
                    <p class="text-xs text-gray-500 mt-1">Current image. Upload a new one to replace it.</p>
                </div>
                <?php endif; ?>
                <input type="file" name="project_image" id="project_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs text-gray-500 mt-1">Optional. Upload a photo of the completed work.</p>
            </div>

            <div class="mt-6">
                <label for="google_review_url" class="block text-sm font-medium text-gray-700">Google Review URL</label>
                <input type="url" name="google_review_url" id="google_review_url" value="<?php echo htmlspecialchars($review['google_review_url']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                <p class="text-xs text-gray-500 mt-1">Optional. Paste the full URL to the original Google Review.</p>
            </div>

            <div class="mt-6">
                <label for="review_text" class="block text-sm font-medium text-gray-700">Review Text <span class="text-red-500">*</span></label>
                <textarea name="review_text" id="review_text" rows="5" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange"><?php echo htmlspecialchars($review['review_text']); ?></textarea>
            </div>

            <div class="grid md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                    <select name="rating" id="rating" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo ($review['rating'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="display_order" class="block text-sm font-medium text-gray-700">Display Order</label>
                    <input type="number" name="display_order" id="display_order" value="<?php echo (int)$review['display_order']; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange">
                </div>
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

