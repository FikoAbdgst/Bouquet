<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerOrderCancellationTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): User
    {
        return User::factory()->create(['role' => 'customer']);
    }

    private function product(int $stock = 5): Product
    {
        return Product::create([
            'name' => 'Buket Test',
            'description' => null,
            'price' => 100000,
            'category' => 'Fresh Flower',
            'stock' => $stock,
            'is_active' => true,
        ]);
    }

    private function makeOrder(User $user, Product $product, string $status, bool $allowCancel = false): Order
    {
        $order = Order::create([
            'order_code' => 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(3)),
            'user_id' => $user->id,
            'orderer_name' => $user->name,
            'orderer_phone' => '081234567890',
            'needed_date' => now()->addDays(2)->format('Y-m-d'),
            'pickup_method' => 'self_pickup',
            'delivery_address' => null,
            'special_note' => null,
            'total_price' => $product->price * 2,
            'payment_proof_url' => 'payment-proofs/test.jpg',
            'status' => $status,
            'allow_customer_cancel' => $allowCancel,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name_snapshot' => $product->name,
            'price_snapshot' => $product->price,
            'quantity' => 2,
            'subtotal' => $product->price * 2,
        ]);

        return $order;
    }

    public function test_customer_can_cancel_order_while_menunggu_konfirmasi_and_stock_is_restored(): void
    {
        $customer = $this->customer();
        $product = $this->product(stock: 5);
        $order = $this->makeOrder($customer, $product, 'menunggu_konfirmasi');

        $response = $this->actingAs($customer)
            ->post(route('customer.orders.cancel', $order));

        $response->assertRedirect();

        $this->assertSame('dibatalkan', $order->fresh()->status);
        $this->assertSame(7, $product->fresh()->stock);
        $this->assertDatabaseHas('tracking_logs', [
            'order_id' => $order->id,
            'previous_status' => 'menunggu_konfirmasi',
            'new_status' => 'dibatalkan',
            'changed_by' => $customer->id,
        ]);
    }

    public function test_customer_cannot_cancel_confirmed_order_without_admin_permission(): void
    {
        $customer = $this->customer();
        $product = $this->product(stock: 5);
        $order = $this->makeOrder($customer, $product, 'dikonfirmasi', allowCancel: false);

        $response = $this->actingAs($customer)
            ->post(route('customer.orders.cancel', $order));

        $response->assertSessionHas('error');
        $this->assertSame('dikonfirmasi', $order->fresh()->status);
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_customer_can_cancel_confirmed_order_when_admin_allows_it(): void
    {
        $customer = $this->customer();
        $product = $this->product(stock: 5);
        $order = $this->makeOrder($customer, $product, 'dikonfirmasi', allowCancel: true);

        $response = $this->actingAs($customer)
            ->post(route('customer.orders.cancel', $order));

        $response->assertRedirect();
        $this->assertSame('dibatalkan', $order->fresh()->status);
        $this->assertSame(7, $product->fresh()->stock);
    }

    public function test_customer_cannot_cancel_order_that_has_been_processed(): void
    {
        $customer = $this->customer();
        $product = $this->product(stock: 5);
        $order = $this->makeOrder($customer, $product, 'diproses', allowCancel: true);

        $response = $this->actingAs($customer)
            ->post(route('customer.orders.cancel', $order));

        $response->assertSessionHas('error');
        $this->assertSame('diproses', $order->fresh()->status);
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_customer_cannot_cancel_another_users_order(): void
    {
        $owner = $this->customer();
        $other = $this->customer();
        $product = $this->product(stock: 5);
        $order = $this->makeOrder($owner, $product, 'menunggu_konfirmasi');

        $response = $this->actingAs($other)
            ->post(route('customer.orders.cancel', $order));

        $response->assertNotFound();
        $this->assertSame('menunggu_konfirmasi', $order->fresh()->status);
        $this->assertSame(5, $product->fresh()->stock);
        $this->assertDatabaseCount('tracking_logs', 0);
    }

    public function test_admin_can_toggle_customer_cancellation_permission(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = $this->customer();
        $product = $this->product();
        $order = $this->makeOrder($customer, $product, 'dikonfirmasi', allowCancel: false);

        $this->actingAs($admin)
            ->patch(route('admin.orders.cancelable', $order), ['allow_customer_cancel' => 1])
            ->assertRedirect();

        $this->assertTrue($order->fresh()->allow_customer_cancel);

        $this->actingAs($admin)
            ->patch(route('admin.orders.cancelable', $order), ['allow_customer_cancel' => 0])
            ->assertRedirect();

        $this->assertFalse($order->fresh()->allow_customer_cancel);
    }

    public function test_order_history_returns_partial_view_on_ajax_request(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $order = $this->makeOrder($customer, $product, 'menunggu_konfirmasi');

        $response = $this->actingAs($customer)
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get(route('customer.orders.index'));

        $response->assertOk()
            ->assertViewIs('customer.orders-list')
            ->assertSee($order->order_code);
    }
}
