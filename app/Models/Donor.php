<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Donor extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'is_anonymous',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];

    // Relations
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
