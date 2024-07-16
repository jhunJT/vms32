<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class setdrpdwnController extends Controller
{
    public function index(){
        return view('forms.setdrpdown');
    }
}
