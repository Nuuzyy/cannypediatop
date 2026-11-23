<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpus Digital</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 50%, #bfdbfe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1e293b;
        }
        .card {
            background: #ffffff;
            width: 100%;
            max-width: 920px;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(29, 78, 216, 0.18);
            display: flex;
            overflow: hidden;
            min-height: 540px;
        }
        .left-panel {
            flex: 1;
            background: linear-gradient(145deg, #1d4ed8 0%, #0284c7 100%);
            color: #ffffff;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            top: -80px;
            right: -80px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            bottom: -60px;
            left: -60px;
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.15);
            padding: 10px 18px;
            border-radius: 50px;
            width: fit-content;
            backdrop-filter: blur(6px);
            font-weight: 600;
            font-size: 15px;
        }
        .hero-text h1 {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 14px;
        }
        .hero-text p {
            font-size: 15px;
            line-height: 1.6;
            color: #e0f2fe;
        }
        .footer-note {
            font-size: 13px;
            color: #bae6fd;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 16px;
        }
        .right-panel {
            flex: 1.15;
            padding: 48px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-header {
            margin-bottom: 28px;
        }
        .form-header h2 {
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .form-header p {
            color: #64748b;
            font-size: 14px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            color: #0284c7;
            display: flex;
        }
        input {
            width: 100%;
            padding: 13px 16px 13px 42px;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            font-size: 14px;
            background: #f8fafc;
            color: #0f172a;
            transition: all 0.2s ease;
        }
        input:focus {
            outline: none;
            border-color: #0284c7;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12);
        }
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0284c7 0%, #1d4ed8 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-top: 6px;
            box-shadow: 0 10px 20px -5px rgba(29, 78, 216, 0.4);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 24px -5px rgba(29, 78, 216, 0.5);
        }
        .bottom-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #64748b;
        }
        .bottom-link a {
            color: #0284c7;
            font-weight: 600;
            text-decoration: none;
        }
        .bottom-link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .card { flex-direction: column; }
            .left-panel, .right-panel { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<div class="card">
    <div class="left-panel">
        <div class="brand-badge">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            <span>Perpus Digital</span>
        </div>

        <div class="hero-text">
            <h1>Jendela Ilmu dalam Satu Genggaman</h1>
            <p>Masuk ke portal perpustakaan untuk menjelajahi koleksi buku, meminjam literatur favorit, dan memantau riwayat bacaan Anda.</p>
        </div>

        <div class="footer-note">
            ✓ Sistem Peminjaman Terpadu &bull; Admin & Anggota
        </div>
    </div>

    <div class="right-panel">
        <div class="form-header">
            <h2>Selamat Datang!</h2>
            <p>Silakan masukkan email dan password akun Anda.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Alamat Email</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input type="password" name="password" placeholder="Masukkan password Anda" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Masuk ke Akun &rarr;</button>
        </form>

        <div class="bottom-link">
            Belum memiliki akun anggota? <a href="{{ route('register') }}">Daftar Sekarang</a>
        </div>
    </div>
</div>

</body>
</html>
