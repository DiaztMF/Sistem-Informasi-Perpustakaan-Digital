<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - SiPerpus (Sistem Informasi Perpustakaan)</title>

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
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-gradient-to-br from-slate-100 via-blue-50/30 to-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-sky-500 text-white shadow-lg shadow-blue-500/25 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">SiPerpus Digital</h1>
            <p class="text-sm text-slate-500 mt-1">Sistem Informasi Pengelolaan Perpustakaan</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-800">Selamat Datang Kembali</h2>
                <p class="text-xs text-slate-500 mt-0.5">Silakan masuk menggunakan akun petugas perpustakaan Anda.</p>
            </div>

            <!-- Error Banner -->
            <div id="errorBanner" class="hidden mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="errorMessage">Email atau kata sandi tidak sesuai.</span>
                </div>
            </div>

            <!-- Demo Account Quick Fill -->
            <div class="mb-6 p-3 bg-brand-50/70 border border-brand-100 rounded-xl flex items-center justify-between">
                <div class="text-xs">
                    <span class="font-bold text-brand-800">Akun Demo:</span>
                    <span class="text-brand-700 ml-1">admin@perpustakaan.test</span>
                </div>
                <button type="button" onclick="fillDemoCredentials()" class="text-xs font-semibold px-2.5 py-1 bg-white border border-brand-200 text-brand-700 rounded-lg shadow-2xs hover:bg-brand-50 transition-colors cursor-pointer">
                    Gunakan Akun
                </button>
            </div>

            <!-- Login Form -->
            <form id="loginForm" onsubmit="submitLogin(event)" class="space-y-4">
                @csrf
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" required autocomplete="email" placeholder="nama@email.com" class="w-full pl-10 pr-3 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-800 placeholder-slate-400">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full pl-10 pr-3 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-800 placeholder-slate-400">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        <span class="text-xs text-slate-600 select-none">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" id="submitBtn" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/30 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60">
                        <span id="btnText">Masuk ke Sistem</span>
                        <svg id="btnSpinner" class="hidden w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Belum memiliki akun petugas? 
                    <a href="{{ route('register.view') }}" class="font-bold text-brand-600 hover:text-brand-700 hover:underline">
                        Daftar Akun Baru
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Script Breeze API Login -->
    <script>
        function fillDemoCredentials() {
            document.getElementById('email').value = 'admin@perpustakaan.test';
            document.getElementById('password').value = 'password';
            document.getElementById('errorBanner').classList.add('hidden');
        }

        async function submitLogin(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            const errorBanner = document.getElementById('errorBanner');
            const errorMessage = document.getElementById('errorMessage');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            // Reset UI
            errorBanner.classList.add('hidden');
            submitBtn.disabled = true;
            btnText.textContent = 'Memproses autentikasi...';
            btnSpinner.classList.remove('hidden');

            try {
                // 1. Inisialisasi CSRF Cookie untuk Sanctum/Breeze API
                await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' });

                // Ambil CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                // 2. Kirim kredensial ke endpoint Breeze API: POST /login
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        remember: remember
                    })
                });

                if (response.ok) {
                    btnText.textContent = 'Berhasil! Mengalihkan...';
                    window.location.href = '/buku';
                } else {
                    const data = await response.json().catch(() => ({}));
                    let msg = data.message || 'Email atau kata sandi tidak valid.';
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0];
                        if (Array.isArray(firstError) && firstError.length > 0) {
                            msg = firstError[0];
                        }
                    }
                    errorMessage.textContent = msg;
                    errorBanner.classList.remove('hidden');
                    submitBtn.disabled = false;
                    btnText.textContent = 'Masuk ke Sistem';
                    btnSpinner.classList.add('hidden');
                }
            } catch (err) {
                console.error('Login error:', err);
                errorMessage.textContent = 'Terjadi kendala koneksi ke server. Silakan coba kembali.';
                errorBanner.classList.remove('hidden');
                submitBtn.disabled = false;
                btnText.textContent = 'Masuk ke Sistem';
                btnSpinner.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
