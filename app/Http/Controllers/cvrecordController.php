<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\d1nle2023;
use Illuminate\Container\RewindableGenerator;
use Illuminate\Support\Facades\Redis;

class cvrecordController extends Controller
{
    public function index(Request $request){

        $municipality = Auth::user()->muncit;
        $district = Auth::user()->district;
            $cvrecord = d1nle2023::select('Name','Barangay','HL','purok_rv','sqn','sethl','Municipality','vstatus','is_member','precinct_no')
                ->where([['district','=', $district],['municipality','=', $municipality],['survey_stat','=', 1]])
                ->orderByRaw ('Barangay, purok_rv,sqn asc,HL asc, position(Name IN HL) desc');

            if($request->ajax()){
                return DataTables::of($cvrecord)
                ->addColumn('id','')
                ->rawColumns(['action'])
                ->make(true);
            }
        return view('forms.cvrecords');
    }

    public function cvhlsumm(Request $request){

        $municipality = Auth::user()->muncit;
        $district = Auth::user()->district;

        $hlsumm = d1nle2023::select(
            DB::raw('IFNULL(Barangay, "TOTAL") as Barangay'),
            DB::raw('count(distinct(HL), case when survey_stat = 1 then HL end) as HL'),
            DB::raw('count(distinct(Name), case when survey_stat = 1 then Name end) - count(distinct(HL), case when survey_stat = 1 then HL end) as Members'),
            DB::raw('count(case when survey_stat = 1 then name end) as totalCV'))
            ->where([['District','=', $district],
                     ['municipality','=', $municipality],
                     ['survey_stat','=', 1]])
            ->groupBy(DB::raw('Barangay WITH ROLLUP'))
            ->having('HL','>=','1')
            ->get();
        if($request->ajax()){
            return DataTables::of($hlsumm )->make(true);
        }
    }

    public function cvmuncit(Request $request){
        // dd($request->all());
        $search = $request->search;
        $dataMuncit = d1nle2023::where([
                ['District','=',$request->dist],
                ['Municipality','like','%'.$search.'%']])
             ->orderBy('Municipality')
             ->pluck('Municipality','Municipality');

        return response()->json(['items'=>$dataMuncit]);
    }

    public function cvrecordbrgy(Request $request){

        if(Auth::user()->role == 'admin' || Auth::user()->role == 'superuser'){
            $muncit = $request->muncit;
        }else if(Auth::user()->role == 'encoder' || Auth::user()->role == 'supervisor'){
            $muncit = $request->muncit2;
        }

        $search = $request->search;
        $databrgy = d1nle2023::where([
                ['Municipality','=',$muncit],
                ['Barangay','like','%'.$search.'%']])
             ->orderBy('Barangay')
             ->pluck('Barangay','Barangay');

        return response()->json(['items'=>$databrgy]);
    }

    public function selHL(Request $request){

        if(Auth::user()->role == 'admin' || Auth::user()->role == 'superuser'){
            $muncit = $request->muncit;
            $dist = $request->dist;
        }else if(Auth::user()->role == 'encoder' || Auth::user()->role == 'supervisor'){
            $muncit = $request->muncit2;
            $dist = $request->dist2;
        }

        $barangay = $request->barangay;
        $search = $request->search;
        $dataHL = d1nle2023::where(
            [
                ['District','=',$dist],
                ['Municipality','=',$muncit],
                ['Barangay','=',$barangay],
                ['HL','like','%'.$search.'%']
            ])
             ->orderBy('HL')
             ->pluck('HL','HL');

        return response()->json(['items'=>$dataHL]);
    }

    public function sortPurok(Request $request){

        // $dist = $request->dist;
        // $muncit = $request->muncit;
        $dist = Auth::user()->district;
        $muncit = Auth::user()->muncit;
        $barangay = $request->barangay;
        $search = $request->search;

        $sortP = d1nle2023::where(
            [
                ['District','=',$dist],
                ['Municipality','=',$muncit],
                ['Barangay','=',$barangay],
                ['survey_stat','=',1],
            ])
             ->orderBy('purok_rv','asc')
             ->pluck('purok_rv','purok_rv');
        return response()->json(['items'=>$sortP]);
    }

    public function depedemployees(Request $request){
        return view('forms.depedemployees');
    }

    public function depedemployeesview(Request $request){
        return view('forms.depedemployeesview');
    }

