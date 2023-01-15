<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthenticationResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'family' => $request->family,
            'email' => $request->email,
            'password' => $request->password,
            'username' => $request->username,
            'phone_number' => $request->phone_number,
        ]);

        $token = $user->createToken('api_token');

        return AuthenticationResource::make($token);
    }
}
