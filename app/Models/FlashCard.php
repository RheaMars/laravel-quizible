<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlashCard extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'frontside',
        'backside',
        'category',
        'creator_id'
    ];

    public function creator(): BelongsTo {
        return $this->belongsTo( User::class );
    }
}
