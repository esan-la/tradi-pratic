<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class SocialLink extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'platform',
        'url',
        'icon',
    ];

    /**
     * Scope pour trier par plateforme
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('platform', 'asc');
    }

    /**
     * Get icon class for platform
     */
    public function getIconClassAttribute()
    {
        if ($this->icon) {
            return $this->icon;
        }

        $icons = [
            'facebook' => 'fab fa-facebook-f',
            'twitter' => 'fab fa-twitter',
            'x' => 'fab fa-x-twitter',
            'instagram' => 'fab fa-instagram',
            'linkedin' => 'fab fa-linkedin-in',
            'youtube' => 'fab fa-youtube',
            'whatsapp' => 'fab fa-whatsapp',
            'tiktok' => 'fab fa-tiktok',
            'telegram' => 'fab fa-telegram-plane',
            'pinterest' => 'fab fa-pinterest-p',
            'snapchat' => 'fab fa-snapchat-ghost',
        ];

        return $icons[strtolower($this->platform)] ?? 'fas fa-link';
    }

    /**
     * Get platform color
     */
    public function getPlatformColorAttribute()
    {
        $colors = [
            'facebook' => '#1877f2',
            'twitter' => '#1da1f2',
            'x' => '#000000',
            'instagram' => '#e4405f',
            'linkedin' => '#0077b5',
            'youtube' => '#ff0000',
            'whatsapp' => '#25d366',
            'tiktok' => '#000000',
            'telegram' => '#0088cc',
            'pinterest' => '#bd081c',
            'snapchat' => '#fffc00',
        ];

        return $colors[strtolower($this->platform)] ?? '#6c757d';
    }

    /**
     * Get platform display name
     */
    public function getPlatformNameAttribute()
    {
        $names = [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'x' => 'X (Twitter)',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'youtube' => 'YouTube',
            'whatsapp' => 'WhatsApp',
            'tiktok' => 'TikTok',
            'telegram' => 'Telegram',
            'pinterest' => 'Pinterest',
            'snapchat' => 'Snapchat',
        ];

        return $names[strtolower($this->platform)] ?? ucfirst($this->platform);
    }
}
