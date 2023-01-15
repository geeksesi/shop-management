<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserController\RegisterRequest;
use App\Http\Resources\AuthenticationResource;
use App\Models\User;

class UserController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        $user = User::create($data);

        $token = $user->createToken('api_token');

        return AuthenticationResource::make($token);
    }
}
