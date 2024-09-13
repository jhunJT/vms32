<?php

namespace App\Http\Controllers;
use App\Models\grantDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\d1nle2023;
use App\Models\grantsdrp;

use DateTime;

class grantController extends Controller
{
    public function index(Request $request){
        $municipality = Auth::user()->muncit;

        $grants = grantDetails::where('muncit','=', $municipality)->get();

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
            DB::raw('sum(case when grant_details.grant = "AKAP" then 1 else 0 end) as AKAP'),
            DB::raw('sum(case when grant_details.grant = "Scholarship" then 1 else 0 end) as Scolarship'),
            DB::raw('sum(case when grant_details.grant = "TUPAD" then 1 else 0 end) as TUPAD'),
            DB::raw('sum(case when grant_details.grant = "MSME GRANT" then 1 else 0 end) as MSME'))
            ->where('muncit','=', $municipality)
            ->groupBy('barangay');
        if($request->ajax()){
            return DataTables::of($grantssumm )->make(true);
        }
        // $colsth = grantDetails::select('grant')->where('muncit','=', $municipality)->distinct()->get();
        //     return view('district.grants',compact('colsth'));
    }

    public function getHLmuncit(Request $request){
        $search = $request->search;
        $datamuncit = grantDetails::where('muncit','like','%'.$search.'%')
             ->orderBy('muncit')
             ->pluck('muncit','muncit');
        return response()->json(['items'=>$datamuncit]);
    }

    public function getHLbrgy(Request $request){

        if(Auth::user()->role == 'encoder' || Auth::user()->role == 'supervisor' ){
            $muncit = Auth::user()->muncit;
        }else{
            $muncit = $request->muncit;
        }
        // dd($request->all());
        $search = $request->search;
        $databrgy = d1nle2023::where([
                ['Municipality','=',$muncit,],
                ['Barangay','like','%'.$search.'%']])
             ->orderBy('Barangay')
             ->pluck('Barangay','Barangay');
        return response()->json(['items'=>$databrgy]);
    }

    public function getgrantType(Request $request){
        $dist = $request->dist;
        $muncit = Auth::user()->muncit;
        // $barangay = $request->barangay;
        $search = $request->search;

        $datagrant = grantsdrp::select('grant_title')
            ->where(
                [
                    ['grant_muncit','=',$muncit],
                    ['isEnabled','=', 1],
                    ['grant_title','like','%'.$search.'%']
                ])
             ->orderBy('grant_title')
             ->pluck('grant_title','grant_title');
        return response()->json(['items'=>$datagrant]);
    }

    public function fltrdate(Request $request){

        $dist = $request->dist;
        $muncit = Auth::user()->muncit;
        // $muncit = $request->grantMuncit_1;
        // $barangay = $request->barangay;
        $typegrant = $request->typegrant;

        // $grtdate =[];
        $search = $request->search;
        // dd($dist,$muncit, $typegrant);
            $grtdate = grantDetails::where(
                // 'date','like', "%$search%")
                [
                    ['district','=',$dist,],
                    ['muncit','=',$muncit,],
                    // ['barangay','=',$barangay,],
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
            'grant' => $request->gntHolder, //
            'date' => $request->gdates, //
            'amount' => $request->gamounts, //
            'remarks' =>$request->gremarkss
        ]);

        grantDetails::where('id',$request->gid)->update($grntUpdate);
        return response()->json(['success' => 'Grant Updated!'], 201);
    }

    public function grantnames(Request $request){

        if(Auth::user()->role == 'encoder' || Auth::user()->role == 'supervisor' ){
            $dist = $request->dist;
            $muncit = Auth::user()->muncit;
        }else{
            $muncit = $request->muncit;
        }
        $barangay = $request->brgy;
        $search = $request->search;

            $grtname = d1nle2023::select('id','Name','survey_stat')
                ->where(
                [
                    ['District','=',$dist,],
                    ['Municipality','=',$muncit,],
                    ['Barangay','=',$barangay,],
                    ['Name','like', "%$search%"]
                ])
            ->orderBy('Name')
            ->get();

            // dd($grtname);

        return response()->json(['items'=>$grtname]);
    }

    public function grantfetch(Request $request){
        $municipality = Auth::user()->muncit;
        $search = $request->search;
            $grnttypes = DB::table('grantsdrps')
                ->where(
                [   ['grant_muncit','=', $municipality],
                    ['grant_title','like', "%$search%"]])
            ->latest()
            ->get();
        return response()->json(['items'=>$grnttypes]);
    }

    public function granttypeadd(Request $request){
        $municipality = Auth::user()->muncit;
        $request->validate([
            'granttype' => 'required',
        ]);

        $ifExsist = DB::table('grantsdrps')
            ->where([["grant_type", $request->granttype],
                     ["date_of_grant", $request->ggdate]
            ])->exists();
        abort_if($ifExsist,400, 'Grant already exist');

        $date = new DateTime($request->ggdate);
        $grnt_title = $request->granttype . " (" . $request->grant_agency . ")";
        grantsdrp::create([
            'grant_type' => strtoupper($request->granttype),
            'date_of_grant' => date_format($date ,"d M, Y" ) ,
            'grant_amount' =>  $request->ggamount,
            'g_remarks' => $request->ggremarks,
            'grant_agency' =>$request->grant_agency,
            'grant_title' => $grnt_title,
            'grant_muncit' =>$municipality,

        ]);

        return response()->json([
            'success' => 'House Leader Added!'
        ], 201);
    }

    public function grantsave(Request $request){
        $uid = Auth::user()->id;
        $request->validate([
            'agname' => 'required',
            'vuname' => 'required',
            'grnt_type' => 'required',
        ]);

        $ifExsist = DB::table('grant_details')
            ->where([["name", $request->vuname],
                     ["date", $request->gdate],
                     ["grant", $request->grnt_type],

            ])->exists();
        abort_if($ifExsist,400, 'Grant already exist');

        grantDetails::create([
            'name' => strtoupper($request->vuname),
            'district' => $request->grnt_dist ,
            'muncit' =>  $request->grnt_muncit,
            'barangay' => $request->grnt_brgy,
            'grant' => $request->grnt_type,
            'date' => $request->gdate,
            'amount' => $request->gamount,
            'grant_agency' => $request->grtagency,
            'grant_type' => $request->grttype,
            'remarks' => $request->gremarks,
            'vid' => $request->vuid,
            'uid' => $uid

        ]);

        return response()->json([
            'success' => 'Record Added!'
        ], 201);
    }

    public function viewrecords(Request $request){
        $municipality = Auth::user()->muncit;
        $grantlist = grantsdrp::where('grant_muncit',$municipality);
        if($request->ajax()){
            return DataTables::of($grantlist)

            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                class="btn btn-danger btn-rounded waves-effect gntlistdelete" ><i class="mdi mdi-account-remove"></i></a>';

                // <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                //     class="btn btn-primary btn-rounded waves-effect gntlistedit" ><i class="mdi mdi-account-edit"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function viewdelete(Request $request){
        grantsdrp::destroy($request->id);
    }

}
