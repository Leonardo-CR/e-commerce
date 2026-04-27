<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\EnviaService;
use Livewire\Component;

class CartDetail extends Component
{
    public string $step = 'cart'; // cart | address | shipping

    public ?int $selectedAddressId = null;

    /** @var array<int, array<string, mixed>> */
    public array $rates = [];

    public ?string $selectedRate = null;
    public float   $shippingCost    = 0;
    public string  $shippingCarrier = '';
    public string  $shippingService = '';

    public function mount(): void
    {
        if (auth()->check()) {
            $default = auth()->user()
                ->addresses()
                ->where('is_default', true)
                ->where('eliminated', false)
                ->first();

            if ($default) {
                $this->selectedAddressId = $default->idAddress;
            }
        }
    }

    // ── Cart step ─────────────────────────────────────────────────────────────

    public function removeItem(int $itemId): void
    {
        $item = CartItem::where('idCart_Item', $itemId)
            ->whereHas('cart', fn ($q) => $q->where('user_id', auth()->id()))
            ->first();

        if ($item) {
            $item->delete();
            $this->dispatch('cart-updated');
        }
    }

    public function updateQuantity(int $itemId, int $quantity): void
    {
        if ($quantity < 1) return;

        $item = CartItem::where('idCart_Item', $itemId)
            ->whereHas('cart', fn ($q) => $q->where('user_id', auth()->id()))
            ->first();

        if ($item) {
            $item->quantity = $quantity;
            $item->subtotal = $quantity * $item->unit_price;
            $item->save();
            $this->dispatch('cart-updated');
        }
    }

    public function proceedToAddress(): void
    {
        $this->step = 'address';
    }

    // ── Address step ──────────────────────────────────────────────────────────

    public function backToCart(): void
    {
        $this->step = 'cart';
    }

    public function confirmAddress(int $addressId): void
    {
        $address = Address::where('idAddress', $addressId)
            ->where('user_id', auth()->id())
            ->where('eliminated', false)
            ->first();

        if (!$address) return;

        $this->selectedAddressId = $addressId;

        try {
            $this->rates         = app(EnviaService::class)->getRatesForAddress($address);
            $this->selectedRate  = null;
            $this->shippingCost  = 0;
            $this->step          = 'shipping';
        } catch (\Throwable $e) {
            session()->flash('error', 'No se pudo cotizar el envío: ' . $e->getMessage());
        }
    }

    // ── Shipping step ─────────────────────────────────────────────────────────

    public function backToAddress(): void
    {
        $this->rates        = [];
        $this->selectedRate = null;
        $this->shippingCost = 0;
        $this->step         = 'address';
    }

    public function selectRate(string $rateKey): void
    {
        $rate = collect($this->rates)->first(
            fn ($r) => $r['carrier'] . '|' . $r['service'] === $rateKey
        );

        if (!$rate) return;

        $this->selectedRate    = $rateKey;
        $this->shippingCost    = (float) ($rate['totalPrice'] ?? 0);
        $this->shippingCarrier = strtoupper($rate['carrier'] ?? '');
        $this->shippingService = $rate['service'] ?? '';
    }

    // ── Checkout ──────────────────────────────────────────────────────────────

    public function checkout()
    {
        $user = auth()->user();
        if (!$user) {
            $this->dispatch('show-auth-modal');
            return;
        }

        if (!$this->selectedRate) return;

        $cart  = $user->cart;
        $items = $cart ? $cart->cartItems()->with('earphone')->get() : collect();

        if ($items->isEmpty()) return;

        $subtotal = $items->sum('subtotal');
        $total    = $subtotal + $this->shippingCost;

        $order = Order::create([
            'user_id'         => $user->id,
            'status'          => 'pending',
            'totalAmount'     => $total,
            'shippingCost'    => $this->shippingCost,
            'shippingCompany' => $this->shippingCarrier,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'idOrder'    => $order->idOrder,
                'idEarphone' => $item->idEarphone,
                'quantity'   => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal'   => $item->subtotal,
            ]);
        }

        session(['pending_order_id' => $order->idOrder]);

        $lineItems = $items->map(fn ($item) => [
            'name'       => $item->earphone->name,
            'unit_price' => (int) ($item->unit_price * 100),
            'quantity'   => $item->quantity,
        ])->values()->all();

        try {
            $config = \Conekta\Configuration::getDefaultConfiguration()
                ->setAccessToken(config('services.conekta.key'));

            $api = new \Conekta\Api\OrdersApi(null, $config);

            $orderRequest = new \Conekta\Model\OrderRequest([
                'currency'       => 'MXN',
                'customer_info'  => [
                    'name'  => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '+525555555555',
                ],
                'line_items'     => $lineItems,
                'shipping_lines' => [[
                    'amount'  => (int) ($this->shippingCost * 100),
                    'carrier' => $this->shippingCarrier,
                ]],
                'metadata'       => ['local_order_id' => $order->idOrder],
                'checkout'       => [
                    'allowed_payment_methods' => ['card', 'cash', 'bank_transfer'],
                    'type'                    => 'HostedPayment',
                    'success_url'             => route('order.success'),
                    'failure_url'             => route('order.failed'),
                ],
            ]);

            $conektaOrder = $api->createOrder($orderRequest, 'es');

            return redirect()->away($conektaOrder->getCheckout()->getUrl());
        } catch (\Throwable $e) {
            $order->orderItems()->delete();
            $order->delete();
            session()->forget('pending_order_id');

            session()->flash('error', 'Error al generar el pago: ' . $e->getMessage());
        }
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $user  = auth()->user();
        $cart  = $user ? $user->cart : null;
        $items = $cart ? $cart->cartItems()->with('earphone')->get() : collect();

        $addresses = $user
            ? $user->addresses()->where('eliminated', false)->orderByDesc('is_default')->get()
            : collect();

        $subtotal = $items->sum('subtotal');

        return view('livewire.cart-detail', [
            'items'     => $items,
            'addresses' => $addresses,
            'subtotal'  => $subtotal,
            'total'     => $subtotal + $this->shippingCost,
        ]);
    }
}
