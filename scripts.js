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

    // Mobile menu toggle
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuButton && mobileMenu) {
        const openIcon = menuButton.querySelector('svg.block');
        const closeIcon = menuButton.querySelector('svg.hidden');

        menuButton.addEventListener('click', () => {
            const isExpanded = menuButton.getAttribute('aria-expanded') === 'true' || false;
            menuButton.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');

            if (openIcon && closeIcon) {
                 openIcon.classList.toggle('hidden');
                 openIcon.classList.toggle('block');
                 closeIcon.classList.toggle('hidden');
                 closeIcon.classList.toggle('block');
            }
        });
    }


    // Intersection Observer for animations on scroll
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    if (animatedElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        animatedElements.forEach(el => {
            observer.observe(el);
        });
    }


    // Basic form validation feedback example
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            let isValid = true;
            const requiredFields = contactForm.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'red';
                    console.warn(`Field ${field.name || field.id} is required.`);
                } else {
                    field.style.borderColor = '';
                }
            });

            const emailField = contactForm.querySelector('input[type="email"]');
            if (emailField && emailField.value.trim() && !isValidEmail(emailField.value.trim())) {
                isValid = false;
                emailField.style.borderColor = 'red';
                console.warn('Invalid email format.');
            }


            if (!isValid) {
                event.preventDefault();
                // A non-blocking notification would be better in a real app
                // alert('Please fill out all required fields correctly.');
            }
        });
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // --- NEW & IMPROVED PORTFOLIO LIGHTBOX LOGIC ---
    const lightbox = document.getElementById('myLightbox');
    
    // Run this logic only if we are on a page with a lightbox (i.e., portfolio.php)
    if (lightbox) {
        const lightboxImg = document.getElementById('lightboxImg');
        const lightboxCaption = document.getElementById('lightboxCaption');
        const portfolioLinks = document.querySelectorAll('.portfolio-link');
        const closeButton = lightbox.querySelector('.lightbox-close');

        function openLightbox(imageUrl, captionText) {
            if (!lightboxImg || !lightboxCaption) return;
            lightboxImg.src = imageUrl;
            lightboxCaption.innerHTML = captionText;
            lightbox.style.display = "block";
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeLightbox() {
            lightbox.style.display = "none";
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        portfolioLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const imageUrl = this.getAttribute('href');
                const captionText = this.dataset.caption || '';
                openLightbox(imageUrl, captionText);
            });
        });

        if (closeButton) {
            closeButton.addEventListener('click', closeLightbox);
        }

        lightbox.addEventListener('click', function(event) {
            // Close lightbox if the dark background is clicked, but not the image itself
            if (event.target === lightbox) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape" && lightbox.style.display === "block") {
                closeLightbox();
            }
        });
    }
    // --- END LIGHTBOX LOGIC ---

});
