<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\Users;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //User registation
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token'=>$token,
            'token_type'=>'Bearer'
        ]);
    }

    //user login
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = User::where('email',$request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['massage'=>'Invalid credentials'],401);
        }

        $token = $user->createToken('auth_token')-> plainTextToken;

        return response()->json([
            'access_token'=>$token,
            'token_type'=>'Bearer'
        ]);
    }    

    //logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['massage'=>'logged out']);
    }    
    
}
