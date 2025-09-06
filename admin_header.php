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
                <nav class="flex items-center space-x-6">
                    <a href="admin_dashboard.php" class="hover:text-accent-orange text-sm font-medium transition-colors">Dashboard</a>
                    <a href="admin_portfolio.php" class="hover:text-accent-orange text-sm font-medium transition-colors">Portfolio</a>
                    <a href="admin_submissions.php" class="hover:text-accent-orange text-sm font-medium transition-colors">Submissions</a>
                    <a href="admin_reviews.php" class="hover:text-accent-orange text-sm font-medium transition-colors">Reviews</a>
                    <a href="admin_manage_account.php" class="hover:text-accent-orange text-sm font-medium transition-colors">My Account</a>
                    <a href="admin_logout.php" class="bg-accent-orange hover:bg-orange-600 text-primary-black px-3 py-1.5 rounded-md text-sm font-bold transition-colors">Logout</a>
                </nav>
            </div>
        </div>
    </header>
    <main class="flex-grow">

