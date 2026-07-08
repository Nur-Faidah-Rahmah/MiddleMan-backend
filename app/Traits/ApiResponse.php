<?php

// File: app/Traits/ApiResponse.php
namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    // Format untuk Response Sukses
    protected function successResponse(mixed $data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    // Format untuk Response Error
    protected function errorResponse(string $message, int $code = 400, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors
        ], $code);
    }
}