<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashcardStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'flashcard_id',
        'known'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function flashcard(): BelongsTo {
        return $this->belongsTo(Flashcard::class);
    }
}
