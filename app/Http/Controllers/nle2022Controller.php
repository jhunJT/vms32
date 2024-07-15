<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\nle2022;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class nle2022Controller extends Controller
{
    public function statistics2022(Request $request){

        $results = nle2022::select(
            'id','barangay',
            DB::raw('sum(rv) as rv'),
            DB::raw('sum(cv) as cv'),
            'candidate','votes','position')
            ->groupBy('barangay','candidate');

        if($request->ajax()){
            return DataTables::of($results)
               ->make(true);
           }
        return view('archives.nle2022data');
    }

    public function statistics2022data(Request $request){
        $district = nle2022::select(
                DB::raw('IFNULL(Municipality,"TOTAL") as Municipality'),
                DB::raw('sum(case when candidate = "SHAREE ANN" then rv else 0 end) as RV'),
                DB::raw('sum(case when candidate = "SHAREE ANN" then cv else 0 end) as CV',),
                DB::raw('sum(case when candidate = "SHAREE ANN" then votes else 0 end) as SHAREE_ANN'),
                DB::raw('sum(case when candidate = "ARNOLD" then votes else 0 end) as ARNOLD'),
                DB::raw('sum(case when candidate = "JIMBOY" then votes else 0 end) as JIMBOY'),
                DB::raw('sum(case when candidate = "GEMMA" then votes else 0 end) as GEMMA'),
                DB::raw('sum(case when candidate = "SARMIENTO" then votes else 0 end) as SARMIENTO'),
                DB::raw('sum(case when candidate = "ALMA" then votes else 0 end) as ALMA'))
            ->where('district',$request->district)
            ->groupBy(DB::raw('Municipality'))
            ->get();

        $district2  = DB::table('nle2022s')
            ->groupBy('municipality','barangay','position','candidate')
            ->select('municipality','barangay','position','candidate',
                DB::raw('sum(rv) as rv'),
                DB::raw('sum(cv) as cv'),
                DB::raw('sum(votes) as votes'),
            )
            ->where([
                     ['district',$request->district],
                    //  ['municipality','=', $request->muncit],
                     ['position','=', $request->sposition]])
            ->get();
            // dd($request->district, $request->muncit, $request->sposition);

        return response()->json(['districtData'=>$district,'districtData2'=>$district2 ]);
    }

    public function fetchmuncitss(Request $request){
        $search = $request->search;
        $district = $request->district;
        // dd($district);
        $datamuncit = nle2022::select('municipality')
            ->distinct()
            ->where([
                ['district','=',$district],
                ['municipality','like','%'.$search.'%']])
            ->orderBy('municipality','asc')
            ->pluck('municipality','municipality');
        return response()->json(['items'=>$datamuncit]);
    }

    public function fetchbrgyss(Request $request){
        $muncitss = $request->muncitss;
        $search = $request->search;
        $databrgy = nle2022::where([
                ['barangay','like','%'.$search.'%'],
                ['municipality','=',$muncitss]
            ])
             ->pluck('barangay','barangay');
        return response()->json(['items'=>$databrgy]);
    }

    public function fetchpositionss(Request $request){
        $search = $request->search;
        $dataposition = nle2022::where(
            // ['municipality','=',$muncitss],
            'position','like', $search.'%')
             ->pluck('position','position');
        return response()->json(['items'=>$dataposition]);
    }

    public function fetchcandidate(Request $request){
        // dd('a');
        $poss = $request->poss;
        $search = $request->search;
        $datacan = nle2022::where([
                ['candidate','like','%'.$search.'%'],
                ['position','=',$poss]])
             ->pluck('candidate','candidate');
        return response()->json(['items'=>$datacan]);
    }

    public function fetchposition(Request $request){
        // dd('a');
        $search = $request->search;
        $smuncit = $request->smuncit;
        $dataposition = DB::table('nle2022s')->select('position')
            ->distinct()
            ->where('position','like','%'.$search.'%')
                //    ['municipality','=',$smuncit]])
            ->orderBy('position','asc')
            ->pluck('position','position');
        return response()->json(['items'=>$dataposition]);
    }

    public function getposition(Request $request){

        $position  = DB::table('nle2022s')
                ->groupBy('barangay')
                ->select('barangay','position',
                    DB::raw('sum(rv)/6 as rv'),
                    DB::raw('sum(cv)/6 as cv'),
                )
            ->distinct()
            // ->where('municipality','=', $request->cmuncit)
            ->get();
        return response()->json(['position'=>$position]);
    }

    public function getposition2(Request $request){

        $rvcv =  DB::table('nle2022s')
                    ->groupBy('barangay')
                    ->select('barangay','position',
                        DB::raw('sum(rv)/6 as rv'),
                        DB::raw('sum(cv)/6 as cv'),
                    )
                ->distinct()
                ->where('municipality','=', $request->cmuncit)
                ->get();

        $position  = DB::table('nle2022s')
            ->groupBy('municipality','barangay','position','candidate')
            ->select('municipality','barangay','position','candidate',
                DB::raw('sum(rv) as rv'),
                DB::raw('sum(cv) as cv'),
                DB::raw('sum(votes) as votes'),
            )
            ->where(
                    'position','=', $request->sposition)
            ->get();
        return response()->json(['position'=>$position,'rvcv'=>$rvcv]);
    }


}
