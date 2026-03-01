<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Donation extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'donor_id',
        'type',
        'amount',
        'currency',
        'description',
        'status',
        'received_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'received_at' => 'datetime',
    ];

    // Relations
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function items()
    {
        return $this->hasMany(DonationItem::class);
    }

    public function paymentDonations()
    {
        return $this->hasMany(PaymentDonation::class);
    }

    // Scopes
    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
