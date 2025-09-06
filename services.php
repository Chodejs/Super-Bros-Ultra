<?php
$pageTitle = "Our Services - Audin's Construction Co.";
$metaDescription = "Explore the range of construction services offered by Audin's Construction Co., including expert drywall, professional painting, and quality flooring installation.";
include 'header.php';
require 'db_connect.php';?>

<div class="bg-background-light">
    <section class="page-header py-16 bg-primary-black text-text-white text-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-bold animate-on-scroll"><?php echo htmlspecialchars($pageTitle); ?></h1>
            <p class="text-lg md:text-xl mt-4 text-gray-300 animate-on-scroll" data-delay="200">Delivering Excellence in Every Detail.</p>
        </div>
    </section>

    <section id="drywall" class="content-section py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll">
                    <img src="https://placehold.co/600x400/ffcc99/1a1a1a?text=Drywall+Installation" alt="Professional drywall installation" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="animate-on-scroll" data-delay="200">
                    <h2 class="text-3xl font-bold text-primary-black mb-4"><i class="fas fa-hammer text-accent-orange mr-3"></i>Drywall Installation & Repair</h2>
                    <p class="text-gray-700 mb-4">
                        Whether you're building a new home, renovating an existing space, or need to repair damaged drywall, Audin's Construction Co. provides top-tier drywall services. We ensure perfectly smooth, durable walls and ceilings, ready for the next stage of your project.
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                        <li>New construction drywall hanging</li>
                        <li>Drywall taping and mudding (Levels 1-5 finish)</li>
                        <li>Texture application (popcorn, orange peel, knockdown, custom)</li>
                        <li>Drywall repair (holes, cracks, water damage)</li>
                        <li>Soundproofing and insulation integration</li>
                        <li>Ceiling installation and repair</li>
                    </ul>
                    <a href="contact.php?service=drywall" class="btn bg-accent-orange hover:bg-orange-600 text-primary-black font-semibold py-2 px-6 rounded-md">Get a Quote for Drywall</a>
                </div>
            </div>
        </div>
    </section>

    <section id="painting" class="content-section py-12 md:py-20 bg-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="order-last md:order-first animate-on-scroll" data-delay="200">
                    <h2 class="text-3xl font-bold text-primary-black mb-4"><i class="fas fa-paint-roller text-accent-orange mr-3"></i>Interior & Exterior Painting</h2>
                    <p class="text-gray-700 mb-4">
                        Transform your property with our professional painting services. We use high-quality paints and meticulous techniques to deliver a flawless finish that enhances curb appeal and interior aesthetics. Our team handles everything from prep work to final cleanup.
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                        <li>Comprehensive interior painting (walls, ceilings, trim)</li>
                        <li>Exterior painting for all types of siding and surfaces</li>
                        <li>Color consultation and selection assistance</li>
                        <li>Surface preparation (cleaning, sanding, priming)</li>
                        <li>Cabinet refinishing and painting</li>
                        <li>Deck and fence staining/painting</li>
                    </ul>
                    <a href="contact.php?service=painting" class="btn bg-accent-orange hover:bg-orange-600 text-primary-black font-semibold py-2 px-6 rounded-md">Request Painting Services</a>
                </div>
                <div class="order-first md:order-last animate-on-scroll">
                    <img src="https://placehold.co/600x400/ff9966/1a1a1a?text=Professional+Painting" alt="House being professionally painted" class="rounded-lg shadow-xl w-full">
                </div>
            </div>
        </div>
    </section>

    <section id="flooring" class="content-section py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll">
                    <img src="https://placehold.co/600x400/ffad33/1a1a1a?text=Flooring+Installation" alt="New flooring being installed" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="animate-on-scroll" data-delay="200">
                    <h2 class="text-3xl font-bold text-primary-black mb-4"><i class="fas fa-layer-group text-accent-orange mr-3"></i>Flooring Installation</h2>
                    <p class="text-gray-700 mb-4">
                        Upgrade your space with beautiful, durable flooring installed by our experts. We handle a wide variety of flooring materials, ensuring precise installation for a long-lasting and attractive finish.
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                        <li>Hardwood flooring installation and refinishing</li>
                        <li>Laminate and vinyl plank (LVP/LVT) installation</li>
                        <li>Tile installation (ceramic, porcelain, stone) for floors and backsplashes</li>
                        <li>Carpet installation and removal</li>
                        <li>Subfloor preparation and leveling</li>
                        <li>Trim and molding installation</li>
                    </ul>
                    <a href="contact.php?service=flooring" class="btn bg-accent-orange hover:bg-orange-600 text-primary-black font-semibold py-2 px-6 rounded-md">Plan Your New Floors</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section py-12 md:py-20 bg-gray-100 text-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-primary-black mb-6 animate-on-scroll">Need Something Else?</h2>
            <p class="text-lg text-gray-700 mb-8 max-w-2xl mx-auto animate-on-scroll" data-delay="200">
                While drywall, painting, and flooring are our specialties, we also undertake various other interior finishing and light remodeling tasks. Don't hesitate to reach out with your specific project needs.
            </p>
            <a href="contact.php" class="btn bg-primary-black hover:bg-gray-800 text-text-white text-lg font-bold py-3 px-10 rounded-lg shadow-lg transition-transform transform hover:scale-105 animate-on-scroll" data-delay="400">
                Discuss Your Custom Project
            </a>
        </div>
    </section>

</div>

<?php include 'footer.php'; ?>
