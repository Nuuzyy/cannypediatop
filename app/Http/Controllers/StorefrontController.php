<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'category' => ['nullable', 'in:games,pulsa,ewallet'],
            'search' => ['nullable', 'string', 'max:80'],
        ]);

        $products = Product::query()
            ->where('is_active', true)
            ->whereHas('items', fn ($query) => $query->where('is_active', true))
            ->with(['items' => fn ($query) => $query->where('is_active', true)->orderBy('price')])
            ->when(
                $filters['category'] ?? null,
                fn ($query, $category) => $query->where('category', $category),
            )
            ->when($filters['search'] ?? null, function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('publisher', 'like', "%{$search}%");
                });
            })
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('storefront.index', [
            'products' => $products,
            'category' => $filters['category'] ?? null,
            'search' => $filters['search'] ?? '',
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load([
            'items' => fn ($query) => $query->where('is_active', true)->orderBy('price'),
        ]);

        $itemOptions = $product->items->map(fn (ProductItem $item): array => [
            'id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
        ])->values();

        return view('storefront.order', compact('product', 'itemOptions'));
    }

    public function payment(string $invoice_code): View
    {
        $transaction = $this->findTransaction($invoice_code);

        return view('storefront.payment', compact('transaction'));
    }

    public function invoice(string $invoice_code): View
    {
        $transaction = $this->findTransaction($invoice_code);

        return view('storefront.invoice', compact('transaction'));
    }

    public function check(Request $request): View
    {
        $data = $request->validate([
            'invoice_code' => ['nullable', 'string', 'max:40'],
        ], [
            'invoice_code.max' => 'Kode invoice terlalu panjang.',
        ]);

        $invoiceCode = trim($data['invoice_code'] ?? '');
        $transaction = $invoiceCode === ''
            ? null
            : Transaction::query()->where('invoice_code', $invoiceCode)->first();

        return view('storefront.check', [
            'invoiceCode' => $invoiceCode,
            'transaction' => $transaction,
            'notFound' => $invoiceCode !== '' && $transaction === null,
        ]);
    }

    private function findTransaction(string $invoiceCode): Transaction
    {
        return Transaction::query()
            ->with('productItem.product')
            ->where('invoice_code', $invoiceCode)
            ->firstOrFail();
    }
}
