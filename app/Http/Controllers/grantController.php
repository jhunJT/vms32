<?php

namespace App\Http\Controllers;
use App\Models\grantDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class grantController extends Controller
{
    public function index(Request $request){
        $municipality = Auth::user()->muncit;
        $grants = grantDetails::where('muncit','=', $municipality)->get();
        // dd($grants);
        if($request->ajax()){
            return DataTables::of($grants)
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                class="btn btn-danger btn-rounded waves-effect gntdelete" ><i class="mdi mdi-account-remove"></i></a>

                <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                    class="btn btn-primary btn-rounded waves-effect gntedit" ><i class="mdi mdi-account-edit"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('forms.districtGrants');
    }

    public function grantSumm(Request $request){
        $municipality = Auth::user()->muncit;
        $grantssumm = grantDetails::select('barangay',
            DB::raw('sum(case when grant_details.grant = "AICS" then 1 else 0 end) as AICS'),
            DB::raw('sum(case when grant_details.grant = "Cash" then 1 else 0 end) as Cash'),
            DB::raw('sum(case when grant_details.grant = "Scholarship" then 1 else 0 end) as Scolarship'),
            DB::raw('sum(case when grant_details.grant = "TUPAD" then 1 else 0 end) as TUPAD'),
            DB::raw('sum(case when grant_details.grant = "MSME GRANT" then 1 else 0 end) as MSME'))
            ->where('muncit','=', $municipality)
            ->groupBy('barangay')
            ->get();
        if($request->ajax()){
            return DataTables::of($grantssumm )->make(true);
        }
    }

    public function getHLmuncit(Request $request){
        $search = $request->search;
        $datamuncit = grantDetails::where('muncit','like','%'.$search.'%')
             ->orderBy('muncit')
             ->pluck('muncit','muncit');
        return response()->json(['items'=>$datamuncit]);
    }

    public function getHLbrgy(Request $request){
        $muncit = $request->muncit;
        $search = $request->search;
        $databrgy = grantDetails::where([
                ['muncit','=',$muncit,],
                ['barangay','like','%'.$search.'%']])
             ->orderBy('barangay')
             ->pluck('barangay','barangay');
        return response()->json(['items'=>$databrgy]);
    }

    public function getgrantType(Request $request){
        $dist = $request->dist;
        $muncit = $request->muncit;
        $barangay = $request->barangay;
        $search = $request->search;
        $datagrant = grantDetails::where(
            [
                ['district','=',$dist,],
                ['muncit','=',$muncit,],
                ['barangay','=',$barangay,],
                ['grant','like','%'.$search.'%']
            ])
             ->orderBy('grant')
             ->pluck('grant','grant');
        return response()->json(['items'=>$datagrant]);
    }

    public function fltrdate(Request $request){
        $dist = $request->dist;
        $muncit = $request->muncit;
        $barangay = $request->barangay;
        $typegrant = $request->typegrant;

        // $grtdate =[];
        $search = $request->search;

            $grtdate = grantDetails::where(
                // 'date','like', "%$search%")
                [
                    ['district','=',$dist,],
                    ['muncit','=',$muncit,],
                    ['barangay','=',$barangay,],
                    ['grant','=',$typegrant,],
                    ['date','like', "%$search%"]
                ]
                )
            ->groupBy('date','barangay')
            ->orderBy('date','desc')
            ->pluck('date','date');

        return response()->json(['item'=>$grtdate]);
    }

    public function grantdelete(Request $request){
        grantDetails::destroy($request->id);
    }

    public function grantedit($id){
        $gdetails = grantDetails::find($id);
        if(! $gdetails){
            abort(404);
        }
        return $gdetails;
    }

    public function grantupdate(Request $request){

        $grntUpdate = ([
            'name' => $request->gname, //
            'grant' => $request->ggrant, //
            'date' => $request->gdate, //
            'amount' => $request->gamount, //
            'remarks' =>$request->gremarks
        ]);

        grantDetails::where('id',$request->gid)->update($grntUpdate);
        return response()->json(['success' => 'Grant Updated!'], 201);
    }
}
