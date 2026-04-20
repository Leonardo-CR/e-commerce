<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Earphone;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Refund;
use App\Models\Review;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── 1. Admin fijo (para acceder a Filament) ──────────────────────────
        $admin = User::factory()->create([
            'name'         => 'Administrador',
            'email'        => 'admin@audifonos.mx',
            'password'     => Hash::make('password'),
            'is_admin'     => true,
            'is_superuser' => true,
            'CURP'         => 'ADMA900101MDFXXX01',
            'phone'        => '5512345678',
        ]);

        // ── 2. Usuarios regulares ─────────────────────────────────────────────
        $users = User::factory(12)->create([
            'is_admin'     => false,
            'is_superuser' => false,
        ]);

        $todos = $users->push($admin);

        // ── 3. Direcciones (1-2 por usuario) ─────────────────────────────────
        $todos->each(function (User $user) {
            $cantidad = fake()->numberBetween(1, 2);
            Address::factory($cantidad)->create(['user_id' => $user->id]);
        });

        // ── 4. Proveedores ───────────────────────────────────────────────────
        $suppliers = Supplier::factory(6)->create();

        // ── 5. Catálogo de audífonos ─────────────────────────────────────────
        $earphones = collect();
        foreach (range(1, 20) as $i) {
            $numColors = fake()->numberBetween(1, 3);
            $colors = [];
            for ($c = 0; $c < $numColors; $c++) {
                $colors[] = [
                    'hex' => fake()->safeHexColor(),
                    'idSupplier' => $suppliers->random()->idSupplier,
                    'stock' => fake()->numberBetween(5, 20),
                    'image' => 'images/products/' . fake()->randomElement(['apple.png', 'huawei.png', 'samsung.png']),
                ];
            }

            $earphone = Earphone::factory()->create([
                'colors' => $colors,
                'stock' => collect($colors)->sum('stock'),
            ]);
            $earphones->push($earphone);
        }

        // ── 6. Carritos y artículos ───────────────────────────────────────────
        $users->each(function (User $user) use ($earphones) {
            $cart = Cart::factory()->create(['user_id' => $user->id]);

            // 1-3 artículos por carrito
            $seleccion = $earphones->random(fake()->numberBetween(1, 3));
            foreach ($seleccion as $earphone) {
                $qty   = fake()->numberBetween(1, 3);
                $price = $earphone->price;
                CartItem::factory()->create([
                    'idCart'     => $cart->idCart,
                    'idEarphone' => $earphone->idEarphone,
                    'quantity'   => $qty,
                    'unit_price' => $price,
                    'subtotal'   => round($price * $qty, 2),
                ]);
            }
        });

        // ── 7. Reseñas ───────────────────────────────────────────────────────
        $users->each(function (User $user) use ($earphones) {
            $cantidad = fake()->numberBetween(1, 3);
            $earphones->random($cantidad)->each(function (Earphone $earphone) use ($user) {
                Review::factory()->create([
                    'user_id'    => $user->id,
                    'idEarphone' => $earphone->idEarphone,
                ]);
            });
        });

        // ── 8. Pagos → Pedidos → Artículos → Reembolsos ──────────────────────
        $todos->each(function (User $user) use ($earphones) {
            $numOrders = fake()->numberBetween(1, 4);

            for ($i = 0; $i < $numOrders; $i++) {
                $payment = Payment::factory()->create();

                $order = Order::factory()->create([
                    'user_id'   => $user->id,
                    'idPayment' => $payment->idPayment,
                ]);

                // 1-3 artículos por pedido
                $seleccion = $earphones->random(fake()->numberBetween(1, 3));
                foreach ($seleccion as $earphone) {
                    $qty      = fake()->numberBetween(1, 2);
                    $price    = $earphone->price;
                    $discount = fake()->boolean(20) ? round($price * 0.1, 2) : 0;
                    $tax      = round($price * $qty * 0.16, 2);
                    OrderItem::factory()->create([
                        'idOrder'    => $order->idOrder,
                        'idEarphone' => $earphone->idEarphone,
                        'quantity'   => $qty,
                        'unit_price' => $price,
                        'discount'   => $discount,
                        'tax'        => $tax,
                        'subtotal'   => round(($price * $qty) - $discount + $tax, 2),
                    ]);
                }

                // ~15% de pedidos tienen reembolso
                if (fake()->boolean(15)) {
                    Refund::factory()->create(['idOrder' => $order->idOrder]);
                }
            }
        });

        // ── 9. Compras a proveedores ──────────────────────────────────────────
        $suppliers->each(function (Supplier $supplier) use ($earphones) {
            $numPurchases = fake()->numberBetween(1, 3);

            for ($i = 0; $i < $numPurchases; $i++) {
                $purchase = Purchase::factory()->create();

                // Audífonos que tengan al menos un color de este proveedor
                $propios = $earphones->filter(function ($earphone) use ($supplier) {
                    return collect($earphone->colors)->contains('idSupplier', $supplier->idSupplier);
                });
                
                if ($propios->isEmpty()) {
                    $propios = $earphones;
                }

                $seleccion = $propios->random(min(fake()->numberBetween(1, 3), $propios->count()));
                foreach ($seleccion as $earphone) {
                    PurchaseItem::factory()->create([
                        'idPurchase' => $purchase->idPurchase,
                        'idEarphone' => $earphone->idEarphone,
                    ]);
                }
            }
        });
    }
}
