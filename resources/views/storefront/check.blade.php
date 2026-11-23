@extends('layouts.app')

@section('title', 'Cek transaksi | Cannypedia')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <section>
            <p class="text-sm font-semibold text-amber-300">Pencarian invoice</p>
            <h1 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Cek transaksi</h1>
            <p class="mt-2 max-w-xl text-sm leading-6 text-slate-300">Masukkan kode invoice dari halaman pembayaran simulasi.</p>
        </section>

        @if($errors->any())
            <div class="rounded-md border border-rose-500/60 bg-rose-500/10 p-4 text-sm text-rose-200" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('transactions.check') }}" method="GET" x-data="{ searching: false }" @submit="searching = true" class="rounded-lg border border-slate-700 bg-slate-800 p-5 sm:p-7">
            <label for="invoice_code" class="mb-2 block text-sm font-semibold text-slate-200">Nomor invoice</label>
            <div class="flex flex-col gap-3 sm:flex-row">
                <input
                    id="invoice_code"
                    name="invoice_code"
                    type="text"
                    value="{{ $invoiceCode }}"
                    maxlength="40"
                    autocomplete="off"
                    class="min-h-12 min-w-0 flex-1 rounded-md border border-slate-600 bg-slate-900 px-4 text-sm text-white placeholder:text-slate-400 focus:border-amber-400"
                    placeholder="Contoh: CNY-20261123-AB12CD34"
                >
                <button type="submit" :disabled="searching" class="inline-flex min-h-12 items-center justify-center rounded-md bg-amber-500 px-5 font-semibold text-slate-950 transition hover:bg-amber-400 active:bg-amber-600 disabled:bg-slate-600 disabled:text-slate-300">
                    <span x-show="!searching">Cari invoice</span>
                    <span x-cloak x-show="searching" role="status">Mencari...</span>
                </button>
            </div>
        </form>

        @if($transaction)
            <section class="rounded-lg border border-slate-700 bg-slate-800 p-5 sm:p-7" aria-labelledby="result-heading">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 id="result-heading" class="text-base font-bold text-white">{{ $transaction->productItem->product->name }}</h2>
                        <p class="mt-1 text-sm text-slate-300">{{ $transaction->productItem->name }}</p>
                    </div>
                    @include('storefront.partials.status', ['transaction' => $transaction])
                </div>
                <p class="mt-4 break-all font-mono text-sm text-slate-300">{{ $transaction->invoice_code }}</p>
                <a href="{{ route('invoices.show', $transaction->invoice_code) }}" class="mt-5 inline-flex min-h-11 items-center rounded-md border border-slate-600 px-4 font-semibold text-white transition hover:border-amber-400 hover:text-amber-300 active:bg-slate-900">
                    Buka rincian invoice
                </a>
            </section>
        @elseif($notFound)
            <div class="rounded-lg border border-rose-500/60 bg-rose-500/10 p-5 text-sm text-rose-100" role="alert">
                Invoice tidak ditemukan. Periksa kembali kode yang dimasukkan.
            </div>
        @else
            <div class="rounded-lg border border-slate-700 bg-slate-800 p-5 text-sm leading-6 text-slate-300" role="status">
                Hasil pencarian akan tampil di sini setelah nomor invoice dikirim.
            </div>
        @endif
    </div>
@endsection
