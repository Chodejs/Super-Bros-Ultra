<?php
// admin_portfolio_edit.php
session_start();
require 'db_connect.php';

// --- CONFIGURATION ---
define("UPLOAD_DIR", "uploads/");

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
$title = ''; $category = ''; $description = ''; $long_description = '';
$image_url = ''; $thumb_url = ''; $before_image_url = ''; $after_image_url = '';
$display_order = 0; $is_visible = 1;

// --- MODE DETERMINATION (ADD vs EDIT) ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $mode = 'edit';
    $itemId = (int)$_GET['id'];
    $pageTitle = "Edit Portfolio Item";
    try {
        $stmt = $db->prepare("SELECT * FROM portfolio WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $item = $result->fetch_assoc();
            $title = $item['title']; $category = $item['category']; $description = $item['description'];
            $long_description = $item['long_description'] ?? ''; $image_url = $item['image_url'];
            $thumb_url = $item['thumb_url']; $before_image_url = $item['before_image_url'] ?? '';
            $after_image_url = $item['after_image_url'] ?? ''; $display_order = $item['display_order'];
            $is_visible = $item['is_visible'];
        } else {
            header('Location: admin_portfolio.php'); exit;
        }
        $stmt->close();
    } catch (Exception $e) {
        error_log("Portfolio fetch for edit error: " . $e->getMessage());
        $feedbackMessage = "Error fetching item data."; $feedbackType = 'error';
    }
}

// --- HELPER FUNCTION FOR IMAGE UPLOADS ---
function handle_image_upload($file_input_name, $existing_url_field, &$feedbackMessage, &$feedbackType) {
    // If a new file is uploaded, it takes precedence
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES[$file_input_name]['tmp_name'];
        $fileName = $_FILES[$file_input_name]['name'];
        $fileSize = $_FILES[$file_input_name]['size'];
        $fileType = $_FILES[$file_input_name]['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $dest_path = UPLOAD_DIR . $newFileName;
        
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // If upload is successful, delete the old file if it exists and is a local file
                if (!empty($existing_url_field) && strpos($existing_url_field, UPLOAD_DIR) === 0 && file_exists($existing_url_field)) {
                    unlink($existing_url_field);
                }
                return $dest_path; // Return the new path
            } else {
                $feedbackMessage = 'Error moving uploaded file. Check directory permissions.';
                $feedbackType = 'error';
                return $existing_url_field; // Return old URL on failure
            }
        } else {
            $feedbackMessage = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
            $feedbackType = 'error';
            return $existing_url_field; // Return old URL on failure
        }
    }
    // If no new file, return the value from the text input (either existing or newly pasted)
    return trim($_POST[$existing_url_field] ?? '');
}

// --- FORM SUBMISSION HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $long_description = trim($_POST['long_description'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_visible = isset($_POST['is_visible']) ? 1 : 0;
    $itemId = isset($_POST['id']) ? (int)$_POST['id'] : $itemId;

    // Handle image processing
    $image_url = handle_image_upload('main_image_file', 'image_url', $feedbackMessage, $feedbackType);
    $thumb_url = handle_image_upload('thumb_image_file', 'thumb_url', $feedbackMessage, $feedbackType);
    $before_image_url = handle_image_upload('before_image_file', 'before_image_url', $feedbackMessage, $feedbackType);
    $after_image_url = handle_image_upload('after_image_file', 'after_image_url', $feedbackMessage, $feedbackType);
    
    // Validation
    if ((empty($title) || empty($category) || empty($image_url) || empty($thumb_url))) {
        $feedbackMessage = "Title, Category, Main Image, and Thumbnail Image are required (either via upload or URL).";
        $feedbackType = 'error';
    }

    if ($feedbackType !== 'error') {
        try {
            if ($mode === 'edit' && $itemId) {
                $stmt = $db->prepare("UPDATE portfolio SET title=?, category=?, description=?, image_url=?, thumb_url=?, display_order=?, is_visible=?, long_description=?, before_image_url=?, after_image_url=? WHERE id=?");
                $stmt->bind_param("sssssiisssi", $title, $category, $description, $image_url, $thumb_url, $display_order, $is_visible, $long_description, $before_image_url, $after_image_url, $itemId);
            } else {
                $stmt = $db->prepare("INSERT INTO portfolio (title, category, description, image_url, thumb_url, display_order, is_visible, long_description, before_image_url, after_image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssiisss", $title, $category, $description, $image_url, $thumb_url, $display_order, $is_visible, $long_description, $before_image_url, $after_image_url);
            }
            if ($stmt->execute()) {
                header("Location: admin_portfolio.php"); exit;
            } else { throw new Exception("Database execution failed."); }
        } catch (Exception $e) {
            error_log("Portfolio " . $mode . " error: " . $e->getMessage());
            $feedbackMessage = "An error occurred while saving the item."; $feedbackType = 'error';
        }
    }
}

