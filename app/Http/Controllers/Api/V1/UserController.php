<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        return response()->json($user);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(null, 204);
    }

    // public function register(Request $request)
    // {

    //     $validatedData = $request->validate([
    //         'name' => 'required|max:55',
    //         'email' => 'email|required|unique:users',
    //         'password' => 'required|confirmed'
    //     ]);
    //      return response()->json($validatedData);
    //     $validatedData['password'] = Hash::make($request['password']);

    //     $user = User::create($validatedData);

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //     ], 201);
    // }
    public function register(Request $request)    {
              print_r($request->all());
              $request['password'] = Hash::make($request['password']);
                $user = User::create($request->all());
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], 201);

    }
    public function login(Request $request)
    {
        // Attempt login with email and password
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details',
            ], 401);
        }

        // Fetch user by email
        $user = User::where('username', $request['username'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // Create a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_id' => $user->id,  // Add this line
        ]);
    }

}