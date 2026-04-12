<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function earphones(): HasMany
    {
        return $this->hasMany(Earphone::class, 'idSupplier', 'idSupplier');
    }
}