include 'admin_header.php';
?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-2xl font-bold text-primary-black mb-6"><?php echo htmlspecialchars($pageTitle); ?></h1>

        <?php if ($feedbackMessage): ?>
        <div class="mb-6 p-4 rounded-md <?php echo $feedbackType === 'error' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>" role="alert">
            <?php echo htmlspecialchars($feedbackMessage); ?>
        </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?php if ($mode === 'edit'): ?><input type="hidden" name="id" value="<?php echo $itemId; ?>"><?php endif; ?>
            
            <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">Core Information</h2>
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Project Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" required class="mt-1 block w-full input-field" value="<?php echo htmlspecialchars($title); ?>">
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Categories <span class="text-red-500">*</span></label>
                <input type="text" name="category" id="category" required class="mt-1 block w-full input-field" value="<?php echo htmlspecialchars($category); ?>">
                <p class="text-xs text-gray-500 mt-1">Separate with commas (e.g., Painting, Residential).</p>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Short Description (for Portfolio Grid)</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full input-field"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div>
                <label for="long_description" class="block text-sm font-medium text-gray-700">Detailed Description (for Single Project Page)</label>
                <textarea name="long_description" id="long_description" rows="6" class="mt-1 block w-full input-field"><?php echo htmlspecialchars($long_description); ?></textarea>
            </div>

            <h2 class="text-lg font-semibold text-gray-800 border-b pt-4 pb-2">Project Images</h2>
            
            <!-- Image Upload Section Template -->
            <?php function image_uploader($label, $file_input_name, $url_input_name, $current_url, $is_required = false) { ?>
            <div class="p-4 border rounded-md mt-4">
                <label class="block text-sm font-medium text-gray-700"><?php echo $label; ?> <?php if ($is_required) echo '<span class="text-red-500">*</span>'; ?></label>
                <?php if (!empty($current_url)): ?>
                    <img src="<?php echo htmlspecialchars($current_url); ?>" alt="Current <?php echo strtolower($label); ?>" class="h-20 w-auto rounded my-2 border">
                <?php endif; ?>
                <label for="<?php echo $file_input_name; ?>" class="text-xs text-gray-600 font-semibold">Upload New File:</label>
                <input type="file" name="<?php echo $file_input_name; ?>" id="<?php echo $file_input_name; ?>" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-center text-xs text-gray-500 my-2">OR</p>
                <label for="<?php echo $url_input_name; ?>" class="text-xs text-gray-600 font-semibold">Paste Image URL:</label>
                <input type="text" name="<?php echo $url_input_name; ?>" id="<?php echo $url_input_name; ?>" class="mt-1 block w-full input-field" value="<?php echo htmlspecialchars($current_url); ?>" placeholder="https://example.com/image.jpg">
            </div>
            <?php } ?>

            <?php image_uploader('Main Image', 'main_image_file', 'image_url', $image_url, true); ?>
            <?php image_uploader('Thumbnail Image', 'thumb_image_file', 'thumb_url', $thumb_url, true); ?>
            <?php image_uploader('"Before" Image', 'before_image_file', 'before_image_url', $before_image_url); ?>
            <?php image_uploader('"After" Image', 'after_image_file', 'after_image_url', $after_image_url); ?>

            <h2 class="text-lg font-semibold text-gray-800 border-b pt-4 pb-2">Settings</h2>
            <div>
                <label for="display_order" class="block text-sm font-medium text-gray-700">Display Order</label>
                <input type="number" name="display_order" id="display_order" class="mt-1 block w-full input-field" value="<?php echo htmlspecialchars($display_order); ?>">
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
// A simple CSS class for consistent input styling
echo '<style>.input-field { border-radius: 0.375rem; border: 1px solid #d1d5db; padding: 0.5rem 0.75rem; width: 100%; } .input-field:focus { outline: none; --tw-ring-color: var(--color-accent-orange); box-shadow: 0 0 0 2px var(--tw-ring-color); border-color: var(--color-accent-orange); }</style>';
include 'admin_footer.php';
?>

