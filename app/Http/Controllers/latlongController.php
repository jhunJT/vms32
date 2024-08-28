<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\d1nle2023;
use App\Models\latlong;


class latlongController extends Controller
{
    public function index(Request $request){
        $municipality = Auth::user()->muncit;
        $district = Auth::user()->district;

            $latlong = latlong::select('id','barangay','longitude','latitude','remarks','district','muncit')
                ->where([
                    ['district','=', $district],
                    ['muncit','=', $municipality]])
                ->get();

            if($request->ajax()){
                return DataTables::of($latlong)
                ->addColumn('action', function($row){
                   return '
                       <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                       class="btn btn-danger btn-rounded waves-effect cdelete"><i class="mdi mdi-account-remove"></i></a>

                       <a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                       class="btn btn-primary btn-rounded waves-effect cedit " ><i class="mdi mdi-account-edit"></i></a>';
                })
                ->addColumn('id','')
                ->rawColumns(['action'])
                ->make(true);
            }
        return view('forms.latlong');
    }

    public function getbrgy(Request $request){
        $municipality = Auth::user()->muncit;
        $district = Auth::user()->district;
        $search = $request->search;

        $brgyList = d1nle2023::select('Barangay')->where([
            ['District','=',$district],
            ['Municipality','=',$municipality],
            ['Barangay','like','%'.$search.'%']])
            ->pluck('Barangay','Barangay');
        return response()->json(['items'=>$brgyList]);
    }

    public function coordsave(Request $request){
        $municipality = Auth::user()->muncit;
        $district = Auth::user()->district;

        $request->validate([
            'Barangay' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $ifExsist = latlong::where([
                ["muncit",  $municipality],
                ["barangay", $request->Barangay]])->exists();

        abort_if($ifExsist,400, 'Coordinate already exist');

        latlong::create([
            'district' => $district,
            'muncit' => $municipality,
            'barangay' => $request->Barangay,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'remarks' => $request->coord_remarks
        ]);
        return response()->json(['success' => 'Coordinate Added!'], 201);
    }

    public function coorddelete(Request $request){
        latlong::destroy($request->id);
    }

    public function coordedit($id){
        $gdetails = latlong::find($id);
        if(! $gdetails){
            abort(404);
        }
        return $gdetails;
    }

    public function coordupdate(Request $request){
        $request->validate([
            'ulatitude' => 'required',
            'ulongitude' => 'required',
        ]);

        $grntUpdate = ([
            'longitude' => $request->ulongitude, //
            'latitude' => $request->ulatitude, //
            'remarks' =>$request->ucoord_remarks
        ]);
        latlong::where('id',$request->cid)->update($grntUpdate);
        return response()->json(['success' => 'Record Updated!'], 201);
    }


}
