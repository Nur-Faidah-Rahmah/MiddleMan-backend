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
        'role_id',
        'name',
        'email',
        'password',
        'status',
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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function verificationDocuments()
    {
        return $this->hasOne(VerificationDocument::class);
    }

    public function requestedJobs()
    {
        return $this->hasMany(Job::class, 'requester_id');
    }

    public function selectedJobs()
    {
        return $this->hasMany(Job::class, 'selected_worker_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'worker_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'worker_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function escrowRequests()
    {
        return $this->hasMany(Escrow::class, 'requester_id');
    }

    public function escrowWorks()
    {
        return $this->hasMany(Escrow::class, 'worker_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Future Feature (v3)
    |--------------------------------------------------------------------------
    */

    // public function disputesAsRequester()
    // {
    //     return $this->hasMany(Dispute::class, 'requester_id');
    // }

    // public function disputesAsWorker()
    // {
    //     return $this->hasMany(Dispute::class, 'worker_id');
    // }
}