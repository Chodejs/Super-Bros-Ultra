<?php
// admin_portfolio_edit.php
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

// --- INITIALIZATION ---
$pageTitle = "Add Portfolio Item";
$mode = 'add';
$itemId = null;
$feedbackMessage = '';
$feedbackType = '';

// Form data variables
$title = '';
$category = '';
$description = '';
$image_url = '';
$thumb_url = '';
$display_order = 0;
$is_visible = 1;

// --- MODE DETERMINATION (ADD vs EDIT) ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $mode = 'edit';
    $itemId = (int)$_GET['id'];
    $pageTitle = "Edit Portfolio Item";

    // Fetch existing item data
    try {
        $stmt = $db->prepare("SELECT * FROM portfolio WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $item = $result->fetch_assoc();
            $title = $item['title'];
            $category = $item['category'];
            $description = $item['description'];
            $image_url = $item['image_url'];
            $thumb_url = $item['thumb_url'];
            $display_order = $item['display_order'];
            $is_visible = $item['is_visible'];
        } else {
            // Item not found, redirect with an error (or show one)
            header('Location: admin_portfolio.php'); // Simplest approach
            exit;
        }
        $stmt->close();
    } catch (Exception $e) {
        error_log("Portfolio fetch for edit error: " . $e->getMessage());
        $feedbackMessage = "Error fetching item data.";
        $feedbackType = 'error';
    }
}

// --- FORM SUBMISSION HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $thumb_url = trim($_POST['thumb_url'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_visible = isset($_POST['is_visible']) ? 1 : 0;

    // Basic Validation
    if (empty($title) || empty($category) || empty($image_url) || empty($thumb_url)) {
        $feedbackMessage = "Title, Category, Image URL, and Thumbnail URL are required fields.";
        $feedbackType = 'error';
    } else {
        try {
            if ($mode === 'edit') {
                // UPDATE existing record
                $stmt = $db->prepare("UPDATE portfolio SET title = ?, category = ?, description = ?, image_url = ?, thumb_url = ?, display_order = ?, is_visible = ? WHERE id = ?");
                $stmt->bind_param("sssssiii", $title, $category, $description, $image_url, $thumb_url, $display_order, $is_visible, $itemId);
                $actionVerb = "updated";
            } else {
                // INSERT new record
                $stmt = $db->prepare("INSERT INTO portfolio (title, category, description, image_url, thumb_url, display_order, is_visible) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssii", $title, $category, $description, $image_url, $thumb_url, $display_order, $is_visible);
                $actionVerb = "created";
            }

            if ($stmt->execute()) {
                // On success, redirect back to the main portfolio page
                // We can use a session variable for a "flash message" on the next page, but this is more complex.
                // For now, we'll just redirect.
                header("Location: admin_portfolio.php");
                exit;
            } else {
                throw new Exception("Database execution failed.");
            }
            $stmt->close();

        } catch (Exception $e) {
            error_log("Portfolio " . $mode . " error: " . $e->getMessage());
            $feedbackMessage = "An error occurred while saving the item.";
            $feedbackType = 'error';
        }
    }
}


include 'admin_header.php';
?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-2xl font-bold text-primary-black mb-6"><?php echo htmlspecialchars($pageTitle); ?></h1>

        <?php if ($feedbackMessage): ?>
        <div class="mb-6 p-4 rounded-md <?php echo $feedbackType === 'error' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'; ?>" role="alert">
            <?php echo htmlspecialchars($feedbackMessage); ?>
        </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST" class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Project Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($title); ?>">
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Categories <span class="text-red-500">*</span></label>
                <input type="text" name="category" id="category" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($category); ?>">
                <p class="text-xs text-gray-500 mt-1">Separate categories with a comma (e.g., Painting, Residential, Drywall).</p>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="5" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange"><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div>
                <label for="image_url" class="block text-sm font-medium text-gray-700">Main Image URL <span class="text-red-500">*</span></label>
                <input type="text" name="image_url" id="image_url" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($image_url); ?>">
                 <p class="text-xs text-gray-500 mt-1">e.g., https://placehold.co/600x400/...</p>
            </div>
             <div>
                <label for="thumb_url" class="block text-sm font-medium text-gray-700">Thumbnail Image URL <span class="text-red-500">*</span></label>
                <input type="text" name="thumb_url" id="thumb_url" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($thumb_url); ?>">
                <p class="text-xs text-gray-500 mt-1">e.g., https://placehold.co/400x300/...</p>
            </div>
            
            <div>
                <label for="display_order" class="block text-sm font-medium text-gray-700">Display Order</label>
                <input type="number" name="display_order" id="display_order" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($display_order); ?>">
                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first (e.g., 0 is highest priority).</p>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_visible" id="is_visible" value="1" <?php echo $is_visible ? 'checked' : ''; ?> class="h-4 w-4 text-accent-orange focus:ring-accent-orange border-gray-300 rounded">
                <label for="is_visible" class="ml-2 block text-sm text-gray-900">Visible on public site</label>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="admin_portfolio.php" class="btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md">Cancel</a>
                <button type="submit" class="btn bg-accent-orange hover:bg-orange-600 text-primary-black font-bold py-2 px-4 rounded-md">
                    Save <?php echo $mode === 'edit' ? 'Changes' : 'Project'; ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?php
include 'admin_footer.php';
?>
