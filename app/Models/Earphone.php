<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Earphone extends Model
{
    use HasFactory;
    protected $table      = 'earphones';
    protected $primaryKey = 'idEarphone';

    protected $fillable = [
        'price',
        'stock',
        'image',
        'description',
        'name',
        'idSupplier',
        'order_item_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'idSupplier', 'idSupplier');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'idEarphone', 'idEarphone');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'idEarphone', 'idEarphone');
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'idEarphone', 'idEarphone');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'idEarphone', 'idEarphone');
    }
}
