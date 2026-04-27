<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = \App\Models\Earphone::take(5)->get();
    return view('welcome', compact('products'));
});

Route::get('/headphones', function () {
    return view('headphones');
})->name('headphones');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('headphones');
    })->name('dashboard');

    Route::get('/cart', function () {
        return view('cart');
    })->name('cart');

    Route::get('/addresses', function () {
        return view('addresses');
    })->name('addresses');

    Route::get('/orders', function () {
        return view('orders');
    })->name('orders');

    Route::get('/order/success', function () {
        $orderId = session()->pull('pending_order_id');

        if ($orderId) {
            $order = Order::where('idOrder', $orderId)
                ->where('user_id', auth()->id())
                ->first();

            if ($order) {
                $order->update(['status' => 'paid']);

                // Vaciar el carrito
                $cart = auth()->user()->cart;
                if ($cart) {
                    $cart->cartItems()->delete();
                }
            }
        }

        return view('order.success');
    })->name('order.success');

    Route::get('/order/failed', function () {
        $orderId = session()->pull('pending_order_id');

        if ($orderId) {
            $order = Order::where('idOrder', $orderId)
                ->where('user_id', auth()->id())
                ->first();

            $order?->update(['status' => 'failed']);
        }

        return view('order.failed');
    })->name('order.failed');
});
