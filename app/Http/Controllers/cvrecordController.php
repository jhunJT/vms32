<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\d1nle2023;
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
        $municipality = Auth::user()->muncit;
        $district = Auth::user()->district;
            $cvrecord = DB::table('master_list_nle2022s')->select('id_main','Name','Barangay','purok_rv','Municipality','HL','survey_stat','district')
            ->where('man_add','0')
            ->orderByRaw ('District,Municipality,Barangay, purok_rv,HL asc, position(Name IN HL) desc');

            if($request->ajax()){
                return DataTables::of($cvrecord)
                ->addColumn('action', function($row){
                    return '<a href="javascript:void(0)" type="button" data-id="'.$row->id_main.'"
                        class="btn btn-success btn-rounded waves-effect depEdEmployee " ><i class="mdi mdi-account-edit"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
            }
        return view('forms.depedemployees');
    }

    public function smuncit(Request $request){
        // dd($request->all());
        $search = $request->search;
        $dataMuncit = d1nle2023::where([
                ['District','=',$request->dist],
                ['Municipality','like','%'.$search.'%']])
             ->orderBy('Municipality')
             ->pluck('Municipality','Municipality');

        return response()->json(['items'=>$dataMuncit]);
    }

    public function sbrgy(Request $request){
        // dd($request->all());
        $search = $request->search;
        $dataMuncit = d1nle2023::where([
                ['District','=',$request->dist],
                ['Municipality','=', $request->muncit],
                ['Barangay','like','%'.$search.'%']])
             ->orderBy('Barangay')
             ->pluck('Barangay','Barangay');

        return response()->json(['items'=>$dataMuncit]);
    }
}
