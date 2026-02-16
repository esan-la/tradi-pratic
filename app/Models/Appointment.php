<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'provenance',
        'doctype',
        'docnumber',
        'imagedoc',
        'consultation_type',
        'autre_consultation',
        'message',
        'status',
        'admin_notes',
    ];

    /**
     * Relation avec l'événement
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relation avec les paiements (système existant)
     *
     * Note: Cette relation utilise soit:
     * - appointment_id directement si la colonne existe
     * - payable_type/payable_id si vous utilisez un polymorphic relationship
     */
    public function payments()
    {
        // Option 1: Si vous avez appointment_id dans payments
        if (Schema::hasColumn('payments', 'appointment_id')) {
            return $this->hasMany(Payment::class, 'appointment_id');
        }

        // Option 2: Si vous utilisez un polymorphic relationship
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * Obtenir le dernier paiement
     */
    public function latestPayment()
    {
        return $this->payments()->latest()->first();
    }

    /**
     * Scope pour un statut spécifique
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour les rendez-vous en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les rendez-vous confirmés
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope pour les rendez-vous annulés
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope pour les rendez-vous complétés
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope pour un type de consultation
     */
    public function scopeOfConsultationType($query, string $type)
    {
        return $query->where('consultation_type', $type);
    }

    /**
     * Vérifier si le rendez-vous a une image de document
     */
    public function hasDocument(): bool
    {
        return !empty($this->imagedoc);
    }

    /**
     * Vérifier si le rendez-vous est payé
     */
    public function isPaid(): bool
    {
        return $this->payments()
            ->where('payment_status', 'paid')
            ->exists();
    }

    /**
     * Obtenir le montant total payé
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payments()
            ->where('payment_status', 'paid')
            ->sum('amount');
    }

    /**
     * Obtenir le montant total à payer
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->payments()
            ->whereIn('payment_status', ['unpaid', 'paid'])
            ->sum('amount');
    }

    /**
     * Obtenir le statut de paiement
     */
    public function getPaymentStatusAttribute(): string
    {
        if (!$this->payments()->exists()) {
            return 'no_payment';
        }

        $totalAmount = $this->total_amount;
        $totalPaid = $this->total_paid;

        if ($totalPaid >= $totalAmount) {
            return 'paid';
        }

        if ($totalPaid > 0) {
            return 'partial';
        }

        return 'unpaid';
    }

    /**
     * Obtenir le libellé du type de consultation
     */
    public function getConsultationTypeLabelAttribute(): string
    {
        $labels = [
            'traditional' => 'Consultation Traditionnelle',
            'prayer' => 'Prière',
            'natural_care' => 'Soin Naturel',
            'Consultation_spirituelle' => 'Consultation Spirituelle',
            'Autres' => $this->autre_consultation ?? 'Autres',
        ];

        return $labels[$this->consultation_type] ?? $this->consultation_type;
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmé',
            'cancelled' => 'Annulé',
            'completed' => 'Terminé',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Obtenir le badge CSS du statut
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'cancelled' => 'danger',
            'completed' => 'success',
        ];

        return $badges[$this->status] ?? 'secondary';
    }
}
