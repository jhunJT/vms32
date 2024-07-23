<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\bske2023;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class bske2023Controller extends Controller
{
    public function index(Request $request){
        $results = bske2023::select(
            'id',
            'municipality',
            'fullname',
            'barangay',
            'position',
            'party',
            'vobtained',
            DB::raw('
                CASE
                    WHEN position = "PUNONG BARANGAY" THEN 1
                    WHEN position = "MEMBER, SANGGUNIANG BARANGAY" THEN
                        CASE
                            WHEN rank <= 7 THEN 1
                            ELSE rank
                        END
                    ELSE rank
                END AS rank'
            )
        )
        ->whereRaw('
            CASE
                WHEN position = "PUNONG BARANGAY" AND rank = 1 THEN 1
                WHEN position = "MEMBER, SANGGUNIANG BARANGAY" AND rank <= 7 THEN 1
                ELSE 0
            END = 1'
        )
        ->get();


        if($request->ajax()){
            return DataTables::of($results)
                ->addColumn('action', function($row){
                   return
                        // '<a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                        //     class="btn btn-primary btn-rounded waves-effect gview" ><i class="mdi mdi-gift"></i></a>';

                       '<a href="javascript:void(0)" type="button" data-id="'.$row->id.'"
                       class="btn btn-primary btn-rounded waves-effect vedit " ><i class="mdi mdi-account-edit"></i></a>';
                })
               ->rawColumns(['action'])
               ->make(true);
           }
        return view('archives.bske2023');
    }

    public function updtparty(Request $request){
        // dd($request->selectedPartyVal, $request->selectedRowId);

        bske2023::updateOrCreate(
            ['id' => $request->selectedRowId ],
            ['party' => $request->selectedPartyVal,]
        );
        return response()->json(['success' => 'Record Updated!']);
    }

    public function fetchmuncitss(Request $request){
        $search = $request->search;
        $datamuncit = bske2023::select('municipality')
            ->distinct()
            ->where('municipality','like','%'.$search.'%')
            ->orderBy('municipality','asc')
            ->pluck('municipality','municipality');
        return response()->json(['items'=>$datamuncit]);
    }

    public function fetchbrgyss(Request $request){
        $muncitss = $request->muncitss;
        $search = $request->search;
        $databrgy = bske2023::where([
                ['barangay','like','%'.$search.'%'],
                ['municipality','=',$muncitss]
            ])
             ->pluck('barangay','barangay');
        return response()->json(['items'=>$databrgy]);
    }

    public function fetchpositionss(Request $request){
        $muncitss = $request->muncitss;
        $brgyss = $request->brgyss;
        $search = $request->search;
        $dataposition = bske2023::where('position','like','%'.$search.'%')
             ->pluck('position','position');
        return response()->json(['items'=>$dataposition]);
    }
}
