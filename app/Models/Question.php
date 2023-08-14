<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'content',
        'points',
        'explanation',
        'image_path',
        'sort'
    ];

    public function quiz() {
        return $this->belongsTo( Quiz::class );
    }

    public function answers() {
        return $this->hasMany( Answer::class );
    }
}
