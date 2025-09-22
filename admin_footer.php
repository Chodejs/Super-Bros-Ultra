<?php
// admin_footer.php
?>
    </main>
    <footer class="bg-white mt-auto py-4 border-t">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            &copy; <?php echo date("Y"); ?> Super Brothers LLC Admin Portal.
        </div>
    </footer>
    <script>
        // Script for the admin mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('admin-menu-button');
            const mobileMenu = document.getElementById('admin-mobile-menu');
            const openIcon = document.getElementById('admin-open-icon');
            const closeIcon = document.getElementById('admin-close-icon');

            if (menuButton && mobileMenu) {
                menuButton.addEventListener('click', () => {
                    const isExpanded = menuButton.getAttribute('aria-expanded') === 'true';
                    menuButton.setAttribute('aria-expanded', !isExpanded);
                    mobileMenu.classList.toggle('hidden');
                    if(openIcon && closeIcon){
                        openIcon.classList.toggle('hidden');
                        closeIcon.classList.toggle('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
