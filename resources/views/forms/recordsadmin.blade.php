@extends('layouts.auth')
@section('content')

<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ Auth::User()->muncit }} Registered Voters as of 2023 Data</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Data</a></li>
                        <li class="breadcrumb-item active">{{ Auth::User()->muncit }}</li>
                    </ol>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="card" style="width: 100%;">
                    {{-- {!! Toastr::message() !!} --}}
                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    {{-- <div class="col-md-12">
                                        <div class="mx-3 pt-3">

                                                <button type="button" class="btn btn-warning w-lg waves-effect waves-light mbPL" name="mbPL" id="mbPL"><i class="mdi mdi-account-multiple-plus"></i> PB/PC </button>
                                                <button type="button" class="btn btn-primary w-lg waves-effect waves-light mbHL" name="mbHL" id="mbHL"><i class="mdi mdi-account-multiple-plus"></i> HL </button>
                                                <button type="button" class="btn btn-info w-lg waves-effect waves-light " name="addMan" id="addMan"><i class="mdi mdi-account-multiple-plus"></i> Voter </button>
                                                <div class="btn-group w-lg pt-2 m-1" role="group" aria-label="Basic checkbox toggle button group">
                                                    <input type="checkbox" class="btn-check  filter-checkbox" id="btncheck1" value="1" autocomplete="off">
                                                    <label class="btn btn-success " for="btncheck1"> S </label>
                                                    <input type="checkbox" class="btn-check filter-checkbox" id="btncheck2" value="0" autocomplete="off">
                                                    <label class="btn btn-danger" for="btncheck2"> U </label>
                                                    <input type="checkbox" class="btn-check filter-checkboxManual" id="btncheck3" value="1" autocomplete="off">
                                                    <label class="btn btn-primary" for="btncheck3"> M </label>
                                                </div>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-md-5">
                                        <div class="mx-3 pt-4 m-1">
                                            <div class="text-end">
                                                <a type="button"  class="btn btn-secondary w-lg"  href="{{ URL('/cvrecord') }}" ><i class="ri-user-heart-fill"></i>CV RECORDS</a>
                                                <button type="button" class="btn btn-primary waves-effect waves-light cvsumm" >CV SUMMARY</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light hlsumm" >HOUSELEADERS SUMMARY</button>
                                                <a type="button"  class="btn btn-secondary w-lg"  href="{{ URL('/district/grants') }}" ><i class="ri-user-heart-fill"></i>GRANTS</a>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                <div><hr style="height:2px;border-width:0;color:gray;background-color:gray"></div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-1 ">
                                            <div class="input-group gap-2">
                                                <select class="form-control " style="width: 15%;"  tabindex="-1" aria-hidden="true" name="selDist" id="selDist">
                                                    <option selected disabled></option>
                                                    <option value="District I">District I</option>
                                                    <option value="District II">District II</option>
                                                </select>
                                                <select class="form-control" style="width: 30%;" id="selMuncit"  tabindex="-1" aria-hidden="true" name="selMuncit"></select>
                                                <select class="form-control " style="width: 30%;" id="selBrgy"   tabindex="-1" aria-hidden="true" name="selBrgy"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div><hr style="height:2px;border-width:0;color:gray;background-color:gray"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table id="calbcity2025" class="table table-hover nowrap " style="width:100%">
                                    <thead >
                                    <tr>
                                        <th><input type="checkbox" ID="checkAll" class="form-check-input input-mini" disabled/></th>
                                        <th >ID</th>
                                        <th >Name</th>
                                        <th >Barangay</th>
                                        <th >Precinct No</th>
                                        <th >PB/PC</th>
                                        <th >House Leader</th>
                                        <th >Purok</th>
                                        {{-- <th >Sequence</th> --}}
                                        <th >SQN</th>
                                        <th class="text-center">Action</th>
                                        <th hidden >survey_stat</th>
                                        <th hidden >man_add</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align:right">User Performance:  </th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="cvsumm-modal-lg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">CV Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="cvsumm" class="table table-striped table-bordered dt-responsive nowrap grantTbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th style="width:15rem;">Barangay</th>
                        <th>Registered Voter</th>
                        <th>CV</th>
                        <th>IB</th>
                        <th>OBWC</th>
                        <th>Transfer</th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="hlsumm-modal-lg" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">CV Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="hlsumm" class="table table-striped table-bordered dt-responsive nowrap grantTbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th style="width:15rem;">Barangay</th>
                        <th>Name</th>
                        <th>Barangay</th>
                        <th>Purok</th>
                        <th>Sequence</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

{{-- modal hl add record --}}
<div id="addHLModal" class="modal fade formModalHL" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">ADD HOUSELEADER</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form  id="hlForm" class="needs-validation" novalidate>
                    <input type="hidden" name="h_id" id="h_id" valu="">
                    <input type="hidden" name="hlId" id="hlId">
                    <input type="hidden" name="hl_brgy" id="hl_brgy">
                    <input type="hidden" name="hlnamemodal" id="hlnamemodal">
                    <input type="hidden" name="hlmuncitmodal" id="hlmuncitmodal">
                    <input type="hidden" value="{{ Auth::user()->id }}" name="userid">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Barangay</label>
                                    <select class="form-control"  id="hlBrgy"  name="hlBrgy"  data-placeholder="Select an Barangay" tabindex="-1" aria-hidden="true" readonly></select>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="contno" class="form-label">Household Leader</label>
                                    <select class="form-control " id="hlName"  tabindex="-1" aria-hidden="true" name="hlName" ></select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="hlPurok" class="form-label">Purok</label>
                                    <input type="text" class="form-control" id="hlPurok"
                                        placeholder="Purok" name="hlPurok">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="seqNum" class="form-label">Sequence Number</label>
                                    <input type="text" class="form-control" id="seqNum"
                                        placeholder="Sequence" name="seqNum" readonly >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label for="hlRemarks" class="form-label">Remarks</label>
                                    <textarea required class="form-control" rows="3" name="hlRemarks" id="hlRemarks"></textarea>
                                </div>
                            </div>
                            <div class="d-grid mb-3 my-4">
                                <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" id="bthHLSave">Add House Leader</button>
                            </div>
                        </div>
                    </div>
                </form>
                {{ csrf_field() }}
            </div><!-- body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{{-- modal pl add record --}}
<div id="addPLModal" class="modal fade formModalPL" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">ADD PB/PC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form  id="plForm" class="needs-validation" novalidate>
                    <input type="hidden" name="plId" id="plId">
                    <input type="hidden" name="plmuncit" id="plmuncit">
                    <div class="card">
                        <div class="card-body">

                            <input type="hidden" value="{{ Auth::user()->id }}" name="userid">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="plBrgy" class="form-label">Barangay</label>
                                    <select class="form-control "  id="plBrgy2"  tabindex="-1" aria-hidden="true" name="plBrgy2" ></select>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="plName" class="form-label">PB/PC</label>
                                    <select class="form-control" id="plName" tabindex="-1" aria-hidden="true" name="plName" ></select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label for="plRemarks" class="form-label">Remarks</label>
                                    <textarea required class="form-control" rows="3" name="plRemarks" id="plRemarks"></textarea>
                                </div>
                            </div>
                            <div class="d-grid mb-3 my-4">
                                <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" id="btnPLSave">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
                {{ csrf_field() }}
            </div><!-- body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{{-- modal update record   --}}
<div class="modal fade bs-example-modal-center ajaxForm" id="formModal"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">

                        @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success')}}
                        </div>
                        @endif

                        <form  id="ajaxForm" name="ajaxForm" class="needs-validation" novalidate>
                            <div id="error"></div>
                            <input type="hidden" value="{{ Auth::user()->username }}" name="user">
                            <input type="hidden" value="{{ Auth::user()->id }}" id="userid" name="userid">
                            <input type="hidden" value="{{ Auth::user()->muncit }}" id="getMuncit">
                            <input type="hidden" value="{{ Auth::user()->district }}" id="getDistrict">
                            <input type="hidden" name="vid" id="vid">
                            {{-- <input type="hidden" name="hlids" id="hlids"> --}}
                            <input type="hidden" name="plids" id="plids">
                            <input type="hidden" name="hlnameeditmodal" id="hlnameeditmodal">

                            <hr>
                            <div class="row">
                                <div class="col-md-1">
                                    <label  class="form-label">Survey</label>
                                    <div  class="mb-2 outer">
                                        {{-- <input  class="form-check-input inner" type="text"  name="survey_stats" id="survey_stats" > --}}
                                        <input switch="bool" class="form-check-input inner" type="checkbox"  name="survey_stat" id="survey_stat" style="text-align: center;" >
                                        <label for="survey_stat" data-on-label="Yes" data-off-label="No"></label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div  class="mb-1">
                                        <label  class="form-label">SQ#</label>
                                        <input type="text" class="form-control" name="sqn" id="sqn">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label for="pl" class="form-label">PB/PC</label>
                                        <select class="form-control plss" id="plss" tabindex="-1" aria-hidden="true" name="pl"></select>

                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">House Leader</label>
                                        <select class="form-control hlss" id="hls" tabindex="-1" aria-hidden="true" name="hl"></select>
                                    </div>
                                </div>

                            </div><hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control text-uppercase" id="name"
                                            placeholder="Dalisay, Cardo Dela Cruz" name="name"  required>
                                        <span id="nameError" class="text-danger error-message"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="occup" class="form-label">Occupation</label>
                                        <select class="form-select" id="occup" name="occup" required>
                                            <option selected value="None">None</option>
                                            <option value="Aquaculture">Aquaculture</option>
                                            <option value="Farming">Farming</option>
                                            <option value="Skilled">Skilled</option>
                                            <option value="Professional">Professional</option>
                                            <option value="MSME">MSME</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="contno" class="form-label">Contact No</label>
                                        <input type="text" class="form-control" id="contno"
                                            placeholder="Contact No" name="contno" required>
                                        <div class="valid-feedback">
                                            <x-input-error :messages="$errors->get('contno')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="sip" class="form-label">SIP</label>
                                        <select class="form-select" id="sip" name="sip" required>
                                            <option selected disabled value="">Choose...</option>
                                            <option value="None">None</option>
                                            <option value="Senior Citizen">Senior Citizen</option>
                                            <option value="Illiterate">Illiterate</option>
                                            <option value="PWD">PWD</option>
                                            <option value="18-30(SK)">18-30(SK)</option>
                                            <option value="SK/Illitirate">SK/Illitirate</option>
                                            <option value="SK/PWD">SK/PWD</option>
                                            <option value="SK/Illitirate/PWD">SK/Illitirate/PWD</option>
                                            <option value="Illitirate/Senior">Illitirate/Senior</option>
                                            <option value="Illiterate/PWD">Illiterate/PWD</option>
                                            <option value="PWD/Senior">PWD/Senior</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" id="vstat2" name="vstat2" required>
                                            <option selected value="None">None</option>
                                            <option value="OBWC">OBWC</option>
                                            <option value="Deceased">Deceased</option>
                                            <option value="Transfer">Transfer</option>
                                            <option value="IB">IB</option>
                                            <option value="Out of Town">Out of Town</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="district" class="form-label" readonly>District</label>
                                        <input type="text" id="district" name="district" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="muncit" class="form-label">Municipality/City</label>
                                        {{-- <select class="form-control" id="muncit" tabindex="-1" aria-hidden="true" name="muncit"></select> --}}
                                        <input type="text" class="form-control" id="muncit" name="muncit">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="barangay" class="form-label">Barangay</label>
                                        <input type="text" class="form-control" id="barangay"
                                            placeholder="Barangay" name="barangay" required>
                                            <div id="brgyList2"></div>
                                        <div class="valid-feedback">
                                            <x-input-error :messages="$errors->get('barangay')" class="mt-2" />
                                        </div>
                                        <span id="brgyError" class="text-danger error-message"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="precno" class="form-label">Precinct No</label>
                                        <input type="text" class="form-control" id="precno"
                                            placeholder="Precinct No" name="precno" required>
                                        <div class="valid-feedback">
                                            <x-input-error :messages="$errors->get('precno')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mb-3">
                                        <label for="purok" class="form-label">Purok</label>
                                        <input type="text" class="form-control" id="purok"
                                            placeholder="Purok" name="purok">
                                        <div class="valid-feedback">
                                            <x-input-error :messages="$errors->get('purok')" class="mt-2" />
                                        </div>
                                    </div>
                                </div><hr>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <input class="form-check-input" type="checkbox" id="grant_check" name="grant_check">
                                        <label for="grant" class="form-label"> Grant</label>
                                        <select class="form-control" id="grant" name="grant">
                                            <option selected disabled value="">Choose...</option>
                                            <option value="AICS">AICS</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Scholarship">Scholarship</option>
                                            <option value="TUPAD">TUPAD</option>
                                            <option value="MSME GRANT">MSME Grant</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Date</label>
                                        <div class="input-group" id="datepicker2">
                                            <input type="text" class="form-control" placeholder="dd M, yyyy" name="gdate" id="gdate"
                                                data-date-format="dd M, yyyy" data-date-container='#datepicker2' data-provide="datepicker"
                                                data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="text" class="form-control" id="amount"
                                            placeholder="Amount" name="amount" required>
                                        <div class="valid-feedback">
                                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="remarks" class="form-label">Grant Remarks</label>
                                        <input type="text" class="form-control" id="gremarks"
                                            placeholder="Remarks" name="gremarks" required>
                                        <div class="valid-feedback">
                                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                            </div><hr>

                            <div class="row">
                                <div class="col-md-12">
                                    <label>Voter Remarks</label>
                                    <div>
                                        <textarea required class="form-control" rows="2" name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </div>

                            </div><!-- end modal body-->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveBtn"></button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

 {{-- modal view record  gview--}}
<div class="modal fade bs-example-modal-center viewModal" id="formViewModal"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">

                        @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success')}}
                        </div>
                        @endif

                        <form  id="ajaxForm" name="ajaxForm" class="needs-validation" novalidate>
                            <div id="error"></div>
                            <input type="hidden" value="{{ Auth::user()->username }}" name="user">
                            <input type="hidden" value="{{ Auth::user()->id }}" id="userid" name="userid">
                            <input type="hidden" name="vid" id="vvid">

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="vname"
                                            name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="sip" class="form-label">Occupation</label>
                                        <input type="text" class="form-control" id="voccup"
                                         name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="vcontno" class="form-label">Contact No</label>
                                        <input type="text" class="form-control" id="vcontno"
                                            name="contno" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="sip" class="form-label">SIP</label>
                                        <input type="text" class="form-control" id="vsip"
                                             name="sip" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">

                                    <table id="vtrs_info" class="table table-hover nowrap" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th >ID</th>
                                            <th >Grant</th>
                                            <th >Date</th>
                                            <th >Amount</th>
                                            <th >Remarks</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            </div><!-- end modal body-->

                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{{-- var encoderDataStore = "{{ route('dashboard.store') }}"; --}}
<script>
    var encoderDataIndex = "{{ route('dashboard.encoder') }}";
    var adminSelMuncit = "{{ route('admin.selectmuncit') }}";
    var adminSelBrgy = "{{ route('admin.selectBrgy') }}";

    var encoderDataPBPC = "{{ route('encoder.fetchpbpc') }}";
    var encoderDataHL = "{{ route('encoder.fetchhl') }}";
    var encoderDataVName = "{{ route('encoder.vnames') }}";
    var encoderDataSavePL = "{{ route('encoder.savepl') }}";
    var encoderDataVlHL = "{{ route('encoder.vhlnames') }}";
    var encoderDataGetHLId = "{{ route('encoder.getHLid')}}";
    var encoderDataDsaveHL = "{{ route('encoder.savehl') }}";
    var encoderDataView = "{{ route("encoder.vedit","") }}";
    var encoderDataViewGrant = "{{ route("encoder.view-info","") }}";
    var encoderDataViewGrantDetails = "{{ route("encoder.view-details") }}";
    var encoderDataUpdate = "{{ route("encoder.storeorupdate") }}";
    var encoderDataVMdelete = "{{ route("encoder.vmdelete","") }}";
    var encoderDataSaveSelda = "{{ route('encoder.save-silda') }}";
</script>

<script src="{{ asset('assets/auth/js/recordsadmin.js') }}"></script>

@include('layouts.footer')
@include('layouts.script')
@endsection



