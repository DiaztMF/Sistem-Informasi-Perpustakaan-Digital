<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Petugas Baru - SiPerpus</title>

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">SiPerpus Digital</h1>
            <p class="text-sm text-slate-500 mt-1">Pendaftaran Petugas Perpustakaan Baru</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-800">Buat Akun Baru</h2>
                <p class="text-xs text-slate-500 mt-0.5">Lengkapi formulir di bawah untuk mendaftarkan akun pengelola.</p>
            </div>

            <!-- Error Banner -->
            <div id="errorBanner" class="hidden mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="errorMessage">Terdapat kesalahan dalam pengisian formulir.</span>
                </div>
            </div>

            <!-- Register Form -->
            <form id="registerForm" onsubmit="submitRegister(event)" class="space-y-4">
                @csrf
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" id="name" name="name" required placeholder="Contoh: Budi Pratama" class="w-full px-3.5 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-800 placeholder-slate-400">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" required autocomplete="email" placeholder="nama@email.com" class="w-full px-3.5 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-800 placeholder-slate-400">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full px-3.5 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-800 placeholder-slate-400">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" class="w-full px-3.5 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-800 placeholder-slate-400">
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" id="submitBtn" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/30 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60">
                        <span id="btnText">Daftar Akun</span>
                        <svg id="btnSpinner" class="hidden w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-700 hover:underline">
                        Masuk di Sini
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Breeze API Register Script -->
    <script>
        async function submitRegister(e) {
            e.preventDefault();

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const errorBanner = document.getElementById('errorBanner');
            const errorMessage = document.getElementById('errorMessage');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            if (password !== passwordConfirmation) {
                errorMessage.textContent = 'Konfirmasi kata sandi tidak cocok.';
                errorBanner.classList.remove('hidden');
                return;
            }

            errorBanner.classList.add('hidden');
            submitBtn.disabled = true;
            btnText.textContent = 'Mendaftarkan...';
            btnSpinner.classList.remove('hidden');

            try {
                // 1. Inisialisasi CSRF Cookie untuk Sanctum/Breeze API
                await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' });

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                // 2. Kirim pendaftaran ke Breeze API: POST /register
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: passwordConfirmation
                    })
                });

                if (response.ok) {
                    btnText.textContent = 'Pendaftaran berhasil! Mengalihkan...';
                    window.location.href = '/buku';
                } else {
                    const data = await response.json().catch(() => ({}));
                    let msg = data.message || 'Gagal mendaftar.';
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0];
                        if (Array.isArray(firstError) && firstError.length > 0) {
                            msg = firstError[0];
                        }
                    }
                    errorMessage.textContent = msg;
                    errorBanner.classList.remove('hidden');
                    submitBtn.disabled = false;
                    btnText.textContent = 'Daftar Akun';
                    btnSpinner.classList.add('hidden');
                }
            } catch (err) {
                console.error('Register error:', err);
                errorMessage.textContent = 'Terjadi kesalahan pada sistem. Silakan coba kembali.';
                errorBanner.classList.remove('hidden');
                submitBtn.disabled = false;
                btnText.textContent = 'Daftar Akun';
                btnSpinner.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
