<?php
$pageTitle = "Reviews & Testimonials - Super Brothers LLC";
$metaDescription = "Read what our satisfied clients have to say about Super Brothers LLC. Real testimonials for our drywall, painting, and flooring services in the Nashville area.";
include 'header.php';
require 'db_connect.php';

$reviews = [];
$fetchError = '';

try {
    $query = "SELECT author, project_type, review_text, rating, created_at FROM reviews WHERE is_visible = 1 ORDER BY display_order ASC, created_at DESC";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
} catch (Exception $e) {
    error_log("Public reviews fetch error: " . $e->getMessage());
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
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col animate-on-scroll" data-delay="<?php echo $index * 100; ?>">
                        <div class="mb-4">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i < $review['rating'] ? 'text-accent-orange' : 'text-gray-300'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-gray-700 italic mb-4 flex-grow">"<?php echo htmlspecialchars($review['review_text']); ?>"</p>
                        <div class="mt-auto border-t pt-4">
                            <p class="font-semibold text-primary-black"><?php echo htmlspecialchars($review['author']); ?></p>
                            <?php if (!empty($review['project_type'])): ?>
                                <p class="text-sm text-gray-500">Project: <?php echo htmlspecialchars($review['project_type']); ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-400">Reviewed on: <?php echo date("F Y", strtotime($review['created_at'])); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
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
