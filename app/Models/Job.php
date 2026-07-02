<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    protected $fillable = [
        'customer_id',
        'worker_id',
        'category_id',
        'title',
        'description',
        'price',
        'status',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}