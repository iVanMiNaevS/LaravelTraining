<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registration(Request $request)
    {
        $request->validate([
            "email" => "required|string|email|unique:users",
            "password" => ["string", "required", Password::min(3)->letters()->numbers()->mixedCase()],
            "first_name" => "required|string",
            "last_name" => "required|string"
        ]);


        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password), // Хешируем пароль
            'first_name' => $request->first_name,
            'last_name' => $request->last_name
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            "success" => true,
            "message" => "Success",
            "token" => $token
        ], 200);
    }

    public function login(Request $request)
    {

        $request->validate([
            "email" => "required|string|email|",
            "password" => ["string", "required", Password::min(3)->letters()->numbers()->mixedCase()],
        ]);

        $user = User::where("email", $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return response()->json([
                "success" => true,
                "message" => "Success",
                "token" => $user->createToken('api_token')->plainTextToken,
            ]);
        }
        return response()->json(
            [
                "success" => false,
                "message" => "Login failed"
            ],
            401
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            [

                "success" => true,
                "message" => "Logout"
            ],
            200
        );
    }

    public function user(User $user)
    {

        return response()->json(["message" => $user]);
    }
    public function getAll(){
        $users = User::select('id', 'email')
                 ->with(['createdTasks:id,title,description,created_by']) 
                 ->get();
        $arr = $users->map(function($user){
            $tasks = $user->createdTasks;
            return $user->toArray();
        });
        return response()->json(["message"=> $arr]);
    }
}
