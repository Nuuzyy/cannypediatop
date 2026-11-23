@extends('layouts.app')

@section('title', 'Cannypedia | Top-up digital')

@section('content')
    <div class="space-y-9">
        <section class="grid gap-7 border-b border-slate-800 pb-8 lg:grid-cols-[1fr_0.8fr] lg:items-end">
            <div class="max-w-2xl">
                <p class="mb-3 text-sm font-semibold text-amber-400">Game, pulsa, dan e-wallet</p>
                <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Cari top-up yang kamu butuhkan</h1>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300 sm:text-base">
                    Pilih produk, isi data tujuan, lalu lanjutkan ke pembayaran simulasi.
                </p>
            </div>

            <form action="{{ route('storefront.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row" x-data="{ searching: false }" @submit="searching = true">
                <div class="min-w-0 flex-1">
                    <label for="search" class="mb-2 block text-sm font-medium text-slate-200">Cari produk atau penerbit</label>
                    <input
                        id="search"
                        name="search"
                        type="search"
                        value="{{ $search }}"
                        maxlength="80"
                        placeholder="Contoh: Mobile Legends"
                        class="min-h-12 w-full rounded-md border border-slate-600 bg-slate-800 px-4 text-sm text-white placeholder:text-slate-400 focus:border-amber-400 focus:bg-slate-800"
                    >
                </div>
                @if($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <button type="submit" class="mt-auto inline-flex min-h-12 items-center justify-center rounded-md bg-amber-500 px-5 font-semibold text-slate-950 transition hover:bg-amber-400 active:bg-amber-600 disabled:bg-slate-600 disabled:text-slate-300 sm:min-w-28" :disabled="searching">
                    <span x-show="!searching">Cari</span>
                    <span x-cloak x-show="searching" role="status">Memuat...</span>
                </button>
            </form>
        </section>

        @if($errors->any())
            <div class="rounded-md border border-rose-500/60 bg-rose-500/10 p-4 text-sm text-rose-200" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <nav class="flex flex-wrap gap-2" aria-label="Filter kategori">
            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" @if(!$category) aria-current="page" @endif class="inline-flex min-h-11 items-center rounded-md border px-4 text-sm font-semibold transition {{ !$category ? 'border-amber-400 bg-amber-500 text-slate-950' : 'border-slate-700 bg-slate-800 text-slate-200 hover:border-slate-500' }}">
                Semua
            </a>
            <a href="{{ request()->fullUrlWithQuery(['category' => 'games']) }}" @if($category === 'games') aria-current="page" @endif class="inline-flex min-h-11 items-center rounded-md border px-4 text-sm font-semibold transition {{ $category === 'games' ? 'border-amber-400 bg-amber-500 text-slate-950' : 'border-slate-700 bg-slate-800 text-slate-200 hover:border-slate-500' }}">
                Games
            </a>
            <a href="{{ request()->fullUrlWithQuery(['category' => 'pulsa']) }}" @if($category === 'pulsa') aria-current="page" @endif class="inline-flex min-h-11 items-center rounded-md border px-4 text-sm font-semibold transition {{ $category === 'pulsa' ? 'border-amber-400 bg-amber-500 text-slate-950' : 'border-slate-700 bg-slate-800 text-slate-200 hover:border-slate-500' }}">
                Pulsa &amp; Data
            </a>
            <a href="{{ request()->fullUrlWithQuery(['category' => 'ewallet']) }}" @if($category === 'ewallet') aria-current="page" @endif class="inline-flex min-h-11 items-center rounded-md border px-4 text-sm font-semibold transition {{ $category === 'ewallet' ? 'border-amber-400 bg-amber-500 text-slate-950' : 'border-slate-700 bg-slate-800 text-slate-200 hover:border-slate-500' }}">
                E-Wallet
            </a>
        </nav>

        <section aria-labelledby="catalog-title">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 id="catalog-title" class="text-xl font-bold text-white">Katalog digital</h2>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $products->total() }} produk
                        @if($search !== '')
                            untuk pencarian “{{ $search }}”
                        @endif
                    </p>
                </div>
                @if($category || $search !== '')
                    <a href="{{ route('storefront.index') }}" class="inline-flex min-h-11 items-center text-sm font-semibold text-amber-300 underline underline-offset-4">
                        Hapus filter
                    </a>
                @endif
            </div>

            @if($products->isEmpty())
                <div class="rounded-lg border border-slate-700 bg-slate-800 p-7 sm:p-10" role="status">
                    <h3 class="font-semibold text-white">
                        {{ $search !== '' || $category ? 'Produk tidak ditemukan' : 'Katalog belum tersedia' }}
                    </h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">
                        {{ $search !== '' || $category ? 'Coba kata kunci atau kategori lain.' : 'Produk aktif akan muncul di sini.' }}
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product) }}" class="group flex min-h-52 flex-col justify-between rounded-lg border border-slate-700 bg-slate-800 p-5 transition hover:border-amber-400 hover:bg-slate-800/80 active:bg-slate-800">
                            <div>
                                <div class="mb-5 flex items-start justify-between gap-3">
                                    <p class="text-xs font-semibold text-amber-300">{{ $product->publisher }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ match ($product->category) {
                                            'games' => 'Games',
                                            'pulsa' => 'Pulsa & Data',
                                            'ewallet' => 'E-Wallet',
                                            default => 'Produk digital',
                                        } }}
                                    </p>
                                </div>
                                <h3 class="text-lg font-bold leading-snug text-white group-hover:text-amber-200">{{ $product->name }}</h3>
                                <p class="mt-2 text-sm text-slate-300">Tujuan: {{ $product->target_label }}</p>
                            </div>
                            <div class="mt-6 flex items-end justify-between gap-3 border-t border-slate-700 pt-4">
                                <div>
                                    <p class="text-xs text-slate-400">Mulai dari</p>
                                    <p class="mt-1 tabular-nums text-base font-bold text-white">Rp{{ number_format($product->items->min('price'), 0, ',', '.') }}</p>
                                </div>
                                <span class="text-sm font-semibold text-amber-300">Pilih nominal</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if($products->hasPages())
                    <div class="mt-7">{{ $products->links() }}</div>
                @endif
            @endif
        </section>
    </div>
@endsection
