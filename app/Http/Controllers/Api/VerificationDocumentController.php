<?php

namespace App\Http\Controllers\Api;

use App\Models\VerificationDocument;
use App\Services\VerificationDocumentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerificationDocument\ReviewVerificationDocumentRequest;
use App\Http\Requests\VerificationDocument\UploadVerificationDocumentRequest;
use App\Http\Resources\VerificationDocumentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class VerificationDocumentController extends Controller
{
    public function __construct(
        protected VerificationDocumentService $verificationDocumentService
    ){
    }

    public function upload(
        UploadVerificationDocumentRequest $request
    ): JsonResponse
    {
        try{

            return $this->successResponse(

                new VerificationDocumentResource(
                    $this->verificationDocumentService->upload(

                        auth()->user(),

                        $request->validated()

                    ),

                    'Dokumen berhasil diupload.',

                    201

                )
            );

        }catch (Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                is_int($e->getCode()) && $e->getCode() > 0
                    ? $e->getCode()
                    : 500
            );

        }
    }

    public function show(): JsonResponse
    {
        return $this->successResponse(

            new VerificationDocumentResource(
                $this->verificationDocumentService->show(

                    auth()->user()

                ),

                'Status verifikasi.'
            )

        );
    }

    public function pending(): JsonResponse
    {
        return $this->successResponse(
            VerificationDocumentResource::collection(
                $this->verificationDocumentService->pending()
            ),
            'Daftar verifikasi.'
        );
    }

    public function approve(
        VerificationDocument $document
    ): JsonResponse
    {
        return $this->successResponse(

            new VerificationDocumentResource(
                $this->verificationDocumentService->approve(

                    $document,

                    auth()->id()

                ),

                'Dokumen disetujui.'

            )
        );
    }

    public function reject(
        ReviewVerificationDocumentRequest $request,
        VerificationDocument $document
    ): JsonResponse
    {
        return $this->successResponse(

            new VerificationDocumentResource(
                    
                $this->verificationDocumentService->reject(

                    $document,

                    $request->review_note,

                    auth()->id()

                ),

                'Dokumen ditolak.'

            
            )
        );
    }
}
