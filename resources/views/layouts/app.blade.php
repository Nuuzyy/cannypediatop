<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f172a">
    <title>@yield('title', 'Cannypedia')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root {
            color-scheme: dark;
            font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
        }

        a,
        button,
        label,
        select,
        input[type="checkbox"],
        input[type="radio"] {
            cursor: pointer;
        }

        button:disabled,
        input:disabled,
        select:disabled {
            cursor: not-allowed;
        }

        label:hover {
            color: #fcd34d;
        }

        input:not([type="hidden"]):hover {
            border-color: #64748b;
        }

        input:not([type="hidden"]):active {
            border-color: #fbbf24;
        }

        a:hover {
            color: #fbbf24;
        }

        a:active,
        button:active {
            transform: translateY(1px);
        }

        :focus-visible {
            outline: 3px solid #fbbf24;
            outline-offset: 3px;
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 antialiased">
    <header class="border-b border-slate-800 bg-slate-900">
        <nav class="mx-auto flex min-h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8" aria-label="Navigasi utama">
            <a href="{{ route('storefront.index') }}" class="rounded-sm text-lg font-bold tracking-tight text-white hover:text-amber-400">
                Cannypedia<span class="text-amber-400">.</span>
            </a>
            <a href="{{ route('transactions.check') }}" class="inline-flex min-h-11 items-center rounded-md border border-slate-700 px-4 text-sm font-semibold text-slate-200 transition hover:border-amber-400 hover:text-amber-300 active:bg-slate-800">
                Cek Transaksi
            </a>
        </nav>
    </header>

    <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
        @yield('content')
    </main>

    <footer class="border-t border-slate-800">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>Cannypedia, katalog top-up untuk pengujian.</p>
            <p>Seluruh pembayaran di halaman ini hanya simulasi lokal.</p>
        </div>
    </footer>
</body>
</html>
