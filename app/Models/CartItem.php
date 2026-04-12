<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;
    protected $table      = 'cart_items';
    protected $primaryKey = 'idCart_Item';

    protected $fillable = [
        'idEarphone',
        'idCart',
        'subtotal',
        'quantity',
        'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'   => 'decimal:2',
            'unit_price' => 'decimal:2',
            'quantity'   => 'integer',
        ];
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'idCart', 'idCart');
    }

    public function earphone(): BelongsTo
    {
        return $this->belongsTo(Earphone::class, 'idEarphone', 'idEarphone');
    }
}
