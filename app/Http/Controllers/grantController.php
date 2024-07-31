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
        if(Auth::user()->role == 'encoder'){
            $dist = $request->dist;
            $muncit = $request->muncit2;
        }else{
            $dist = $request->dist2;
            $muncit = $request->muncit1;
        }

        // dd($dist, $muncit);
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

        // dd( $dist , $muncit, $barangay,$search);

            $grtname = d1nle2023::select('id','Name')
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
        // $events = grantsdrp::all();
        // $data = [];

        // foreach ($events as $event) {
        //     $id = $event->id;
        //     $category = $event->grant_type;
        //     $date =  $event->date_of_grant;

        //     $found = false;
        //     foreach ($data as &$item) {
        //         if ($item['text'] === $category) {
        //             $item['children'][] = [
        //                 'id' => $event->id,
        //                 'text' => $date
        //             ];
        //             $found = true;
        //             break;
        //         }
        //     }
        //     if (!$found) {
        //         $data[] = [
        //             'id' => $category,
        //             'text' => $category,
        //             'children' => [
        //                 [
        //                     'id' => $event->id,
        //                     'text' => $date
        //                 ]
        //             ]
        //         ];
        //     }
        // }
        // return response()->json($data);


        $search = $request->search;
            $grnttypes = DB::table('grantsdrps')
                ->where(
                [['grant_type','like', "%$search%"]])
            ->latest()
            ->get();
        return response()->json(['items'=>$grnttypes]);
    }

    public function granttypeadd(Request $request){

        $request->validate([
            'granttype' => 'required',
        ]);

        $ifExsist = DB::table('grantsdrps')
            ->where([["grant_type", $request->granttype],
                     ["date_of_grant", $request->ggdate]
            ])->exists();
        abort_if($ifExsist,400, 'Grant already exist');

        $date = new DateTime($request->ggdate);

        grantsdrp::create([
            'grant_type' => strtoupper($request->granttype),
            'date_of_grant' => date_format($date ,"d M, Y" ) ,
            'grant_amount' =>  $request->ggamount,
            'g_remarks' => $request->ggremarks,
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
                     ["date", $request->gdate]
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
            'remarks' => $request->gremarks,
            'vid' => $request->vuid,
            'uid' => $uid

        ]);

        return response()->json([
            'success' => 'Record Added!'
        ], 201);
    }

    public function viewrecords(Request $request){
        $grantlist = grantsdrp::all();

        if($request->ajax()){
            return DataTables::of($grantlist)

            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                class="btn btn-danger btn-rounded waves-effect gntlistdelete" ><i class="mdi mdi-account-remove"></i></a>

                <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                    class="btn btn-primary btn-rounded waves-effect gntlistedit" ><i class="mdi mdi-account-edit"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function viewdelete(Request $request){
        grantsdrp::destroy($request->id);
    }

}
