<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Hotel extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone',
        'email',
        'description',
    ];

    // Relations
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function reservations()
    {
        return $this->hasMany(HotelReservation::class);
    }

    // Scopes
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }
}
