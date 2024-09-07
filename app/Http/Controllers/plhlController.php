<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\houseleader;
use App\Models\purokleader;
use App\Models\d1nle2023;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class plhlController extends Controller
{
    public function hlindex(Request $request){
        $municipality = Auth::user()->muncit;

        // select houseleaders.houseleader, houseleaders.barangay, houseleaders.purok, d1nle2023s.grant_rv from vms.houseleaders
        // join vms.d1nle2023s on d1nle2023s.id = houseleaders.vid
        // where houseleaders.muncit = 'santa margarita'
        // group by houseleaders.houseleader, houseleaders.barangay, houseleaders.purok;

        // $houseleader = houseleader::select('houseleader','barangay','purok', 'remarks')
        //     ->where('muncit','=', $municipality)
        //     ->distinct()
        //     ->get();

        $houseleader = houseleader::select('houseleaders.houseleader', 'houseleaders.barangay', 'houseleaders.purok', 'd1nle2023s.grant_rv','houseleaders.id','houseleaders.vid')
            ->leftJoin ('vms.d1nle2023s','d1nle2023s.id','=','houseleaders.vid')
            ->groupBy('houseleaders.houseleader', 'houseleaders.barangay', 'houseleaders.purok','d1nle2023s.grant_rv','houseleaders.id','houseleaders.vid');

        if($request->ajax()){
            return DataTables::of($houseleader)
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" type="button" data-id="'.$row->id.'" data-vid="'.$row->vid.'"
                class="btn btn-danger btn-rounded waves-effect gntdelete" ><i class="mdi mdi-account-remove"></i></a>

                <a href="javascript:void(0)" type="button" data-id="'.$row->id.'" data-name="'.$row->houseleader.'"
                       class="btn btn-info btn-rounded waves-effect hlmemview" ><i class="mdi mdi-account-group"></i></a>';

                // <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                //     class="btn btn-primary btn-rounded waves-effect hledit" ><i class="mdi mdi-account-edit"></i></a>'
            })
            ->addColumn('mid','')
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('forms.hlrecords');
    }

    public function pbpcindex(Request $request){
        $municipality = Auth::user()->muncit;

        $houseleader = purokleader::where('muncit','=', $municipality)->get();
        if($request->ajax()){
            return DataTables::of($houseleader)
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                class="btn btn-danger btn-rounded waves-effect pbpcdelete" ><i class="mdi mdi-account-remove"></i></a>
                <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                    class="btn btn-primary btn-rounded waves-effect pbpcedit" ><i class="mdi mdi-account-edit"></i></a>';
            })
            ->addColumn('mid','')
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('forms.pbpcrecords');
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
        $unset = ([
            'survey_stat' => 0,
            'HL' => '',
            'hlids' => null,
            'sethl' => 0,
            'sqn' => null
        ]);

        DB::table('d1nle2023s')->where('id', $request->vid)->update($unset);
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
            ->select('houseleaders.houseleader','houseleaders.barangay','d1nle2023s.Name',
                'd1nle2023s.remarks', 'd1nle2023s.grant_rv')
            ->join('houseleaders','houseleaders.houseleader','=','d1nle2023s.HL')
            ->where('houseleaders.houseleader','=',$request->hlname)
            ->orderByRaw ('position(d1nle2023s.Name IN houseleaders.houseleader) desc')
            ->get();

            if($request->ajax()){
                return DataTables::of($members)->make(true);
        }
    }

    public function pbpcedit($id){
        $pbpcdetails = purokleader::find($id);
        if(! $pbpcdetails){
            abort(404);
        }
        return $pbpcdetails;
    }

    public function pbpcdelete (Request $request){

        purokleader::destroy($request->id);
    }

    public function pbpcupdate(Request $request){
        $HLUpdate = ([
            'status' => $request->pbstatus, //
            'remarks' => $request->hlRemarks, //
        ]);
        purokleader::where('id',$request->hlid)->update($HLUpdate);
        return response()->json(['success' => 'PB/PC Updated!'], 201);
    }
}
