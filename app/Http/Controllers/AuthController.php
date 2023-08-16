<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validation 
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        //Create User object
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        //Assign Role
        $employee_role = Role::where('name', 'employee')->first();
        $employee_role->user()->save($user);

        //Create Tokens
        $token = $user->createToken('api_token')->plainTextToken;

        //Return response
        $response = [
            'user' => $user,
            'access_token' => $token,
        ];

        return response()->json($response, 201);
    }

    public function login(Request $request)
    {
        // Validation
        $validated = $request->validate(['email' => 'required|email', 'password' => 'required']);

        // Authentication
        if (Auth::attempt($validated)) {
            $user = User::where('email', $validated['email'])->first();
            $token = $user->createToken('api_token')->plainTextToken;

            $response = [
                'data' => [
                    'access_token' => $token
                ]
            ];

            return response()->json($response);
        } else {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(null, 204);
    }

    public function resetpassword(Request $request)
    {
        $validated = $request->validate(['password' => 'required|min:8']);

        $user = $request->user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        $user->tokens()->delete();

        return response()->json(['message' => 'password changed suscessfully'], 200);
    }
}