<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class MyOrders extends Component
{
    use WithPagination;

    public array $expanded = [];
    public array $trackingData = [];

    public function toggle(int $orderId): void
    {
        if (in_array($orderId, $this->expanded)) {
            $this->expanded = array_values(array_filter($this->expanded, fn($id) => $id !== $orderId));
        } else {
            $this->expanded[] = $orderId;

            if (!isset($this->trackingData[$orderId])) {
                $order = Order::find($orderId);
                if ($order && $order->TrackingNumber) {
                    try {
                        $envia = app(\App\Services\EnviaService::class);
                        $track = $envia->track($order->TrackingNumber);

                        $this->trackingData[$orderId] = [
                            'status' => $track['status'] ?? 'Desconocido',
                            'carrier' => $track['carrier'] ?? $order->shippingCompany ?? 'Envío',
                            'estimatedDelivery' => $track['estimatedDelivery'] ?? null,
                            'url' => $track['trackUrl'] ?? null,
                        ];
                    } catch (\Exception $e) {
                        $this->trackingData[$orderId] = ['error' => 'Estado temporalmente no disponible'];
                    }
                } else {
                    $this->trackingData[$orderId] = ['error' => 'Aún no se genera la guía de rastreo'];
                }
            }
        }
    }

    public function render()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['orderItems.earphone'])
            ->latest()
            ->paginate(8);

        return view('livewire.my-orders', ['orders' => $orders]);
    }
}
