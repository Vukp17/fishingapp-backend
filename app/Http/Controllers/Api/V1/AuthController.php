<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Google_Client;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        try{
            $username = $request->input('username');
            $email = $request->input('email');
    
    
    
            // Find or create the user
            $user = User::firstOrCreate(
                ['username' => $username, 'email' => $email,]
            );
    
            // Create a new personal access token
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'access_token' => $token,
                'user_id' => $user->id
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }

    }
}
