<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;
    protected $table      = 'order_items';
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'idOrder',
        'idEarphone',
        'quantity',
        'unit_price',
        'subtotal',
        'discount',
        'tax',
    ];

    protected function casts(): array
    {
        return [
            'quantity'   => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal'   => 'decimal:2',
            'discount'   => 'decimal:2',
            'tax'        => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'idOrder', 'idOrder');
    }

    public function earphone(): BelongsTo
    {
        return $this->belongsTo(Earphone::class, 'idEarphone', 'idEarphone');
    }
}
