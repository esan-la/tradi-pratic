<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_path',
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
     * Scope pour les images publiées
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get full URL de l'image
     */
    public function getUrlAttribute()
    {
        // Si c'est un chemin storage (commence par 'media/' ou ne contient pas 'http')
        if (strpos($this->image_path, 'http') === false) {
            return Storage::url($this->image_path);
        }

        // Si c'est déjà une URL complète
        return $this->image_path;
    }

    /**
     * Get image URL (alias pour compatibilité)
     */
    public function getImageUrlAttribute()
    {
        return $this->url;
    }

    /**
     * Check if image is external URL
     */
    public function getIsExternalAttribute()
    {
        return strpos($this->image_path, 'http') !== false;
    }

    /**
     * Check if image is local file
     */
    public function getIsLocalAttribute()
    {
        return !$this->is_external;
    }

    /**
     * Delete image file from storage
     */
    public function deleteFile()
    {
        if ($this->is_local && Storage::exists($this->image_path)) {
            Storage::delete($this->image_path);
        }
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Supprimer automatiquement le fichier lors de la suppression du modèle
        static::deleting(function ($image) {
            $image->deleteFile();
        });
    }
}
