<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokoOnlineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guest_can_view_product_catalog(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Laptop Sleek 14 Inch');
        $response->assertSee('Smartwatch Fitness Tracker');
        $response->assertSee('Stok Habis');
        $response->assertSee('Login untuk Membeli');
    }

    public function test_guest_cannot_add_to_cart_without_login(): void
    {
        $response = $this->post(route('cart.add', 'BRG001'), [
            'jumlah' => 1,
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_login_with_username_and_password(): void
    {
        $response = $this->post(route('login.post'), [
            'username' => 'budi',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post(route('login.post'), [
            'username' => 'budi',
            'password' => 'salah123',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_add_item_to_cart(): void
    {
        $user = User::where('username', 'budi')->first();

        $response = $this->actingAs($user)->post(route('cart.add', 'BRG002'), [
            'jumlah' => 3,
        ]);

        $response->assertSessionHas('success');
        $response->assertSessionHas('cart', ['BRG002' => 3]);

        $cartResponse = $this->actingAs($user)->get(route('cart.index'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('Mouse Wireless Ergonomis');
        $cartResponse->assertSee('450.000');
    }

    public function test_cannot_add_product_with_zero_stock_to_cart(): void
    {
        $user = User::where('username', 'budi')->first();

        $response = $this->actingAs($user)->post(route('cart.add', 'BRG010'), [
            'jumlah' => 1,
        ]);

        $response->assertSessionHas('error', 'Barang dengan stok 0 tidak dapat dibeli.');
    }

    public function test_cannot_add_quantity_exceeding_stock(): void
    {
        $user = User::where('username', 'budi')->first();
        $product = Product::where('id_barang', 'BRG001')->first();

        $response = $this->actingAs($user)->post(route('cart.add', 'BRG001'), [
            'jumlah' => $product->stok + 1,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_can_update_and_remove_cart_item(): void
    {
        $user = User::where('username', 'budi')->first();

        $this->actingAs($user)->post(route('cart.add', 'BRG002'), ['jumlah' => 2]);

        $updateResponse = $this->actingAs($user)->patch(route('cart.update', 'BRG002'), ['jumlah' => 5]);
        $updateResponse->assertSessionHas('success');
        $this->assertEquals(5, session('cart')['BRG002']);

        $removeResponse = $this->actingAs($user)->delete(route('cart.remove', 'BRG002'));
        $removeResponse->assertSessionHas('success');
        $this->assertArrayNotHasKey('BRG002', session('cart', []));
    }

    public function test_checkout_creates_order_and_reduces_stock(): void
    {
        $user = User::where('username', 'budi')->first();
        $product = Product::where('id_barang', 'BRG003')->first();
        $initialStock = $product->stok;
        $buyQty = 2;

        $this->actingAs($user)->post(route('cart.add', 'BRG003'), ['jumlah' => $buyQty]);

        $checkoutResponse = $this->actingAs($user)->post(route('checkout.process'), [
            'alamat_pengiriman' => 'Jl. Pengiriman Khusus No. 88, Jakarta',
        ]);

        $this->assertDatabaseHas('orders', [
            'id_user' => $user->id_user,
            'total_harga' => $product->harga * $buyQty,
            'alamat_pengiriman' => 'Jl. Pengiriman Khusus No. 88, Jakarta',
        ]);

        $createdOrder = Order::where('id_user', $user->id_user)->latest()->first();
        $this->assertNotNull($createdOrder);

        $this->assertDatabaseHas('order_details', [
            'id_order' => $createdOrder->id_order,
            'id_barang' => 'BRG003',
            'harga_satuan' => $product->harga,
            'jumlah_beli' => $buyQty,
        ]);

        $this->assertEquals($initialStock - $buyQty, $product->fresh()->stok);
        $this->assertNull(session('cart'));

        $checkoutResponse->assertRedirect(route('orders.show', $createdOrder->id_order));
    }

    public function test_user_can_view_order_history_and_details(): void
    {
        $user = User::where('username', 'budi')->first();

        $order = Order::create([
            'id_order' => 'ORD2610100001',
            'id_user' => $user->id_user,
            'tanggal_order' => now(),
            'total_harga' => 300000,
            'alamat_pengiriman' => $user->alamat,
        ]);

        OrderDetail::create([
            'id_order' => $order->id_order,
            'id_barang' => 'BRG002',
            'harga_satuan' => 150000,
            'jumlah_beli' => 2,
        ]);

        $historyResponse = $this->actingAs($user)->get(route('orders.index'));
        $historyResponse->assertStatus(200);
        $historyResponse->assertSee($order->id_order);

        $detailResponse = $this->actingAs($user)->get(route('orders.show', $order->id_order));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($order->id_order);
        $detailResponse->assertSee('Mouse Wireless Ergonomis');
    }
}
