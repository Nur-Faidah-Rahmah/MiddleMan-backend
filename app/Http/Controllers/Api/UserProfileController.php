<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfile\UpdateProfileRequest;
use App\Http\Resources\User\UserResource;
use App\Services\UserProfileService;
use Illuminate\Http\JsonResponse;
use Exception;

class UserProfileController extends Controller
{
    public function __construct(
        protected UserProfileService $profileService
    ){
    }

    public function show(): JsonResponse
    {
        try {

            return $this->successResponse(

                new UserResource(auth()->user()->load('profile','role')),

                'Profile ditemukan.'

            );

        } catch (Exception $e) {

            return $this->errorResponse(

                $e->getMessage(),

                $e->getCode() ?: 400

            );

        }
    }

    public function update(
        UpdateProfileRequest $request
    ): JsonResponse
    {
        try {

            $this->profileService->update(

                auth()->user(),

                $request->validated()

            );

            return $this->successResponse(

                new UserResource(
                    auth()->user()->fresh()->load(
                        'profile',
                        'role'
                    )
                ),

                'Profile berhasil diperbarui.'

            );

        } catch (Exception $e) {

            return $this->errorResponse(

                $e->getMessage(),

                $e->getCode() ?: 400

            );

        }
    }

    public function publicProfile(User $user): JsonResponse
    {
        return $this->successResponse(

            new UserResource(
                $user->load('profile', 'role')
            ),

            'Profil user berhasil diambil.'

        );
    }
}