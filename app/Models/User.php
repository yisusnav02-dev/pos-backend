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
        'phone_number',
        'password',
        'role',
        'status'
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeWaiters($query)
    {
        return $query->where('role', 3);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 1);
    }

    public function scopeManagers($query)
    {
        return $query->where('role', 2);
    }

    // Methods
    public function isActive()
    {
        return $this->status;
    }

    public function isWaiter()
    {
        return $this->role === 3;
    }

    public function isAdmin()
    {
        return $this->role === 1;
    }

    public function isManager()
    {
        return $this->role === 2;
    }
}
