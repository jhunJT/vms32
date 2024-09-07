<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\d1nle2023;

class unsurveyedController extends Controller
{
    public function index(Request $request){
        $municipality = Auth::user()->muncit;
        $district = Auth::user()->district;
            $cvrecord = d1nle2023::select('Name','Barangay','purok_rv','Precinct_no','SIP','Municipality')
                ->where([['district','=', $district],['municipality','=', $municipality],['survey_stat','=', 0]])
                ->orderByRaw ('Barangay, purok_rv,HL asc, position(Name IN HL) desc');

            if($request->ajax()){
                return DataTables::of($cvrecord)
                ->addColumn('id','')
                ->rawColumns(['action'])
                ->make(true);
            }
        return view('forms.unsurveyed');
    }

    public function cvhlsumm(Request $request){

        $municipality = Auth::user()->muncit;
        $district = Auth::user()->district;

        $hlsumm = d1nle2023::select(
            DB::raw('IFNULL(Barangay, "TOTAL") as Barangay'),
            DB::raw('count(case when survey_stat = 0 then Name end) as count'))
            ->where([['District','=', $district],
                     ['municipality','=', $municipality],
                     ['survey_stat','=', 0],
                     ['man_add','=', 0]])
            ->groupBy(DB::raw('Barangay WITH ROLLUP'))
            ->get();
        if($request->ajax()){
            return DataTables::of($hlsumm )->make(true);
        }
    }

    public function cvmuncit(Request $request){
        $search = $request->search;
        $dataMuncit = d1nle2023::where([
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

}
