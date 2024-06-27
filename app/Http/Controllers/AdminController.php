<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function updateRole(Request $request, $id)
    {
        $user = User::find($id);
        $user->role = $request->input('role');
        $user->save();
        return response()->json(['success' => 'Role updated successfully']);
    }

    public function deactivateUser($id)
    {
        $user = User::find($id);
        $user->is_active = false;
        $user->save();
        return response()->json(['success' => 'User deactivated successfully']);
    }


}
