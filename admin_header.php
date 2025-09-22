<?php
// admin_header.php
// A simplified header for the admin section.

// The page title is set in the individual admin pages before including this file.
if (!isset($pageTitle)) {
    $pageTitle = "Admin Area - Super Brothers LLC";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <!-- Prevent search engines from indexing the admin area -->
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Re-using the same Tailwind config for consistency
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                'primary-black': 'var(--color-primary-black, #1a1a1a)',
                'accent-orange': 'var(--color-accent-orange, #ff6600)',
                'text-white': 'var(--color-text-white, #f8f9fa)',
                'background-light': 'var(--color-background-light, #ffffff)',
                'gray-700': '#4a5568',
              }
            }
          }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-primary-black text-text-white shadow-md">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <a href="admin_dashboard.php" class="text-xl font-bold hover:text-accent-orange transition-colors">
                        Super Brothers LLC - Admin
                    </a>
                </div>
                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center space-x-4">
                    <a href="admin_dashboard.php" class="hover:text-accent-orange text-sm font-medium transition-colors">Dashboard</a>
                    <a href="admin_portfolio.php" class="hover:text-accent-orange text-sm font-medium transition-colors">Portfolio</a>
                    <a href="admin_submissions.php" class="hover:text-accent-orange text-sm font-medium transition-colors">Submissions</a>
                    <a href="admin_reviews.php" class="hover:text-accent-orange text-sm font-medium transition-colors">Reviews</a>
                    <a href="admin_settings.php" class="hover:text-accent-orange text-sm font-medium transition-colors">Site Settings</a>
                    <a href="admin_manage_account.php" class="hover:text-accent-orange text-sm font-medium transition-colors">My Account</a>
                    <a href="admin_logout.php" class="bg-accent-orange hover:bg-orange-600 text-primary-black px-3 py-1.5 rounded-md text-sm font-bold transition-colors">Logout</a>
                </nav>
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="admin-menu-button" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="admin-mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!-- Hamburger icon -->
                        <svg id="admin-open-icon" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Close icon -->
                        <svg id="admin-close-icon" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu, shows/hides based on button click -->
        <div id="admin-mobile-menu" class="md:hidden hidden">
            <nav class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="admin_dashboard.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Dashboard</a>
                <a href="admin_portfolio.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Portfolio</a>
                <a href="admin_submissions.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Submissions</a>
                <a href="admin_reviews.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Reviews</a>
                <a href="admin_settings.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Site Settings</a>
                <a href="admin_manage_account.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">My Account</a>
                <a href="admin_logout.php" class="block mt-2 text-center bg-accent-orange hover:bg-orange-600 text-primary-black px-3 py-2 rounded-md text-base font-bold">Logout</a>
            </nav>
        </div>
    </header>
    <main class="flex-grow">
