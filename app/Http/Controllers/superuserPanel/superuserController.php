<?php

namespace App\Http\Controllers\superuserPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\d1nle2023;
use Illuminate\Support\Facades\Cache;

class superuserController extends Controller
{
    public function index(Request $request){

        $muncit = Auth::user()->muncit;
        $district = Auth::user()->district;
        $users =[];

        if($request->ajax()) {
            $users = User::all();

            foreach ($users as $user) {
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

                ->rawColumns(['action','online_status','last_seen_minutes_ago'])
                ->order(function ($query){
                    if(request()->has('online_status')){
                        $query->orderBy('online_status', 'desc');
                    }
                })
                ->toJson();
                // ->make(true);
        }

        $totalRV = d1nle2023::where([
            ['man_add','=', 0]])->count();

        $totalCV = d1nle2023::where([
            ['survey_stat','=', 1]])->count();

        $totalHL = d1nle2023::where([
            ['survey_stat','=', 1]])->distinct('HL')->count('HL');

        $totalMA = d1nle2023::where([
            ['survey_stat','=', 1],
            ['man_add','=', 1]])->count();

        $dist1 = d1nle2023::select(
            DB::raw('IFNULL(Municipality, "TOTAL") as Municipality'),
            DB::raw('count(case when man_add <> 1 then id end) as RV'),
            DB::raw('count(distinct(HL), case when survey_stat = 1 then HL end) as HL'),
            DB::raw('(count(case when survey_stat = 1 then survey_stat end) - count(distinct(HL), case when survey_stat = 1 then HL end ))  as Members'),
            DB::raw('count(case when survey_stat = 1 and man_add = 1 then survey_stat end)  as MA'),
            DB::raw('count(case when survey_stat = 1 then Name end) as CV'))
        ->where([['district','=', 'DISTRICT I']])
        ->groupBy(DB::raw('Municipality with rollup'))
        ->get();

        $dist2 = d1nle2023::select(
            DB::raw('IFNULL(Municipality, "ZZ-TOTAL") as Municipality'),
            DB::raw('count(*) as RV'),
            DB::raw('count(distinct(HL), case when survey_stat = 1 then HL end) as HL'),
            DB::raw('(count(case when survey_stat = 1 then survey_stat end) - count(distinct(HL), case when survey_stat = 1 then HL end ))  as Members'),
            DB::raw('count(case when survey_stat = 1 and man_add = 1 then survey_stat end)  as MA'),
            DB::raw('count(case when survey_stat = 1 then Name end) as CV'))
        ->where([['district','=', 'DISTRICT II']])
        ->groupBy(DB::raw('Municipality with rollup'))
        ->get();

        return view('dashboard.superuser',compact('users','totalRV','totalCV','totalHL','totalMA','dist1','dist2'));
    }
}
