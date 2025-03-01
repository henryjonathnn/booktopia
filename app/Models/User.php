<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'original_password',
        'role',
        'profile_img',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to search for users by name, email, or username.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter users by role.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRole($query, $role)
    {
        if (!$role) {
            return $query;
        }
        
        return $query->where('role', $role);
    }

    // Relationships
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_user');
    }

    public function handledPeminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_staff');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'id_user');
    }

    public function sukas()
    {
        return $this->hasMany(Suka::class, 'id_user');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_user');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_user');
    }
}