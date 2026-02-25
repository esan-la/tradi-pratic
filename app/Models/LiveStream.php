<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LiveStream extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'youtube_video_id',
        'youtube_url',
        'thumbnail',
        'status',
        'scheduled_at',
        'started_at',
        'ended_at',
        'viewer_count',
        'chat_enabled',
        'is_featured',
        'category',
        'user_id',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at'   => 'datetime',
        'ended_at'     => 'datetime',
        'chat_enabled' => 'boolean',
        'is_featured'  => 'boolean',
    ];

    /**
     * Catégories disponibles
     */
    public const CATEGORIES = [
        'consultation'  => 'Consultation',
        'priere'        => 'Prière',
        'recette'       => 'Recette traditionnelle',
        'temoignage'    => 'Témoignage',
        'enseignement'  => 'Enseignement',
        'evenement'     => 'Événement spécial',
        'autre'         => 'Autre',
    ];

    /**
     * Statuts avec labels
     */
    public const STATUSES = [
        'scheduled' => 'Programmé',
        'live'      => 'En direct',
        'ended'     => 'Terminé',
        'cancelled' => 'Annulé',
    ];

    // ===== RELATIONS =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ===== SCOPES =====

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                     ->where('scheduled_at', '>', now())
                     ->orderBy('scheduled_at', 'asc');
    }

    public function scopeEnded($query)
    {
        return $query->where('status', 'ended')
                     ->orderBy('ended_at', 'desc');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ===== ACCESSORS =====

    public function getIsLiveAttribute(): bool
    {
        return $this->status === 'live';
    }

    public function getIsScheduledAttribute(): bool
    {
        return $this->status === 'scheduled';
    }

    public function getIsEndedAttribute(): bool
    {
        return $this->status === 'ended';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'live'      => 'danger',
            'scheduled' => 'info',
            'ended'     => 'secondary',
            'cancelled' => 'warning',
            default     => 'secondary',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category ?? 'Non catégorisé';
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        if ($this->youtube_video_id) {
            return "https://img.youtube.com/vi/{$this->youtube_video_id}/maxresdefault.jpg";
        }

        return asset('images/default-live-thumbnail.jpg');
    }

    public function getEmbedUrlAttribute(): string
    {
        if ($this->youtube_video_id) {
            return "https://www.youtube.com/embed/{$this->youtube_video_id}";
        }

        return '';
    }

    public function getChatEmbedUrlAttribute(): string
    {
        if ($this->youtube_video_id && $this->chat_enabled) {
            $domain = request()->getHost();
            return "https://www.youtube.com/live_chat?v={$this->youtube_video_id}&embed_domain={$domain}";
        }

        return '';
    }

    public function getYoutubeWatchUrlAttribute(): string
    {
        if ($this->youtube_video_id) {
            return "https://www.youtube.com/watch?v={$this->youtube_video_id}";
        }

        return $this->youtube_url ?? '#';
    }

    public function getDurationAttribute(): ?string
    {
        if ($this->started_at && $this->ended_at) {
            return $this->started_at->diff($this->ended_at)->format('%Hh %Imin');
        }

        if ($this->started_at && $this->is_live) {
            return $this->started_at->diffForHumans(null, true) . ' en cours';
        }

        return null;
    }

    public function getScheduledDateFormattedAttribute(): string
    {
        if ($this->scheduled_at) {
            return $this->scheduled_at->locale('fr')->isoFormat('dddd D MMMM YYYY à HH:mm');
        }

        return 'Date non définie';
    }

    // ===== HELPERS =====

    /**
     * Extraire l'ID YouTube d'une URL
     */
    public static function extractYoutubeId(?string $url): ?string
    {
        if (empty($url)) return null;

        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/live\/)([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        // Si c'est déjà un ID (11 caractères)
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        return null;
    }

    /**
     * Obtenir le live en cours (s'il y en a un)
     */
    public static function getCurrentLive(): ?self
    {
        return static::live()->first();
    }

    /**
     * Vérifier s'il y a un live en cours
     */
    public static function hasActiveLive(): bool
    {
        return static::live()->exists();
    }

    /**
     * Démarrer le live
     */
    public function goLive(): void
    {
        $this->update([
            'status'     => 'live',
            'started_at' => now(),
        ]);
    }

    /**
     * Terminer le live
     */
    public function endStream(): void
    {
        $this->update([
            'status'   => 'ended',
            'ended_at' => now(),
        ]);
    }

    /**
     * Annuler le live
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
