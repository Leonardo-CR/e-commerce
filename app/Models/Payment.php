<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;
    protected $table      = 'payments';
    protected $primaryKey = 'idPayment';

    protected $fillable = [
        'payment_date',
        'amount',
        'status',
        'method',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime',
            'amount'       => 'decimal:2',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'idPayment', 'idPayment');
    }
}
