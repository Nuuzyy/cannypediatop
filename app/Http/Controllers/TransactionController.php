<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'product_item_id' => [
                'required',
                'integer',
                Rule::exists('product_items', 'id')->where(
                    fn (Builder $query) => $query
                        ->where('product_id', $product->id)
                        ->where('is_active', true),
                ),
            ],
            'target_id' => ['required', 'string', 'max:120'],
            'server_id' => [
                $product->has_server_id ? 'required' : 'nullable',
                'string',
                'max:80',
            ],
            'payment_method' => [
                'required',
                Rule::in([
                    'qris_simulation',
                    'virtual_account_simulation',
                    'ewallet_simulation',
                ]),
            ],
        ], [
            'product_item_id.required' => 'Pilih nominal terlebih dahulu.',
            'product_item_id.exists' => 'Nominal yang dipilih tidak sesuai dengan produk ini.',
            'target_id.required' => 'Isi data tujuan sebelum melanjutkan.',
            'server_id.required' => 'Isi Server / Zone ID untuk produk ini.',
            'payment_method.required' => 'Pilih metode pembayaran simulasi.',
            'payment_method.in' => 'Metode pembayaran yang dipilih tidak tersedia.',
        ]);

        $item = $product->items()
            ->whereKey($validated['product_item_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $invoiceCode = 'CNY-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));

        $transaction = Transaction::query()->create([
            'invoice_code' => $invoiceCode,
            'product_item_id' => $item->id,
            'target_id' => $validated['target_id'],
            'server_id' => $product->has_server_id ? $validated['server_id'] : null,
            'payment_method' => $validated['payment_method'],
            'total_price' => $item->price,
            'status' => 'pending',
        ]);

        return redirect()->route('payments.show', $transaction->invoice_code);
    }

    public function simulateSuccess(string $invoice_code): RedirectResponse
    {
        return $this->setPendingTransactionStatus($invoice_code, 'success');
    }

    public function simulateFailure(string $invoice_code): RedirectResponse
    {
        return $this->setPendingTransactionStatus($invoice_code, 'failed');
    }

    private function setPendingTransactionStatus(string $invoiceCode, string $status): RedirectResponse
    {
        $transaction = Transaction::query()
            ->where('invoice_code', $invoiceCode)
            ->firstOrFail();

        Transaction::query()
            ->whereKey($transaction->id)
            ->where('status', 'pending')
            ->update(['status' => $status]);

        return redirect()->route('invoices.show', $transaction->invoice_code);
    }
}
