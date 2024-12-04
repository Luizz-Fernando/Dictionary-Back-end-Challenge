<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{


    public function singin(Request $request){
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!$token = JWTAuth::attempt($user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->responseWithToken($token, auth()->user());
    }

    public function singup(Request $request){
        $user = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $userCreate = User::Create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => Hash::make($user['password']),
        ]);

        $token = JWTAuth::fromUser($userCreate);

        return $this->responseWithToken($token, $userCreate);

    }


    private function responseWithToken(string $token, User $user)
    {
        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'token' =>  $token
        ]);
    }
}
