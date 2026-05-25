<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Refund;

test('order status updates when refund status is changed', function () {
    $user = User::factory()->create();

    $order = Order::create([
        'user_id' => $user->id,
        'status' => 'paid',
        'totalAmount' => 1999.00,
        'shippingCost' => 0.00,
    ]);

    // Create refund (should trigger pending status and set order to refund_requested)
    $refund = Refund::create([
        'idOrder' => $order->idOrder,
        'reason' => 'Defective product',
        'status' => 'pending',
    ]);

    $order->refresh();
    expect($order->status)->toEqual('refund_requested');

    // Update refund to resolved (should set order status to refunded)
    $refund->update(['status' => 'resolved']);
    $order->refresh();
    expect($order->status)->toEqual('refunded');

    // Update refund back to pending (should set order status to refund_requested)
    $refund->update(['status' => 'pending']);
    $order->refresh();
    expect($order->status)->toEqual('refund_requested');
});
