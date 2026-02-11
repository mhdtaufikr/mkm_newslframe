<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Base App - Modern Management System')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --app-primary: #475569;
            --app-secondary: #64748b;
            --app-accent: #f8fafc;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
        .bg-app-gradient {
            background: linear-gradient(135deg, var(--app-primary) 0%, #334155 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .page-fade {
            animation: slideUp 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .blob {
            position: absolute;
            width: 400px;
            height: 400px;
            background: var(--app-primary);
            filter: blur(80px);
            opacity: 0.1;
            z-index: -1;
            border-radius: 50%;
        }

        /* ============================================
           🎨 LOADING SCREEN STYLES
        ============================================ */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }

        #loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        /* Animated Background Particles */
        .loading-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 8s infinite ease-in-out;
        }

        .particle:nth-child(1) { width: 80px; height: 80px; top: 10%; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 60px; height: 60px; top: 70%; left: 80%; animation-delay: 1s; }
        .particle:nth-child(3) { width: 100px; height: 100px; top: 40%; left: 70%; animation-delay: 2s; }
        .particle:nth-child(4) { width: 50px; height: 50px; top: 80%; left: 20%; animation-delay: 1.5s; }
        .particle:nth-child(5) { width: 70px; height: 70px; top: 20%; left: 60%; animation-delay: 0.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-30px) rotate(90deg); }
            50% { transform: translateY(0) rotate(180deg); }
            75% { transform: translateY(30px) rotate(270deg); }
        }

        /* Logo Container */
        .loading-logo-container {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        /* Animated Logo */
        .loading-logo {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse-rotate 2s ease-in-out infinite;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .loading-logo svg {
            width: 60px;
            height: 60px;
            color: white;
            animation: bounce-subtle 1.5s ease-in-out infinite;
        }

        @keyframes pulse-rotate {
            0%, 100% { transform: scale(1) rotate(0deg); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); }
            50% { transform: scale(1.05) rotate(5deg); box-shadow: 0 15px 60px rgba(0, 0, 0, 0.5); }
        }

        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Loading Text */
        .loading-text {
            color: white;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: 1px;
            animation: fade-slide 1s ease-in-out infinite alternate;
        }

        .loading-subtext {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        @keyframes fade-slide {
            from { opacity: 0.7; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Spinning Dots Loader */
        .loading-dots {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .loading-dots .dot {
            width: 12px;
            height: 12px;
            background: white;
            border-radius: 50%;
            animation: dot-bounce 1.4s infinite ease-in-out;
        }

        .loading-dots .dot:nth-child(1) { animation-delay: -0.32s; }
        .loading-dots .dot:nth-child(2) { animation-delay: -0.16s; }
        .loading-dots .dot:nth-child(3) { animation-delay: 0s; }

        @keyframes dot-bounce {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1.2); opacity: 1; }
        }

        /* Progress Bar */
        .loading-progress {
            width: 280px;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 40px;
            position: relative;
        }

        .loading-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #64748b, #94a3b8);
            border-radius: 10px;
            animation: progress-flow 1.5s ease-in-out infinite;
            box-shadow: 0 0 15px rgba(148, 163, 184, 0.5);
        }

        @keyframes progress-flow {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }

        /* Gear Animation (Optional Extra) */
        .loading-gear {
            position: absolute;
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: spin 3s linear infinite;
        }

        .loading-gear::before,
        .loading-gear::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        .loading-gear::before { top: -4px; left: 50%; transform: translateX(-50%); }
        .loading-gear::after { bottom: -4px; left: 50%; transform: translateX(-50%); }

        .gear-1 { top: 15%; right: 15%; animation-duration: 2s; }
        .gear-2 { bottom: 15%; left: 15%; animation-duration: 3s; animation-direction: reverse; }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .loading-logo { width: 100px; height: 100px; }
            .loading-logo svg { width: 50px; height: 50px; }
            .loading-text { font-size: 24px; }
            .loading-subtext { font-size: 12px; }
            .loading-progress { width: 220px; }
        }
    </style>

    @stack('styles')
</head>
<body class="text-slate-900 overflow-x-hidden">

    <!-- 🎨 LOADING SCREEN -->
    <div id="loading-screen">
        <!-- Animated Background Particles -->
        <div class="loading-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <!-- Spinning Gears (Decorative) -->
        <div class="loading-gear gear-1"></div>
        <div class="loading-gear gear-2"></div>

        <!-- Main Loading Content -->
        <div class="loading-logo-container">
            <!-- Animated Logo -->
            <div class="loading-logo">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>

            <!-- Loading Text -->
            <h1 class="loading-text">Base App</h1>
            <p class="loading-subtext">Loading...</p>

            <!-- Animated Dots -->
            <div class="loading-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>

            <!-- Progress Bar -->
            <div class="loading-progress">
                <div class="loading-progress-bar"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    @yield('content')
    @include('partials.alert')

    <!-- 🚀 LOADING SCREEN SCRIPT -->
    <script>
        // Hide loading screen when page is fully loaded
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loading-screen');

            // Add a slight delay for smooth transition
            setTimeout(() => {
                loadingScreen.classList.add('hidden');

                // Remove from DOM after animation completes
                setTimeout(() => {
                    loadingScreen.remove();
                }, 500);
            }, 300);
        });

        // Show loading screen on navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Intercept all links
            const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"]):not([download])');

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');

                    // Only show loader for internal navigation
                    if (href && !href.startsWith('http') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
                        // Check if loading screen still exists
                        let loadingScreen = document.getElementById('loading-screen');

                        if (!loadingScreen) {
                            // Recreate loading screen if it was removed
                            loadingScreen = createLoadingScreen();
                            document.body.appendChild(loadingScreen);
                        }

                        // Show loading screen
                        loadingScreen.classList.remove('hidden');
                    }
                });
            });

            // Intercept form submissions
            const forms = document.querySelectorAll('form:not([target="_blank"])');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    let loadingScreen = document.getElementById('loading-screen');

                    if (!loadingScreen) {
                        loadingScreen = createLoadingScreen();
                        document.body.appendChild(loadingScreen);
                    }

                    loadingScreen.classList.remove('hidden');
                });
            });
        });

        // Function to create loading screen HTML
        function createLoadingScreen() {
            const div = document.createElement('div');
            div.id = 'loading-screen';
            div.innerHTML = `
                <div class="loading-particles">
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                </div>
                <div class="loading-gear gear-1"></div>
                <div class="loading-gear gear-2"></div>
                <div class="loading-logo-container">
                    <div class="loading-logo">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h1 class="loading-text">Base App</h1>
                    <p class="loading-subtext">Loading...</p>
                    <div class="loading-dots">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                    <div class="loading-progress">
                        <div class="loading-progress-bar"></div>
                    </div>
                </div>
            `;
            return div;
        }
    </script>

    @stack('scripts')
</body>
</html>
