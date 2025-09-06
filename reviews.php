<?php
$pageTitle = "Reviews & Testimonials - Audin's Construction Co.";
$metaDescription = "Read what our satisfied clients have to say about Audin's Construction Co. Real testimonials and reviews for our drywall, painting, and flooring services.";
include 'header.php';

// Placeholder for reviews data. In a real application, this would come from a database or API.
$reviews = [
    [
        'author' => 'John B. - Homeowner',
        'date' => 'April 15, 2024',
        'rating' => 5, // 1 to 5 stars
        'text' => "Audin's Construction Co. transformed our outdated living room with new drywall and a fantastic paint job. The team was professional, punctual, and incredibly tidy. The quality of work is outstanding. We couldn't be happier!",
        'project' => 'Living Room Renovation (Drywall & Painting)',
        'source' => 'Website Submission' // or 'Google Review', 'Yelp', etc.
    ],
    [
        'author' => 'Sarah L. - Small Business Owner',
        'date' => 'March 22, 2024',
        'rating' => 5,
        'text' => "We hired Audin's for a complete flooring overhaul in our retail space. They helped us choose the perfect durable material and completed the installation quickly with minimal disruption to our business. The new floors look amazing and are holding up great. Highly recommend!",
        'project' => 'Commercial Flooring Installation',
        'source' => 'Google Review'
    ],
    [
        'author' => 'Mike & Emily P.',
        'date' => 'February 10, 2024',
        'rating' => 4,
        'text' => "Good quality work on our drywall repair and interior painting. There was a slight delay due to weather affecting an exterior portion, but communication was good throughout. The final result is very professional.",
        'project' => 'Drywall Repair & Full Interior Painting',
        'source' => 'Email Feedback'
    ],
    [
        'author' => 'David K. - Property Manager',
        'date' => 'January 05, 2024',
        'rating' => 5,
        'text' => "Audin's is our go-to for all tenant unit turnovers. They are reliable, efficient, and always deliver quality painting and flooring work. Makes our job much easier knowing we can count on them.",
        'project' => 'Apartment Unit Turnover (Painting & Flooring)',
        'source' => 'Direct Referral'
    ]
];

?>

<div class="bg-gray-100">
    <section class="page-header py-16 bg-primary-black text-text-white text-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-bold animate-on-scroll"><?php echo htmlspecialchars($pageTitle); ?></h1>
            <p class="text-lg md:text-xl mt-4 text-gray-300 animate-on-scroll" data-delay="200">Hear From Our Satisfied Clients.</p>
        </div>
    </section>

    <section class="content-section py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-primary-black text-center mb-12 animate-on-scroll">Client Experiences</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($reviews as $index => $review): ?>
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col animate-on-scroll" data-delay="<?php echo $index * 100; ?>">
                    <div class="mb-4">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <i class="fas fa-star <?php echo $i < $review['rating'] ? 'text-accent-orange' : 'text-gray-300'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="text-gray-700 italic mb-4 flex-grow">"<?php echo htmlspecialchars($review['text']); ?>"</p>
                    <div class="mt-auto border-t pt-4">
                        <p class="font-semibold text-primary-black"><?php echo htmlspecialchars($review['author']); ?></p>
                        <p class="text-sm text-gray-500">Project: <?php echo htmlspecialchars($review['project']); ?></p>
                        <p class="text-xs text-gray-400">Date: <?php echo htmlspecialchars($review['date']); ?> | Source: <?php echo htmlspecialchars($review['source']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-16 pt-10 border-t border-gray-300 text-center animate-on-scroll">
                <h3 class="text-2xl font-semibold text-primary-black mb-6">Find Us on Google</h3>
                <p class="text-gray-700 mb-6">
                    We value your feedback! Check out more reviews or leave your own on our Google Business Profile.
                </p>
                <div class="bg-white p-6 rounded-lg shadow-md inline-block">
                    <p class="text-lg font-medium">Audin's Construction Co. on Google</p>
                    <div class="my-2">
                        <span class="text-yellow-500 text-2xl">★★★★★</span> <span class="ml-2 text-gray-600">(Based on X reviews)</span>
                    </div>
                    <a href="#" target="_blank" rel="noopener noreferrer" class="btn bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md inline-flex items-center">
                        <i class="fab fa-google mr-2"></i> View Google Reviews
                    </a>
                </div>
                <p class="mt-4 text-sm text-gray-500">(Google Reviews widget/link to be embedded here)</p>
            </div>
        </div>
    </section>

    <section class="content-section py-16 bg-accent-orange text-primary-black">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-6 animate-on-scroll">Had a Great Experience?</h2>
            <p class="text-lg mb-8 max-w-xl mx-auto animate-on-scroll" data-delay="200">
                We'd love to hear about your project with Audin's Construction Co. Your feedback helps us grow and helps others make informed decisions.
            </p>
            <a href="contact.php?subject=ReviewSubmission" class="btn bg-primary-black hover:bg-gray-800 text-text-white text-lg font-bold py-3 px-10 rounded-lg shadow-lg transition-transform transform hover:scale-105 animate-on-scroll" data-delay="400">
                Leave a Review
            </a>
        </div>
    </section>

</div>

<?php include 'footer.php'; ?>
