<!-- Navigation -->
<nav class="fixed w-full z-50 bg-white/80 backdrop-blur-lg shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <span class="text-2xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">TrackPoint</span>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-gray-600 hover:text-emerald-600 transition-colors">Features</a>
                <a href="#testimonials" class="text-gray-600 hover:text-emerald-600 transition-colors">Testimonials</a>
                <a href="#pricing" class="text-gray-600 hover:text-emerald-600 transition-colors">Pricing</a>
                <a href="/login" class="text-emerald-600 font-semibold">Login</a>
                <a href="/register" class="btn-gradient px-6 py-2">Get Started</a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button
                    class="mobile-menu-button p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    onclick="toggleMobileMenu()"
                >
                    <svg
                        class="h-6 w-6 text-gray-600"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white rounded-b-lg shadow-lg">
                <a
                    href="#features"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-gray-50 transition-colors"
                >
                    Features
                </a>
                <a
                    href="#testimonials"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-gray-50 transition-colors"
                >
                    Testimonials
                </a>
                <a
                    href="#pricing"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-gray-50 transition-colors"
                >
                    Pricing
                </a>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <a
                        href="/login"
                        class="block px-3 py-2 rounded-md text-base font-medium text-emerald-600 hover:text-emerald-700 hover:bg-gray-50 transition-colors"
                    >
                        Login
                    </a>
                    <a
                        href="/register"
                        class="block px-3 py-2 mt-1 rounded-md text-base font-medium text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition-colors"
                    >
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    .mobile-menu {
        transform-origin: top;
        transition: transform 0.3s ease-out, opacity 0.2s ease-out;
        opacity: 0;
        transform: scaleY(0);
    }

    .mobile-menu.show {
        opacity: 1;
        transform: scaleY(1);
    }

    @media (max-width: 768px) {
        .mobile-menu-button:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }
    }
</style>

<script>
    function toggleMobileMenu() {
        const mobileMenu = document.querySelector('.mobile-menu');
        mobileMenu.classList.toggle('hidden');

        // Add a small delay to allow the display change to take effect
        setTimeout(() => {
            mobileMenu.classList.toggle('show');
        }, 10);
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        const mobileMenu = document.querySelector('.mobile-menu');
        const mobileMenuButton = document.querySelector('.mobile-menu-button');

        if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
            mobileMenu.classList.remove('show');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
        }
    });

    // Close mobile menu when clicking on a link
    document.querySelectorAll('.mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            const mobileMenu = document.querySelector('.mobile-menu');
            mobileMenu.classList.remove('show');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
        });
    });

    // Handle resize events
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            const mobileMenu = document.querySelector('.mobile-menu');
            mobileMenu.classList.remove('show');
            mobileMenu.classList.add('hidden');
        }
    });
</script>
