<?php

namespace App\Http\Controllers\AdminPanel;

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

class adminController extends Controller
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
            DB::raw('count(*) as RV'),
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

        return view('dashboard.admin',compact('users','totalRV','totalCV','totalHL','totalMA','dist1','dist2'));
    }

    public function getMuncit(Request $request){
        $muncitval = $request->sendmuncit;
        $CVSummary = d1nle2023::select(
            DB::raw('IFNULL(Barangay, "TOTAL") as Barangay'),
            DB::raw('count(*) as RV'),
            DB::raw('count(distinct(HL), case when survey_stat = 1 then HL end) as HL'),
            DB::raw('(count(case when survey_stat = 1 then survey_stat end) - count(distinct(HL), case when survey_stat = 1 then HL end ))  as Members'),
            DB::raw('count(case when survey_stat = 1 and man_add = 1 then survey_stat end)  as MA'),
            DB::raw('count(case when survey_stat = 1 then Name end) as CV'),)
        ->where([['municipality','=', $muncitval]])
        ->groupBy(DB::raw('Barangay with rollup'))
        ->get();

        // Return JSON response
        return response()->json(['data' => $CVSummary]);
    }

    public function userShow(){
        return view('auth.user-reg');
    }

    public function uselogs(){
        return view('auth.userlogs');
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
            'password' => 'required|min:5',
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
                'password' => Hash::make($request->password),
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

    public function performanceView(Request $request)
    {

        $encoders = DB::table('users')
            ->select('users.name as name','d1nle2023s.District as district','d1nle2023s.Municipality as muncit','d1nle2023s.Barangay as brgy',
                DB::raw(DB::raw('concat(MONTHNAME(d1nle2023s.userlogs), " ",YEAR(d1nle2023s.userlogs)) as monthyear')),
                DB::raw('count(d1nle2023s.userid) as encoded'))
            ->join('d1nle2023s','d1nle2023s.userid','=','users.id')
            // ->where([['d1nle2023s.district','=', $district],['d1nle2023s.municipality','=', $municipality]])
            // ->groupBy(DB::raw('name with rollup','brgy'))
            ->groupBy(['name','brgy','monthyear','district','Municipality'])
            ->get();
        if($request->ajax()){
                return DataTables::of( $encoders)
                ->make(true);
            }
       return view('forms.encoder-performance');
    }

    public function fetchEncoder(Request $request){

        $search = $request->search;
        $dataEncoder = DB::table('users')
            ->join('stamargaritas','users.id','=','stamargaritas.userid')
            ->select('users.name')
            ->where('users.name','like','%'.$search.'%')
            ->pluck('users.name','users.name');
        return response()->json(['items'=>$dataEncoder]);
    }

    public function fetchBrgyEncoder(Request $request){
        $filterEncoder = $request->fltEncoder;
        $dataBrgy = DB::table('users')
            ->select('stamargaritas.Barangay')
            ->join('stamargaritas','stamargaritas.userid','=','users.id')
            ->where('users.name', "=", $filterEncoder)
            ->pluck('stamargaritas.Barangay','stamargaritas.Barangay');
        return response()->json(['items'=>$dataBrgy]);
    }

    public function fetchMonthYear(Request $request){
        // encoder name
        $filterEncoder = $request->fltEncoder;
        $fltBrgy = $request->fltBrgy;
        $dataMY = DB::table('users')
            ->select(DB::raw('concat(MONTHNAME(stamargaritas.userlogs), " ",YEAR(stamargaritas.userlogs)) as monthyear'))
            ->join('stamargaritas','stamargaritas.userid','=','users.id')
            ->where([['users.name', "=", $filterEncoder],
                    ['stamargaritas.Barangay',"=",$fltBrgy]])
            ->pluck('monthyear','monthyear');
        return response()->json(['items'=>$dataMY]);
    }

    public function profileedit($id){

        $users = User::find($id);
        if(! $users){
            abort(404);
        }
        return view('profile.pedit', compact('users'));
    }

    public function profileupdate(Request $request){
        $id = Auth::user()->id;
        $data = ([
            'name' => $request->fname, //
            'contno' => $request->contno, //
        ]);

       User::where('id',$id)->update( $data);
       $updatedUser = User::find($id);

        return response()->json([
            'success' => 'Profile Updated',
            'name' =>  $updatedUser->name
        ], 201);
    }

    public function changePassword(Request $request){

        // dd($request->current_password, $request->password);
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'The current password is incorrect.');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('dashboard.supervisor')->with('success', 'Password changed successfully.');
    }

}
