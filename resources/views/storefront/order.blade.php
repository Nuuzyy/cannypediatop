@extends('layouts.app')

@section('title', 'Pesan ' . $product->name . ' | Cannypedia')

@section('content')
    <div class="space-y-7">
        <div class="flex flex-wrap items-center gap-3 text-sm">
            <a href="{{ route('storefront.index') }}" class="inline-flex min-h-11 items-center text-slate-300 underline underline-offset-4">Katalog</a>
            <span aria-hidden="true" class="text-slate-500">/</span>
            <span class="text-white">{{ $product->name }}</span>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem] lg:items-start">
            <form
                action="{{ route('transactions.store', $product) }}"
                method="POST"
                x-data="{
                    items: @js($itemOptions),
                    selectedItem: @js((int) old('product_item_id')),
                    payment: @js(old('payment_method', 'qris_simulation')),
                    target: @js(old('target_id', '')),
                    server: @js(old('server_id', '')),
                    submitting: false,
                    get selected() {
                        return this.items.find((item) => item.id === Number(this.selectedItem));
                    },
                    get paymentLabel() {
                        return {
                            qris_simulation: 'QRIS Simulasi',
                            virtual_account_simulation: 'Virtual Account Simulasi',
                            ewallet_simulation: 'E-Wallet Simulasi'
                        }[this.payment];
                    },
                    formatPrice(price) {
                        return new Intl.NumberFormat('id-ID').format(price);
                    }
                }"
                @submit="submitting = true"
                class="space-y-5"
            >
                @csrf
                <section class="rounded-lg border border-slate-700 bg-slate-800 p-5 sm:p-7" aria-labelledby="product-heading">
                    <p class="text-sm font-semibold text-amber-300">{{ $product->publisher }}</p>
                    <h1 id="product-heading" class="mt-2 text-2xl font-bold text-white">{{ $product->name }}</h1>
                    <p class="mt-2 text-sm text-slate-300">Isi data tujuan dan pilih nominal untuk melanjutkan.</p>
                </section>

                @if($errors->any())
                    <div class="rounded-md border border-rose-500/60 bg-rose-500/10 p-4 text-sm text-rose-200" role="alert">
                        <p class="font-semibold">Periksa kembali pesananmu.</p>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <section class="rounded-lg border border-slate-700 bg-slate-800 p-5 sm:p-7" aria-labelledby="target-heading">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex size-8 shrink-0 items-center justify-center rounded-md bg-amber-500 text-sm font-bold text-slate-950">1</span>
                        <h2 id="target-heading" class="text-lg font-bold text-white">Data tujuan</h2>
                    </div>
                    <div class="grid gap-4 {{ $product->has_server_id ? 'sm:grid-cols-2' : '' }}">
                        <div>
                            <label for="target_id" class="mb-2 block text-sm font-semibold text-slate-200">{{ $product->target_label }}</label>
                            <input
                                id="target_id"
                                name="target_id"
                                type="text"
                                value="{{ old('target_id') }}"
                                x-model="target"
                                maxlength="120"
                                autocomplete="off"
                                required
                                class="min-h-12 w-full rounded-md border border-slate-600 bg-slate-900 px-4 text-sm text-white placeholder:text-slate-400 focus:border-amber-400"
                                placeholder="Masukkan {{ strtolower($product->target_label) }}"
                            >
                            @error('target_id')
                                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                        @if($product->has_server_id)
                            <div>
                                <label for="server_id" class="mb-2 block text-sm font-semibold text-slate-200">Server / Zone ID</label>
                                <input
                                    id="server_id"
                                    name="server_id"
                                    type="text"
                                    value="{{ old('server_id') }}"
                                    x-model="server"
                                    maxlength="80"
                                    autocomplete="off"
                                    required
                                    class="min-h-12 w-full rounded-md border border-slate-600 bg-slate-900 px-4 text-sm text-white placeholder:text-slate-400 focus:border-amber-400"
                                    placeholder="Masukkan server atau zone"
                                >
                                @error('server_id')
                                    <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                </section>

                <section class="rounded-lg border border-slate-700 bg-slate-800 p-5 sm:p-7" aria-labelledby="item-heading">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex size-8 shrink-0 items-center justify-center rounded-md bg-amber-500 text-sm font-bold text-slate-950">2</span>
                        <div>
                            <h2 id="item-heading" class="text-lg font-bold text-white">Pilih nominal</h2>
                            <p class="mt-1 text-sm text-slate-400">Harga diperbarui saat nominal dipilih.</p>
                        </div>
                    </div>
                    @if($product->items->isEmpty())
                        <p class="rounded-md border border-slate-700 bg-slate-900 p-4 text-sm text-slate-300" role="status">Nominal untuk produk ini belum tersedia.</p>
                    @else
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach($product->items as $item)
                                <button
                                    type="button"
                                    @click="selectedItem = {{ $item->id }}"
                                    :aria-pressed="selectedItem === {{ $item->id }}"
                                    :class="selectedItem === {{ $item->id }} ? 'border-amber-400 bg-slate-700' : 'border-slate-700 bg-slate-900 hover:border-slate-500'"
                                    class="flex min-h-16 items-center justify-between gap-3 rounded-md border px-4 py-3 text-left transition active:bg-slate-700"
                                >
                                    <span class="font-semibold text-white">{{ $item->name }}</span>
                                    <span class="shrink-0 tabular-nums text-sm font-bold text-amber-300">Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                    <input type="hidden" name="product_item_id" :value="selectedItem">
                    @error('product_item_id')
                        <p class="mt-3 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </section>

                <section class="rounded-lg border border-slate-700 bg-slate-800 p-5 sm:p-7" aria-labelledby="payment-heading">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex size-8 shrink-0 items-center justify-center rounded-md bg-amber-500 text-sm font-bold text-slate-950">3</span>
                        <div>
                            <h2 id="payment-heading" class="text-lg font-bold text-white">Metode pembayaran</h2>
                            <p class="mt-1 text-sm text-slate-400">Pilih metode untuk simulasi lokal.</p>
                        </div>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-3">
                        @foreach([
                            'qris_simulation' => 'QRIS Simulasi',
                            'virtual_account_simulation' => 'Virtual Account Simulasi',
                            'ewallet_simulation' => 'E-Wallet Simulasi',
                        ] as $value => $label)
                            <button
                                type="button"
                                @click="payment = '{{ $value }}'"
                                :aria-pressed="payment === '{{ $value }}'"
                                :class="payment === '{{ $value }}' ? 'border-amber-400 bg-slate-700 text-white' : 'border-slate-700 bg-slate-900 text-slate-200 hover:border-slate-500'"
                                class="min-h-16 rounded-md border px-4 py-3 text-left text-sm font-semibold transition active:bg-slate-700"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="payment_method" :value="payment">
                    @error('payment_method')
                        <p class="mt-3 text-sm text-rose-300">{{ $message }}</p>
                    @enderror
                </section>

                <section class="rounded-lg border border-slate-700 bg-slate-800 p-5 sm:p-7" aria-labelledby="review-heading">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex size-8 shrink-0 items-center justify-center rounded-md bg-amber-500 text-sm font-bold text-slate-950">4</span>
                        <h2 id="review-heading" class="text-lg font-bold text-white">Ringkasan pesanan</h2>
                    </div>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-400">Tujuan</dt>
                            <dd class="max-w-[65%] break-all text-right font-medium text-white" x-text="target || 'Belum diisi'"></dd>
                        </div>
                        @if($product->has_server_id)
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-400">Server / Zone ID</dt>
                                <dd class="max-w-[65%] break-all text-right font-medium text-white" x-text="server || 'Belum diisi'"></dd>
                            </div>
                        @endif
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-400">Nominal</dt>
                            <dd class="text-right font-medium text-white" x-text="selected ? selected.name : 'Pilih nominal'"></dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-400">Pembayaran</dt>
                            <dd class="text-right font-medium text-white" x-text="paymentLabel"></dd>
                        </div>
                        <div class="flex justify-between gap-4 border-t border-slate-700 pt-4">
                            <dt class="font-semibold text-slate-200">Total</dt>
                            <dd class="tabular-nums text-lg font-bold text-white" x-text="selected ? `Rp${formatPrice(selected.price)}` : 'Rp0'"></dd>
                        </div>
                    </dl>
                    <button
                        type="submit"
                        :disabled="submitting || !selectedItem"
                        class="mt-6 inline-flex min-h-12 w-full items-center justify-center rounded-md bg-amber-500 px-5 font-bold text-slate-950 transition hover:bg-amber-400 active:bg-amber-600 disabled:bg-slate-600 disabled:text-slate-300"
                    >
                        <span x-show="!submitting">Beli Sekarang</span>
                        <span x-cloak x-show="submitting" role="status">Membuat transaksi...</span>
                    </button>
                    <p class="mt-3 text-xs leading-5 text-slate-400">Tidak ada pembayaran atau pengiriman produk sungguhan.</p>
                </section>
            </form>

            <aside class="rounded-lg border border-slate-700 bg-slate-800 p-5 lg:sticky lg:top-6" aria-labelledby="summary-heading">
                <h2 id="summary-heading" class="text-base font-bold text-white">Sebelum melanjutkan</h2>
                <p class="mt-3 text-sm leading-6 text-slate-300">
                    Pastikan ID tujuan dan nominal sesuai. Status pembayaran hanya dapat diubah lewat tombol simulasi.
                </p>
                <a href="{{ route('transactions.check') }}" class="mt-5 inline-flex min-h-11 items-center text-sm font-semibold text-amber-300 underline underline-offset-4">
                    Cari invoice yang sudah dibuat
                </a>
            </aside>
        </div>
    </div>
@endsection
