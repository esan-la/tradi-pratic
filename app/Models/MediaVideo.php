<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasUuid;

class MediaVideo extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'user_id',
        'video_url',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Relation avec User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les vidéos publiées
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get video URL
     */
    public function getUrlAttribute()
    {
        // Si c'est un chemin storage
        if (strpos($this->video_url, 'http') === false) {
            return Storage::url($this->video_url);
        }

        // Si c'est déjà une URL
        return $this->video_url;
    }

    /**
     * Déterminer automatiquement le type de source
     */
    public function getSourceTypeAttribute()
    {
        if (strpos($this->video_url, 'youtube.com') !== false ||
            strpos($this->video_url, 'youtu.be') !== false) {
            return 'youtube';
        }

        if (strpos($this->video_url, 'vimeo.com') !== false) {
            return 'vimeo';
        }

        return 'file';
    }

    /**
     * Get video ID (YouTube/Vimeo)
     */
    public function getVideoIdAttribute()
    {
        if ($this->source_type === 'youtube') {
            return self::extractYouTubeId($this->video_url);
        }

        if ($this->source_type === 'vimeo') {
            return self::extractVimeoId($this->video_url);
        }

        return null;
    }

    /**
     * Get embed URL pour iframe
     */
    public function getEmbedUrlAttribute()
    {
        $videoId = $this->video_id;

        if ($this->source_type === 'youtube' && $videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }

        if ($this->source_type === 'vimeo' && $videoId) {
            return "https://player.vimeo.com/video/{$videoId}";
        }

        // Pour les fichiers locaux, retourner l'URL directe
        return $this->url;
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailAttribute()
    {
        // YouTube : miniature automatique haute résolution
        if ($this->source_type === 'youtube' && $this->video_id) {
            return "https://img.youtube.com/vi/{$this->video_id}/maxresdefault.jpg";
        }

        // Vimeo : nécessite API, on utilise un placeholder
        if ($this->source_type === 'vimeo') {
            return asset('images/vimeo-placeholder.jpg');
        }

        // Fichier local : placeholder
        return asset('images/video-placeholder.jpg');
    }

    /**
     * Check if video is YouTube
     */
    public function getIsYoutubeAttribute()
    {
        return $this->source_type === 'youtube';
    }

    /**
     * Check if video is Vimeo
     */
    public function getIsVimeoAttribute()
    {
        return $this->source_type === 'vimeo';
    }

    /**
     * Check if video is file
     */
    public function getIsFileAttribute()
    {
        return $this->source_type === 'file';
    }

    /**
     * Check if video is external (YouTube/Vimeo)
     */
    public function getIsExternalAttribute()
    {
        return $this->is_youtube || $this->is_vimeo;
    }

    /**
     * Extract YouTube video ID from URL
     */
    public static function extractYouTubeId($url)
    {
        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/',           // Standard
            '/youtube\.com\/embed\/([^?]+)/',              // Embed
            '/youtu\.be\/([^?]+)/',                        // Short URL
            '/youtube\.com\/v\/([^?]+)/',                  // Old embed
            '/youtube\.com\/shorts\/([^?]+)/',             // Shorts
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Extract Vimeo video ID from URL
     */
    public static function extractVimeoId($url)
    {
        $patterns = [
            '/vimeo\.com\/(\d+)/',                         // Standard
            '/vimeo\.com\/video\/(\d+)/',                  // Video page
            '/player\.vimeo\.com\/video\/(\d+)/',         // Player
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Delete video file (si fichier local)
     */
    public function deleteFile()
    {
        if ($this->is_file && Storage::exists($this->video_url)) {
            Storage::delete($this->video_url);
        }
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($video) {
            $video->deleteFile();
        });
    }
}
