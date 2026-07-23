<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    protected $with = [
        'category',
    ];

    protected $fillable = [
        'requester_id',
        'selected_worker_id',
        'category_id',
        'title',
        'description',
        'budget',
        'deadline',
        'location_type',
        'location',
        'custom_terms',
        'status',
        'opened_at',
        'taken_at',
        'completed_at',
        'dispute_reason',
        'disputed_by',
        'disputed_at',
        'dispute_notes',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'deadline' => 'datetime',
            'opened_at' => 'datetime',
            'taken_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function selectedWorker()
    {
        return $this->belongsTo(User::class, 'selected_worker_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function submission()
    {
        return $this->hasOne(Submission::class);
    }

    public function escrow()
    {
        return $this->hasOne(Escrow::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Future Feature (v3)
    |--------------------------------------------------------------------------
    |
    | AgreementLog dan Dispute belum diimplementasikan.
    | Aktifkan kembali ketika fitur v3 mulai dikerjakan.
    |
    */

    // public function agreementLogs()
    // {
    //     return $this->hasMany(AgreementLog::class);
    // }

    // public function dispute()
    // {
    //     return $this->hasOne(Dispute::class);
    // }

    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeTaken($query)
    {
        return $query->taken();
    }

    public function scopeCompleted($query)
    {
        return $query->completed();
    }
}