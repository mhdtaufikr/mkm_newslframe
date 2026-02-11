<!-- Alert Notification System -->
@if(session('success') || session('error') || session('warning') || session('info') || $errors->any())
<div id="alert-container" class="fixed top-6 right-6 z-[9999] space-y-3 max-w-md">

    <!-- Success Alert -->
    @if(session('success'))
    <div class="alert-item bg-white rounded-2xl shadow-2xl border-l-4 border-green-500 p-4 flex items-start gap-4 animate-slide-in" role="alert">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-sm font-bold text-slate-800 mb-1">Success!</h3>
            <p class="text-xs text-slate-600 leading-relaxed">{{ session('success') }}</p>
        </div>
        <button type="button" class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors" onclick="this.closest('.alert-item').remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <!-- Error Alert -->
    @if(session('error'))
    <div class="alert-item bg-white rounded-2xl shadow-2xl border-l-4 border-red-500 p-4 flex items-start gap-4 animate-slide-in" role="alert">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-sm font-bold text-slate-800 mb-1">Error!</h3>
            <p class="text-xs text-slate-600 leading-relaxed">{{ session('error') }}</p>
        </div>
        <button type="button" class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors" onclick="this.closest('.alert-item').remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <!-- Warning Alert -->
    @if(session('warning'))
    <div class="alert-item bg-white rounded-2xl shadow-2xl border-l-4 border-amber-500 p-4 flex items-start gap-4 animate-slide-in" role="alert">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-sm font-bold text-slate-800 mb-1">Warning!</h3>
            <p class="text-xs text-slate-600 leading-relaxed">{{ session('warning') }}</p>
        </div>
        <button type="button" class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors" onclick="this.closest('.alert-item').remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <!-- Info Alert -->
    @if(session('info'))
    <div class="alert-item bg-white rounded-2xl shadow-2xl border-l-4 border-blue-500 p-4 flex items-start gap-4 animate-slide-in" role="alert">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-sm font-bold text-slate-800 mb-1">Info</h3>
            <p class="text-xs text-slate-600 leading-relaxed">{{ session('info') }}</p>
        </div>
        <button type="button" class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors" onclick="this.closest('.alert-item').remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <!-- Validation Errors Alert -->
    @if($errors->any())
    <div class="alert-item bg-white rounded-2xl shadow-2xl border-l-4 border-red-500 p-4 flex items-start gap-4 animate-slide-in" role="alert">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-sm font-bold text-slate-800 mb-2">Validation Errors</h3>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-xs text-slate-600 flex items-start gap-2">
                        <span class="text-red-500 mt-0.5">•</span>
                        <span>{{ $error }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors" onclick="this.closest('.alert-item').remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

</div>

<!-- Alert Styles & Scripts -->
<style>
    @keyframes slide-in {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .animate-slide-in {
        animation: slide-in 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .animate-slide-out {
        animation: slide-out 0.3s cubic-bezier(0.4, 0, 1, 1);
    }

    /* Progress bar for auto-dismiss */
    .alert-item::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: currentColor;
        opacity: 0.3;
        animation: progress 5s linear;
        border-radius: 0 0 0 1rem;
    }

    @keyframes progress {
        from { width: 100%; }
        to { width: 0%; }
    }

    /* Responsive */
    @media (max-width: 640px) {
        #alert-container {
            left: 1rem;
            right: 1rem;
            max-width: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert-item');

        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.remove('animate-slide-in');
                alert.classList.add('animate-slide-out');

                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000); // 5 seconds
        });
    });
</script>
@endif
