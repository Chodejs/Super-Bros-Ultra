// scripts.js

document.addEventListener('DOMContentLoaded', function() {
    console.log("Audin's Construction Co. scripts loaded successfully!");

    // Example: Smooth scrolling for anchor links (if you add any)
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    for (let anchor of smoothScrollLinks) {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    }

    // Mobile menu toggle (if not handled by inline script in footer.php)
    // This is a more robust way if you move the script from footer.php
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuButton && mobileMenu) {
        const openIcon = menuButton.querySelector('svg.block'); // Assuming first SVG is open
        const closeIcon = menuButton.querySelector('svg.hidden'); // Assuming second SVG is close

        menuButton.addEventListener('click', () => {
            const isExpanded = menuButton.getAttribute('aria-expanded') === 'true' || false;
            menuButton.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');

            if (openIcon && closeIcon) { // Check if icons exist
                 openIcon.classList.toggle('hidden');
                 openIcon.classList.toggle('block');
                 closeIcon.classList.toggle('hidden');
                 closeIcon.classList.toggle('block');
            }
        });
    }


    // Optional: Intersection Observer for animations on scroll
    // Example: Fade in elements as they enter viewport
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    if (animatedElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target); // Optional: stop observing once animated
                }
            });
        }, { threshold: 0.1 }); // Trigger when 10% of the element is visible

        animatedElements.forEach(el => {
            observer.observe(el);
        });
    }
    // Add a CSS class for this:
    // .animate-on-scroll { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
    // .animate-on-scroll.is-visible { opacity: 1; transform: translateY(0); }


    // Basic form validation feedback example (can be greatly expanded)
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            let isValid = true;
            const requiredFields = contactForm.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    // You would typically show an error message next to the field
                    field.style.borderColor = 'red'; // Simple visual feedback
                    console.warn(`Field ${field.name || field.id} is required.`);
                } else {
                    field.style.borderColor = ''; // Reset border color
                }
            });

            const emailField = contactForm.querySelector('input[type="email"]');
            if (emailField && emailField.value.trim() && !isValidEmail(emailField.value.trim())) {
                isValid = false;
                emailField.style.borderColor = 'red';
                console.warn('Invalid email format.');
                // Add user-facing error message here
            }


            if (!isValid) {
                event.preventDefault(); // Prevent form submission
                alert('Please fill out all required fields correctly.'); // Replace with a nicer modal/message
            } else {
                // Optionally, you can add a "submitting..." state here
                console.log('Form submitted (simulated).');
                // For a real submission, you'd use Fetch API or XMLHttpRequest
            }
        });
    }

    function isValidEmail(email) {
        // Basic email validation regex
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

});
