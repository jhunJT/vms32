<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use Illuminate\Http\Request;

class userlogsController extends Controller
{
    public function userlogs(Request $request){
        // dd('wot');
        if($request->ajax()) {
            $userslogs = DB::table('activitylogs')->get();

            return DataTables::of($userslogs)
                ->toJson();
        }
        return view('auth.userlogs');
    }
}
