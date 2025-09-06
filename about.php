<?php
$pageTitle = "About Super Brothers LLC - Nashville's Trusted Contractors";
$metaDescription = "Learn more about Super Brothers LLC, a family-owned construction company in Nashville, TN, with 17 years of experience. We specialize in drywall, painting, and flooring, valuing hard work, professionalism, and quality. Licensed and Insured.";
include 'header.php';
?>

<div class="bg-gray-100">
    <section class="page-header py-16 bg-primary-black text-text-white text-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-bold animate-on-scroll"><?php echo htmlspecialchars($pageTitle); ?></h1>
            <p class="text-lg md:text-xl mt-4 text-gray-300 animate-on-scroll" data-delay="200">Building with Integrity, Skill, and Passion for Over 17 Years.</p>
        </div>
    </section>

    <section class="content-section py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="animate-on-scroll">
                    <img src="https://placehold.co/600x400/ff6600/1a1a1a?text=Super+Brothers+Team" alt="Audin and the Super Brothers LLC team working on a project" class="rounded-lg shadow-2xl w-full">
                </div>
                <div class="animate-on-scroll" data-delay="200">
                    <h2 class="text-3xl md:text-4xl font-bold text-primary-black mb-6">Our Story</h2>
                    <p class="text-lg text-gray-700 mb-4">
                        Super Brothers LLC is a proud, family-owned company founded on the core values of hard work, professionalism, and an unwavering commitment to producing the highest quality service and workmanship. With over 17 years of dedicated experience in the construction industry, Audin, our founder, established Super Brothers LLC to provide exceptional contracting services you can trust.
                    </p>
                    <p class="text-gray-700 mb-4">
                        We specialize in commercial and residential drywall, interior/exterior painting, and flooring. Our journey has been one of continuous growth, adapting to new techniques, and always prioritizing client satisfaction. We are fully Licensed and Insured, so you can rest assured you are working with top professionals.
                    </p>
                    <p class="text-gray-700">
                        Serving Nashville, Brentwood, Green Hills, Governor Clubs, Inglewood, West Meadow, Hermitage, Gallatin, Henderson, and surrounding areas, we are committed to upholding the highest standards in every job we undertake. We don't cut corners, ensuring your project is completed on time and to your utmost satisfaction.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section py-12 md:py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-primary-black mb-12 animate-on-scroll">Our Mission & Core Values</h2>
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="p-6 animate-on-scroll">
                    <div class="text-accent-orange mb-4">
                        <i class="fas fa-hard-hat fa-3x"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-3 text-primary-black">Professionalism & Hard Work</h3>
                    <p class="text-gray-600">
                        As a family-owned business, we instill a strong work ethic and professional conduct in every aspect of our operations, ensuring a respectful and efficient service.
                    </p>
                </div>
                <div class="p-6 animate-on-scroll" data-delay="200">
                    <div class="text-accent-orange mb-4">
                        <i class="fas fa-medal fa-3x"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-3 text-primary-black">Highest Quality Workmanship</h3>
                    <p class="text-gray-600">
                        With our experienced experts, you can expect top results. We use high-quality materials and proven techniques to ensure durable and aesthetically pleasing outcomes. No cutting corners!
                    </p>
                </div>
                <div class="p-6 animate-on-scroll" data-delay="400">
                    <div class="text-accent-orange mb-4">
                        <i class="fas fa-handshake fa-3x"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-3 text-primary-black">Client-Focused & On Time</h3>
                    <p class="text-gray-600">
                        Your needs and vision are our priority. We work with you, communicate transparently, and ensure your project is completed on time and to your complete satisfaction.
                    </p>
                </div>
            </div>
             <p class="text-center text-gray-700 mt-10 animate-on-scroll" data-delay="500">
                Super Brothers LLC is <span class="font-semibold">Fully Licensed and Insured</span>.
            </p>
        </div>
    </section>

    <section class="content-section py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-primary-black mb-12 animate-on-scroll">Meet Our Core Team</h2>
            <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-center">
                <div class="bg-white rounded-lg shadow-lg p-6 text-center animate-on-scroll md:col-span-1 lg:col-start-2 lg:col-span-1"> {/* Centering Audin if he's the only one listed */}
                    <img src="https://placehold.co/200x200/cccccc/333333?text=Audin" alt="Audin - Founder of Super Brothers LLC" class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-accent-orange">
                    <h3 class="text-xl font-semibold text-primary-black">Audin</h3>
                    <p class="text-accent-orange">Founder & Lead Contractor</p>
                    <p class="text-sm text-gray-600 mt-2">
                        With a passion for precision and a commitment to excellence cultivated over 17 years, Audin leads every project with hands-on expertise and dedication to client satisfaction.
                    </p>
                </div>
                <!-- Placeholder for additional team members - uncomment and modify as needed
                <div class="bg-white rounded-lg shadow-lg p-6 text-center animate-on-scroll" data-delay="200">
                    <img src="https://placehold.co/200x200/cccccc/333333?text=Team+Member" alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-accent-orange">
                    <h3 class="text-xl font-semibold text-primary-black">[Team Member Name]</h3>
                    <p class="text-accent-orange">[Role/Specialty]</p>
                    <p class="text-sm text-gray-600 mt-2">
                        [Brief description of their expertise and contribution to the team.]
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6 text-center animate-on-scroll" data-delay="400">
                    <img src="https://placehold.co/200x200/cccccc/333333?text=Team+Member" alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-accent-orange">
                    <h3 class="text-xl font-semibold text-primary-black">[Team Member Name]</h3>
                    <p class="text-accent-orange">[Role/Specialty]</p>
                    <p class="text-sm text-gray-600 mt-2">
                        [Brief description of their expertise and contribution to the team.]
                    </p>
                </div>
                -->
            </div>
        </div>
    </section>

    <section class="content-section py-16 bg-accent-orange text-primary-black">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-6 animate-on-scroll">Partner With Super Brothers LLC</h2>
            <p class="text-lg mb-8 max-w-xl mx-auto animate-on-scroll" data-delay="200">
                Experience the Super Brothers LLC difference. Let's build something great together. Our team of professionals is here to assist you with every project you have in mind.
            </p>
            <a href="contact.php" class="btn bg-primary-black hover:bg-gray-800 text-text-white text-lg font-bold py-3 px-10 rounded-lg shadow-lg transition-transform transform hover:scale-105 animate-on-scroll" data-delay="400">
                Request a Free Estimate
            </a>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>

