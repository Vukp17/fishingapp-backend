<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Google_Client;

class AuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        $idToken = $request->input('id_token');

        // Verify the token with Google
        $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($idToken);

        if ($payload) {
            $googleId = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];

            // Find or create the user
            $user = User::firstOrCreate(
                ['google_id' => $googleId],
                ['name' => $name, 'email' => $email]
            );

            // Create a new personal access token
            $token = $user->createToken('google-login-token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'user_id' => $user->id
            ], 200);
        } else {
            return response()->json(['error' => 'Invalid ID token'], 401);
        }
    }
}
