<?php

namespace App\Http\Controllers;

use App\Events\OnlineStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            // Authentication was successful
            // You can return a success message or token here
            return response(['message' => 'Login successful'], 200);
        }
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('token')->plainTextToken;
        return response()->json(['token' => $token], 200);

        // Authentication failed
        // return response(['message' => 'Invalid credentials'], 401);
    }
    public function Register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
            ]);
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            return response()->json(['message' => 'User created successfully'], 200);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        $user->is_online = true;
        $user->save();
        broadcast(new OnlineStatus($user))->toOthers();
        return response()->json(['user' => $user], 200);
    }
    public function userOnlineStatus(Request $request){
        $request->validate(
            [
                'is_online' => 'required|boolean',
            ]
            );
            $user = $request->user();
            $user->is_online = $request->is_online;
            $user->save();
            broadcast(new OnlineStatus($user))->toOthers();
            
            return response()->json(['success' =>true]);
    }
    
}
