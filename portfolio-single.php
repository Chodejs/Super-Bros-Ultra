<?php
// portfolio-single.php
include 'header.php';
require 'db_connect.php';

$project = null;
$fetchError = '';

// 1. Get and validate the project ID from the URL
$projectId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($projectId <= 0) {
    $fetchError = "Invalid project ID.";
} else {
    try {
        // 2. Fetch the specific project from the database
        $stmt = $db->prepare("SELECT * FROM portfolio WHERE id = ? AND is_visible = 1");
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $project = $result->fetch_assoc();
            // Dynamically set the page title and meta description
            $pageTitle = htmlspecialchars($project['title']) . " - Super Brothers LLC Portfolio";
            $metaDescription = "Details of the " . htmlspecialchars($project['title']) . " project, a " . htmlspecialchars($project['category']) . " job by Super Brothers LLC. " . htmlspecialchars(substr($project['description'], 0, 120)) . "...";
        } else {
            $fetchError = "Project not found. It may have been removed or is no longer visible.";
        }
    } catch (Exception $e) {
        error_log("Single portfolio fetch error: " . $e->getMessage());
        $fetchError = "An error occurred while trying to load the project details.";
    }
}

// Update the <title> tag after the header has been included.
if ($project && !$fetchError) {
    echo "<script>document.title = " . json_encode($pageTitle) . ";</script>";
}
?>
<style>
    /* Styles for the new lightbox on this page */
    .lightbox { display: none; position: fixed; z-index: 10000; padding-top: 50px; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.9); }
    .lightbox-content { margin: auto; display: block; width: 90%; max-width: 900px; max-height: 85vh; object-fit: contain; }
    .lightbox-caption { margin: 10px auto; display: block; width: 80%; max-width: 800px; text-align: center; color: #ccc; padding: 10px 0; font-size: 1rem; }
    .lightbox-close { position: absolute; top: 15px; right: 35px; color: #f1f1f1; font-size: 40px; font-weight: bold; transition: 0.3s; cursor: pointer; }
    .lightbox-close:hover, .lightbox-close:focus { color: #bbb; text-decoration: none; }
    .project-image { cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .project-image:hover { transform: scale(1.03); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
</style>

<div class="bg-background-light">
    <?php if ($fetchError): ?>
        <section class="page-header py-16 bg-primary-black text-text-white text-center">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl md:text-5xl font-bold">Error</h1>
            </div>
        </section>
        <section class="content-section py-12 md:py-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-red-600 text-xl py-10"><?php echo $fetchError; ?></p>
                <a href="portfolio.php" class="btn bg-accent-orange hover:bg-orange-600 text-primary-black font-semibold py-2 px-6 rounded-md">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Portfolio
                </a>
            </div>
        </section>
    <?php elseif ($project): ?>
        <section class="page-header py-16 bg-primary-black text-text-white text-center">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-accent-orange font-semibold mb-2 animate-on-scroll"><?php echo htmlspecialchars($project['category']); ?></p>
                <h1 class="text-4xl md:text-5xl font-bold animate-on-scroll" data-delay="100"><?php echo htmlspecialchars($project['title']); ?></h1>
            </div>
        </section>

        <section class="content-section py-12 md:py-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
                <div class="bg-white p-8 md:p-10 rounded-xl shadow-2xl">
                    <div class="prose max-w-none">
                        <h2 class="text-2xl font-bold text-primary-black mb-4">Project Overview</h2>
                        <?php 
                            $description_to_show = !empty(trim($project['long_description'])) ? $project['long_description'] : $project['description'];
                        ?>
                        <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($description_to_show)); ?></p>
                    </div>

                    <?php if (!empty($project['before_image_url']) || !empty($project['after_image_url'])): ?>
                    <div class="mt-12">
                        <h3 class="text-2xl font-bold text-primary-black mb-6 text-center">Project Transformation</h3>
                        <div class="grid md:grid-cols-2 gap-8">
                            <?php if (!empty($project['before_image_url'])): ?>
                            <div class="text-center animate-on-scroll">
                                <h4 class="text-xl font-semibold text-gray-700 mb-3">Before</h4>
                                <img src="<?php echo htmlspecialchars($project['before_image_url']); ?>" alt="Before photo of the <?php echo htmlspecialchars($project['title']); ?> project" class="rounded-lg shadow-lg w-full project-image" onclick="openLightbox('<?php echo htmlspecialchars($project['before_image_url']); ?>', 'Before: <?php echo htmlspecialchars(addslashes($project['title'])); ?>')">
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($project['after_image_url'])): ?>
                             <div class="text-center animate-on-scroll" data-delay="200">
                                <h4 class="text-xl font-semibold text-gray-700 mb-3">After</h4>
                                <img src="<?php echo htmlspecialchars($project['after_image_url']); ?>" alt="After photo of the <?php echo htmlspecialchars($project['title']); ?> project" class="rounded-lg shadow-lg w-full project-image" onclick="openLightbox('<?php echo htmlspecialchars($project['after_image_url']); ?>', 'After: <?php echo htmlspecialchars(addslashes($project['title'])); ?>')">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-12">
                    <a href="portfolio.php" class="btn bg-gray-700 hover:bg-gray-800 text-white font-semibold py-3 px-8 rounded-md">
                        <i class="fas fa-arrow-left mr-2"></i> View All Projects
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

     <section class="content-section py-16 bg-primary-black text-text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-6 animate-on-scroll">Inspired by This Project?</h2>
            <p class="text-lg mb-8 max-w-xl mx-auto text-gray-300 animate-on-scroll" data-delay="200">
                Let's bring the same level of quality and dedication to your home or business.
            </p>
            <a href="contact.php" class="btn bg-accent-orange hover:bg-orange-600 text-primary-black text-lg font-bold py-3 px-10 rounded-lg shadow-lg transition-transform transform hover:scale-105 animate-on-scroll" data-delay="400">
                Get Your Free Estimate
            </a>
        </div>
    </section>
</div>

<!-- Lightbox Modal HTML -->
<div id="myLightbox" class="lightbox">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightboxImg" src="#" alt="Enlarged project image">
    <div id="lightboxCaption" class="lightbox-caption"></div>
</div>

<?php include 'footer.php'; ?>

