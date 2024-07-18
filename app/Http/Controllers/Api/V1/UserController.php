<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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
        try {
            // Authentication check: Ensure the request is authenticated (middleware should handle this)
            $currentUser = $request->user(); // Get the authenticated user
            $userToUpdate = User::find($id);
    
            if (!$userToUpdate) {
                return response()->json(['error' => 'User not found'], 404);
            }
    
            // Authorization logic: Ensure the user has permission to update the user data
            if ($currentUser->id !== $userToUpdate->id && !$currentUser->isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
    
            // Update user with request data
            $userToUpdate->username = $request->input('username', $userToUpdate->username);
            $userToUpdate->email = $request->input('email', $userToUpdate->email);
            $userToUpdate->language_id = $request->input('language_id', $userToUpdate->language_id);
    
            // Hash the password if it's included in the request
            if ($request->has('password') && !empty($request->input('password'))) {
                $userToUpdate->password = Hash::make($request->input('password'));
            }
    
            $userToUpdate->save();
    
            return response()->json([
                'message' => 'User updated successfully',
                'user' => $userToUpdate
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    


    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(null, 204);
    }
    public function spots(User $user)
    {
        return response()->json($user->spots);
    }
    public function storeSpot(Request $request, User $user)
    {
        Log::info('Request data: ', $request->all());
        $image = $request->file('image');

        Log::info('Uploaded image details: ', [
            'original_name' => $image->getClientOriginalName(),
            'mime_type' => $image->getClientMimeType(),
            'size' => $image->getSize(),
        ]);
        if ($request->hasFile('image')) {


            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images', $imageName, 'public');

            $spotData = $request->all();
            $spotData['image_id'] = '/storage/' . $imagePath;
            $spotData['image_id'] = str_replace('/', '-', trim($imagePath, '/'));

            $newSpotData = [
                'name' => $request->title,
                'description' => $request->description,
                'image_id' => $spotData['image_id'],
                'user_id' => $request->user_id,
                'updated_at' => now(),
                'created_at' => now(),
                'lng' => $request->lng,
                'lat' => $request->lat,
            ];

            try {
                $spot = $user->spots()->create($newSpotData);
                return response()->json($spot, 201);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        } else {
            return response()->json(['error' => 'No image uploaded'], 400);
        }
    }

    public function register(Request $request)
    {
        $request['password'] = Hash::make($request['password']);

        $user = User::create($request->all());

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'user_id' => $user->id,  // Add this line
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

        // Fetch user by username
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

    public function getImage($filename)
    {
        $realFilename = substr($filename, strrpos($filename, '-') + 1);

        // rest of the code...

        $path = storage_path('app/public/images/' . $realFilename);

        if (!File::exists($path)) {
            return response()->json(['message' => 'Image not found. Path: ' . $realFilename], 404);
        }
        $file = File::get($path);
        $type = File::mimeType($path);

        $response = response($file, 200)->header("Content-Type", $type);

        return $response;
    }
}
