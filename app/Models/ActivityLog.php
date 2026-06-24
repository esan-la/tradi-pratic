<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    protected $appends = [
        'action',
        'ip_address',
        'user_agent',
        'user_name',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $activity) {
            if (!app()->bound('request')) {
                return;
            }

            $request = request();
            $properties = $activity->properties?->toArray() ?? [];

            $activity->properties = array_merge([
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            ], $properties);
        });
    }

    public function getActionAttribute(): ?string
    {
        return $this->event ?: $this->log_name;
    }

    public function getIpAddressAttribute(): ?string
    {
        return $this->properties['ip_address'] ?? null;
    }

    public function getUserAgentAttribute(): ?string
    {
        return $this->properties['user_agent'] ?? null;
    }

    public function getUserNameAttribute(): string
    {
        return $this->causer?->name ?? 'Systeme';
    }

    public function user()
    {
        return $this->causer();
    }
}
