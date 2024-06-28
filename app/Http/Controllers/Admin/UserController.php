<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    public function index()
{
    // dd('Customers Page');
    return view('customers');
}

public function getUsers(Request $request)
{
    if (request()->ajax()) {
        $users = User::select(['id', 'name', 'email', 'is_active', 'role']);
        return DataTables::of($users)
            ->addColumn('action', function($row) {
                $btn = '<button onclick="updateRole(' . $row->id . ', \'admin\')" class="edit btn btn-success btn-sm">Update Role</button>';
                $btn .= ' <button onclick="deactivateUser(' . $row->id . ')" class="delete btn btn-danger btn-sm">Deactivate</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return abort(404);
}
}
