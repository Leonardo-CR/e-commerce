<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Earphone;
use App\Models\Color;

test('order success route registers payment and empties cart', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create a color
    $color = Color::create(['name' => 'Negro', 'hex' => '#000000']);

    // Create earphone
    $earphone = Earphone::create([
        'name' => 'Halo Pro',
        'description' => 'Great headphones',
        'price' => 1999.00,
        'stock' => 10,
        'image_url' => 'http://example.com/image.jpg',
    ]);

    // Create cart and items
    $cart = Cart::create(['user_id' => $user->id, 'status' => 'active']);
    CartItem::create([
        'idCart' => $cart->idCart,
        'idEarphone' => $earphone->idEarphone,
        'color_id' => $color->id,
        'quantity' => 1,
        'unit_price' => 1999.00,
        'subtotal' => 1999.00,
    ]);

    // Create pending order
    $order = Order::create([
        'user_id' => $user->id,
        'status' => 'pending',
        'totalAmount' => 2099.00,
        'shippingCost' => 100.00,
    ]);

    // Put pending order ID in session
    session(['pending_order_id' => $order->idOrder]);

    // Hit order success route
    $response = $this->get(route('order.success'));

    $response->assertStatus(200);

    // Verify order is paid
    $order->refresh();
    expect($order->status)->toEqual('paid');
    expect($order->idPayment)->not->toBeNull();

    // Verify payment is registered
    $this->assertDatabaseHas('payments', [
        'idPayment' => $order->idPayment,
        'amount' => 2099.00,
        'status' => 'paid',
        'method' => 'conekta',
    ]);

    // Verify cart is empty
    expect($cart->cartItems()->count())->toEqual(0);
});
