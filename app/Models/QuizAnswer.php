<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content',
        'order_index',
        'is_correct',
        'image_path'
    ];

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class);
    }
}
