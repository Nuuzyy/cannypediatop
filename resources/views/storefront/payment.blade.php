@extends('layouts.app')

@section('title', 'Pembayaran simulasi | Cannypedia')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <p class="text-sm font-semibold text-amber-300">Tahap pembayaran</p>
            <h1 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Simulasi pembayaran</h1>
            <p class="mt-2 text-sm leading-6 text-slate-300">Halaman ini hanya untuk pengujian. Tidak ada pembayaran sungguhan yang diproses.</p>
        </div>

        <section class="rounded-lg border border-slate-700 bg-slate-800 p-5 sm:p-7" aria-labelledby="invoice-heading">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-700 pb-5">
                <div>
                    <h2 id="invoice-heading" class="text-sm font-semibold text-slate-300">Kode invoice</h2>
                    <p class="mt-1 break-all font-mono text-lg font-bold text-white">{{ $transaction->invoice_code }}</p>
                </div>
                @include('storefront.partials.status', ['transaction' => $transaction])
            </div>

            <dl class="grid gap-5 py-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium text-slate-400">Produk</dt>
                    <dd class="mt-1 font-semibold text-white">{{ $transaction->productItem->product->name }}</dd>
                    <dd class="mt-1 text-sm text-slate-300">{{ $transaction->productItem->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400">Tujuan</dt>
                    <dd class="mt-1 break-all font-semibold text-white">{{ $transaction->target_id }}</dd>
                    @if($transaction->server_id)
                        <dd class="mt-1 text-sm text-slate-300">Server / Zone ID: {{ $transaction->server_id }}</dd>
                    @endif
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400">Metode</dt>
                    <dd class="mt-1 font-semibold text-white">{{ $transaction->payment_method_label }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-400">Total simulasi</dt>
                    <dd class="mt-1 tabular-nums text-lg font-bold text-white">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</dd>
                </div>
            </dl>

            @if($transaction->status === 'pending')
                <div class="border-t border-slate-700 pt-5">
                    <h2 class="font-semibold text-white">Pilih hasil untuk pengujian</h2>
                    <p class="mt-1 text-sm text-slate-300">Aksi ini hanya memperbarui status invoice pada database lokal.</p>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <form action="{{ route('payments.simulate-success', $transaction->invoice_code) }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
                            @csrf
                            <button type="submit" :disabled="submitting" class="inline-flex min-h-12 w-full items-center justify-center rounded-md bg-emerald-500 px-4 font-bold text-slate-950 transition hover:bg-emerald-400 active:bg-emerald-600 disabled:bg-slate-600 disabled:text-slate-300">
                                <span x-show="!submitting">Simulasikan Bayar Sukses</span>
                                <span x-cloak x-show="submitting" role="status">Memperbarui...</span>
                            </button>
                        </form>
                        <form action="{{ route('payments.simulate-failure', $transaction->invoice_code) }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
                            @csrf
                            <button type="submit" :disabled="submitting" class="inline-flex min-h-12 w-full items-center justify-center rounded-md bg-rose-500 px-4 font-bold text-slate-950 transition hover:bg-rose-400 active:bg-rose-600 disabled:bg-slate-600 disabled:text-slate-300">
                                <span x-show="!submitting">Simulasikan Gagal</span>
                                <span x-cloak x-show="submitting" role="status">Memperbarui...</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="border-t border-slate-700 pt-5">
                    <p class="text-sm text-slate-300">Transaksi sudah memiliki hasil simulasi.</p>
                    <a href="{{ route('invoices.show', $transaction->invoice_code) }}" class="mt-4 inline-flex min-h-11 items-center rounded-md bg-amber-500 px-5 font-semibold text-slate-950 transition hover:bg-amber-400 active:bg-amber-600">
                        Lihat invoice
                    </a>
                </div>
            @endif
        </section>
    </div>
@endsection
