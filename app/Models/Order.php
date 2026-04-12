<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $table      = 'orders';
    protected $primaryKey = 'idOrder';

    protected $fillable = [
        'user_id',
        'idPayment',
        'shippingCost',
        'totalAmount',
        'shippingCompany',
        'TrackingNumber',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'shippingCost' => 'decimal:2',
            'totalAmount'  => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'idPayment', 'idPayment');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'idOrder', 'idOrder');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'idOrder', 'idOrder');
    }
}
