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
}
