<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',

        'phone',

        'gender',

        'birth_date',

        'province',

        'city',

        'address',

        'bio',

        'skills',

        'avatar',
        'wallet_balance',
        'rating',
        'level',
        'exp'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'wallet_balance' => 'decimal:2',
        'rating' => 'decimal:2',
        'level' => 'integer',
        'exp' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}