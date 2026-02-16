<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bibliography extends Model
{
    use HasFactory;

    protected $table = 'bibliography';

    protected $fillable = [
        'full_name',
        'contact',
        'email',
        'profile',
        'parcours',
        'experiences',
    ];
}
