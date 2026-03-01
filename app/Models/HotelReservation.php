<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class HotelReservation extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'appointment_id',
        'hotel_id',
        'room_id',
        'check_in',
        'check_out',
        'total_nights',
        'total_amount',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_amount' => 'decimal:2',
        'total_nights' => 'integer',
    ];

    // Relations
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function paymentHotelReservations()
    {
        return $this->hasMany(PaymentHotelReservation::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}
