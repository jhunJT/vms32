<?php

namespace App\Http\Controllers\EncoderPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\d1nle2023;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\grantDetails;



// /** @var \App\Models\User $user **/

class encoderController extends Controller
{
    public function index(Request $request){

        $muncit = Auth::user()->muncit;
        $tbname = Auth::user()->tbname;

        $voters = d1nle2023::select('id','Name','District','Municipality','Barangay',
            'Province','man_add','Precinct_no','SIP','purok_rv','survey_stat','grant_rv',
            'HL','PL','remarks','vstatus','sqn','contact_no','dob','user','userlogs','qrcode_id')
        ->where('Municipality','=',$muncit);

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

        return view('dashboard.encoder',compact('muncit'));
    }

    public function profileedit($id){

        $users = User::find($id);
        if(! $users){
            abort(404);
        }
        return view('profile.pedit', compact('users'));
    }

    public function profileupdate(Request $request){
        $id = Auth::user()->id;
        $data = ([
            'name' => $request->fname, //
            'contno' => $request->contno, //
        ]);

       User::where('id',$id)->update( $data);
       $updatedUser = User::find($id);

        return response()->json([
            'success' => 'Profile Updated',
            'name' =>  $updatedUser->name
        ], 201);
    }

    public function fetchBrgy(Request $request){
        $muncit = Auth::user()->muncit;
        // $tbname = Auth::user()->tbname;

        $search = $request->search;
        $databrgy = d1nle2023::where([
                ['Barangay','like','%'.$search.'%'],
                ['Municipality','=',$muncit]
            ])
             ->pluck('Barangay','Barangay');
        return response()->json(['items'=>$databrgy]);
    }

    public function fetchpbpc(Request $request){
        $muncit = Auth::user()->muncit;
        $fltbrgy = $request->fltbrgy;
        $search = $request->search;
        $datapl = DB::table('purokleaders')->where([
                            ['muncit','=',$muncit],
                            ['barangay','=', $fltbrgy],
                            ['purok_leader','like','%'.$search.'%']])
                                ->orderBy('id','desc')
                                ->pluck('purok_leader','purok_leader');
        return response()->json(['items'=>$datapl]);
    }

    public function fetchhl(Request $request){
        $muncit = Auth::user()->muncit;
        $hlsbrgy = $request->hlsbrgy;

        $search = $request->search;
        $datahl = DB::table('houseleaders')->where([
                    ['muncit','=', $muncit],
                    ['barangay','=', $hlsbrgy],
                    ['houseleader','like','%'.$search.'%']])
                    ->orderBy('id','desc')
                    ->get();
                    // ->pluck('houseleader','id');
        return response()->json(['items'=>$datahl]);
    }

    public function vnames(Request $request){
        $muncit = Auth::user()->muncit;
        $tbname = Auth::user()->tbname;
        $brgypl = $request->brgypl;

        $search = $request->search;
        $datanames = d1nle2023::where([
                    ['Municipality','=', $muncit],
                    ['Barangay','=', $brgypl],
                    ['Name','like','%'.$search.'%']])
                    ->pluck('Name','Name');
        return response()->json(['items'=>$datanames]);
    }

    public function savePl(Request $request){
        $muncit = Auth::user()->muncit;
        $ifExsist = DB::table('purokleaders')->where([
            ['purok_leader',$request->plName]
            ])->exists();

            abort_if($ifExsist,400, 'Purok Leader already exist');

        $data = ([
            'barangay' => $request->plBrgy2, //
            'purok_leader' => $request->plName, //
            'muncit' => $muncit, //
            'mid' => $request->userid, //
            'remarks' => $request->plRemarks, //
        ]);

        DB::table('purokleaders')->updateOrInsert($data);

        return response()->json([
            'success' => 'Purok Leader Added!'
        ], 201);
    }

    public function vhlnames(Request $request){
        $muncit = Auth::user()->muncit;
        $tbname = Auth::user()->tbname;
        $brgyhl = $request->brgyhl;
        $search = $request->search;
        $datanames = d1nle2023::where([
            ['Municipality','=', $muncit],
            ['Name','like','%'.$search.'%'],
            ['survey_stat','=', 0],
            ['Barangay','=', $brgyhl]])
            // ->pluck('Name','id',);
            ->get();
        return response()->json(['items'=>$datanames]);
    }

    public function getHLid(Request $request){
        $hlbrgyssss = $request->hlbrgyssss;
        $sqnHL = DB::table('houseleaders')->select('barangay')->where('Barangay','=',$hlbrgyssss)->count();
        return $sqnHL;
    }

