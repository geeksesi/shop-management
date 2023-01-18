<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserController\LoginRequest;
use App\Http\Requests\API\UserController\RegisterRequest;
use App\Http\Resources\AuthenticationResource;
use App\Models\User;
use Illuminate\Http\Request;

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
        $data = $request->validated();
        $user = User::where("username", $data["username"])->first();

        if (empty($user)){
            return response()->json("user doesn't exist.", 404);
        }
        else if(password_verify($data["password"], $user["password"])){
            $token = $user->createToken('api_token');
            return AuthenticationResource::make($token);
        }
        else{
            return response()->json("Password Incorrect!", 401);
        }

    }
}
