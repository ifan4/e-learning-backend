<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    function index()
    {
        $roles = Role::all();

        return response()->json($roles);
    }
    function showDetail($role_id)
    {
        $role = Role::findOrFail($role_id);

        return response()->json($role);
    }
}
