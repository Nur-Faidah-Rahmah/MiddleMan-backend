<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. REGISTER
    public function register(RegisterRequest $request): JsonResponse
    {
        // Buat user baru
        $user = User::create([
            'role_id'  => $request->role_id,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'status'   => 'pending',
        ]);

        // Langsung buatkan token Sanctum setelah berhasil register
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(

            [

                'access_token'=>$token,

                'token_type'=>'Bearer',

                'user'=>new UserResource(

                    $user->load(

                        'role',

                        'profile'

                    )

                )

            ],

            'Registrasi berhasil.',

            201

        );
    }

    // 2. LOGIN
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        // Cek email dan kecocokan password
        if (! $user || ! Hash::check($request->password, $user->password)) {

            return $this->errorResponse(
                'Email atau password salah.',
                401
            );

        }

        // if ($user->status !== 'active') {

        //     return $this->errorResponse(
        //         'Akun belum diverifikasi Admin.',
        //         403
        //     );

        // }

        // Buat token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(

            [

                'access_token'=>$token,

                'token_type'=>'Bearer',

                'user'=>new UserResource(

                    $user->load(

                        'role',

                        'profile'

                    )

                )

            ],

            'Login berhasil.'

        );
    }

    // 3. LOGOUT
    public function logout(): JsonResponse
    {
        // Hapus token yang sedang digunakan saat ini
        auth()->user()->currentAccessToken()->delete();

        return $this->successResponse(

            null,

            'Logout berhasil.'

        );
    }

    public function me(): JsonResponse
    {
        return $this->successResponse(
            new UserResource(
                auth()->user()
            ),
            'Profil berhasil diambil.'
        );
    }
}