<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class AvailabilityPeriod extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'admin_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_recurring',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'day_of_week' => 'integer',
    ];

    /**
     * Relation avec l'administrateur
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relation avec les événements
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'availability_period_id');
    }

    /**
     * Scope pour les disponibilités actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour un jour spécifique
     */
    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    /**
     * Scope pour les disponibilités récurrentes
     */
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    /**
     * Obtenir le nom du jour
     */
    public function getDayNameAttribute(): string
    {
        $days = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];

        return $days[$this->day_of_week] ?? 'Inconnu';
    }

    /**
     * Vérifier si la disponibilité est valide à une date donnée
     */
    public function isValidForDate(\DateTime $date): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Vérifier le jour de la semaine
        if ((int) $date->format('w') !== $this->day_of_week) {
            return false;
        }

        // Vérifier la période de validité
        if ($this->start_date && $date < $this->start_date) {
            return false;
        }

        if ($this->end_date && $date > $this->end_date) {
            return false;
        }

        return true;
    }

    /**
     * Formater l'horaire
     */
    public function getTimeRangeAttribute(): string
    {
        return $this->start_time . ' - ' . $this->end_time;
    }
}
