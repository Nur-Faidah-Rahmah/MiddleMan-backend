<?php

namespace App\Services;

use App\Models\User;
use App\Models\VerificationDocument;
use App\Services\Base\BaseService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class VerificationDocumentService extends BaseService
{
    public function upload(
        User $user,
        array $data
    ): VerificationDocument {

        /** @var UploadedFile|null $file */
        $file = $data['document'] ?? null;

        $path = null;

        if ($file) {

            $path = $file->store(
                'verification-documents',
                'public'
            );

        }

        return DB::transaction(function() use(

            $user,

            $data,

            $path,

            $file

        ){

            return VerificationDocument::updateOrCreate(

                [

                    'user_id' => $user->id

                ],

                [

                    'document_name' => $file?->getClientOriginalName(),

                    'document_path' => $path,

                    'document_type' => $data['document_type'],

                    'mime_type' => $file?->getMimeType(),

                    'file_size' => $file?->getSize(),

                    'status' => 'pending',

                    'review_note' => null,

                    'verified_by' => null,

                    'verified_at' => null,

                ]

            );

        });

    }

    public function show(User $user): ?VerificationDocument
    {
        return VerificationDocument::where(

            'user_id',

            $user->id

        )->first();
    }

    public function pending()
    {
        return VerificationDocument::with('user')

            ->where('status','pending')

            ->latest()

            ->paginate(15);
    }

    public function approve(
        VerificationDocument $document,
        int $adminId
    ): VerificationDocument {

        $document->update([

            'status' => 'approved',

            'verified_by' => $adminId,

            'verified_at' => now(),

            'review_note' => null,

        ]);

        return $document->fresh();

    }

    public function reject(
        VerificationDocument $document,
        string $note,
        int $adminId
    ): VerificationDocument {

        $document->update([

            'status' => 'rejected',

            'verified_by' => $adminId,

            'verified_at' => now(),

            'review_note' => $note,

        ]);

        return $document->fresh();

    }
}