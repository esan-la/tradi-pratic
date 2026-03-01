<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'admin_id',
        'title',
        'event_type',
        'start_datetime',
        'end_datetime',
        'availability_period_id',
        'description',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    /**
     * Relation avec l'administrateur
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relation avec la période de disponibilité
     */
    public function availabilityPeriod()
    {
        return $this->belongsTo(AvailabilityPeriod::class, 'availability_period_id');
    }

    /**
     * Relation avec le rendez-vous (si event_type = 'appointment')
     */
    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }

    /**
     * Scope pour les événements d'un type spécifique
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope pour les rendez-vous
     */
    public function scopeAppointments($query)
    {
        return $query->where('event_type', 'appointment');
    }

    /**
     * Scope pour les travaux journaliers
     */
    public function scopeDailyWork($query)
    {
        return $query->where('event_type', 'daily_work');
    }

    /**
     * Scope pour les réunions
     */
    public function scopeMeetings($query)
    {
        return $query->where('event_type', 'meeting');
    }

    /**
     * Scope pour un statut spécifique
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour les événements programmés
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope pour une période donnée
     */
    public function scopeBetween($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_datetime', [$startDate, $endDate])
              ->orWhereBetween('end_datetime', [$startDate, $endDate])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('start_datetime', '<=', $startDate)
                     ->where('end_datetime', '>=', $endDate);
              });
        });
    }

    /**
     * Scope pour les événements futurs
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', now());
    }

    /**
     * Scope pour les événements passés
     */
    public function scopePast($query)
    {
        return $query->where('end_datetime', '<', now());
    }

    /**
     * Vérifier si l'événement chevauche un autre
     */
    public function overlaps(Event $other): bool
    {
        return $this->start_datetime < $other->end_datetime
            && $this->end_datetime > $other->start_datetime;
    }

    /**
     * Vérifier si l'événement chevauche une période
     */
    public function overlapsPeriod($startDatetime, $endDatetime): bool
    {
        return $this->start_datetime < $endDatetime
            && $this->end_datetime > $startDatetime;
    }

    /**
     * Obtenir la durée en minutes
     */
    public function getDurationInMinutesAttribute(): int
    {
        return $this->start_datetime->diffInMinutes($this->end_datetime);
    }

    /**
     * Obtenir la durée formatée
     */
    public function getDurationFormattedAttribute(): string
    {
        $minutes = $this->duration_in_minutes;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return $mins > 0 ? "{$hours}h {$mins}min" : "{$hours}h";
        }

        return "{$mins}min";
    }

    /**
     * Vérifier si l'événement est un rendez-vous
     */
    public function isAppointment(): bool
    {
        return $this->event_type === 'appointment';
    }

    /**
     * Vérifier si l'événement est passé
     */
    public function isPast(): bool
    {
        return $this->end_datetime < now();
    }

    /**
     * Vérifier si l'événement est en cours
     */
    public function isOngoing(): bool
    {
        return $this->start_datetime <= now() && $this->end_datetime >= now();
    }

    /**
     * Vérifier si l'événement est futur
     */
    public function isFuture(): bool
    {
        return $this->start_datetime > now();
    }
}
