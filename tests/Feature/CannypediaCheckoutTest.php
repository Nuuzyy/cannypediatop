<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Transaction;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CannypediaCheckoutTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_can_create_a_pending_transaction_from_the_catalog(): void
    {
        $product = $this->createProduct(hasServerId: true);
        $item = $product->items()->firstOrFail();

        $response = $this->post(route('transactions.store', $product), [
            'product_item_id' => $item->id,
            'target_id' => 'player-123',
            'server_id' => '5432',
            'payment_method' => 'qris_simulation',
        ]);

        $transaction = Transaction::query()->firstOrFail();

        $response->assertRedirect(route('payments.show', $transaction->invoice_code));
        $this->assertDatabaseHas('transactions', [
            'product_item_id' => $item->id,
            'target_id' => 'player-123',
            'server_id' => '5432',
            'payment_method' => 'qris_simulation',
            'total_price' => 19000,
            'status' => 'pending',
        ]);
    }

    public function test_guest_can_browse_catalog_and_server_id_product_order_page(): void
    {
        $this->seed(DatabaseSeeder::class);

        $catalog = $this->get(route('storefront.index'));
        $order = $this->get(route('products.show', Product::query()->where('slug', 'mobile-legends')->firstOrFail()));

        $catalog->assertOk()->assertSee('Mobile Legends: Bang Bang');
        $order->assertOk()
            ->assertSee('86 Diamonds')
            ->assertSee('Server / Zone ID')
            ->assertSee('Beli Sekarang');
    }

    public function test_guest_cannot_select_an_item_from_another_product(): void
    {
        $product = $this->createProduct();
        $otherProduct = $this->createProduct(slug: 'other-game');
        $otherItem = $otherProduct->items()->firstOrFail();

        $response = $this->from(route('products.show', $product))
            ->post(route('transactions.store', $product), [
                'product_item_id' => $otherItem->id,
                'target_id' => 'player-123',
                'payment_method' => 'qris_simulation',
            ]);

        $response->assertSessionHasErrors('product_item_id');
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_server_id_is_required_for_products_that_use_it(): void
    {
        $product = $this->createProduct(hasServerId: true);
        $item = $product->items()->firstOrFail();

        $response = $this->from(route('products.show', $product))
            ->post(route('transactions.store', $product), [
                'product_item_id' => $item->id,
                'target_id' => 'player-123',
                'payment_method' => 'qris_simulation',
            ]);

        $response->assertSessionHasErrors('server_id');
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_pending_transaction_can_be_simulated_as_successful(): void
    {
        $transaction = $this->createTransaction();

        $payment = $this->get(route('payments.show', $transaction->invoice_code));
        $response = $this->post(route('payments.simulate-success', $transaction->invoice_code));

        $payment->assertOk()
            ->assertSee('Simulasikan Bayar Sukses')
            ->assertSee('Simulasikan Gagal');
        $response->assertRedirect(route('invoices.show', $transaction->invoice_code));
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'success',
        ]);

        $this->get(route('invoices.show', $transaction->invoice_code))
            ->assertOk()
            ->assertSee('Sukses')
            ->assertSee('Rp19.000');
    }

    public function test_pending_transaction_can_be_simulated_as_failed(): void
    {
        $transaction = $this->createTransaction();

        $response = $this->post(route('payments.simulate-failure', $transaction->invoice_code));

        $response->assertRedirect(route('invoices.show', $transaction->invoice_code));
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'failed',
        ]);
    }

    public function test_invoice_lookup_renders_a_matching_guest_transaction(): void
    {
        $transaction = $this->createTransaction();

        $response = $this->get(route('transactions.check', [
            'invoice_code' => $transaction->invoice_code,
        ]));

        $response->assertOk()
            ->assertSee($transaction->invoice_code)
            ->assertSee('Test Game');
    }

    public function test_database_seeder_adds_each_requested_product_category_and_nominals(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('products', 8);
        $this->assertDatabaseCount('product_items', 34);
        $this->assertSame(4, Product::query()->where('category', 'games')->count());
        $this->assertSame(2, Product::query()->where('category', 'pulsa')->count());
        $this->assertSame(2, Product::query()->where('category', 'ewallet')->count());
        $this->assertSame(0, Product::query()->has('items', '<', 4)->count());
    }

    private function createProduct(
        bool $hasServerId = false,
        string $slug = 'test-game',
    ): Product {
        $product = Product::query()->create([
            'name' => 'Test Game',
            'slug' => $slug,
            'category' => 'games',
            'publisher' => 'Test Publisher',
            'target_label' => 'Player ID',
            'has_server_id' => $hasServerId,
            'is_active' => true,
        ]);

        $product->items()->create([
            'name' => '86 Diamonds',
            'price' => 19000,
            'is_active' => true,
        ]);

        return $product;
    }

    private function createTransaction(): Transaction
    {
        $product = $this->createProduct();
        $item = $product->items()->firstOrFail();

        return Transaction::query()->create([
            'invoice_code' => 'CNY-TEST-00000001',
            'product_item_id' => $item->id,
            'target_id' => 'player-123',
            'payment_method' => 'qris_simulation',
            'total_price' => $item->price,
            'status' => 'pending',
        ]);
    }
}
