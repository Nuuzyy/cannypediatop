@extends('layouts.app')

@section('title', 'Invoice ' . $transaction->invoice_code . ' | Cannypedia')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-amber-300">Rincian transaksi</p>
                <h1 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Invoice</h1>
            </div>
            @include('storefront.partials.status', ['transaction' => $transaction])
        </div>

        <section class="overflow-hidden rounded-lg border border-slate-700 bg-slate-800" aria-labelledby="receipt-heading">
            <div class="border-b border-slate-700 p-5 sm:p-7">
                <h2 id="receipt-heading" class="text-sm font-semibold text-slate-300">Kode invoice</h2>
                <p class="mt-1 break-all font-mono text-xl font-bold text-white">{{ $transaction->invoice_code }}</p>
            </div>
            <dl class="divide-y divide-slate-700 px-5 sm:px-7">
                <div class="flex flex-wrap justify-between gap-2 py-4">
                    <dt class="text-sm text-slate-400">Produk</dt>
                    <dd class="text-right font-semibold text-white">{{ $transaction->productItem->product->name }}</dd>
                </div>
                <div class="flex flex-wrap justify-between gap-2 py-4">
                    <dt class="text-sm text-slate-400">Nominal</dt>
                    <dd class="text-right font-semibold text-white">{{ $transaction->productItem->name }}</dd>
                </div>
                <div class="flex flex-wrap justify-between gap-2 py-4">
                    <dt class="text-sm text-slate-400">Tujuan</dt>
                    <dd class="max-w-[65%] break-all text-right font-semibold text-white">{{ $transaction->target_id }}</dd>
                </div>
                @if($transaction->server_id)
                    <div class="flex flex-wrap justify-between gap-2 py-4">
                        <dt class="text-sm text-slate-400">Server / Zone ID</dt>
                        <dd class="text-right font-semibold text-white">{{ $transaction->server_id }}</dd>
                    </div>
                @endif
                <div class="flex flex-wrap justify-between gap-2 py-4">
                    <dt class="text-sm text-slate-400">Metode</dt>
                    <dd class="text-right font-semibold text-white">{{ $transaction->payment_method_label }}</dd>
                </div>
                <div class="flex flex-wrap justify-between gap-2 py-4">
                    <dt class="text-sm text-slate-400">Dibuat</dt>
                    <dd class="text-right font-medium text-white">{{ $transaction->created_at->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-700 bg-slate-900/60 px-5 py-5 sm:px-7">
                <p class="font-semibold text-slate-200">Total simulasi</p>
                <p class="tabular-nums text-xl font-bold text-white">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</p>
            </div>
        </section>

        <p class="rounded-md border border-slate-700 bg-slate-800 p-4 text-sm leading-6 text-slate-300">
            Invoice ini mencatat hasil simulasi lokal dan bukan bukti pembayaran sungguhan.
        </p>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('storefront.index') }}" class="inline-flex min-h-11 items-center rounded-md bg-amber-500 px-5 font-semibold text-slate-950 transition hover:bg-amber-400 active:bg-amber-600">Kembali ke katalog</a>
            <a href="{{ route('transactions.check', ['invoice_code' => $transaction->invoice_code]) }}" class="inline-flex min-h-11 items-center rounded-md border border-slate-600 px-5 font-semibold text-slate-200 transition hover:border-amber-400 hover:text-amber-300 active:bg-slate-800">Cek invoice</a>
        </div>
    </div>
@endsection
