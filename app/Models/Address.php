<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $primaryKey = 'idAddress';

    protected $fillable = [
        'street',
        'colony',
        'city',
        'number',
        'state',
        'zip',
        'is_default',
        'user_id',
        'eliminated'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'eliminated' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