    public function loadDistrictDataView(Request $request){
        // dd($request->all());
        $cvrecord = DB::table('master_list_nle2022s')->select('id_main','Name','Barangay',
            'is_depedEmployee','Municipality','survey_stat','district','school','level','HL')
                ->where('man_add', 0)
            ->whereIn('is_depedEmployee', [1, 2, 3])
            ->where('school', $request->selectSchool)  // Use where for exact match
            ->orderBy('level', 'asc');

        if($request->ajax()){
            return DataTables::of($cvrecord)
            ->addColumn('action', function($row){
                return '<div class="btn-group mt-2" role="group">
                            <button type="button" class="btn btn-success depEmp" data-id="'.$row->id_main.'" title="Supporter"><i class="fa fa-check" style="font-size: 1.5em;"></i></button>
                            <button type="button" class="btn btn-warning notSupp" data-id="'.$row->id_main.'" title="Not Supporter"><i class="fa fas fa-times" style="font-size: 1.5em;"></i></button>
                            <button type="button" class="btn btn-danger depClear" data-id="'.$row->id_main.'" title="Clear"><i class="fa fa-eraser" style="font-size: 1.5em;"></i></button>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function loadDistrictData(Request $request){
        $cvrecord = DB::table('master_list_nle2022s')->select('id_main','Name','Barangay','is_depedEmployee','Municipality','HL',
            'survey_stat','district','school','level')
        ->where([['man_add','0'],['Name','like','%'.$request->search.'%']])
        ->orderByRaw ('is_depedEmployee asc, Name');

        if($request->ajax()){
            return DataTables::of($cvrecord)
            ->addColumn('action', function($row){
                return '<div class="btn-group mt-2" role="group">
                            <button type="button" class="btn btn-success depEmp" data-id="'.$row->id_main.'" title="Supporter"><i class="fa fa-check" style="font-size: 1.5em;"></i></button>
                            <button type="button" class="btn btn-warning notSupp" data-id="'.$row->id_main.'" title="Not Supporter"><i class="fa fas fa-times" style="font-size: 1.5em;"></i></button>
                            <button type="button" class="btn btn-danger depClear" data-id="'.$row->id_main.'" title="Clear"><i class="fa fa-eraser" style="font-size: 1.5em;"></i></button>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function smuncit(Request $request){
        $search = $request->search;
        $dataMuncit = DB::table('master_list_nle2022s')->where([
                ['District','=',$request->dist],
                ['Municipality','like','%'.$search.'%']])
             ->orderBy('Municipality')
             ->pluck('Municipality','Municipality');

        return response()->json(['items'=>$dataMuncit]);
    }

    public function sbrgy(Request $request){
        $search = $request->search;
        $dataMuncit = DB::table('master_list_nle2022s')->where([
                ['District','=',$request->dist],
                ['Municipality','=', $request->muncit],
                ['Barangay','like','%'.$search.'%']])
             ->orderBy('Barangay')
             ->pluck('Barangay','Barangay');

        return response()->json(['items'=>$dataMuncit]);
    }

    public function employeeSave(Request $request){
        DB::table('master_list_nle2022s')->where('id_main',$request->dataId )
            ->update(['is_depedEmployee' => 1,'school' => $request->school, 'level' => $request->level]);
        return response()->json(['success' => 'Record Updated!']);
    }

    public function notsupporterSave(Request $request){
        DB::table('master_list_nle2022s')->where('id_main',$request->dataId )->update(['is_depedEmployee' => 2,'school' => $request->school, 'level' => $request->level]);
        return response()->json(['success' => 'Record Updated!']);
    }

    public function depClear(Request $request){

        $request->validate([
            'dataId' => 'required|integer',
        ]);

        $checkm = DB::table('master_list_nle2022s')->where('id_main', $request->dataId)->value('id_mun');

        if (is_null($checkm)) {
            DB::table('master_list_nle2022s')->where('id_main', $request->dataId)->delete();
        }else{
            DB::table('master_list_nle2022s')->where('id_main', $request->dataId)->update([
                'is_depedEmployee' => 0,
                'school' => 'NONE',
                'level' => 'NONE'
            ]);
        }
        return response()->json(['success' => 'Record processed successfully!']);
    }

    public function schoolList(Request $request){
        $search = $request->search;
        $dataSchool = DB::table('master_list_nle2022s')
             ->where('school','like','%'.$search.'%')
             ->orderBy('school')
             ->pluck('school','school');
        return response()->json(['items'=>$dataSchool]);
    }

    public function techearsRecord(Request $request){
        $cvrecord = DB::table('master_list_nle2022s')->select('Name','is_depedEmployee','survey_stat','school','level')
        ->where([['school',$request->selectSchool]])
        ->orderByRaw ('is_depedEmployee asc, Name');

        if($request->ajax()){
            return DataTables::of($cvrecord)
            ->make(true);
        }
    }

    public function techearsRecordFiltered(Request $request){
        $cvrecord = DB::table('master_list_nle2022s')->select('Name','is_depedEmployee','survey_stat','school','level')
        ->where([['school',$request->empschool],['is_depedEmployee',$request->empstatus]])
        ->orderByRaw ('is_depedEmployee asc, Name');

        if($request->ajax()){
            return DataTables::of($cvrecord)
            ->make(true);
        }
    }

    public function techearsNotfound(Request $request){

        $request->validate([
            'fullname' => 'required|string|max:255',
            'level' => 'required|string|max:50',
            'school' => 'required|string|max:255',
        ]);

        $newrecord = [
            'Name' => $request->fullname,
            'level' => $request->level,
            'school' => $request->school,
            'is_depedEmployee' => 3,
            'man_add' => 0,
        ];

        DB::table('master_list_nle2022s')->insert($newrecord);

        return response()->json([
            'success' => 'New Record Added!'
        ], 201);
    }

    public function datajson(Request $request){
        $search = $request->search;
        $datasamar = DB::table('samardata')
             ->where([
                    ['district',$request->dist],
                    ['muncit','like','%'.$search.'%']
                ])
             ->orderBy('muncit', 'asc')
             ->pluck('muncit','muncit');
        return response()->json(['items'=>$datasamar]);
    }
}
