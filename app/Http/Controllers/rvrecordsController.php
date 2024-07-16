<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\d1nle2023;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class rvrecordsController extends Controller
{
   public function recordsadmin(Request $request){

    $voters = d1nle2023::select('id','Name','Barangay','Precinct_no','PL','HL','purok_rv',
        'sqn','survey_stat','man_add','District','Municipality');
    if($request->ajax()){
        return DataTables::of($voters)
            ->addColumn('action', function($row){
                return '
                    <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                    class="btn btn-danger btn-rounded waves-effect vmdelete"><i class="mdi mdi-account-remove"></i></a>

                    <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                    class="btn btn-info btn-rounded waves-effect gview" ><i class="mdi mdi-gift"></i></a>

                    <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                    class="btn btn-primary btn-rounded waves-effect vedit " ><i class="mdi mdi-account-edit"></i></a>';
            })
            ->rawColumns(['action'])
            ->toJson();
            // ->make(true);
        }
        return view('forms.recordsadmin');
    }

   public function selectmuncit(Request $request){

    $distval = $request->distval;
    $search = $request->search;
    $dataSelMuncit = d1nle2023::select('Municipality','District')->where([
            ['Municipality','like','%'.$search.'%'],
            ['District','=',$distval]])
        ->orderBy('Municipality','asc')
        ->pluck('Municipality','Municipality');
            // dd($dataSelMuncit);
        return response()->json(['items'=>$dataSelMuncit]);
    }

    public function selectBrgy(Request $request){
        $distval = $request->distval;
        $munval = $request->munval;
        $search = $request->search;
        $dataSelBarangay = d1nle2023::where([
                ['Barangay','like','%'.$search.'%'],
                ['District','=',$distval],
                ['Municipality','=',$munval]])
            ->orderBy('Barangay','asc')
            ->pluck('Barangay','Barangay');
        return response()->json(['items'=>$dataSelBarangay]);
    }
}
