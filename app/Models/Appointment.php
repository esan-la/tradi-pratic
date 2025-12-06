<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'consultation_type',
        'preferred_date',
        'preferred_time',
        'message',
        'status',
        'payment_status',
        'amount',
        'payment_method',
        'transaction_id',
        'confirmed_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'preferred_time' => 'datetime',
        'confirmed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('preferred_date', '>=', now()->toDateString())
                     ->where('status', 'confirmed');
    }

    public function getConsultationTypeNameAttribute()
    {
        return match($this->consultation_type) {
            'traditional' => 'Consultation Traditionnelle',
            'prayer' => 'Prière et Rituels',
            'natural_care' => 'Soins Naturels',
            default => 'Autre',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">En attente</span>',
            'confirmed' => '<span class="badge bg-success">Confirmé</span>',
            'cancelled' => '<span class="badge bg-danger">Annulé</span>',
            'completed' => '<span class="badge bg-info">Terminé</span>',
            default => '<span class="badge bg-secondary">Inconnu</span>',
        };
    }
}
