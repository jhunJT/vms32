<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\houseleader;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class plhlController extends Controller
{
    public function hlindex(Request $request){
        $municipality = Auth::user()->muncit;

        $houseleader = houseleader::select('houseleader','barangay','remarks')
            ->where('muncit','=', $municipality)->get();

        if($request->ajax()){
            return DataTables::of($houseleader)
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                class="btn btn-danger btn-rounded waves-effect gntdelete" ><i class="mdi mdi-account-remove"></i></a>

                <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                       class="btn btn-info btn-rounded waves-effect gview" ><i class="mdi mdi-account-group"></i></a>

                <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                    class="btn btn-primary btn-rounded waves-effect gntedit" ><i class="mdi mdi-account-edit"></i></a>';
            })
            ->addColumn('id', '')
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('forms.hlrecords');
    }
}
