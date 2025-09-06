<?php
// admin_dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: admin_login.php'); exit; }
if (isset($_SESSION['must_change_password']) && $_SESSION['must_change_password'] === true) {
    header('Location: admin_manage_account.php'); exit;
}
$pageTitle = "Admin Dashboard - Super Brothers LLC";
$username = $_SESSION['username'] ?? 'Admin';
include 'admin_header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
        <h1 class="text-3xl font-bold text-primary-black mb-2">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p class="text-gray-600">This is your central hub for managing the website's content. Use the links below or the navigation bar above to get started.</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Portfolio Management Card -->
        <a href="admin_portfolio.php" class="block bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
            <div class="flex items-center">
                <div class="bg-accent-orange text-primary-black rounded-lg p-3 mr-4"><i class="fas fa-images fa-2x"></i></div>
                <div>
                    <h2 class="text-xl font-bold text-primary-black">Manage Portfolio</h2>
                    <p class="text-gray-600 text-sm">Add, edit, or delete project showcases.</p>
                </div>
            </div>
        </a>
        <!-- Contact Submissions Card -->
        <a href="admin_submissions.php" class="block bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
            <div class="flex items-center">
                <div class="bg-teal-500 text-white rounded-lg p-3 mr-4"><i class="fas fa-inbox fa-2x"></i></div>
                <div>
                    <h2 class="text-xl font-bold text-primary-black">View Submissions</h2>
                    <p class="text-gray-600 text-sm">See all messages from the contact form.</p>
                </div>
            </div>
        </a>
        <!-- Reviews Management Card -->
        <a href="admin_reviews.php" class="block bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
            <div class="flex items-center">
                <div class="bg-yellow-500 text-white rounded-lg p-3 mr-4"><i class="fas fa-star fa-2x"></i></div>
                <div>
                    <h2 class="text-xl font-bold text-primary-black">Manage Reviews</h2>
                    <p class="text-gray-600 text-sm">Add, edit, and display client testimonials.</p>
                </div>
            </div>
        </a>
        <!-- Manage Account Card -->
         <a href="admin_manage_account.php" class="block bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
            <div class="flex items-center">
                <div class="bg-blue-500 text-white rounded-lg p-3 mr-4"><i class="fas fa-user-cog fa-2x"></i></div>
                <div>
                    <h2 class="text-xl font-bold text-primary-black">Manage Your Account</h2>
                    <p class="text-gray-600 text-sm">Update your password or account details.</p>
                </div>
            </div>
        </a>
    </div>
</div>

<?php include 'admin_footer.php'; ?>

