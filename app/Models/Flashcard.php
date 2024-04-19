<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flashcard extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'frontside',
        'backside',
        'user_id',
        'course_id',
        'category_id',
    ];

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
