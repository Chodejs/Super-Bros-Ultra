<?php
// admin_settings.php
session_start();
require_once 'db_connect.php'; // For session validation consistency
require_once 'settings_loader.php'; // To load current settings

// Authentication and authorization checks
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_login.php');
    exit;
}
if ($_SESSION['must_change_password']) {
    header('Location: admin_manage_account.php');
    exit;
}

$pageTitle = "Site Settings - Admin Dashboard";
$feedbackMessage = '';
$feedbackType = '';
$settings_file_path = __DIR__ . '/settings.json';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A little cheeky, but we're basically just taking all POST data
    // and putting it into our settings array.
    $new_settings = $_POST;

    // Convert array to a nicely formatted JSON string
    $json_data = json_encode($new_settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    // Attempt to write the new settings to the file
    if (file_put_contents($settings_file_path, $json_data) !== false) {
        $feedbackMessage = "Site settings updated successfully!";
        $feedbackType = 'success';
        // Reload settings to show the updated values immediately
        $settings = $new_settings;
    } else {
        $feedbackMessage = "Error: Could not write to the settings file. Please check file permissions.";
        $feedbackType = 'error';
    }
}

include 'admin_header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-2xl font-bold text-primary-black mb-6">Manage Site Settings</h1>
        <p class="text-gray-600 mb-6 text-sm">Update the general contact information and social media links that appear across the public website. These changes will be reflected immediately.</p>

        <?php if ($feedbackMessage): ?>
        <div class="mb-6 p-4 rounded-md <?php echo $feedbackType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>" role="alert">
            <?php echo htmlspecialchars($feedbackMessage); ?>
        </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="space-y-6">
            
            <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">Company Information</h2>
            
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                <input type="text" name="company_name" id="company_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($settings['company_name']); ?>">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" name="phone" id="phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($settings['phone']); ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($settings['email']); ?>">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Company Address</label>
                <input type="text" name="address" id="address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($settings['address']); ?>">
            </div>
            <div>
                <label for="service_areas" class="block text-sm font-medium text-gray-700">Service Areas Description</label>
                <textarea name="service_areas" id="service_areas" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange"><?php echo htmlspecialchars($settings['service_areas']); ?></textarea>
            </div>

            <h2 class="text-lg font-semibold text-gray-800 border-b pt-4 pb-2">Social Media</h2>

            <div>
                <label for="facebook_url" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                <input type="url" name="facebook_url" id="facebook_url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($settings['facebook_url']); ?>">
            </div>
             <div>
                <label for="instagram_url" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                <input type="url" name="instagram_url" id="instagram_url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($settings['instagram_url']); ?>">
            </div>
             <div>
                <label for="tiktok_url" class="block text-sm font-medium text-gray-700">TikTok URL</label>
                <input type="url" name="tiktok_url" id="tiktok_url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange" value="<?php echo htmlspecialchars($settings['tiktok_url']); ?>">
            </div>


            <div class="flex justify-end pt-4">
                <button type="submit" class="btn bg-accent-orange hover:bg-orange-600 text-primary-black font-bold py-2 px-6 rounded-md">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
