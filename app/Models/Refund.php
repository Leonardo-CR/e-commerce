<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    use HasFactory;
    protected $table      = 'refunds';
    protected $primaryKey = 'idRefund';

    protected $fillable = [
        'reason',
        'status',
        'idOrder',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'idOrder', 'idOrder');
    }

    protected static function booted()
    {
        static::saved(function ($refund) {
            if ($refund->wasChanged('status') || $refund->wasRecentlyCreated) {
                if ($refund->status === 'resolved') {
                    $refund->order()->update(['status' => 'refunded']);
                } elseif ($refund->status === 'pending') {
                    $refund->order()->update(['status' => 'refund_requested']);
                }
            }
        });
    }
}
