<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Realisation extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'category',
        'image',
        'gallery',
        'video_url',
        'is_featured',
        'is_published',
        'order',
        'views',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'views' => 'integer',
    ];

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-génération du slug
        static::creating(function ($realisation) {
            if (empty($realisation->slug)) {
                $realisation->slug = Str::slug($realisation->title);
            }
        });

        static::updating(function ($realisation) {
            if ($realisation->isDirty('title')) {
                $realisation->slug = Str::slug($realisation->title);
            }
        });
    }

    /**
     * URL de l'image principale
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/placeholder-realisation.jpg');
    }

    /**
     * URLs de la galerie
     */
    public function getGalleryUrlsAttribute()
    {
        if (!$this->gallery || !is_array($this->gallery)) {
            return [];
        }

        return array_map(function($path) {
            return asset('storage/' . $path);
        }, $this->gallery);
    }

    /**
     * Vérifier si la galerie existe
     */
    public function hasGallery()
    {
        return !empty($this->gallery) && is_array($this->gallery) && count($this->gallery) > 0;
    }

    /**
     * Nombre d'images dans la galerie
     */
    public function getGalleryCountAttribute()
    {
        return $this->hasGallery() ? count($this->gallery) : 0;
    }

    /**
     * Temps de lecture estimé (en minutes)
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->description));
        return ceil($wordCount / 200); // 200 mots par minute
    }

    /**
     * Description nettoyée (sans HTML)
     */
    public function getPlainDescriptionAttribute()
    {
        return strip_tags($this->description);
    }

    /**
     * Extrait de la description
     */
    public function getExcerptAttribute()
    {
        return Str::limit($this->plain_description, 150);
    }

    /**
     * Scope pour les réalisations publiées
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope pour les réalisations en vedette
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope par catégorie
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope recherche
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%");
        });
    }

    /**
     * Incrémenter les vues
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
