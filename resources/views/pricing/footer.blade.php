<!-- Simple Copyright Footer -->
    <footer class="bg-white border-t border-gray-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-600 text-sm">
                    © {{ date('Y') }} Walive. All rights reserved. | 
                    <a href="{{route('privacy-policy')}}" class="text-green-600 hover:text-green-800">Privacy Policy</a> | 
                    <a href="/terms" class="text-green-600 hover:text-green-800">Terms of Service</a>
                </p>
                <p class="text-gray-500 text-xs mt-2">WhatsApp is a trademark of WhatsApp LLC. Walive is not affiliated with WhatsApp.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>