<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id'=>$this->id,

            'name'=>$this->name,

            'email'=>$this->email,

            'role'=>$this->role?->name,

            'profile' => [

                'phone' => $this->profile?->phone,

                'gender' => $this->profile?->gender,

                'birth_date' => $this->profile?->birth_date,

                'province' => $this->profile?->province,

                'city' => $this->profile?->city,

                'address' => $this->profile?->address,

                'bio' => $this->profile?->bio,

                'skills' => $this->profile?->skills,

                'avatar' => $this->profile?->avatar,

                'wallet_balance' => (float) ($this->profile?->wallet_balance ?? 5000000.00),

                'rating' => (float) ($this->profile?->rating ?? 5.00),

                'level' => (int) ($this->profile?->level ?? 1),

                'exp' => (int) ($this->profile?->exp ?? 0),

            ],

            'status'=>$this->status

        ];
    }
}