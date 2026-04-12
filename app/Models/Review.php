<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;
    protected $table      = 'reviews';
    protected $primaryKey = 'idReview';

    protected $fillable = [
        'comment',
        'user_id',
        'idEarphone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function earphone(): BelongsTo
    {
        return $this->belongsTo(Earphone::class, 'idEarphone', 'idEarphone');
    }
}
