<?php
session_start();

// This is the security check. If the user is not logged in, redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_login.php');
    exit;
}

$pageTitle = "Admin Dashboard - Super Brothers LLC";
include 'admin_header.php';
?>

<div class="bg-gray-100">
    <section class="page-header py-12 bg-primary-black text-text-white text-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold">Admin Dashboard</h1>
            <p class="text-lg md:text-xl mt-2 text-gray-300">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        </div>
    </section>

    <section class="content-section py-12 md:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-primary-black mb-6">Management Tools</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Placeholder for future management panels -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-xl font-semibold mb-3 text-primary-black">Manage Portfolio</h3>
                    <p class="text-gray-600 mb-4">Add, edit, or remove projects from the portfolio page.</p>
                    <a href="admin_portfolio_edit.php" class="text-accent-orange font-semibold hover:underline">Click Here!<i class="fas fa-arrow-right text-xs ml-1"></i></a>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-xl font-semibold mb-3 text-primary-black">View Contact Submissions</h3>
                    <p class="text-gray-600 mb-4">See all messages submitted through the website's contact form.</p>
                    <a href="admin_submissions.php" class="text-accent-orange font-semibold hover:underline">Click Here! <i class="fas fa-arrow-right text-xs ml-1"></i></a>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-xl font-semibold mb-3 text-primary-black">Update Reviews</h3>
                    <p class="text-gray-600 mb-4">Manage client testimonials and reviews displayed on the site.</p>
                    <a href="#" class="text-accent-orange font-semibold hover:underline">Coming Soon <i class="fas fa-arrow-right text-xs ml-1"></i></a>
                </div>
            </div>
            
            <div class="text-center mt-12">
                 <a href="admin_logout.php" class="btn bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-md">
                    <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                </a>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>
