<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\d1nle2023;

use Illuminate\Http\Request;

class fixController extends Controller
{
    public function index(Request $request){
        $municipality = Auth::user()->muncit;
        $district = Auth::user()->district;
            $cvrecord = d1nle2023::select('Name','Barangay','HL','purok_rv','sqn','sethl','Municipality','vstatus','is_member')
                ->where([['district','=', $district],['survey_stat','=', 1]])
                // ->orderby()
                ->orderByRaw ('Municipality, Barangay, purok_rv, sqn asc, position(Name IN HL) desc');

            if($request->ajax()){
                return DataTables::of($cvrecord)
                ->addColumn('id','')
                ->rawColumns(['action'])
                ->make(true);
            }
        return view('errors.fixhl-member');
    }

    public function selmuncit(Request $request){
        $search = $request->search;
        $dataMuncit = d1nle2023::where([
                ['District','=',$request->dist],
                ['Municipality','like','%'.$search.'%']])
             ->orderBy('Municipality')
             ->pluck('Municipality','Municipality');

        return response()->json(['items'=>$dataMuncit]);
    }

    public function selbrgy(Request $request){
        $search = $request->search;
        $dataBrgy = d1nle2023::where([
                ['District','=',$request->dist],
                ['Municipality','=',$request->muncit],
                ['Barangay','like','%'.$search.'%']
                ])
             ->orderBy('Barangay')
             ->pluck('Barangay','Barangay');

        return response()->json(['items'=>$dataBrgy]);
    }

    public function hleader(Request $request){
        $search = $request->search;
        $dataHL = d1nle2023::where([
                    ['District','=',$request->dist],
                    ['Municipality', '=' , $request->muncit],
                    ['Barangay', '=' , $request->brgy],
                    ['HL','like','%'.$search.'%']
                ])
             ->orderBy('HL')
             ->pluck('HL','HL');

        return response()->json(['items'=>$dataHL]);
    }

}
