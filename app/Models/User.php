<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function name(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Str::title($value),
            set: fn ($value) => Str::lower($value)
        );
    }
    public function email(): Attribute
    {
        return new Attribute(
            set: fn ($value) => Str::lower($value)
        );
    }

    public function profilePhotoUrl(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->profile_photo_path ? Storage::url($this->profile_photo_path) : $this->defaultProfilePhotoUrl()
        );
    }

    protected function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=FFFFFF&background=06B6D4';
    }

    #relations
    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }

    public function terminals()
    {
        return $this->belongsToMany(Terminal::class);
    }

    public function accessToken()
    {
        return $this->hasOne(AccessToken::class);
    }
}
