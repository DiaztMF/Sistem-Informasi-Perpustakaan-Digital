<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Informasi Perpustakaan') - SiPerpus</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 flex flex-col" x-data="{ mobileMenu: false }">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('buku.index') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-sky-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold bg-gradient-to-r from-blue-700 to-blue-600 bg-clip-text text-transparent">SiPerpus</span>
                        </div>
                    </a>

                    <!-- Nav Links -->
                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('buku.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('buku.*') ? 'bg-brand-50 text-brand-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 {{ request()->routeIs('buku.*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Data Buku
                            </div>
                        </a>
                        <a href="{{ route('anggota.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('anggota.*') ? 'bg-brand-50 text-brand-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 {{ request()->routeIs('anggota.*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Data Anggota
                            </div>
                        </a>
                        <a href="{{ route('peminjaman.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('peminjaman.*') ? 'bg-brand-50 text-brand-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 {{ request()->routeIs('peminjaman.*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Peminjaman Buku
                            </div>
                        </a>
                    </nav>
                </div>

                <!-- Right Header: User Profile & Breeze Logout -->
                <div class="flex items-center gap-3">
                    @auth
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-sm font-semibold text-slate-800 leading-tight">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-slate-400">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-brand-100 border border-brand-200 text-brand-700 flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>

                        <!-- Breeze API Logout Button -->
                        <button type="button" onclick="handleLogout()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-colors ml-2 cursor-pointer" title="Keluar dari sistem">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-brand-600 hover:text-brand-700">Masuk</a>
                        <a href="{{ route('register.view') }}" class="px-4 py-2 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-lg shadow-sm">Daftar</a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button type="button" @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenu" x-cloak class="md:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-4 space-y-1">
            <a href="{{ route('buku.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold {{ request()->routeIs('buku.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600' }}">
                Data Buku
            </a>
            <a href="{{ route('anggota.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold {{ request()->routeIs('anggota.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600' }}">
                Data Anggota
            </a>
            <a href="{{ route('peminjaman.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold {{ request()->routeIs('peminjaman.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600' }}">
                Peminjaman Buku
            </a>
            @auth
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="font-medium text-slate-800">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-400">{{ Auth::user()->email }}</div>
                    </div>
                    <button type="button" onclick="handleLogout()" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg">
                        Keluar
                    </button>
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Flash Messages (Shadcn Alert Style) -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-start justify-between p-4 bg-emerald-50/80 border border-emerald-200/90 text-emerald-900 rounded-2xl shadow-xs">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 border border-emerald-200 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800">Berhasil</h4>
                        <p class="text-sm font-medium text-emerald-900 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-800 p-1 rounded-lg hover:bg-emerald-100/50 transition-colors cursor-pointer" title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-start justify-between p-4 bg-rose-50/80 border border-rose-200/90 text-rose-900 rounded-2xl shadow-xs">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 border border-rose-200 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-rose-800">Perhatian</h4>
                        <p class="text-sm font-medium text-rose-900 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-rose-500 hover:text-rose-800 p-1 rounded-lg hover:bg-rose-100/50 transition-colors cursor-pointer" title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-start justify-between p-4 bg-amber-50/80 border border-amber-200/90 text-amber-900 rounded-2xl shadow-xs">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 border border-amber-200 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-amber-800">Terdapat Kesalahan</h4>
                        <ul class="mt-1 list-disc list-inside text-xs text-amber-800 space-y-0.5 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-amber-500 hover:text-amber-800 p-1 rounded-lg hover:bg-amber-100/50 transition-colors cursor-pointer" title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} <span class="font-semibold text-slate-700">SiPerpus</span> | Sistem Informasi Perpustakaan Digital
            </p>
        </div>
    </footer>

    <!-- Shadcn-Style Alert Dialog (Blade + Tailwind + Alpine.js) -->
    <div 
        x-data="{
            open: false,
            isAlert: false,
            title: '',
            description: '',
            confirmText: 'Lanjutkan',
            cancelText: 'Batal',
            variant: 'primary',
            icon: '',
            callback: null,

            showConfirm({ title, description, confirmText = 'Lanjutkan', cancelText = 'Batal', variant = 'primary', icon = null, onConfirm }) {
                this.isAlert = false;
                this.title = title || 'Konfirmasi Tindakan';
                this.description = description || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                this.confirmText = confirmText;
                this.cancelText = cancelText;
                this.variant = variant;
                this.icon = icon || (variant === 'logout' ? 'logout' : (variant === 'danger' ? 'trash' : (variant === 'warning' ? 'warning' : (variant === 'success' ? 'success' : 'info'))));
                this.callback = onConfirm;
                this.open = true;
            },

            showAlert({ title, description, confirmText = 'Mengerti', variant = 'info', icon = null, onConfirm }) {
                this.isAlert = true;
                this.title = title || 'Informasi';
                this.description = description || '';
                this.confirmText = confirmText;
                this.cancelText = '';
                this.variant = variant;
                this.icon = icon || (variant === 'danger' ? 'trash' : (variant === 'warning' ? 'warning' : (variant === 'success' ? 'success' : 'info')));
                this.callback = onConfirm;
                this.open = true;
            },

            close() {
                this.open = false;
                this.callback = null;
            },

            onAction() {
                const cb = this.callback;
                this.close();
                if (typeof cb === 'function') {
                    cb();
                }
            }
        }"
        x-init="
            window.confirmDialog = (opts) => showConfirm(opts);
            window.alertDialog = (opts) => showAlert(opts);
            window.alert = (msg) => showAlert({ title: 'Pemberitahuan', description: msg });
        "
        @keydown.escape.window="close()"
        class="relative z-50"
    >
        <!-- Backdrop Overlay with blur -->
        <div 
            x-cloak
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-950/45 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6"
            @click="if(!isAlert) close()"
            aria-hidden="true"
        ></div>

        <!-- Alert Dialog Content Card (Symmetrical Shadcn-style) -->
        <div 
            x-cloak
            x-show="open"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none"
        >
            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="pointer-events-auto w-full max-w-sm sm:max-w-md bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-2xl shadow-slate-950/15 overflow-hidden text-center flex flex-col items-center"
                role="alertdialog"
                aria-modal="true"
                @click.stop
            >
                <!-- Centered Semantic Icon -->
                <div class="mb-4">
                    <!-- Logout Icon (Door Exit) -->
                    <template x-if="icon === 'logout' || variant === 'logout'">
                        <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100/80 text-rose-600 flex items-center justify-center mx-auto shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                    </template>

                    <!-- Destructive / Trash Icon -->
                    <template x-if="(icon === 'trash' || variant === 'danger') && variant !== 'logout'">
                        <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100/80 text-rose-600 flex items-center justify-center mx-auto shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                    </template>

                    <!-- Warning Icon -->
                    <template x-if="(icon === 'warning' || variant === 'warning') && variant !== 'logout'">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100/80 text-amber-600 flex items-center justify-center mx-auto shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </template>

                    <!-- Success Icon -->
                    <template x-if="icon === 'success' || variant === 'success'">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100/80 text-emerald-600 flex items-center justify-center mx-auto shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </template>

                    <!-- Primary / Info Icon (Blue) -->
                    <template x-if="icon === 'info' || (variant !== 'danger' && variant !== 'logout' && variant !== 'warning' && variant !== 'success')">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100/80 text-blue-600 flex items-center justify-center mx-auto shadow-xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </template>
                </div>

                <!-- Text Header (Centered & Highly Legible) -->
                <div class="w-full space-y-2 mb-6">
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight" x-text="title"></h3>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-sm mx-auto" x-text="description"></p>
                </div>

                <!-- Footer Actions (Symmetrical Grid with Equal Proportions) -->
                <div class="w-full">
                    <template x-if="!isAlert">
                        <div class="grid grid-cols-2 gap-3 w-full">
                            <button 
                                type="button" 
                                @click="close()" 
                                class="w-full py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm transition-all cursor-pointer shadow-xs hover:border-slate-300 active:scale-[0.98] text-center"
                                x-text="cancelText"
                            ></button>
                            <button 
                                type="button" 
                                @click="onAction()" 
                                :class="{
                                    'bg-rose-600 hover:bg-rose-700 text-white shadow-sm shadow-rose-500/25': variant === 'danger' || variant === 'logout',
                                    'bg-amber-600 hover:bg-amber-700 text-white shadow-sm shadow-amber-500/25': variant === 'warning',
                                    'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-500/25': variant === 'success',
                                    'bg-blue-600 hover:bg-blue-700 text-white shadow-sm shadow-blue-500/25': variant !== 'danger' && variant !== 'logout' && variant !== 'warning' && variant !== 'success'
                                }"
                                class="w-full py-2.5 px-4 rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-[0.98] text-center"
                            >
                                <span x-text="confirmText"></span>
                            </button>
                        </div>
                    </template>

                    <template x-if="isAlert">
                        <button 
                            type="button" 
                            @click="onAction()" 
                            class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all cursor-pointer shadow-sm shadow-blue-500/25 flex items-center justify-center active:scale-[0.98] text-center"
                        >
                            <span x-text="confirmText"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts & Global Form Interception -->
    <script>
        // Intercept all forms with data-confirm
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('form[data-confirm]');
                if (form && !form.dataset.confirmed) {
                    e.preventDefault();
                    const variant = form.dataset.confirmVariant || 'primary';
                    const title = form.dataset.confirmTitle || (variant === 'danger' ? 'Konfirmasi Hapus Data' : 'Konfirmasi Tindakan');
                    const description = form.dataset.confirm || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                    const confirmText = form.dataset.confirmBtn || (variant === 'danger' ? 'Ya, Hapus' : 'Lanjutkan');
                    const cancelText = form.dataset.cancelBtn || 'Batal';

                    if (window.confirmDialog) {
                        window.confirmDialog({
                            title,
                            description,
                            confirmText,
                            cancelText,
                            variant,
                            onConfirm: () => {
                                form.dataset.confirmed = 'true';
                                form.submit();
                            }
                        });
                    } else if (confirm(description)) {
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                }
            });
        });

        // Breeze API Logout via Shadcn Alert Dialog
        function handleLogout() {
            if (window.confirmDialog) {
                window.confirmDialog({
                    title: 'Keluar dari Sistem',
                    description: 'Apakah Anda yakin ingin mengakhiri sesi dan keluar dari sistem perpustakaan?',
                    confirmText: 'Ya, Keluar',
                    cancelText: 'Batal',
                    variant: 'logout',
                    onConfirm: async () => {
                        await executeLogout();
                    }
                });
            } else if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
                executeLogout();
            }
        }

        async function executeLogout() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            try {
                await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
            } finally {
                window.location.href = '/login';
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
