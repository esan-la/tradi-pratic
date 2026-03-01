<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class DonationItem extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'donation_id',
        'name',
        'quantity',
        'description',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relations
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
