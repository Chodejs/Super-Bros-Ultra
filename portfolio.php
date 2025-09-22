<?php
$pageTitle = "Portfolio - Super Brothers LLC | Nashville Construction Projects";
$metaDescription = "View our portfolio of completed commercial and residential drywall, painting, and flooring projects by Super Brothers LLC in Nashville and surrounding areas. See our quality craftsmanship.";
include 'header.php';
require 'db_connect.php'; // Connect to the database

// Initialize an empty array for portfolio items
$portfolioItems = [];
$fetchError = '';

try {
    // We now need the ID to link to the single project page
    $query = "SELECT id, title, category, description, image_url, thumb_url FROM portfolio WHERE is_visible = 1 ORDER BY display_order ASC, created_at DESC";
    $result = $db->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $portfolioItems[] = $row;
    }
} catch (Exception $e) {
    error_log("Public portfolio fetch error: " . $e->getMessage());
    $fetchError = "We're currently unable to load our project portfolio. Please check back soon.";
}

// --- Filtering Logic ---
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

$filterCategory = $_GET['category'] ?? 'all';
$filteredItems = $portfolioItems;
if ($filterCategory !== 'all') {
    $filteredItems = array_filter($portfolioItems, function($item) use ($filterCategory) {
        return stripos($item['category'], $filterCategory) !== false;
    });
}

?>

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
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col hover:shadow-2xl transition-shadow duration-300 group animate-on-scroll" data-delay="<?php echo $index * 100; ?>">
                        <div class="relative">
                            <img src="<?php echo htmlspecialchars($item['thumb_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?> - by Super Brothers LLC" 
                                 class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105">
                            <a href="portfolio-single.php?id=<?php echo $item['id']; ?>" class="absolute inset-0" aria-label="View details for <?php echo htmlspecialchars($item['title']); ?>"></a>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-xl font-semibold text-primary-black mb-2">
                                <a href="portfolio-single.php?id=<?php echo $item['id']; ?>" class="hover:text-accent-orange transition-colors"><?php echo htmlspecialchars($item['title']); ?></a>
                            </h3>
                            <p class="text-sm text-accent-orange font-medium mb-3"><?php echo htmlspecialchars($item['category']); ?></p>
                            <p class="text-gray-600 text-sm mb-4 flex-grow"><?php echo htmlspecialchars($item['description']); ?></p>
                            <div class="mt-auto">
                                <a href="portfolio-single.php?id=<?php echo $item['id']; ?>" class="font-semibold text-accent-orange hover:underline">
                                    View Project Details <i class="fas fa-arrow-right text-xs ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

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

<?php include 'footer.php'; ?>

