<?php
session_start();
require 'db_connect.php';

$pageTitle = "Contact Super Brothers LLC - Free Estimate for Construction Services";
$metaDescription = "Get in touch with Super Brothers LLC for a free estimate on your drywall, painting, or flooring project in Nashville and surrounding areas. Call or email us today!";
include 'header.php';

$formMessage = '';
$formError = false;
$formSubmittedSuccessfully = false;

// Define the recipient email address for notifications
$recipient_email = "chris@maracentral.com";
$from_email = "contactform@superbrothersllc.com"; // Example

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $project_type = trim($_POST['project_type'] ?? '');
    $message_text = trim($_POST['message'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($message_text) || empty($project_type)) {
        $formMessage = "Please fill out all required fields (Name, Email, Project Type, Message).";
        $formError = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formMessage = "Invalid email format. Please provide a valid email address.";
        $formError = true;
    } else {
        // --- 1. ATTEMPT TO SAVE TO DATABASE ---
        $db_success = false;
        if (isset($db) && $db instanceof mysqli) {
            try {
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $stmt = $db->prepare("INSERT INTO contact_submissions (name, email, phone, project_type, message, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $name, $email, $phone, $project_type, $message_text, $ip_address);
                $stmt->execute();
                $db_success = true;
                $stmt->close();
            } catch (Exception $e) {
                // Database operation failed. Log it and set a specific user-facing error.
                $db_error_message = $e->getMessage();
                error_log("Contact Form DB Error: " . $db_error_message);
                $formMessage = "Sorry, there was a database error. Your message could not be saved. Please contact us directly. (Dev Info: " . htmlspecialchars($db_error_message) . ")";
                $formError = true;
            }
        } else {
            // Database connection object wasn't available.
            error_log("Contact Form Error: Database connection object not available.");
            $formMessage = "Sorry, the site is experiencing a configuration issue and cannot save your submission. Please contact us directly.";
            $formError = true;
        }

        // --- 2. ATTEMPT TO SEND EMAIL (only if DB was successful) ---
        if ($db_success) {
            $email_subject = "New Contact Form Submission from " . htmlspecialchars($name);
            $email_body = "You have received a new message from your website contact form:\n\n" .
                          "Name: " . htmlspecialchars($name) . "\n" .
                          "Email: " . htmlspecialchars($email) . "\n" .
                          (!empty($phone) ? "Phone: " . htmlspecialchars($phone) . "\n" : "") .
                          "Project Type: " . htmlspecialchars($project_type) . "\n" .
                          "Message:\n" . htmlspecialchars($message_text) . "\n\n" .
                          "Submitted from IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
            $headers = "From: Super Brothers LLC Form <" . $from_email . ">\r\n" .
                       "Reply-To: " . htmlspecialchars($email) . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();

            if (mail($recipient_email, $email_subject, $email_body, $headers)) {
                // Email sent successfully
                $formMessage = "Thank you, " . htmlspecialchars($name) . "! Your message has been received. We'll be in touch soon.";
                $formSubmittedSuccessfully = true;
                $_POST = []; // Clear form fields
            } else {
                // Email failed, but DB succeeded. This is a partial success.
                error_log("Contact Form Error: mail() function failed.");
                $formMessage = "Thank you, " . htmlspecialchars($name) . "! Your message was saved to our system, but a notification email could not be sent. We will get back to you shortly.";
                $formSubmittedSuccessfully = true; // Still a success for the user.
                $_POST = []; // Clear form fields
            }
        }
        // If $db_success was false, the error message is already set from the DB catch block.
    }
}
?>

<div class="bg-background-light">
    <section class="page-header py-16 bg-primary-black text-text-white text-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-bold animate-on-scroll">Contact Us</h1>
            <p class="text-lg md:text-xl mt-4 text-gray-300 animate-on-scroll" data-delay="200">We're Ready to Build Your Vision. Let's Talk!</p>
        </div>
    </section>

    <section class="content-section py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12">
                <div class="bg-white p-8 md:p-10 rounded-xl shadow-2xl animate-on-scroll">
                    <h2 class="text-2xl md:text-3xl font-bold text-primary-black mb-6">Send Us a Message</h2>
                    
                    <?php if (!empty($formMessage)): ?>
                        <div class="mb-6 p-4 rounded-md <?php echo ($formError) ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>" role="alert">
                            <?php echo $formMessage; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!$formSubmittedSuccessfully): ?>
                    <form id="contactForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#contactForm" method="POST" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-accent-orange">*</span></label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange sm:text-sm" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-accent-orange">*</span></label>
                            <input type="email" name="email" id="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange sm:text-sm" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number (Optional)</label>
                            <input type="tel" name="phone" id="phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange sm:text-sm" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                        </div>

                        <div>
                            <label for="project_type" class="block text-sm font-medium text-gray-700">Project Type <span class="text-accent-orange">*</span></label>
                            <select id="project_type" name="project_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange sm:text-sm">
                                <option value="" disabled <?php echo empty($_POST['project_type']) ? 'selected' : ''; ?>>Select a service...</option>
                                <option value="Drywall" <?php echo (($_POST['project_type'] ?? '') == 'Drywall' || ($_GET['service'] ?? '') == 'drywall') ? 'selected' : ''; ?>>Drywall Installation/Repair</option>
                                <option value="Painting" <?php echo (($_POST['project_type'] ?? '') == 'Painting' || ($_GET['service'] ?? '') == 'painting') ? 'selected' : ''; ?>>Interior/Exterior Painting</option>
                                <option value="Flooring" <?php echo (($_POST['project_type'] ?? '') == 'Flooring' || ($_GET['service'] ?? '') == 'flooring') ? 'selected' : ''; ?>>Flooring Installation</option>
                                <option value="Multiple Services" <?php echo ($_POST['project_type'] ?? '') == 'Multiple Services' ? 'selected' : ''; ?>>Multiple Services</option>
                                <option value="General Inquiry" <?php echo ($_POST['project_type'] ?? '') == 'General Inquiry' ? 'selected' : ''; ?>>General Inquiry</option>
                                <option value="Other" <?php echo ($_POST['project_type'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message / Project Details <span class="text-accent-orange">*</span></label>
                            <textarea id="message" name="message" rows="5" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-accent-orange focus:border-accent-orange sm:text-sm"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>

                        <div>
                            <button type="submit" class="w-full btn bg-accent-orange hover:bg-orange-600 text-primary-black font-bold py-3 px-4 rounded-md shadow-md transition duration-300">
                                Send Message
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>

                <div class="animate-on-scroll" data-delay="200">
                    <h2 class="text-2xl md:text-3xl font-bold text-primary-black mb-6">Contact Information</h2>
                    <p class="text-gray-700 mb-8">
                        We're here to answer any questions you may have about our services or to discuss your next project. Reach out to us via phone, email, or by filling out the contact form. We serve Nashville and surrounding areas.
                    </p>
                    <div class="space-y-6">
                         <div class="flex items-start">
                            <i class="fas fa-building fa-lg text-accent-orange mt-1 mr-4"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-primary-black">Our Address</h3>
                                <p class="text-gray-600">8608 Lebanon Rd, Mt. Juliet, TN 37122</p>
                                </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt fa-lg text-accent-orange mt-1 mr-4"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-primary-black">Service Areas</h3>
                                <p class="text-gray-600">Nashville, Brentwood, Green Hills, Governor Clubs, Inglewood, West Meadow, Hermitage, Gallatin, Henderson, and more.</p>
                                </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-phone fa-lg text-accent-orange mt-1 mr-4"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-primary-black">Call Us</h3>
                                <a href="tel:+16158910749" class="text-gray-600 hover:text-accent-orange transition-colors">615-891-0749</a>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-envelope fa-lg text-accent-orange mt-1 mr-4"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-primary-black">Email Us</h3>
                                <a href="mailto:superbrothersllc@gmail.com" class="text-gray-600 hover:text-accent-orange transition-colors">superbrothersllc@gmail.com</a>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-clock fa-lg text-accent-orange mt-1 mr-4"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-primary-black">Business Hours</h3>
                                <p class="text-gray-600">Monday - Friday: 7:00 AM - 5:00 PM</p>
                                <p class="text-gray-600">Saturday: 10:00 AM - 2:00 PM</p>
                                <p class="text-gray-600">Sunday: Closed</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10">
                        <h3 class="text-lg font-semibold text-primary-black mb-3">Follow Us</h3>
                        <div class="flex space-x-4">
                            <a href="https://www.facebook.com/superbrothersllc" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-accent-orange transition-colors duration-300" aria-label="Facebook">
                                <i class="fab fa-facebook-f fa-2x"></i>
                            </a>
                            <a href="https://www.instagram.com/superbrothersllc" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-accent-orange transition-colors duration-300" aria-label="Instagram">
                                <i class="fab fa-instagram fa-2x"></i>
                            </a>
                            <a href="https://www.tiktok.com/@superbrothersllc?lang=en" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-accent-orange transition-colors duration-300" aria-label="TikTok">
                                <i class="fab fa-tiktok fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="map-section animate-on-scroll">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d206169.7178962695!2d-86.9784963992529!3d36.18642195808433!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x886466db23507649%3A0x735c5b903c5d8000!2sNashville%2C%2D%2DTN!5e0!3m2!1sen!2sus!4v1684889890000!5m2!1sen!2sus" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy"
            title="Map of Nashville, TN - Service Area for Super Brothers LLC"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </section>
</div>

<?php include 'footer.php'; ?>
