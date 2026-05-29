<?php

use App\Models\Order;
use App\Mail\OrderPaidMail;
use Illuminate\Support\Facades\Mail;
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
                // Registrar el pago en el sistema si aún no se ha hecho
                if (!$order->idPayment) {
                    $payment = \App\Models\Payment::create([
                        'payment_date' => now(),
                        'amount'       => $order->totalAmount,
                        'status'       => 'paid',
                        'method'       => 'conekta',
                    ]);

                    $order->update([
                        'status'    => 'paid',
                        'idPayment' => $payment->idPayment,
                    ]);
                } else {
                    $order->update(['status' => 'paid']);
                }

                // Vaciar el carrito
                $cart = auth()->user()->cart;
                if ($cart) {
                    $cart->cartItems()->delete();
                }

                // Enviar el correo de confirmación de compra
                try {
                    Mail::to(auth()->user()->email)->send(new OrderPaidMail($order));
                } catch (\Throwable $mailEx) {
                    \Illuminate\Support\Facades\Log::error("No se pudo enviar el correo del pedido {$order->idOrder}: " . $mailEx->getMessage());
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

            if ($order && $order->status !== 'failed' && $order->status !== 'paid') {
                Illuminate\Support\Facades\DB::beginTransaction();
                try {
                    foreach ($order->orderItems as $orderItem) {
                        $earphone = \App\Models\Earphone::where('idEarphone', $orderItem->idEarphone)
                            ->lockForUpdate()
                            ->first();

                        if ($earphone) {
                            $colors = $earphone->colors ?? [];
                            foreach ($colors as $index => $colorData) {
                                if (($colorData['color_id'] ?? null) == $orderItem->color_id) {
                                    $colors[$index]['stock'] = ((int) ($colorData['stock'] ?? 0)) + $orderItem->quantity;
                                    break;
                                }
                            }
                            $earphone->colors = $colors;
                            $earphone->stock = collect($colors)->sum('stock');
                            $earphone->save();
                        }
                    }
                    $order->update(['status' => 'failed']);
                    Illuminate\Support\Facades\DB::commit();
                } catch (\Throwable $e) {
                    Illuminate\Support\Facades\DB::rollBack();
                }
            }
        }

        return view('order.failed');
    })->name('order.failed');
});
