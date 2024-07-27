<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\houseleader;
use App\Models\d1nle2023;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class plhlController extends Controller
{
    public function hlindex(Request $request){
        $municipality = Auth::user()->muncit;

        $houseleader = houseleader::where('muncit','=', $municipality)->get();

        // $houseleader = houseleader::select(DB::raw('houseleaders.houseleader, houseleaders.barangay ,houseleaders.purok,
        //     houseleaders.remarks,COUNT(d1nle2023s.HL) AS count_house_leader'))
        //     ->leftJoin('d1nle2023s','d1nle2023s.HL','=','houseleaders.houseleader')
        //     ->groupBy(DB::raw('houseleaders.houseleader'))
        //     ->where('muncit','=', $municipality)->get();

        if($request->ajax()){
            return DataTables::of($houseleader)
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                class="btn btn-danger btn-rounded waves-effect gntdelete" ><i class="mdi mdi-account-remove"></i></a>

                <a href="javascript:void(0)" type="button" data-id="'.$row->id.'" data-name="'.$row->houseleader.'"
                       class="btn btn-info btn-rounded waves-effect hlmemview" ><i class="mdi mdi-account-group"></i></a>

                <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                    class="btn btn-primary btn-rounded waves-effect hledit" ><i class="mdi mdi-account-edit"></i></a>';
            })
            ->addColumn('mid','')
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('forms.hlrecords');
    }

    public function selbrgy(Request $request){
        $muncit = Auth::user()->muncit;
        $search = $request->search;
        $databrgy = DB::table('houseleaders')->where([
                ['muncit','=',$muncit,],
                ['barangay','like','%'.$search.'%']])
                ->orderBy('barangay')
                ->pluck('barangay','barangay');
        return response()->json(['items'=>$databrgy]);
    }

    public function hldelete (Request $request){
        houseleader::destroy($request->id);
    }

    public function hledit($id){
        $hldetails = houseleader::find($id);
        if(! $hldetails){
            abort(404);
        }
        return $hldetails;
    }

    public function hlupdate(Request $request){

        $HLUpdate = ([
            'purok' => $request->hlPurok, //
            'remarks' => $request->hlRemarks, //
        ]);

        houseleader::where('id',$request->hlid)->update($HLUpdate);
        return response()->json(['success' => 'HL Updated!'], 201);
    }

    public function vmembers(Request $request){
        // dd($request->id, $request->hlname);

        $members  =  DB::table('d1nle2023s')
            ->select('houseleaders.houseleader','houseleaders.barangay','d1nle2023s.Name','d1nle2023s.remarks', 'd1nle2023s.grant_rv')
            ->join('houseleaders','houseleaders.houseleader','=','d1nle2023s.HL')
            ->where('houseleaders.houseleader','=',$request->hlname)
            ->get();

            if($request->ajax()){
                return DataTables::of($members)->make(true);
        }
    }
}
