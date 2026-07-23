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

    public function index(): JsonResponse
    {
        $users = User::with('profile', 'role')->get();
        return $this->successResponse(
            UserResource::collection($users),
            'Daftar pengguna berhasil diambil.'
        );
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

    /**
     * Top-up wallet balance for the authenticated user.
     * POST /api/v1/profile/topup
     * Body: { "amount": 100000 }
     */
    public function topUp(\Illuminate\Http\Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:100000000',
        ]);

        $user = auth()->user()->load('profile');
        $profile = $user->profile;

        if (!$profile) {
            return $this->errorResponse('Profile tidak ditemukan.', 404);
        }

        $profile->increment('wallet_balance', $request->amount);

        \App\Models\Transaction::create([
            'user_id'        => $user->id,
            'job_id'         => null,
            'escrow_id'      => null,
            'amount'         => $request->amount,
            'type'           => 'topup',
            'status'         => 'success',
            'description'    => 'Top-up saldo wallet sebesar Rp ' . number_format($request->amount, 0, ',', '.') . '.',
            'transaction_at' => now(),
        ]);

        return $this->successResponse(
            new UserResource($user->fresh()->load('profile', 'role')),
            'Top-up berhasil. Saldo telah ditambahkan.'
        );
    }
}