    public function saveHl(Request $request){
        $muncit = Auth::user()->muncit;
        $tbname = Auth::user()->tbname;

        $request->validate([
            'hl_brgy' => 'required',
        ]);

        $ifExsist = DB::table('houseleaders')->where("houseleader", $request->hlnamemodal)->exists();
        abort_if($ifExsist,400, 'Houseleader already exist');

        $data = ([
            'barangay' => $request->hl_brgy, //
            'houseleader' => $request->hlnamemodal, //
            'muncit' =>  $muncit, //
            'purok' => $request->hlPurok, //
            'sqn' => $request-> seqNum, //
            'remarks' => $request->hlRemarks, //
        ]);

        DB::table('houseleaders')->Insert($data);

        $vupdate =([
            'HL' => $request->hlnamemodal, //
            'hlids' => $request->hlId, //
            'survey_stat' => '1',
            'purok_rv' => $request->hlPurok,
            'sqn' => $request->seqNum,
            'sethl' => '1',
        ]);

        $vupdate = d1nle2023::find($request->hlId);

        $vupdate->update([
            'HL' => $request->hlnamemodal,
            'hlids' => $request->hlId,
            'survey_stat' => '1',
            'purok_rv' => $request->hlPurok,
            'sqn' => $request->seqNum,
            'sethl' => '1',
        ]);

        if(! $vupdate){
            abort(404);
        }

        return response()->json([
            'success' => 'House Leader Added!'
        ], 201);
    }

    public function vedit($id){

        $voters = d1nle2023::find($id);
        if(! $voters){
            abort(404);
        }
        return $voters;
    }

    public function storeorupdate(Request $request){

        $vcheck = $request->survey_stat;
        $gcheck = $request->grant_check;
        $empid = $request->vid;

        $muncit = Auth::user()->muncit;
        $tbname = Auth::user()->tbname;

        // dd($empid);
        $newVoter = ([
            'Name' => $request->name, //
            'District' => $request->district, //
            'Municipality' => $request->muncit, //
            'Barangay' => $request->barangay, //
            'Province' =>'SAMAR (WESTERN SAMAR)',
            'man_add' =>'1',
            'Precinct_no' => $request->precno, //
            'SIP' => $request->sip, //
            'purok_rv' => $request->purok, //
            'survey_stat' => '1', //
            'grant_rv' => $request->grant, //
            'HL' => $request->hlnameeditmodal, // separate table
            'PL' => $request->pl, // separate table
            'remarks' => $request->remarks, //text area
            'vstatus' => $request->vstat2,  //OB, IB,
            'occupation' => $request->occup,
            'contact_no' => $request->contno,
            'dob' => $request->dob,
            'sqn' => $request->sqn,
            'user' => $request->user,
            'userlogs' => now(),
        ]);

        $updateVoterDetailsOnly = ([
            'Name' => $request->name, //
            'District' => $request->district, //
            'Municipality' => $request->muncit, //
            'Barangay' => $request->barangay, //
            'Province' =>'SAMAR (WESTERN SAMAR)',
            'Precinct_no' => $request->precno, //
            'SIP' => $request->sip ? $request->sip : 'None', //
            'purok_rv' => $request->purok, //
            'remarks' => $request->remarks, //text area
            'vstatus' => $request->vstat ? $request->vstat : 'None',
            'occupation' => $request->occup,
            'contact_no' => $request->contno,
            'sqn' => '',
            'user' => $request->user,
            'userlogs' => now(),
        ]);

        $updateVoterDetailsAlso = ([
            'Name' => $request->name, //
            'District' => $request->district, //
            'Municipality' => $request->muncit, //
            'Barangay' => $request->barangay, //
            'Province' =>'SAMAR (WESTERN SAMAR)',
            'Precinct_no' => $request->precno, //
            'SIP' => $request->sip ? $request->sip : 'None', //
            'purok_rv' => $request->purok, //
            'survey_stat' => '1', //
            'grant_rv' => $request->grant, //
            'HL' => $request->hlnameeditmodal, // separate table
            'PL' => $request->pl, // separate table
            'remarks' => $request->remarks, //text area
            'vstatus' => $request->vstat ? $request->vstat : 'None',
            'occupation' => $request->occup,
            'contact_no' => $request->contno,
            'sqn' => $request->sqn,
            'user' => $request->user,
            'userlogs' => now(),
        ]);

        // 00
        $updateVoterDetailsAlways = ([
            'Province' =>'SAMAR (WESTERN SAMAR)',
            'SIP' => $request->sip ? $request->sip : 'None', //
            'purok_rv' => $request->purok, //
            'survey_stat' => '0', //
            'HL' => '', // separate table
            'PL' => '', // separate table
            'remarks' => $request->remarks, //text area
            'vstatus' => $request->vstat ? $request->vstat : 'None',
            'occupation' => $request->occup,
            'contact_no' => $request->contno,
            'sqn' => '',
            'user' => $request->user,
            'userlogs' => now(),
        ]);

        $grantCreate = ([
            'name' => $request->name, //
            'district' => $request->district, //
            'muncit' => $request->muncit, //
            'barangay' => $request->barangay, //
            'grant' => $request->grant, //
            'date' => $request->gdate, // separate table
            'amount' => $request->amount, // separate table
            'remarks' => $request->gremarks, // separate table
            'vid' =>$request->vid,
            'uid' =>$request->userid,
        ]);

        // 1 1 AB
        // 1 0 A
        // 0 1 B

        if($request->vid != null){
            if($vcheck == true && $gcheck == true){

                $request->validate([
                    'hl' => 'required',
                    'grant' => 'required',
                    'gdate' => 'required',
                    'amount' => 'required'
                ]);
                d1nle2023::where('id',$empid)->update($updateVoterDetailsAlso);
                grantDetails::create($grantCreate);
                return response()->json(['success' => 'Record/Grant Updated!'], 201);
            }else if($vcheck == true && $gcheck == false){
                $request->validate([
                    'hl' => 'required'
                ]);
                d1nle2023::where('id',$empid)->update($updateVoterDetailsAlso);
                return response()->json(['success' => 'Record Updated!'], 201);
            }else if($vcheck == false && $gcheck == true){
                grantDetails::create($grantCreate);
                return response()->json(['success' => 'Grant Updated!'], 201);
            }else if($vcheck == false && $gcheck == false){
                d1nle2023::where('id',$empid)->update($updateVoterDetailsAlways);
                return response()->json(['success' => 'Record/Grant Recorded!'], 201);
            }
            // else{
            //     d1nle2023::where('id',$empid)->update($updateVoterDetailsOnly);
            //     return response()->json(['success' => 'Record Updated!'], 201);
            // }
       }else{

        // dd($newVoter,$grantCreate );

            $request->validate([
                'name' => 'required',
                'barangay' => 'required',
                'muncit' => 'required',
                'hl' => 'required',
                // 'pl' => 'required',
            ]);

            if($gcheck == true){
                $request->validate([
                    'name' => 'required',
                ]);
                grantDetails::create($grantCreate);
            }

            $request->validate([
                'name' => 'required',
                'barangay' => 'required',
                'muncit' => 'required',
                'hl' => 'required',
                // 'pl' => 'required',
            ]);

            d1nle2023::create($newVoter);

            return response()->json([
                'success' => 'Record Added!'
            ], 201);
        }


    }

