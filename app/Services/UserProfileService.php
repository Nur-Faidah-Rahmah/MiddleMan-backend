<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UserProfileService extends BaseService
{
    /**
     * Ambil profile user.
     */
    public function show(int $userId): ?UserProfile
    {
        return UserProfile::where(
            'user_id',
            $userId
        )->first();
    }

    /**
     * Update profile.
     */
    public function update(
        User $user,
        array $data
    ): UserProfile {

        return DB::transaction(function () use ($user, $data) {

            $avatar = null;

            if (
                isset($data['avatar']) &&
                $data['avatar'] instanceof UploadedFile
            ) {

                $avatar = $data['avatar']->store(
                    'avatars',
                    'public'
                );

            }

            if (isset($data['name'])) {

                $user->update([
                    'name' => $data['name']
                ]);

            }

            $profile = UserProfile::updateOrCreate(

                [
                    'user_id'=>$user->id
                ],

                [
                    'phone'=>$data['phone'] ?? null,

                    'gender'=>$data['gender'] ?? null,

                    'birth_date'=>$data['birth_date'] ?? null,

                    'province'=>$data['province'] ?? null,

                    'city'=>$data['city'] ?? null,

                    'address'=>$data['address'] ?? null,

                    'bio'=>$data['bio'] ?? null,

                    'skills'=>$data['skills'] ?? null,

                    'avatar'=>$avatar
                        ?? optional($user->profile)->avatar

                ]

            );

            return $profile;

        });

    }
}