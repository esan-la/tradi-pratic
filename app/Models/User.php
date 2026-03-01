<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use App\Traits\HasUuid;
use App\Mail\ResetPasswordMail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification; // ← CORRECTION ICI

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUuid;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'phone',
        'password',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Nom complet
     */
    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Initiales pour avatar par défaut
     */
    public function getInitialsAttribute(): string
    {
        $prenom = mb_substr($this->prenom, 0, 1);
        $nom = mb_substr($this->nom, 0, 1);
        return mb_strtoupper($prenom . $nom);
    }

    /**
     * URL de l'avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }

        // Avatar par défaut avec initiales (UI Avatars)
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name)
            . '&size=200&background=198754&color=ffffff&bold=true&font-size=0.4';
    }

    /**
     * Envoyer la notification de réinitialisation de mot de passe
     * Override de la méthode par défaut
     */
    public function sendPasswordResetNotification($token): void
    {
        $resetUrl = url(route('auth.password.reset', [
            'token' => $token,
            'email' => $this->email,
        ], false));

        $expiresInMinutes = config('auth.passwords.' .
            config('auth.defaults.passwords') . '.expire', 60);

        Mail::to($this->email)->send(
            new ResetPasswordMail(
                $resetUrl,
                $this->prenom ?? 'Utilisateur',  // ✅ Valeur par défaut si null
                $expiresInMinutes)
        );
    }

    // Relations
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function pubServices()
    {
        return $this->hasMany(PubService::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function mediaImages()
    {
        return $this->hasMany(MediaImage::class);
    }

    public function mediaVideos()
    {
        return $this->hasMany(MediaVideo::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Helper methods
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }
}
