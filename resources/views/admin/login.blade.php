<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PT Trac & PT. MJA TEKNOLOGI</title>
    <link rel="icon" href="/storage/gambar/logo-mja.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --ts-slate-950: #090d16;
            --ts-slate-900: #0f172a;
            --ts-slate-800: #1e293b;
            --ts-slate-700: #334155;
            --ts-slate-400: #94a3b8;
            --ts-primary: #dc2626;
            --ts-primary-dark: #b91c1c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--ts-slate-950);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            position: relative;
            overflow-x: hidden;
            padding: 20px;
        }

        /* Subtle Cyber Background Matrix/Glow */
        body::before {
            content: '';
            position: absolute;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 750px;
            height: 500px;
            background: radial-gradient(circle, rgba(220, 38, 38, 0.15) 0%, rgba(15, 23, 42, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
        }

        .login-brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-badge-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: rgba(220, 38, 38, 0.12);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #f87171;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 18px;
            box-shadow: 0 0 25px rgba(220, 38, 38, 0.2);
            transition: all 0.3s ease;
        }

        .login-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 38px 34px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7);
        }

        .login-title {
            color: #ffffff;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .login-subtitle {
            color: var(--ts-slate-400);
            font-size: 13.5px;
            line-height: 1.5;
            margin-bottom: 26px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-label-custom {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--ts-slate-400);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .input-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 16px;
            color: var(--ts-slate-400);
            font-size: 16px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-control-corp {
            width: 100%;
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            padding: 13px 44px 13px 44px;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control-corp::placeholder {
            color: #64748b;
        }

        .form-control-corp:focus {
            background: rgba(30, 41, 59, 0.9);
            border-color: var(--ts-primary);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
            color: #ffffff;
            outline: none;
        }

        .form-control-corp:focus + .input-icon-left {
            color: #f87171;
        }

        .password-toggle-btn {
            position: absolute;
            right: 14px;
            background: transparent;
            border: none;
            color: var(--ts-slate-400);
            cursor: pointer;
            padding: 4px;
            font-size: 16px;
            transition: color 0.2s;
        }

        .password-toggle-btn:hover {
            color: #ffffff;
        }

        .btn-corp-submit {
            background-color: var(--ts-primary);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 14px 20px;
            font-size: 14.5px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.35);
            margin-top: 10px;
        }

        .btn-corp-submit:hover {
            background-color: var(--ts-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.45);
            color: #ffffff;
        }

        .btn-corp-submit:active {
            transform: translateY(0);
        }

        .security-footer-badge {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
            color: #64748b;
        }

        .security-badge-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .alert-corp-danger {
            background: rgba(220, 38, 38, 0.15);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .back-home-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--ts-slate-400);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            margin-top: 22px;
            transition: color 0.2s;
        }

        .back-home-link:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-brand-header">
            <div class="brand-badge-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h1 class="login-title">PT. MJA TEKNOLOGI</h1>
            <p class="login-subtitle">
                Unified Operations & Administration Console
            </p>
        </div>

        <div class="login-card">
            @if(isset($errors) && $errors->has('login'))
                <div class="alert-corp-danger">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ $errors->first('login') }}</div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div class="input-group-custom">
                    <label class="input-label-custom">
                        <i class="bi bi-person-fill"></i> Username / Email
                    </label>
                    <div class="input-box">
                        <input type="text" 
                               class="form-control-corp" 
                               name="username" 
                               placeholder="Masukkan username atau email" 
                               required 
                               autocomplete="username"
                               value="{{ old('username') }}"
                               style="padding-left: 44px; padding-right: 14px;">
                        <i class="bi bi-person input-icon-left"></i>
                    </div>
                </div>

                <div class="input-group-custom">
                    <label class="input-label-custom">
                        <i class="bi bi-key-fill"></i> Kata Sandi
                    </label>
                    <div class="input-box">
                        <input type="password" 
                               class="form-control-corp" 
                               id="passwordInput" 
                               name="password" 
                               placeholder="Masukkan password akun" 
                               required
                               autocomplete="current-password">
                        <i class="bi bi-lock input-icon-left"></i>
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility()" title="Lihat Password">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-corp-submit">
                    <span>Masuk ke Admin Console</span>
                    <i class="bi bi-arrow-right-short fs-4"></i>
                </button>
            </form>

            <div class="security-footer-badge">
                <div class="security-badge-item">
                    <i class="bi bi-shield-check text-success"></i> 256-Bit SSL Enkripsi
                </div>
                <div class="security-badge-item">
                    <i class="bi bi-person-badge text-secondary"></i> Akses Terotorisasi
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ url('/') }}" class="back-home-link">
                <i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama TechStore
            </a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Hapus semua logika localStorage palsu kemarin agar tidak bentrok
        localStorage.removeItem('adminLoggedIn');
    </script>
</body>
</html>