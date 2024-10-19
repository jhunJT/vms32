<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\d1nle2023;
use App\Models\houseleader;

use Illuminate\Http\Request;

class fixController extends Controller
{
    public function index(Request $request){
        $municipality = Auth::user()->muncit;
        $district = Auth::user()->district;
            $cvrecord = d1nle2023::select('Name','Barangay','HL','purok_rv','sqn','sethl','Municipality','vstatus','is_member')
                ->where([['district','=', $district],['survey_stat','=', 1],['Municipality',$municipality]])
                // ->orderby()
                ->orderByRaw ('Municipality, Barangay, purok_rv, Name asc, position(Name IN HL) desc');

            if($request->ajax()){
                return DataTables::of($cvrecord)
                ->addColumn('id','')
                ->rawColumns(['action'])
                ->make(true);
            }
        return view('errors.fixhl-member');
    }

    public function fixhlindex(Request $request){
        $municipality = '';
        $hlrecords = houseleader::select('houseleader','muncit','barangay','purok','sqn','id')
                ->where('muncit','=',$municipality)->orderBy('houseleader','asc');

        if($request->ajax()){
            return DataTables::of($hlrecords)
            ->addColumn('action', function($row){
                return '
                   <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                   class="btn btn-danger btn-rounded waves-effect recdelete"><i class="mdi mdi-account-remove"></i></a>';
            })
            ->addColumn('hid','')
            ->rawColumns(['action','hid'])
            ->make(true);
        }
        return view('errors.fixhl');
    }

    public function selmuncit(Request $request){
        $search = $request->search;
        $dataMuncit = d1nle2023::where([
                // ['District','=',$request->dist],
                ['Municipality','like','%'.$search.'%']])
             ->orderBy('Municipality')
             ->pluck('Municipality','Municipality');

        return response()->json(['items'=>$dataMuncit]);
    }

    public function selbrgy(Request $request){
        $search = $request->search;
        $dataBrgy = d1nle2023::where([
                // ['District','=',$request->dist],
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

    public function fixhldel(Request $request){
        // dd($request->id);
        houseleader::destroy($request->id);
        return response()->json([
            'success'=>'Record deleted successfully.'
        ],200);
    }

}
