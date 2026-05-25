<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class EnviaService
{
    private string $baseUrl;
    private ?string $token;
    private array $origin;

    public function __construct()
    {
        $this->token   = config('services.envia.token');
        $this->baseUrl = config('services.envia.sandbox')
            ? 'https://api-test.envia.com'
            : 'https://api.envia.com';

        $cfg = Setting::get('shipping.origin', config('services.envia.origin'));
        $this->origin = [
            'name'       => $cfg['name'],
            'phone'      => $cfg['phone'],
            'street'     => $cfg['street'],
            'city'       => $cfg['city'],
            'state'      => $cfg['state'],
            'country'    => 'MX',
            'postalCode' => $cfg['postal_code'],
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function headers(): array
    {
        if (empty($this->token)) {
            throw new RuntimeException(
                'El servicio de envíos no está configurado. Define la variable ENVIA_MX_API_KEY en el entorno.'
            );
        }

        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type'  => 'application/json',
        ];
    }

    private function getStateCode(string $stateName): string
    {
        $states = [
            'aguascalientes' => 'AG', 'baja california' => 'BC', 'baja california sur' => 'BS', 'campeche' => 'CM', 'chiapas' => 'CS', 'chihuahua' => 'CH',
            'coahuila' => 'CO', 'colima' => 'CL', 'ciudad de mexico' => 'DF', 'cdmx' => 'DF', 'distrito federal' => 'DF', 'durango' => 'DG',
            'guanajuato' => 'GT', 'guerrero' => 'GR', 'hidalgo' => 'HG', 'jalisco' => 'JA', 'mexico' => 'ME', 'estado de mexico' => 'ME',
            'michoacan' => 'MI', 'morelos' => 'MO', 'nayarit' => 'NA', 'nuevo leon' => 'NL', 'oaxaca' => 'OA', 'puebla' => 'PU',
            'queretaro' => 'QT', 'quintana roo' => 'QR', 'san luis potosi' => 'SL', 'sinaloa' => 'SI', 'sonora' => 'SO', 'tabasco' => 'TB',
            'tamaulipas' => 'TM', 'tlaxcala' => 'TL', 'veracruz' => 'VE', 'yucatan' => 'YU', 'zacatecas' => 'ZA',
        ];

        $unwanted = ['á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'ü'=>'u'];
        $cleanName = strtr(mb_strtolower(trim($stateName), 'UTF-8'), $unwanted);

        return strtoupper($states[$cleanName] ?? substr($cleanName, 0, 2));
    }

    private function destination(Order $order): array
    {
        $address = $order->user
            ->addresses()
            ->where('is_default', true)
            ->where('eliminated', false)
            ->first()
            ?? $order->user->addresses()->where('eliminated', false)->first();

        if (!$address) {
            throw new RuntimeException('El usuario no tiene una dirección de entrega registrada.');
        }

        return [
            'name'       => $order->user->name,
            'phone'      => '+525555555555',
            'street'     => trim($address->street . ' ' . $address->number),
            'city'       => $address->city,
            'state'      => $this->getStateCode($address->state),
            'country'    => 'MX',
            'postalCode' => $address->zip,
        ];
    }

    private function defaultPackages(): array
    {
        return [[
            'type'          => 'box',
            'content'       => 'Audifonos',
            'amount'        => 1,
            'declaredValue' => 1000,
            'lengthUnit'    => 'CM',
            'weightUnit'    => 'KG',
            'weight'        => 1,
            'dimensions'    => ['length' => 30, 'width' => 20, 'height' => 10],
        ]];
    }

    // ── Public API ────────────────────────────────────────────────────────────

    /**
     * Returns rates using the order's user default address.
     */
    public function getRates(Order $order): array
    {
        return $this->fetchRates($this->destination($order));
    }

    /**
     * Returns rates for a specific Address model.
     */
    public function getRatesForAddress(\App\Models\Address $address): array
    {
        $destination = [
            'name'       => $address->user->name ?? 'Cliente',
            'phone'      => '+525555555555',
            'street'     => trim($address->street . ' ' . $address->number),
            'city'       => $address->city,
            'state'      => $this->getStateCode($address->state),
            'country'    => 'MX',
            'postalCode' => $address->zip,
        ];

        return $this->fetchRates($destination);
    }

    private function fetchRates(array $destination): array
    {
        $carriers = ['dhl', 'fedex', 'estafeta'];
        $allRates = [];
        $lastError = '';

        foreach ($carriers as $carrier) {
            $response = Http::withHeaders($this->headers())
                ->post($this->baseUrl . '/ship/rate/', [
                    'origin'      => $this->origin,
                    'destination' => $destination,
                    'packages'    => $this->defaultPackages(),
                    'shipment'    => ['type' => 1, 'carrier' => $carrier],
                ]);

            if ($response->successful() && $response->json('meta') !== 'error') {
                $allRates = array_merge($allRates, $response->json('data', []));
            } else {
                $lastError = $response->json('error.message') ?? $response->body();
            }
        }

        if (empty($allRates) && $lastError) {
            throw new RuntimeException('Error al cotizar con Envia: ' . $lastError);
        }

        return $allRates;
    }

    /**
     * Generates a shipping label.
     * Returns: ['trackingNumber', 'label' (URL), 'totalPrice', 'carrier', 'service']
     */
    public function generateLabel(Order $order, string $carrier, string $service): array
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl . '/ship/generate/', [
                'origin'      => $this->origin,
                'destination' => $this->destination($order),
                'packages'    => $this->defaultPackages(),
                'shipment'    => [
                    'type'    => 1,
                    'carrier' => $carrier,
                    'service' => $service,
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Error al generar guía: ' . $response->body());
        }

        $data = $response->json('data.0');

        if (empty($data)) {
            throw new RuntimeException('Envia no devolvió datos de la guía.');
        }

        return $data;
    }

    /**
     * Tracks a shipment by tracking number.
     */
    public function track(string $trackingNumber): array
    {
        $response = Http::withHeaders($this->headers())
            ->post($this->baseUrl . '/ship/generaltrack/', [
                'trackingNumbers' => [$trackingNumber],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Error al rastrear envío: ' . $response->body());
        }

        return $response->json('data.0', []);
    }
}
