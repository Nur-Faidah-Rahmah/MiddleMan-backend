<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VerificationDocument;
use Illuminate\Database\Seeder;

class VerificationSeeder extends Seeder
{
    public function run(): void
    {
        User::where('role_id',3)

            ->get()

            ->each(function($worker){

                VerificationDocument::create([

                    'user_id'=>$worker->id,

                    'document_name' => 'KTP Verification',

                    'document_type' => 'identity',

                    'document_path' => 'verification/sample.pdf',

                    'status'=>'approved',

                ]);

            });

    }
}