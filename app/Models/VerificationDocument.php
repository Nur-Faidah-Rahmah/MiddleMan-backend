<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationDocument extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',

        'document_name',

        'document_path',

        'document_type',

        'status',

        'review_note',

        'verified_by',

        'verified_at',

        'mime_type',

        'file_size',

    ];

    protected function casts(): array
    {
        return [

            'verified_at' => 'datetime',

            'file_size' => 'integer',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }
}