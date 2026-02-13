<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Digital Checksheet SL-Frame - Quality Management System')</title>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
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
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            position: relative;
            overflow-x: hidden;
        }

        /* ✨ Animated Mesh Background (Gray Tones) */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 30%, rgba(71, 85, 105, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(100, 116, 139, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(148, 163, 184, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
            animation: mesh-float 20s ease-in-out infinite;
        }

        @keyframes mesh-float {
            0%, 100% { transform: scale(1) rotate(0deg); }
            33% { transform: scale(1.05) rotate(1deg); }
            66% { transform: scale(0.95) rotate(-1deg); }
        }

        /* 🎨 Floating Blobs (Gray Gradient) */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.12;
            z-index: -1;
            animation: blob-float 15s ease-in-out infinite;
        }

        .blob-1 {
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .blob-2 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
            bottom: -150px;
            right: -150px;
            animation-delay: 5s;
        }

        .blob-3 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            top: 40%;
            right: -100px;
            animation-delay: 10s;
        }

        @keyframes blob-float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }

        /* 🔷 Dot Pattern Overlay */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(71, 85, 105, 0.15) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
            z-index: -1;
            opacity: 0.3;
        }

        .bg-app-gradient {
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }

        .page-fade {
            animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============================================
           🎨 LOADING SCREEN STYLES (GRAY THEME)
        ============================================ */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #334155 0%, #475569 50%, #64748b 100%);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }

        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        #loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        /* ✨ Glassmorphic Particles (Gray) */
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
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: float 8s infinite ease-in-out;
        }

        .particle:nth-child(1) { width: 120px; height: 120px; top: 10%; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 80px; height: 80px; top: 70%; left: 80%; animation-delay: 1s; }
        .particle:nth-child(3) { width: 150px; height: 150px; top: 40%; left: 70%; animation-delay: 2s; }
        .particle:nth-child(4) { width: 60px; height: 60px; top: 80%; left: 20%; animation-delay: 1.5s; }
        .particle:nth-child(5) { width: 100px; height: 100px; top: 20%; left: 60%; animation-delay: 0.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.3; }
            25% { transform: translateY(-40px) rotate(90deg); opacity: 0.6; }
            50% { transform: translateY(0) rotate(180deg); opacity: 0.3; }
            75% { transform: translateY(40px) rotate(270deg); opacity: 0.6; }
        }

        /* 🎯 Logo Container with Glass Effect */
        .loading-logo-container {
            position: relative;
            z-index: 2;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(30px) saturate(150%);
            border-radius: 30px;
            padding: 50px 60px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        /* 💎 Animated Logo */
        .loading-logo {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            animation: pulse-glow 2s ease-in-out infinite;
            box-shadow:
                0 10px 40px rgba(255, 255, 255, 0.2),
                inset 0 0 20px rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .loading-logo svg {
            width: 60px;
            height: 60px;
            color: white;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
            animation: bounce-subtle 1.5s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% {
                transform: scale(1) rotate(0deg);
                box-shadow: 0 10px 40px rgba(255, 255, 255, 0.2);
            }
            50% {
                transform: scale(1.08) rotate(3deg);
                box-shadow: 0 15px 60px rgba(255, 255, 255, 0.4);
            }
        }

        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* 📝 Text Styles */
        .loading-text {
            color: white;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            animation: fade-slide 1s ease-in-out infinite alternate;
        }

        .loading-subtext {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 30px;
        }

        @keyframes fade-slide {
            from { opacity: 0.8; transform: translateY(-3px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 🔮 Enhanced Dots */
        .loading-dots {
            display: flex;
            gap: 12px;
            justify-content: center;
            align-items: center;
        }

        .loading-dots .dot {
            width: 14px;
            height: 14px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            animation: dot-bounce 1.4s infinite ease-in-out;
        }

        .loading-dots .dot:nth-child(1) { animation-delay: -0.32s; }
        .loading-dots .dot:nth-child(2) { animation-delay: -0.16s; }
        .loading-dots .dot:nth-child(3) { animation-delay: 0s; }

        @keyframes dot-bounce {
            0%, 80%, 100% { transform: scale(0.7); opacity: 0.4; }
            40% { transform: scale(1.3); opacity: 1; }
        }

        /* 📊 Glass Progress Bar */
        .loading-progress {
            width: 280px;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 40px;
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .loading-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #fff 0%, rgba(255, 255, 255, 0.7) 100%);
            border-radius: 10px;
            animation: progress-glow 1.5s ease-in-out infinite;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.6);
        }

        @keyframes progress-glow {
            0% { width: 0%; opacity: 0.8; }
            50% { width: 70%; opacity: 1; }
            100% { width: 100%; opacity: 0.8; }
        }

        /* ⚙️ Decorative Rings */
        .loading-ring {
            position: absolute;
            border: 3px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: spin-slow 20s linear infinite;
        }

        .ring-1 { width: 500px; height: 500px; top: 50%; left: 50%; margin: -250px 0 0 -250px; }
        .ring-2 { width: 700px; height: 700px; top: 50%; left: 50%; margin: -350px 0 0 -350px; animation-direction: reverse; animation-duration: 30s; }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* 📱 Responsive */
        @media (max-width: 640px) {
            .loading-logo-container { padding: 40px 30px; border-radius: 20px; }
            .loading-logo { width: 100px; height: 100px; }
            .loading-logo svg { width: 50px; height: 50px; }
            .loading-text { font-size: 22px; }
            .loading-subtext { font-size: 11px; }
            .loading-progress { width: 220px; }
        }
    </style>

    @stack('styles')
</head>
<body class="text-slate-900 overflow-x-hidden">
    <!-- 🎨 Animated Background Blobs (Gray) -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <!-- 🎨 LOADING SCREEN (GRAY THEME) -->
    <div id="loading-screen">
        <!-- Decorative Rings -->
        <div class="loading-ring ring-1"></div>
        <div class="loading-ring ring-2"></div>

        <!-- Glassmorphic Particles -->
        <div class="loading-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <!-- Main Loading Content -->
        <div class="loading-logo-container">
            <!-- Animated Logo -->
            <div class="loading-logo">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Loading Text -->
            <h1 class="loading-text">Digital Checksheet SL-Frame</h1>
            <p class="loading-subtext">Loading Quality System...</p>

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
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loading-screen');
            setTimeout(() => {
                loadingScreen.classList.add('hidden');
                setTimeout(() => loadingScreen.remove(), 500);
            }, 300);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"]):not([download])');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && !href.startsWith('http') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
                        let loadingScreen = document.getElementById('loading-screen');
                        if (!loadingScreen) {
                            loadingScreen = createLoadingScreen();
                            document.body.appendChild(loadingScreen);
                        }
                        loadingScreen.classList.remove('hidden');
                    }
                });
            });

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

        function createLoadingScreen() {
            const div = document.createElement('div');
            div.id = 'loading-screen';
            div.innerHTML = `
                <div class="loading-ring ring-1"></div>
                <div class="loading-ring ring-2"></div>
                <div class="loading-particles">
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                </div>
                <div class="loading-logo-container">
                    <div class="loading-logo">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="loading-text">Digital Checksheet SL-Frame</h1>
                    <p class="loading-subtext">Loading Quality System...</p>
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
