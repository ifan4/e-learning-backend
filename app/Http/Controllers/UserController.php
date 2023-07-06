<?php

namespace App\Http\Controllers;

use App\Http\Resources\userResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();


        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'data' => $user->loadMissing('role:id,name'),
            'accessToken' => $user->createToken("token")->plainTextToken
        ]);
    }


    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successfully',
            'data' => $request->user()->loadMissing('role:id,name')
        ]);
    }

    public function register(Request $request)
    {
        $validate = $request->validate([
            'first_name' => 'required|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            // 'password_confirm' => 'required|min:6'
        ]);
        $request['role'] = 2;
        $request['password'] = bcrypt($request['password']);
        $newUser = User::create($request->all());

        return response()->json([
            'message' => 'Successfully registered!',
            'data' => $newUser
        ]);
    }

    public function myData()
    {
        $user = Auth::User()->loadMissing("role:id,name");

        return response()->json([
            'data' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {

        $user_id = Auth::id();
        $user = User::findOrFail($user_id);

        $user->update($request->all());

        // $user->save();
        return response()->json([
            'message' => 'Profile Successfully Updated!',
            'data' => $user->loadMissing('role:id,name'),
            'data_client' => $request->all()
        ]);
    }

    public function getAllUsers()
    {
        $users = User::all();

        return response()->json($users->loadMissing('role:id,name'));
    }
}
