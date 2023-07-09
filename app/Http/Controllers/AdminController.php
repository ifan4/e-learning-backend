<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        $admins = User::where('role', 1)->get();

        return response()->json($admins->loadMissing('role:id,name'));
    }
    public function showDetail($user_id)
    {
        $admin = User::where('role', 1)->findOrFail($user_id);

        return response()->json($admin->loadMissing('role:id,name'));
    }

    public function changeTo(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:App\Models\User,id',
            'changeTo' => 'required|in:admin,user,creator'
        ]);

        $user_id = $request->user_id;
        $changeTo = $request->changeTo;

        $user = User::findOrFail($user_id);

        switch ($changeTo) {
            case 'admin':
                $user->role = 1;
                break;

            case 'user':
                $user->role = 2;
                break;

            case 'creator':
                $user->role = 3;
                break;
        }

        $user->save();

        return response()->json([
            'Message' => 'User Successfully Changed his Role to ' . $changeTo,
            'data' => $user->loadMissing('role:id,name')
        ]);
    }
}
