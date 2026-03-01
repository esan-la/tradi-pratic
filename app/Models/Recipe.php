<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasUuid;

class Recipe extends Model
{
    use HasUuid;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'category',
        'difficulty',
        'prep_time',
        'cook_time',
        'servings',
        'ingredients',
        'instructions',
        'image',
        'gallery',
        'video_url',
        'is_published',
        'is_featured',
        'views',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
        'gallery' => 'array',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'views' => 'integer',
        'prep_time' => 'integer',
        'cook_time' => 'integer',
        'servings' => 'integer',
    ];

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-génération du slug
        static::creating(function ($recipe) {
            if (empty($recipe->slug)) {
                $recipe->slug = Str::slug($recipe->title);
            }
        });

        static::updating(function ($recipe) {
            if ($recipe->isDirty('title')) {
                $recipe->slug = Str::slug($recipe->title);
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
        return asset('images/placeholder-recipe.jpg');
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
     * Temps total de préparation (prep + cook)
     */
    public function getTotalTimeAttribute()
    {
        return ($this->prep_time ?? 0) + ($this->cook_time ?? 0);
    }

    /**
     * Badge couleur pour la difficulté
     */
    public function getDifficultyBadgeColorAttribute()
    {
        return match($this->difficulty) {
            'Facile' => 'success',
            'Moyen' => 'warning',
            'Difficile' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Nombre d'ingrédients
     */
    public function getIngredientsCountAttribute()
    {
        return is_array($this->ingredients) ? count($this->ingredients) : 0;
    }

    /**
     * Nombre d'instructions
     */
    public function getInstructionsCountAttribute()
    {
        return is_array($this->instructions) ? count($this->instructions) : 0;
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
        $text = $this->short_description ?? $this->plain_description;
        return Str::limit($text, 150);
    }

    /**
     * Temps de lecture estimé (en minutes)
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count($this->plain_description);
        return max(1, ceil($wordCount / 200)); // 200 mots par minute
    }

    /**
     * Scope pour les recettes publiées
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope pour les recettes en vedette
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
     * Scope par difficulté
     */
    public function scopeDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
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
     * Scope par temps maximum
     */
    public function scopeMaxTime($query, $maxMinutes)
    {
        return $query->whereRaw('(COALESCE(prep_time, 0) + COALESCE(cook_time, 0)) <= ?', [$maxMinutes]);
    }

    /**
     * Incrémenter les vues
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Formater le temps pour affichage
     */
    public function getFormattedTimeAttribute()
    {
        $total = $this->total_time;

        if ($total < 60) {
            return $total . ' min';
        }

        $hours = floor($total / 60);
        $minutes = $total % 60;

        if ($minutes > 0) {
            return $hours . 'h' . $minutes . 'min';
        }

        return $hours . 'h';
    }
}
