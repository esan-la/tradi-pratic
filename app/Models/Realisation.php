<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Realisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'image',        // Image principale (singulier)
        'gallery',      // Galerie d'images (array)
        'video_url',
        'is_featured',
        'is_published',
        'order',
    ];

    protected $casts = [
        'gallery' => 'array',      // Changé de 'images' à 'gallery'
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($realisation) {
            if (empty($realisation->slug)) {
                $realisation->slug = Str::slug($realisation->title);
            }
        });

        static::updating(function ($realisation) {
            // Mettre à jour le slug si le titre change
            if ($realisation->isDirty('title')) {
                $realisation->slug = Str::slug($realisation->title);
            }
        });
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
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour trier par ordre personnalisé
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    /**
     * Obtenir le nom de la catégorie formaté
     */
    public function getCategoryNameAttribute()
    {
        return match($this->category) {
            'Agriculture' => 'Agriculture',
            'Élevage' => 'Élevage',
            'Artisanat' => 'Artisanat',
            'Autres' => 'Autres',
            default => $this->category,
        };
    }

    /**
     * Obtenir l'URL complète de l'image principale
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Obtenir les URLs complètes de la galerie
     */
    public function getGalleryUrlsAttribute()
    {
        if (!$this->gallery || !is_array($this->gallery)) {
            return [];
        }

        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->gallery);
    }

    /**
     * Obtenir la première image de la galerie
     */
    public function getFirstGalleryImageAttribute()
    {
        if ($this->gallery && is_array($this->gallery) && count($this->gallery) > 0) {
            return asset('storage/' . $this->gallery[0]);
        }
        return null;
    }

    /**
     * Vérifier si la réalisation a une galerie
     */
    public function hasGallery()
    {
        return $this->gallery && is_array($this->gallery) && count($this->gallery) > 0;
    }

    /**
     * Obtenir le nombre d'images dans la galerie
     */
    public function getGalleryCountAttribute()
    {
        return $this->gallery ? count($this->gallery) : 0;
    }
}
