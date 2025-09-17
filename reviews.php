<?php
$pageTitle = "Reviews & Testimonials - Super Brothers LLC";
$metaDescription = "Read what our satisfied clients have to say about Super Brothers LLC. Real testimonials for our drywall, painting, and flooring services in the Nashville area.";
include 'header.php';
require 'db_connect.php';

$reviews = [];
$fetchError = '';

// --- PAGINATION LOGIC ---
$reviewsPerPage = 9; // Display 9 reviews for a perfect 3x3 grid
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
}

try {
    // 1. Get the total number of visible reviews
    $totalResult = $db->query("SELECT COUNT(id) as total FROM reviews WHERE is_visible = 1");
    $totalReviews = $totalResult->fetch_assoc()['total'];
    $totalPages = ceil($totalReviews / $reviewsPerPage);

    // Redirect if user tries to access a page that doesn't exist
    if ($currentPage > $totalPages && $totalPages > 0) {
        header('Location: reviews.php?page=' . $totalPages);
        exit;
    }

    // 2. Calculate the offset for the SQL query
    $offset = ($currentPage - 1) * $reviewsPerPage;

    // 3. Fetch only the reviews for the current page
    $stmt = $db->prepare("SELECT author, project_type, title, review_text, rating, created_at, author_image_url, project_image_url, google_review_url FROM reviews WHERE is_visible = 1 ORDER BY display_order ASC, created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $reviewsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
} catch (Exception $e) {
    error_log("Public reviews fetch error with pagination: " . $e->getMessage());
    $fetchError = "We're currently unable to load client reviews. Please check back soon.";
}
?>

<div class="bg-gray-100">
    <section class="page-header py-16 bg-primary-black text-text-white text-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-bold animate-on-scroll">Client Testimonials</h1>
            <p class="text-lg md:text-xl mt-4 text-gray-300 animate-on-scroll" data-delay="200">Hear From Our Satisfied Clients.</p>
        </div>
    </section>

    <section class="content-section py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-primary-black text-center mb-12 animate-on-scroll">What Our Customers Are Saying</h2>
            
            <?php if ($fetchError): ?>
                <p class="text-center text-red-600 text-xl py-10"><?php echo $fetchError; ?></p>
            <?php elseif (empty($reviews)): ?>
                <p class="text-center text-gray-600 text-xl py-10">We are currently gathering testimonials. Please check back soon to see what our clients have to say about our work!</p>
            <?php else: ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($reviews as $index => $review): ?>
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col animate-on-scroll overflow-hidden" data-delay="<?php echo $index * 100; ?>">
                        
                        <?php if (!empty($review['project_image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($review['project_image_url']); ?>" alt="Photo of the project for <?php echo htmlspecialchars($review['author']); ?>'s review" class="w-full h-64 object-cover">
                        <?php endif; ?>
                        
                        <div class="p-6 flex flex-col flex-grow">
                             
                             <?php if (!empty($review['title'])): ?>
                                <h3 class="text-xl font-bold text-primary-black mb-2"><?php echo htmlspecialchars($review['title']); ?></h3>
                            <?php endif; ?>

                            <div class="mb-4">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i < $review['rating'] ? 'text-accent-orange' : 'text-gray-300'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-700 italic mb-6 flex-grow">"<?php echo htmlspecialchars($review['review_text']); ?>"</p>
                            
                            <div class="mt-auto border-t pt-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="h-10 w-10 rounded-full object-cover flex-shrink-0" src="<?php echo !empty($review['author_image_url']) ? htmlspecialchars($review['author_image_url']) : 'https://placehold.co/100x100/cccccc/333333?text=Client'; ?>" alt="<?php echo htmlspecialchars($review['author']); ?>'s profile picture">
                                    <div class="ml-3">
                                        <p class="font-semibold text-primary-black leading-tight"><?php echo htmlspecialchars($review['author']); ?></p>
                                        <?php if (!empty($review['project_type'])): ?>
                                            <p class="text-sm text-gray-500 leading-tight">Project: <?php echo htmlspecialchars($review['project_type']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if (!empty($review['google_review_url'])): ?>
                                    <a href="<?php echo htmlspecialchars($review['google_review_url']); ?>" target="_blank" rel="noopener noreferrer" title="View original review on Google" class="text-gray-500 hover:text-blue-600 transition-colors">
                                        <i class="fab fa-google fa-xl"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- --- PAGINATION LINKS --- -->
                <?php if ($totalPages > 1): ?>
                <nav class="flex justify-center items-center mt-12 pt-8 border-t" aria-label="Page navigation">
                    <ul class="inline-flex items-center -space-x-px">
                        <!-- Previous Page Link -->
                        <?php if ($currentPage > 1): ?>
                        <li>
                            <a href="reviews.php?page=<?php echo $currentPage - 1; ?>" class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!-- Page Number Links -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                             <a href="reviews.php?page=<?php echo $i; ?>" class="py-2 px-4 leading-tight border border-gray-300 <?php echo $i === $currentPage ? 'text-primary-black bg-orange-100 font-bold' : 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700'; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <!-- Next Page Link -->
                        <?php if ($currentPage < $totalPages): ?>
                         <li>
                            <a href="reviews.php?page=<?php echo $currentPage + 1; ?>" class="py-2 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </section>

    <section class="content-section py-16 bg-accent-orange text-primary-black">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-6 animate-on-scroll">Ready to Be Our Next Success Story?</h2>
            <p class="text-lg mb-8 max-w-xl mx-auto animate-on-scroll" data-delay="200">
                We'd love to provide you with the same five-star service our clients rave about. Contact us today for a free, no-obligation estimate on your project.
            </p>
            <a href="contact.php" class="btn bg-primary-black hover:bg-gray-800 text-text-white text-lg font-bold py-3 px-10 rounded-lg shadow-lg transition-transform transform hover:scale-105 animate-on-scroll" data-delay="400">
                Request Your Free Estimate
            </a>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>

