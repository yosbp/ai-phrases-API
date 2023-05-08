<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        //Validated
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|email|string|unique:users,email',
                'password' => 'required'
            ]
        );

        // Message if validation fails
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Return response
        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("TOKEN")->plainTextToken,
            'user' => $request->all(),
        ], 200);
    }

    public function login(Request $request)
    {
        //Validated
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|string|exists:users,email',
                'password' => [
                    'required'
                ],
                'remember' => 'boolean'
            ]
        );

        // Message if validation fails

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        };

        // Check if user exists

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'The Provided credentials are not correct',
            ], 401);
        };

        // Get user data

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'Loggin Successfully',
            'token' => $user->createToken("TOKEN")->plainTextToken,
            'user' => $user->only('name', 'email'),
        ], 200);
    }
}
