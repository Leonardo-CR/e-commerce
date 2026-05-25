<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Earphone;
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
        // ── 0. Roles y permisos ──────────────────────────────────────────────
        $this->call(RolesAndPermissionsSeeder::class);

        // ── 1. Colores ───────────────────────────────────────────────────────
        $colorList = [
            ['name' => 'Negro',    'hex' => '#111827'],
            ['name' => 'Blanco',   'hex' => '#F9FAFB'],
            ['name' => 'Rojo',     'hex' => '#EF4444'],
            ['name' => 'Azul',     'hex' => '#3B82F6'],
            ['name' => 'Verde',    'hex' => '#22C55E'],
            ['name' => 'Gris',     'hex' => '#6B7280'],
            ['name' => 'Dorado',   'hex' => '#F59E0B'],
            ['name' => 'Plateado', 'hex' => '#94A3B8'],
        ];

        $colors = collect($colorList)->mapWithKeys(
            fn ($c) => [$c['name'] => Color::firstOrCreate(['name' => $c['name']], $c)->id]
        );

        // ── 2. Empresas proveedoras ──────────────────────────────────────────
        $suppliersData = [
            ['name' => 'Apple México',             'phone' => '+525555550101', 'email' => 'ventas@apple.mx',          'address' => 'Av. Reforma 222, CDMX'],
            ['name' => 'Sony de México',           'phone' => '+525555550102', 'email' => 'b2b@sony.com.mx',          'address' => 'Av. Insurgentes Sur 1602, CDMX'],
            ['name' => 'Bose México',              'phone' => '+525555550103', 'email' => 'mayoreo@bose.mx',          'address' => 'Av. Vasco de Quiroga 3800, CDMX'],
            ['name' => 'Audio Distribuciones MX',  'phone' => '+528180000104', 'email' => 'pedidos@audiodist.mx',     'address' => 'Av. Constitución 1500, Monterrey, NL'],
        ];

        $suppliers = collect($suppliersData)->mapWithKeys(function ($s) {
            $supplier = Supplier::firstOrCreate(['email' => $s['email']], $s);

            return [$s['name'] => $supplier->idSupplier];
        });

        // ── 3. Usuarios ──────────────────────────────────────────────────────
        $admin = User::firstOrCreate(['email' => 'admin@halosound.com'], [
            'name'         => 'Administrador',
            'password'     => Hash::make('admin@halosound.com'),
            'is_admin'     => true,
            'is_superuser' => true,
        ]);
        $admin->syncRoles(['super_admin']);

        $proveedor = User::firstOrCreate(['email' => 'proveedor@halosound.com'], [
            'name'         => 'Audio Distribuciones MX',
            'password'     => Hash::make('proveedor@halosound.com'),
            'is_admin'     => false,
            'is_superuser' => false,
            'supplier_id'  => $suppliers['Audio Distribuciones MX'],
        ]);
        $proveedor->syncRoles(['proveedor']);

        // ── 4. Catálogo de audífonos (modelos reales) ────────────────────────
        // Stock total = 10 por audífono, distribuido equitativamente entre sus variantes.
        $catalog = [
            ['name' => 'Apple AirPods Pro 2',           'price' => 5999.00,  'supplier' => 'Apple México',            'image' => 'apple.png',   'colors' => ['Blanco'],
             'description' => 'Cancelación activa de ruido adaptable, audio espacial personalizado y chip H2.'],
            ['name' => 'Apple AirPods Max',             'price' => 14999.00, 'supplier' => 'Apple México',            'image' => 'apple.png',   'colors' => ['Plateado', 'Negro', 'Verde'],
             'description' => 'Diadema premium de aluminio, audio espacial dinámico y 20 h de batería.'],
            ['name' => 'Sony WH-1000XM5',               'price' => 9499.00,  'supplier' => 'Sony de México',          'image' => 'huawei.png',  'colors' => ['Negro', 'Plateado'],
             'description' => 'Cancelación de ruido líder en la industria, 30 h de batería y Hi-Res Audio inalámbrico.'],
            ['name' => 'Sony WF-1000XM5',               'price' => 6999.00,  'supplier' => 'Sony de México',          'image' => 'huawei.png',  'colors' => ['Negro', 'Plateado'],
             'description' => 'Auriculares true wireless con procesador integrado V2 y ANC superior.'],
            ['name' => 'Sony LinkBuds S',               'price' => 3999.00,  'supplier' => 'Sony de México',          'image' => 'huawei.png',  'colors' => ['Negro', 'Blanco'],
             'description' => 'Ultraligeros, conectividad multipunto y modo ambiente ajustable.'],
            ['name' => 'Bose QuietComfort Ultra',       'price' => 10499.00, 'supplier' => 'Bose México',             'image' => 'huawei.png',  'colors' => ['Negro', 'Blanco'],
             'description' => 'Cancelación CustomTune, sonido inmersivo y 24 h de autonomía.'],
            ['name' => 'Bose QuietComfort Earbuds II',  'price' => 5999.00,  'supplier' => 'Bose México',             'image' => 'huawei.png',  'colors' => ['Negro', 'Blanco'],
             'description' => 'Audífonos in-ear con cancelación inteligente personalizada.'],
            ['name' => 'Samsung Galaxy Buds3 Pro',      'price' => 5499.00,  'supplier' => 'Audio Distribuciones MX', 'image' => 'samsung.png', 'colors' => ['Plateado', 'Blanco'],
             'description' => 'Audio Hi-Fi de 24 bit y ANC con detección de voz inteligente.'],
            ['name' => 'Sennheiser Momentum 4',         'price' => 7999.00,  'supplier' => 'Audio Distribuciones MX', 'image' => 'huawei.png',  'colors' => ['Negro', 'Blanco'],
             'description' => 'Sonido de referencia con 60 h de batería y ANC adaptativo.'],
            ['name' => 'JBL Tune 770NC',                'price' => 2499.00,  'supplier' => 'Audio Distribuciones MX', 'image' => 'huawei.png',  'colors' => ['Negro', 'Azul'],
             'description' => 'Cancelación adaptativa de ruido y hasta 70 h de batería.'],
            ['name' => 'Beats Studio Pro',              'price' => 6999.00,  'supplier' => 'Audio Distribuciones MX', 'image' => 'huawei.png',  'colors' => ['Negro', 'Dorado'],
             'description' => 'Chip Apple personalizado, USB-C lossless y ANC potente.'],
            ['name' => 'Audio-Technica ATH-M50x',       'price' => 3999.00,  'supplier' => 'Audio Distribuciones MX', 'image' => 'huawei.png',  'colors' => ['Negro'],
             'description' => 'Estándar de la industria para monitoreo profesional en estudio.'],
        ];

        foreach ($catalog as $item) {
            $variants     = $item['colors'];
            $stockPerVar  = intdiv(10, count($variants));
            $remainder    = 10 % count($variants);

            $colorRows = [];
            foreach ($variants as $idx => $colorName) {
                $colorRows[] = [
                    'color_id'   => $colors[$colorName],
                    'idSupplier' => $suppliers[$item['supplier']],
                    'stock'      => $stockPerVar + ($idx === 0 ? $remainder : 0),
                    'image'      => 'images/products/' . $item['image'],
                ];
            }

            Earphone::firstOrCreate(
                ['name' => $item['name']],
                [
                    'price'       => $item['price'],
                    'stock'       => 10,
                    'description' => $item['description'],
                    'colors'      => $colorRows,
                ]
            );
        }
    }
}
