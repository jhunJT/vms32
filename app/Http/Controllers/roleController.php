<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class roleController extends Controller
{
    public function dashboard(){

        if(Auth::user()->role === 'admin'){
            return redirect()->route('dashboard.admin');

        }elseif(Auth::user()->role === 'supervisor'){
            return redirect()->route('dashboard.supervisor');

        }elseif(Auth::user()->role === 'encoder'){
            return redirect()->route('dashboard.encoder');
        }
        return view('dashboard');
    }
}
