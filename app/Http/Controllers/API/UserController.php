<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserController\LoginRequest;
use App\Http\Requests\API\UserController\RegisterRequest;
use App\Http\Resources\AuthenticationResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $user = User::where("username", $credentials["username"])->first();

        if (Auth::attempt($credentials)){
            $token = $user->createToken('api_token');
            return AuthenticationResource::make($token);
        }

        throw new AuthenticationException();

    }
}