    public function vmdelete(Request $request){
        // dd($request->id);
        d1nle2023::destroy($request->id);
        return response()->json([
            'success'=>'Record deleted successfully.'
        ],200);

    }

    public function saveSilda(Request $request){

        $silda_id = $request->input('id');
        $silda_details = [];

        $checkPL = $request->pl;

        if( $checkPL == null ||  $checkPL === "" ){
            $checkPL = $checkPL;
        }
        $silda_details[] = d1nle2023::whereIn('id', $silda_id)->update([
            'PL' => $checkPL,
            'HL' => $request->hl, // separate table
            'purok_rv' => $request->purok, //
            'survey_stat' => '1', // separate table
            'sqn' => $request->seqno , //
            'userid' => $request->userid , //
        ]);

        return response()->json(['success' => 'New Silda Saved!']);
    }

    public function changePassword(Request $request){

        // dd($request->current_password, $request->password);

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'The current password is incorrect.');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('dashboard.encoder')->with('success', 'Password changed successfully.');
    }

    public function viewInfo(Request $request ){
        $voters = d1nle2023::find($request->id);
        if(! $voters){
            abort(404);
        }
        return $voters;
    }

    public function viewDetails(Request $request ){
        $vtrID = $request->id;
        $vtrsDetails = DB::table('grant_details')
            ->select('grant_details.id','grant_details.grant','grant_details.date','grant_details.amount','grant_details.remarks')
            ->join('d1nle2023s','grant_details.vid','=','d1nle2023s.id')
            ->where('d1nle2023s.id','=',$vtrID)
            ->get();

        if($request->ajax()){
            return DataTables::of($vtrsDetails)->make(true);
        }
    }

    public function generateQRCode($id)
    {
        // dd($id);
        // Fetch QR code data based on ID
        $item = d1nle2023::find($id);

        if (!$item) {
            abort(404);
        }

        // Generate QR code for the item
        $qrCode = QrCode::size(300)->generate($item->qrcode_id);

        // Return QR code image as response
        return response($qrCode)->header('Content-Type', 'image/png');
    }

}
