<?php
// header.php
// Page title is set dynamically in each individual PHP file before including this header.
// Example: $pageTitle = "Super Brothers LLC - Welcome";
if (!isset($pageTitle)) {
    $pageTitle = "Super Brothers LLC"; // Default title
}
if (!isset($metaDescription)) {
    $metaDescription = "Super Brothers LLC - Your trusted experts for drywall, painting, and flooring services in Nashville and surrounding areas."; // Default meta description
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Tailwind config (minimal for the color variables, can be expanded)
        // This is usually in a tailwind.config.js file if you're using a build process
        // For CDN use, you can extend it like this, but it's more limited.
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                'primary-black': 'var(--color-primary-black, #1a1a1a)',
                'accent-orange': 'var(--color-accent-orange, #ff6600)',
                'text-white': 'var(--color-text-white, #f8f9fa)',
                'background-light': 'var(--color-background-light, #ffffff)',
                'text-dark': 'var(--color-text-dark, #333333)',
                'custom-gray': {
                    100: '#f7fafc',
                    // ...
                    700: '#4a5568', // Added for mobile menu text
                    800: '#2d3748',
                    900: '#1a202c',
                }
              },
              fontFamily: {
                sans: ['Inter', 'sans-serif'],
              }
            }
          }
        }
    </script>
</head>
<body class="flex flex-col min-h-screen font-sans bg-background-light text-text-dark">

    <header class="bg-primary-black text-text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <a href="index.php" class="text-2xl sm:text-3xl font-bold hover:text-accent-orange transition-colors duration-300">
                        Super Brothers LLC
                    </a>
                </div>

                <nav class="hidden md:flex space-x-4 lg:space-x-6 items-center">
                    <a href="index.php" class="hover:text-accent-orange px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300">Home</a>
                    <a href="about.php" class="hover:text-accent-orange px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300">About Us</a>
                    <a href="services.php" class="hover:text-accent-orange px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300">Services</a>
                    <a href="portfolio.php" class="hover:text-accent-orange px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300">Portfolio</a>
                    <a href="reviews.php" class="hover:text-accent-orange px-3 py-2 rounded-md text-sm font-medium transition-colors duration-300">Reviews</a>
                    <a href="contact.php" class="bg-accent-orange hover:bg-orange-600 text-primary-black px-4 py-2 rounded-md text-sm font-bold transition-colors duration-300 shadow-md">Contact Us</a>
                </nav>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="tel:+16158910749" class="hover:text-accent-orange transition-colors duration-300 flex items-center" aria-label="Call us">
                        <i class="fas fa-phone mr-2"></i> <span class="text-sm">615-891-0749</span>
                    </a>
                    <a href="https://www.facebook.com/superbrothersllc" target="_blank" rel="noopener noreferrer" class="hover:text-accent-orange transition-colors duration-300" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/superbrothersllc" target="_blank" rel="noopener noreferrer" class="hover:text-accent-orange transition-colors duration-300" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.tiktok.com/@superbrothersllc?lang=en" target="_blank" rel="noopener noreferrer" class="hover:text-accent-orange transition-colors duration-300" aria-label="TikTok">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>

                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-custom-gray-400 hover:text-text-white hover:bg-custom-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="index.php" class="text-custom-gray-300 hover:bg-custom-gray-700 hover:text-text-white block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="about.php" class="text-custom-gray-300 hover:bg-custom-gray-700 hover:text-text-white block px-3 py-2 rounded-md text-base font-medium">About Us</a>
                <a href="services.php" class="text-custom-gray-300 hover:bg-custom-gray-700 hover:text-text-white block px-3 py-2 rounded-md text-base font-medium">Services</a>
                <a href="portfolio.php" class="text-custom-gray-300 hover:bg-custom-gray-700 hover:text-text-white block px-3 py-2 rounded-md text-base font-medium">Portfolio</a>
                <a href="reviews.php" class="text-custom-gray-300 hover:bg-custom-gray-700 hover:text-text-white block px-3 py-2 rounded-md text-base font-medium">Reviews</a>
                <a href="contact.php" class="bg-accent-orange hover:bg-orange-600 text-primary-black block px-3 py-2 rounded-md text-base font-bold shadow-md text-center">Contact Us</a>
            </div>
            <div class="pt-4 pb-3 border-t border-custom-gray-700">
                <div class="flex items-center justify-center mb-3 px-5">
                     <a href="tel:+16158910749" class="text-custom-gray-300 hover:text-accent-orange transition-colors duration-300 flex items-center text-base" aria-label="Call us">
                        <i class="fas fa-phone mr-2"></i> <span>615-891-0749</span>
                    </a>
                </div>
                <div class="flex items-center justify-center space-x-5">
                    <a href="https://www.facebook.com/superbrothersllc" target="_blank" rel="noopener noreferrer" class="text-custom-gray-300 hover:text-accent-orange transition-colors duration-300" aria-label="Facebook">
                        <i class="fab fa-facebook-f fa-lg"></i>
                    </a>
                    <a href="https://www.instagram.com/superbrothersllc" target="_blank" rel="noopener noreferrer" class="text-custom-gray-300 hover:text-accent-orange transition-colors duration-300" aria-label="Instagram">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                    <a href="https://www.tiktok.com/@superbrothersllc?lang=en" target="_blank" rel="noopener noreferrer" class="text-custom-gray-300 hover:text-accent-orange transition-colors duration-300" aria-label="TikTok">
                        <i class="fab fa-tiktok fa-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        