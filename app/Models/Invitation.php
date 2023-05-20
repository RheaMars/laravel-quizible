<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Invitation extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'email',
        'token'
    ];


}
