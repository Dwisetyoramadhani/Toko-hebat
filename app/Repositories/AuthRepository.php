<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
        // kode yoga (password tidak di hash)
        // User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => $data['password']
        // ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')
            ->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(array $data)
    {

        // kode yoga sebelumnya
        // return User::where('email', $data['email'])
        // ->first();

        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
            return false;
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')
            ->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
