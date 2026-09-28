<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements HasMedia, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use HasRoles, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'status', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    // -- Filament Access --
    public function canAccessFilament(): bool
    {
        return $this->hasRole(['admin', 'manager']);
    }

    // -- Relationships --
    public function miniApps()
    {
        return $this->hasMany(MiniApp::class, 'creator_id');
    }

    // -- Media Collections --
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    // -- Activity Log --
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'status'])
            ->logOnlyDirty();
    }
}
