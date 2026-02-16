<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payer_type',
        'transaction_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'payment_data',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_data' => 'array',
        'paid_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentAppointments()
    {
        return $this->hasMany(PaymentAppointment::class);
    }

    public function paymentProducts()
    {
        return $this->hasMany(PaymentProduct::class);
    }

    public function paymentHotelReservations()
    {
        return $this->hasMany(PaymentHotelReservation::class);
    }

    public function paymentDonations()
    {
        return $this->hasMany(PaymentDonation::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
