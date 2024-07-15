<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\d1nle2023;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class rvManagmentController extends Controller
{
    public function index(Request $request,$mun){

        $voters = d1nle2023::select('id','Name','District','Municipality','Barangay',
            'Province','man_add','Precinct_no','SIP','purok_rv','survey_stat','grant_rv',
            'HL','PL','remarks','vstatus','sqn','contact_no','dob','user','userlogs','qrcode_id')
        ->where('Municipality','=',$mun);

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
                ->addColumn('checkbox', '<input type="checkbox" name="newSildaCheckBox[]"
                    class="form-check-input input-mini align-middle newSildaCheckBox" value="{{$id}}" />')
                ->rawColumns(['checkbox','action'])
                ->make(true);
           }

           return redirect()->route('rv.manage', ['mun' => $mun]);
    }
}
