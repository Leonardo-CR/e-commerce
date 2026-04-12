<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;
    protected $table      = 'addresses';
    protected $primaryKey = 'idAddress';

    protected $fillable = [
        'street',
        'colony',
        'city',
        'eliminated',
        'number',
        'state',
        'zip',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'eliminated' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
