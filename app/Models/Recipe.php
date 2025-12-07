<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'ingredients',      // JSON array
        'instructions',     // JSON array
        'prep_time',        // Changé de preparation_time
        'cook_time',        // Changé de cooking_time
        'servings',
        'category',
        'image',            // Changé de featured_image
        'video_url',        // Changé de youtube_video_id (URL complète)
        'is_published',
        'views_count',
    ];

    protected $casts = [
        'ingredients' => 'array',      // ✅ Cast en array
        'instructions' => 'array',     // ✅ Cast en array
        'prep_time' => 'integer',
        'cook_time' => 'integer',
        'servings' => 'integer',
        'is_published' => 'boolean',
        'views_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recipe) {
            if (empty($recipe->slug)) {
                $recipe->slug = Str::slug($recipe->title);
            }
        });

        static::updating(function ($recipe) {
            // Mettre à jour le slug si le titre change
            if ($recipe->isDirty('title')) {
                $recipe->slug = Str::slug($recipe->title);
            }
        });
    }

    /**
     * Scope pour les recettes publiées
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

     /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour les recettes populaires
     */
    public function scopePopular($query, $limit = 6)
    {
        return $query->orderBy('views_count', 'desc')->limit($limit);
    }

    /**
     * Obtenir le nom de la catégorie formaté
     */
    public function getCategoryNameAttribute()
    {
        return match($this->category) {
            'Plats' => 'Plats',
            'Boissons' => 'Boissons',
            'Desserts' => 'Desserts',
            'Remèdes' => 'Remèdes',
            default => $this->category,
        };
    }

    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Obtenir l'URL de l'image
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Obtenir le temps total (préparation + cuisson)
     */
    public function getTotalTimeAttribute()
    {
        return ($this->prep_time ?? 0) + ($this->cook_time ?? 0);
    }

    /**
     * Obtenir le temps total formaté
     */
    public function getTotalTimeFormattedAttribute()
    {
        $total = $this->total_time;

        if ($total < 60) {
            return $total . ' min';
        }

        $hours = floor($total / 60);
        $minutes = $total % 60;

        return $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'min' : '');
    }

    /**
     * Obtenir l'URL d'embed YouTube
     */
    public function getYoutubeEmbedUrlAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        // Si c'est déjà une URL embed
        if (strpos($this->video_url, 'embed') !== false) {
            return $this->video_url;
        }

        // Convertir différents formats d'URL YouTube
        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/',
            '/youtu\.be\/([^?]+)/',
            '/youtube\.com\/embed\/([^?]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->video_url, $matches)) {
                return "https://www.youtube.com/embed/{$matches[1]}";
            }
        }

        return null;
    }

    /**
     * Obtenir l'ID de la vidéo YouTube
     */
    public function getYoutubeVideoIdAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/',
            '/youtu\.be\/([^?]+)/',
            '/youtube\.com\/embed\/([^?]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->video_url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Vérifier si la recette a une vidéo
     */
    public function hasVideo()
    {
        return !empty($this->video_url);
    }

    /**
     * Obtenir le nombre d'ingrédients
     */
    public function getIngredientsCountAttribute()
    {
        return $this->ingredients ? count($this->ingredients) : 0;
    }

    /**
     * Obtenir le nombre d'étapes
     */
    public function getInstructionsCountAttribute()
    {
        return $this->instructions ? count($this->instructions) : 0;
    }
}
