<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use HasFactory;
    protected $table      = 'purchase_items';
    protected $primaryKey = 'idPurchase_Item';

    protected $fillable = [
        'idPurchase',
        'idEarphone',
        'quantity',
        'unit_cost',
        'received_date',
    ];

    protected function casts(): array
    {
        return [
            'quantity'      => 'integer',
            'unit_cost'     => 'decimal:2',
            'received_date' => 'date',
        ];
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'idPurchase', 'idPurchase');
    }

    public function earphone(): BelongsTo
    {
        return $this->belongsTo(Earphone::class, 'idEarphone', 'idEarphone');
    }
}
