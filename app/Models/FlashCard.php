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
        'user_id',
        'course_id'
    ];

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
