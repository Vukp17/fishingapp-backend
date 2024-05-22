<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

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
    public function spots(User $user)
    {
        return response()->json($user->spots);
    }
    public function storeSpot(Request $request, User $user)
    {
        // $request->validate([
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     // validate other input fields...
        // ]);
        // var_dump($request->all());
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images', $imageName, 'public');

            $spotData = $request->all();
            $spotData['image_id'] = '/storage/' . $imagePath;
            // $location = explode(',', $request->location);
            // $lat = trim($location[0]);
            // $lng = trim($location[1]);
            $newSpotData = [
                'name' => $request->title,
                'description' => $request->description,
                'image_id' => $spotData['image_id'],
                'user_id' => $request->user_id,
                'updated_at' => '2021-09-01 00:00:00',
                'created_at' => '2021-09-01 00:00:00',
                'lng' => $request->lng,
                'lat' => $request->lat,
            ];
            try {
                $spot = $user->spots()->create($newSpotData);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
            return response()->json($spot, 201);
        } else {
            return response()->json(['error' => 'No image uploaded'], 400);
        }
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
    $path = storage_path('app/public/images/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
}
}
