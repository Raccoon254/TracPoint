<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TrackPoint - Modern Asset Management by kenTom</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        @keyframes blob {
            0% { transform: scale(1) translate(0, 0); }
            33% { transform: scale(1.1) translate(30px, -50px); }
            66% { transform: scale(0.9) translate(-20px, 20px); }
            100% { transform: scale(1) translate(0, 0); }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-blob {
            animation: blob 15s infinite;
        }

        .btn-gradient {
            background: linear-gradient(45deg, #059669, #0d9488);
            color: white;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-gradient::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }

        .feature-card {
            transition: all 0.5s;
            cursor: pointer;
        }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .stats-counter {
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .content-wrapper {
            position: relative;
            z-index: 2;
        }

        .testimonial-card {
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: scale(1.05);
        }

        .tech-stack-icon {
            transition: all 0.3s ease;
            filter: grayscale(100%);
            opacity: 0.7;
            width: 70px;
            cursor: pointer;
        }

        .tech-stack-icon:hover {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.1);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex flex-col relative">
<div id="particles-js"></div>

<!-- Navigation -->
<nav class="fixed w-full z-50 bg-white/80 backdrop-blur-lg shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <span class="text-2xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">TrackPoint</span>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-gray-600 hover:text-emerald-600 transition-colors">Features</a>
                <a href="#testimonials" class="text-gray-600 hover:text-emerald-600 transition-colors">Testimonials</a>
                <a href="#pricing" class="text-gray-600 hover:text-emerald-600 transition-colors">Pricing</a>
                <a href="/login" class="text-emerald-600 font-semibold">Login</a>
                <a href="/register" class="btn-gradient px-6 py-2">Get Started</a>
            </div>
        </div>
    </div>
</nav>

<main class="content-wrapper">
    <!-- Hero Section -->
    <section class="relative z-50 pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center space-y-8 relative z-50">
            <div class="absolute inset-0 -z-10">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-teal-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
                <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
                <div class="absolute bottom-1/4 left-1/3 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
            </div>

            <h1 class="text-5xl font-bold tracking-tight text-gray-900 sm:text-6xl md:text-7xl opacity-1" id="hero-title">
                Take Control of Your Assets with
                <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">TrackPoint</span>
            </h1>
            <p class="mt-6 text-xl text-gray-600 max-w-3xl mx-auto opacity-1" id="hero-subtitle">
                Modern asset management solution with full lifecycle tracking, barcode integration,
                and real-time auditing capabilities. A kenTom project.
            </p>
            <div class="flex gap-4 justify-center opacity-0" id="hero-cta">
                <a href="/register" class="btn-gradient px-8 py-4 text-lg">
                    Get Started Free
                </a>
                <a href="#features" class="px-8 py-4 text-lg font-semibold text-emerald-600 hover:text-emerald-500 transition-colors">
                    Learn More →
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-emerald-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="stats-card bg-white p-8 rounded-2xl shadow-lg">
                    <div class="text-4xl font-bold text-emerald-600 stats-counter" data-target="1000">0</div>
                    <p class="text-gray-600 mt-2">Active Users</p>
                </div>
                <div class="stats-card bg-white p-8 rounded-2xl shadow-lg">
                    <div class="text-4xl font-bold text-emerald-600 stats-counter" data-target="50000">0</div>
                    <p class="text-gray-600 mt-2">Assets Tracked</p>
                </div>
                <div class="stats-card bg-white p-8 rounded-2xl shadow-lg">
                    <div class="text-4xl font-bold text-emerald-600 stats-counter" data-target="99">0</div>
                    <p class="text-gray-600 mt-2">Customer Satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Powerful Asset Management Features</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Everything you need to manage physical assets effectively
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Smart Tracking -->
            <div class="feature-card bg-white/40 backdrop-blur-sm p-8 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Smart Tracking</h3>
                <p class="text-gray-600">
                    AI-powered asset tracking with predictive maintenance alerts and real-time location updates.
                </p>
            </div>

            <!-- Barcode Integration -->
            <div class="feature-card bg-white/40 backdrop-blur-sm p-8 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-emerald-600"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Barcode Integration</h3>
                <p class="text-gray-600">
                    Seamless barcode scanning and generation with mobile app support for quick asset identification.
                </p>
            </div>

            <!-- Audit Management -->
            <div class="feature-card bg-white/40 backdrop-blur-sm p-8 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-emerald-600"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Audit Management</h3>
                <p class="text-gray-600">
                    Comprehensive audit trails with photo documentation and compliance reporting features.
                </p>
            </div>

            <!-- Maintenance Scheduling -->
            <div class="feature-card bg-white/40 backdrop-blur-sm p-8 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Maintenance Scheduling</h3>
                <p class="text-gray-600">
                    Automated maintenance scheduling with smart notifications and service history tracking.
                </p>
            </div>

            <!-- Department Management -->
            <div class="feature-card bg-white/40 backdrop-blur-sm p-8 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-emerald-600"><path d="M12.034 12.681a.498.498 0 0 1 .647-.647l9 3.5a.5.5 0 0 1-.033.943l-3.444 1.068a1 1 0 0 0-.66.66l-1.067 3.443a.5.5 0 0 1-.943.033z"/><path d="M5 3a2 2 0 0 0-2 2"/><path d="M19 3a2 2 0 0 1 2 2"/><path d="M5 21a2 2 0 0 1-2-2"/><path d="M9 3h1"/><path d="M9 21h2"/><path d="M14 3h1"/><path d="M3 9v1"/><path d="M21 9v2"/><path d="M3 14v1"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Department Management</h3>
                <p class="text-gray-600">
                    Hierarchical department structure with role-based access control and asset allocation.
                </p>
            </div>

            <!-- Analytics Dashboard -->
            <div class="feature-card bg-white/40 backdrop-blur-sm p-8 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-emerald-600"><path d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z"/><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Analytics Dashboard</h3>
                <p class="text-gray-600">
                    Advanced analytics with customizable reports and real-time asset utilization insights.
                </p>
            </div>
        </div>
    </section>

    <!-- Technology Stack -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center mb-16">Built with Modern Technology</h2>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-8 items-center justify-items-center">
                <!-- Tech stack icons with hover effects -->

                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/laravel/laravel-original.svg"
                        alt="Laravel" class="tech-stack-icon" />
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/livewire/livewire-original.svg" alt="Livewire" class="tech-stack-icon" />

                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/tailwindcss/tailwindcss-original.svg" alt="Tailwind CSS" class="tech-stack-icon" />

                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/alpinejs/alpinejs-original.svg" alt="Alpine.js" class="tech-stack-icon" />


                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/github/github-original.svg" alt="GitHub" class="tech-stack-icon" />

                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/amazonwebservices/amazonwebservices-original-wordmark.svg" alt="AWS" class="tech-stack-icon" />
            </div>
        </div>
    </section>
</main>

<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">TrackPoint</h3>
                <p class="text-gray-400">A kenTom project revolutionizing asset management.</p>
            </div>
            <!-- Add more footer sections... -->
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            © 2024 TrackPoint by kenTom. All rights reserved.
        </div>
    </div>
</footer>

<script>
    // Initialize particles.js
    particlesJS('particles-js', {
        particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: '#10b981' },
            shape: { type: 'circle' },
            opacity: { value: 0.5, random: false },
            size: { value: 3, random: true },
            line_linked: { enable: true, distance: 150, color: '#10b981', opacity: 0.4, width: 1 },
            move: { enable: true, speed: 3, direction: 'none', random: false, straight: false, out_mode: 'out', bounce: false }
        },
        interactivity: {
            detect_on: 'canvas',
            events: {
                onhover: { enable: true, mode: 'repulse' },
                onclick: { enable: true, mode: 'push' },
                resize: true
            },
            modes: {
                repulse: { distance: 100, duration: 0.4 },
                push: { particles_nb: 4 }
            }
        },
        retina_detect: true
    });

    // GSAP Animations
    gsap.registerPlugin(ScrollTrigger);

    // Hero section animations
    gsap.from("#hero-title", {
        opacity: 0,
        y: 50,
        duration: 1,
        delay: 0.5
    });

    gsap.from("#hero-subtitle", {
        opacity: 0,
        y: 30,
        duration: 1,
        delay: 0.8
    });

    gsap.from("#hero-cta", {
        opacity: 0,
        y: 30,
        duration: 1,
        delay: 1.1
    });

    // Feature cards animation
    gsap.utils.toArray('.feature-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: "top bottom-=100",
                toggleActions: "play none none reverse"
            },
            opacity: 0,
            y: 50,
            duration: 0.6,
            delay: i * 0.2
        });
    });

    // Stats counter animation
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = value.toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // Trigger stats animation when in view
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                animateValue(counter, 0, target, 2000);
                statsObserver.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stats-counter').forEach(counter => {
        statsObserver.observe(counter);
    });

    // Testimonial cards animation
    gsap.utils.toArray('.testimonial-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: "top bottom-=50",
                toggleActions: "play none none reverse"
            },
            opacity: 0,
            x: i % 2 === 0 ? -50 : 50,
            duration: 0.8,
            delay: i * 0.2
        });
    });

    // Tech stack icons animation
    gsap.utils.toArray('.tech-stack-icon').forEach((icon, i) => {
        gsap.from(icon, {
            scrollTrigger: {
                trigger: icon,
                start: "top bottom-=50",
                toggleActions: "play none none reverse"
            },
            opacity: 0,
            scale: 0.5,
            duration: 0.5,
            delay: i * 0.1
        });
    });

    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Navbar scroll effect
    const navbar = document.querySelector('nav');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        if (currentScroll <= 0) {
            navbar.classList.remove('shadow-lg');
            return;
        }

        if (currentScroll > lastScroll) {
            // Scrolling down
            navbar.classList.add('shadow-lg');
        } else {
            // Scrolling up
            navbar.classList.remove('shadow-lg');
        }

        lastScroll = currentScroll;
    });
</script>
</body>
</html>
