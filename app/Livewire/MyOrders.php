<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Refund;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class MyOrders extends Component
{
    use WithPagination;

    public const REFUND_ELIGIBLE_STATUSES = ['paid', 'completed', 'shipped', 'delivering', 'delivered'];
    public const REFUND_REQUESTED_STATUS  = 'refund_requested';

    public array $expanded = [];
    public array $trackingData = [];

    public bool $showRefundModal = false;
    public ?int $refundOrderId   = null;

    #[Validate('required|string|min:10|max:1000')]
    public string $refundReason = '';

    public function toggle(int $orderId): void
    {
        if (in_array($orderId, $this->expanded)) {
            $this->expanded = array_values(array_filter($this->expanded, fn ($id) => $id !== $orderId));
            return;
        }

        $this->expanded[] = $orderId;

        if (isset($this->trackingData[$orderId])) {
            return;
        }

        $order = Order::find($orderId);
        if ($order && $order->TrackingNumber) {
            try {
                $envia = app(\App\Services\EnviaService::class);
                $track = $envia->track($order->TrackingNumber);

                $this->trackingData[$orderId] = [
                    'status'            => $track['status'] ?? 'Desconocido',
                    'carrier'           => $track['carrier'] ?? $order->shippingCompany ?? 'Envío',
                    'estimatedDelivery' => $track['estimatedDelivery'] ?? null,
                    'url'               => $track['trackUrl'] ?? null,
                ];
            } catch (\Exception $e) {
                $this->trackingData[$orderId] = ['error' => 'Estado temporalmente no disponible'];
            }
        } else {
            $this->trackingData[$orderId] = ['error' => 'Aún no se genera la guía de rastreo'];
        }
    }

    public function openRefundModal(int $orderId): void
    {
        $order = Order::where('idOrder', $orderId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order || !in_array($order->status, self::REFUND_ELIGIBLE_STATUSES, true)) {
            session()->flash('error', 'Este pedido no es elegible para reembolso.');
            return;
        }

        $this->refundOrderId   = $orderId;
        $this->refundReason    = '';
        $this->showRefundModal = true;
        $this->resetValidation();
    }

    public function closeRefundModal(): void
    {
        $this->showRefundModal = false;
        $this->refundOrderId   = null;
        $this->refundReason    = '';
        $this->resetValidation();
    }

    public function submitRefund(): void
    {
        $this->validate();

        $order = Order::where('idOrder', $this->refundOrderId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order || !in_array($order->status, self::REFUND_ELIGIBLE_STATUSES, true)) {
            session()->flash('error', 'El pedido ya no es elegible para reembolso.');
            $this->closeRefundModal();
            return;
        }

        Refund::create([
            'idOrder' => $order->idOrder,
            'reason'  => $this->refundReason,
            'status'  => 'pending',
        ]);

        $order->update(['status' => self::REFUND_REQUESTED_STATUS]);

        session()->flash(
            'success',
            'Solicitud enviada. Nuestro equipo de soporte revisará tu caso y te contactará en las próximas 48 horas.'
        );

        $this->closeRefundModal();
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
