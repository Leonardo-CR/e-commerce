<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Supplier extends Model
{
    use HasFactory;
    protected $table      = 'suppliers';
    protected $primaryKey = 'idSupplier';

    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'supplier_id', 'idSupplier');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'idSupplier', 'idSupplier');
    }

    public function earphones()
    {
        return Earphone::whereJsonContains('colors', [['idSupplier' => $this->idSupplier]]);
    }
}
