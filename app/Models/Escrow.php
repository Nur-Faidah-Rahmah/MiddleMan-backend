<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Escrow extends Model
{
    use HasFactory;

    protected $with = [
        'requester',
        'worker'
    ];

    protected $fillable = [
        'job_id',
        'requester_id',
        'worker_id',
        'amount',
        'status',
        'funded_at',
        'released_at',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'funded_at' => 'datetime',
            'released_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}