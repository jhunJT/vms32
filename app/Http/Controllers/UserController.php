<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Blocked';
        $user->save();

        return redirect()->back()->with('status', 'User has been blocked.');
    }

}
