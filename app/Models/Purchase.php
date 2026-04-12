<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;
    protected $table      = 'purchases';
    protected $primaryKey = 'idPurchase';

    protected $fillable = [
        'purchaseDate',
        'iva',
        'shipping_cost',
        'notes',
        'paymentMethod',
        'totalAmount',
        'invoiceNumber',
    ];

    protected function casts(): array
    {
        return [
            'purchaseDate'  => 'date',
            'iva'           => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'totalAmount'   => 'decimal:2',
        ];
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'idPurchase', 'idPurchase');
    }
}
