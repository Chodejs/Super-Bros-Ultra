<?php
$pageTitle = "Portfolio - Super Brothers LLC | Nashville Construction Projects";
$metaDescription = "View our portfolio of completed commercial and residential drywall, painting, and flooring projects by Super Brothers LLC in Nashville and surrounding areas. See our quality craftsmanship.";
include 'header.php';
require 'db_connect.php'; // Connect to the database

// Initialize an empty array for portfolio items
$portfolioItems = [];
$fetchError = '';

try {
    // Prepare and execute the query to fetch only visible portfolio items
    // Ordered by the 'display_order' field first, then by creation date
    $query = "SELECT title, category, description, image_url, thumb_url FROM portfolio WHERE is_visible = 1 ORDER BY display_order ASC, created_at DESC";
    $result = $db->query($query);
    
    // Fetch all rows into the array
    while ($row = $result->fetch_assoc()) {
        $portfolioItems[] = $row;
    }
} catch (Exception $e) {
    // Log the error for debugging and set a user-friendly message
    error_log("Public portfolio fetch error: " . $e->getMessage());
    $fetchError = "We're currently unable to load our project portfolio. Please check back soon.";
}

// --- Filtering Logic (now operates on the data fetched from DB) ---

// Get all unique categories from the database results
$allIndividualCategories = [];
foreach ($portfolioItems as $item) {
    $cats = explode(',', $item['category']);
    foreach ($cats as $cat) {
        $trimmedCat = trim($cat);
        if (!empty($trimmedCat) && !in_array($trimmedCat, $allIndividualCategories)) {
            $allIndividualCategories[] = $trimmedCat;
        }
    }
}
sort($allIndividualCategories);

// Handle the category filter from the URL
$filterCategory = $_GET['category'] ?? 'all';
$filteredItems = $portfolioItems;
if ($filterCategory !== 'all') {
    $filteredItems = array_filter($portfolioItems, function($item) use ($filterCategory) {
        // Check if the item's category string contains the filter category
        return stripos($item['category'], $filterCategory) !== false;
    });
}

?>
<style>
    /* Basic lightbox styles - these should remain functional */
    .lightbox {
        display: none; position: fixed; z-index: 10000; padding-top: 50px;
        left: 0; top: 0; width: 100%; height: 100%; overflow: auto;
        background-color: rgba(0,0,0,0.9);
    }
    .lightbox-content {
        margin: auto; display: block; width: 90%; max-width: 800px;
        max-height: 85vh; object-fit: contain;
    }
    .lightbox-caption {
        margin: 10px auto; display: block; width: 80%; max-width: 800px;
        text-align: center; color: #ccc; padding: 10px 0; font-size: 0.9rem;
    }
    .lightbox-close {
        position: absolute; top: 15px; right: 35px; color: #f1f1f1;
        font-size: 40px; font-weight: bold; transition: 0.3s; cursor: pointer;
    }
    .lightbox-close:hover, .lightbox-close:focus { color: #bbb; text-decoration: none; }
    .portfolio-item img { cursor: pointer; }
</style>

<div class="bg-background-light">
    <section class="page-header py-16 bg-primary-black text-text-white text-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-bold animate-on-scroll">Our Portfolio</h1>
            <p class="text-lg md:text-xl mt-4 text-gray-300 animate-on-scroll" data-delay="200">A Showcase of Our Quality Workmanship & Dedication.</p>
        </div>
    </section>

    <section class="content-section py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if (!$fetchError && !empty($portfolioItems)): ?>
            <div class="flex flex-wrap justify-center gap-2 mb-12 animate-on-scroll">
                <a href="portfolio.php?category=all" class="filter-btn <?php echo ($filterCategory === 'all') ? 'bg-accent-orange text-primary-black' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?> py-2 px-4 rounded-md font-semibold transition-colors">All Projects</a>
                <?php foreach ($allIndividualCategories as $cat): ?>
                    <a href="portfolio.php?category=<?php echo urlencode($cat); ?>" class="filter-btn <?php echo ($filterCategory === $cat) ? 'bg-accent-orange text-primary-black' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?> py-2 px-4 rounded-md font-semibold transition-colors">
                        <?php echo htmlspecialchars($cat); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($fetchError): ?>
                 <p class="text-center text-red-600 text-xl py-10"><?php echo $fetchError; ?></p>
            <?php elseif (empty($filteredItems)): ?>
                <p class="text-center text-gray-600 text-xl py-10">No projects found for this category. Please try another filter or check back soon!</p>
            <?php else: ?>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($filteredItems as $index => $item): ?>
                    <div class="portfolio-item bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 group animate-on-scroll" data-delay="<?php echo $index * 100; ?>">
                        <div class="relative">
                            <img src="<?php echo htmlspecialchars($item['thumb_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?> - by Super Brothers LLC" 
                                 class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105"
                                 onclick="openLightbox('<?php echo htmlspecialchars($item['image_url']); ?>', '<?php echo htmlspecialchars(addslashes($item['title'])); ?>')">
                            <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-10 transition-opacity duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <i class="fas fa-search-plus fa-3x text-white"></i>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-primary-black mb-2"><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p class="text-sm text-accent-orange font-medium mb-3"><?php echo htmlspecialchars($item['category']); ?></p>
                            <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($item['description']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <div id="myLightbox" class="lightbox" onclick="closeLightboxOutside(event)">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <img class="lightbox-content" id="lightboxImg" src="#" alt="Enlarged portfolio image by Super Brothers LLC">
        <div id="lightboxCaption" class="lightbox-caption"></div>
    </div>

    <section class="content-section py-16 bg-primary-black text-text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-6 animate-on-scroll">Inspired by Our Work?</h2>
            <p class="text-lg mb-8 max-w-xl mx-auto text-gray-300 animate-on-scroll" data-delay="200">
                Let Super Brothers LLC bring this level of quality and dedication to your next project.
            </p>
            <a href="contact.php" class="btn bg-accent-orange hover:bg-orange-600 text-primary-black text-lg font-bold py-3 px-10 rounded-lg shadow-lg transition-transform transform hover:scale-105 animate-on-scroll" data-delay="400">
                Get Your Free Estimate
            </a>
        </div>
    </section>
</div>

<script>
    // Lightbox functionality (no changes needed here)
    const lightbox = document.getElementById('myLightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxCaption = document.getElementById('lightboxCaption');

    function openLightbox(imageUrl, captionText) {
        if (!lightbox || !lightboxImg || !lightboxCaption) return;
        lightboxImg.src = imageUrl;
        lightboxCaption.innerHTML = captionText;
        lightbox.style.display = "block";
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        if (!lightbox) return;
        lightbox.style.display = "none";
        document.body.style.overflow = 'auto';
    }

    function closeLightboxOutside(event) {
        if (event.target === lightbox) {
            closeLightbox();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape" && lightbox && lightbox.style.display === "block") {
            closeLightbox();
        }
    });
</script>

<?php include 'footer.php'; ?>
