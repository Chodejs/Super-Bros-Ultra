<?php
// admin_portfolio.php
session_start();
require 'db_connect.php';

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_login.php');
    exit;
}
// Redirect if password change is required
if ($_SESSION['must_change_password']) {
    header('Location: admin_manage_account.php');
    exit;
}

$pageTitle = "Manage Portfolio - Admin Dashboard";
$feedbackMessage = '';
$feedbackType = '';

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $itemId = $_POST['item_id'] ?? 0;
    if ($itemId > 0) {
        try {
            $stmt = $db->prepare("DELETE FROM portfolio WHERE id = ?");
            $stmt->bind_param("i", $itemId);
            if ($stmt->execute()) {
                $feedbackMessage = "Portfolio item deleted successfully.";
                $feedbackType = 'success';
            } else {
                throw new Exception("Failed to delete item.");
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Portfolio delete error: " . $e->getMessage());
            $feedbackMessage = "Error deleting portfolio item.";
            $feedbackType = 'error';
        }
    }
}

// Fetch all portfolio items to display
$portfolioItems = [];
try {
    $query = "SELECT id, title, category, thumb_url, is_visible, display_order FROM portfolio ORDER BY display_order ASC, created_at DESC";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $portfolioItems[] = $row;
    }
} catch (Exception $e) {
    error_log("Portfolio fetch error: " . $e->getMessage());
    $feedbackMessage = "Error fetching portfolio items.";
    $feedbackType = 'error';
}


include 'admin_header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary-black">Manage Portfolio</h1>
        <a href="admin_portfolio_edit.php" class="btn bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-md">
            <i class="fas fa-plus mr-2"></i>Add New Project
        </a>
    </div>

    <?php if ($feedbackMessage): ?>
    <div class="mb-6 p-4 rounded-md <?php echo $feedbackType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>" role="alert">
        <?php echo htmlspecialchars($feedbackMessage); ?>
    </div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
        <?php if (empty($portfolioItems) && $feedbackType !== 'error'): ?>
            <p class="text-center text-gray-500">No portfolio items found. Click 'Add New Project' to get started!</p>
        <?php else: ?>
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 px-4">Image</th>
                        <th class="py-2 px-4">Title</th>
                        <th class="py-2 px-4">Category</th>
                        <th class="py-2 px-4">Status</th>
                        <th class="py-2 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($portfolioItems as $item): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2 px-4">
                            <img src="<?php echo htmlspecialchars($item['thumb_url']); ?>" alt="Thumbnail for <?php echo htmlspecialchars($item['title']); ?>" class="h-12 w-16 object-cover rounded-md">
                        </td>
                        <td class="py-2 px-4 font-medium text-gray-800"><?php echo htmlspecialchars($item['title']); ?></td>
                        <td class="py-2 px-4 text-gray-600"><?php echo htmlspecialchars($item['category']); ?></td>
                        <td class="py-2 px-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $item['is_visible'] ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-700'; ?>">
                                <?php echo $item['is_visible'] ? 'Visible' : 'Hidden'; ?>
                            </span>
                        </td>
                        <td class="py-2 px-4 text-right">
                            <a href="admin_portfolio_edit.php?id=<?php echo $item['id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium mr-4">Edit</a>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this item? This cannot be undone.');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php
include 'admin_footer.php';
?>
