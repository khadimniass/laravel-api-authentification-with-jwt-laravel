<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {   //on accessède sans connexion
        $this->middleware('auth:api',['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'invalide cridentials'], 401);
        }
        return $this->createNewToken($token);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6|max:15|confirmed', //password_confirmation
            'name'=>'required',
            'telephone'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 500);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            [
                'name'=>$request->name,
                'telephone'=>$request->telephone,
                'email'=>$request->email,
                'password' => bcrypt($request->password),
            ],
        ));
        return response()->json([
            'user' => $user
        ], 201);
    }
    public function createNewToken($token)
    {
        return response()->json([
            'token' => $token,
            'type' => 'bearer',
            'expired' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
            'message' => 'welecome ' . auth()->user()->name
        ]);
    }
}
