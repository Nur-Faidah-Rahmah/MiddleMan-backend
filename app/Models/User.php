<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Pastikan ini ada untuk fitur Token
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Jangan lupa tambahkan role_id di sini
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // User memiliki satu peran
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // Tugas yang DIBUAT oleh user (sebagai Customer)
    public function customerJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'customer_id');
    }

    // Tugas yang DIAMBIL oleh user (sebagai Worker)
    public function workerJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'worker_id');
    }
}