<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\d1nle2023;
use Illuminate\Support\Facades\Cache;

class userManagementController extends Controller
{
    public function index(Request $request){

        $muncit = Auth::user()->muncit;
        $district = Auth::user()->district;
        $users =[];

        if($request->ajax()) {
            $users = User::where([
                    ['district','=',$district],
                    ['muncit','=',$muncit]
                ])->get();

            foreach ($users as $user) {
                // $user->stat = $user->status === 'active' ? '<span class="btn btn-success btn-rounded waves-effect waves-light">Online</span>' : '<span class="btn btn-danger btn-rounded waves-effect waves-light">Offline</span>';
                $user->online_status = Cache::has('user-is-online-' . $user->id) ? '<span class="btn btn-success btn-rounded waves-effect waves-light">Online</span>' : '<span class="btn btn-danger btn-rounded waves-effect waves-light">Offline</span>';
                $user->last_seen_minutes_ago = $user->last_seen ? Carbon::parse($user->last_seen)->diffForHumans() : 'Never'; // Calculate
            }


            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a style="padding-left:4px;" href="javascript:void(0)" type="button" data-id="'.$row->id.'" class="waves-effect userEdit"><i class="ri-user-follow-fill lg" style="font-size: 30px;"></i></a>';
                    $btn .= '<a style="padding-left:4px;" href="javascript:void(0)" type="button" data-id="'.$row->id.'"  data-name="'.$row->name.'"class="waves-effect userDelete"><i class="ri-user-unfollow-line" style="font-size: 30px;color:Tomato;"></i></a>';
                    return $btn;
                })

                ->addColumn('online_status', function($user) {
                    return $user->online_status;
                })

                ->addColumn('last_seen_minutes_ago', function($user) {
                    return $user->last_seen_minutes_ago;
                })

                ->addColumn('stat', function($user) {
                    return $user->stat;
                })

                ->rawColumns(['action','online_status','last_seen_minutes_ago'])
                ->make(true);
        }

            // dd($CVSummary,$district,$muncit);
        return view('forms.userview',compact('users'));
    }

    public function registerUser(Request $request)
    {

        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $activity = [
            'name' =>   $request->user()->name,
            'descrption' => 'Register/Update New User',
            'role' =>  $request->user()->role,
            'details' => $request->name,
            'date_time' => $todayDate,
        ];

        $request->validate([
            'username' => 'required',
            // 'password' => 'required|min:5',
            'level' => 'required',
            'name' => 'required',
            'email' => 'required',
            'birthday' => 'required',
            'district' => 'required',
            'muncit' => 'required',
        ]);

        User::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                // 'password' => Hash::make($request->password),
                'birthday' => $request->birthday,
                'district' => $request->district,
                'muncit' => $request->muncit,
                'contno' => $request->contno,
                'role' => $request->level,
                'tbname' => $request->tbname,
                'ulat' => $request->u_lat,
                'ulong' => $request->u_long
            ]
        );

        DB::table('activitylogs')->insert($activity);
        return response()->json(['success' => 'Record saved!']);
    }

    public function userEdit($id)
    {
        // dd('delete');
        $users = User::find($id);
        if(! $users){
            abort(404);
        }
        return response()->json($users);
    }

    public function userDelete(Request $request)
    {
        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $activity = [
            'name' =>   $request->user()->name,
            'descrption' => 'Delete User',
            'role' =>  $request->user()->role,
            'details' =>  $request->name,
            'date_time' => $todayDate,
        ];

        // dd($request->id);
        User::destroy($request->id);
        DB::table('activitylogs')->insert($activity);
        return response()->json([
            'success'=>'Record deleted successfully.'
        ],200);


    }
}
