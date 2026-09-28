<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MiniApp extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'name', 'slug', 'description', 'creator_id',
        'status', 'version', 'api_rate_limit', 'webhook_url', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'api_rate_limit' => 'integer',
    ];

    // -- Relationships --
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function settings()
    {
        return $this->hasMany(MiniAppSetting::class);
    }

    public function requestLogs()
    {
        return $this->hasMany(ApiRequestLog::class);
    }

    // -- Media Collections --
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('assets');
        $this->addMediaCollection('screenshots');
    }

    // -- Activity Log --
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'status', 'version'])
            ->logOnlyDirty();
    }

    // -- Scopes --
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // -- Helpers --
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
