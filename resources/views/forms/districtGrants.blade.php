@extends('layouts.auth')
@section('content')
<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0"> Grant Records</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Data</a></li>
                            <li class="breadcrumb-item active">{{ Auth::User()->muncit }}</li>
                        </ol>
                    </div>
                </div>
                <div class="card" id="refreshTB">
                    <div class="card-body">
                        <div class="px-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 d-flex justify-content-start">
                                            <div class="d-flex align-items-start ">
                                                <button type="button" class="btn btn-success waves-effect waves-light btnAddgrnt">Add Record</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light mx-2" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">Grant Summary</button>
                                            </div>
                                        </div>

                                        <!-- Buttons aligned to the right side -->
                                        <div class="col-md-6 d-flex justify-content-end">
                                            <div class="d-flex align-items-end">
                                                <button type="button" class="btn btn-info waves-effect waves-light btngrnttype">Add Grant Type</button>
                                                <button type="button" class="btn btn-warning waves-effect waves-light mx-2 btngrntview">View Grant Type</button>
                                                <a href="" ><i class="mdi mdi-refresh-circle "  style="font-size: 1.8em;"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row">
                                        <div class="col-2">
                                            @if (Auth::user()->role == 'encoder' || Auth::user()->role == 'supervisor' )
                                                <select id="dist" class="form-control" name="dist">
                                                    <option value="{{ Auth::user()->district}}" selected>{{ Auth::user()->district}}</option>
                                                </select>
                                            @elseif (Auth::user()->role == 'admin' || Auth::user()->role == 'superuser')
                                                <select id="gdist" class="form-control" name="gdist">
                                                    <option selected disabled>Select District</option>
                                                    <option value="District I">District I</option>
                                                    <option value="District II">District II</option>
                                                </select>
                                            @endif
                                        </div>
                                        <div class="col-2">
                                            @if (Auth::user()->role == 'encoder' || Auth::user()->role == 'supervisor')
                                                    <select id="grantMuncit_1" class="form-control" name="grantMuncit_1" >
                                                        <option value="{{ Auth::user()->muncit}}" selected>{{ Auth::user()->muncit}}</option>
                                                    </select>
                                            @elseif (Auth::user()->role == 'admin' || Auth::user()->role == 'superuser')
                                                    <select id="grantMuncit" class="form-control" name="grantMuncit"></select>
                                            @endif
                                        </div>
                                        <div class="col-2">
                                            @if (Auth::user()->role == 'encoder' || Auth::user()->role == 'supervisor')
                                                <select id="hlbrgy" class="form-control" name="hlbrgy"></select>
                                            @elseif (Auth::user()->role == 'admin' || Auth::user()->role == 'superuser')
                                                <select id="hlbrgy2" class="form-control" name="hlbrgy2"></select>
                                            @endif
                                        </div>
                                        <div class="col-2">
                                            <select id="typegrant" class="form-control" name="typegrant"></select>
                                        </div>
                                        <div class="col-4">
                                            <select id="fltrDate" class="form-control fltrDate" name="fltrDate[]" multiple="multiple"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <table id="grantTbl" class="table table-striped table-bordered dt-responsive nowrap grantTbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width:2rem;">Id</th>
                                    <th style="width:15rem;">Name</th>
                                    <th>Municipality</th>
                                    <th>Barangay</th>
                                    <th>Grant</th>
                                    <th>Date Granted</th>
                                    <th>Amount</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
</div>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Grant Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="grntsumm" class="table table-striped table-bordered dt-responsive nowrap grantTbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th style="width:15rem;">Barangay</th>
                        <th>AICS</th>
                        <th>AKAP</th>
                        <th>Scholarship</th>
                        <th>TUPAD</th>
                        <th>MSME</th>
                    </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Total:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade grntViewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Grant Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="grntviewtbl" class="table table-striped table-bordered dt-responsive nowrap grantTbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th style="width:15rem;">Grant</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

{{-- gadd record --}}
<div class="modal fade bs-example-modal-sm addgrnt" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-m modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Add Grant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmGrantAdd">
                    <input class="form-control" type="hidden" name="vuid" id="vuid">
                    <input class="form-control" type="hidden" name="vuname" id="vuname">
                    <input class="form-control" type="hidden" name="grnt_type" id="grnt_type">
                    <input class="form-control" type="hidden" name="grttype" id="grttype">
                    <input class="form-control" type="hidden" name="grtagency" id="grtagency">
                    <input class="form-control" type="hidden" name="grnt_dist" id="grnt_dist" value="{{ Auth::user()->district}}">
                    <input class="form-control" type="hidden" name="grnt_muncit" id="grnt_muncit" value="{{ Auth::user()->muncit}}">
                    <input class="form-control" type="hidden" name="grnt_brgy" id="grnt_brgy">
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="agname">Name</label>
                        </div>
                        <div class="col-sm-10">
                            <select name="agname" class="form-control" id="agname"></select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gname">Grant</label>
                        </div>
                        <div class="col-sm-10">
                            {{-- <input type="text" name="ggrant" id="ggrant" class="form-control"> --}}
                            <select class="form-select" name="gggrant" id="gggrant"></select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gdate">Date</label>
                        </div>
                        <div class="col-sm-10">
                            {{-- <input type="text" name="gdate" id="gdate" class="form-control"> --}}
                            <div class="input-group" id="datepicker2">
                                <input type="text" class="form-control" placeholder="dd M, yyyy" name="gdate" id="gdate"
                                    data-date-format="dd M, yyyy" data-date-container='#datepicker2' data-provide="datepicker"
                                    data-date-autoclose="true">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gamount">Amount</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="number" name="gamount" id="gamount" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gremarks">Remarks</label>
                        </div>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="gremarks" id="gremarks" cols="10" rows="2"></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class=" d-grid mb-3">
                        <button type="button" class="btn btn-primary" id="grntbtnAdd">Save</button>
                    </div>

                </form>


            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

{{-- gntedit --}}
<div class="modal fade bs-example-modal-sm grntedit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-m modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Grant Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="frmGrantEdit">
                    <input class="form-control" type="hidden" name="gid" id="gid">
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gname">Name</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" name="gname" id="gname" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gname">Grant</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="hidden" name="gntHolder" id="gntHolder" class="form-control">
                            <select class="form-select" name="ggrant" id="ggrant">
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gname">Date</label>
                        </div>
                        <div class="col-sm-10">
                            {{-- <input type="text" name="gdate" id="gdate" class="form-control"> --}}
                            <div class="input-group" id="datepicker2">
                                <input type="text" class="form-control" placeholder="dd M, yyyy" data-provide="datepicker"
                                    name="gdates" id="gdates"  data-date-format="dd M, yyyy" data-date-autoclose="true">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gamounts">Amount</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" name="gamounts" id="gamounts" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gname">Remarks</label>
                        </div>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="gremarkss" id="gremarkss" cols="10" rows="2"></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class=" d-grid mb-3">
                        <button type="button" class="btn btn-primary" id="grntedit">Update</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

{{-- grnttype --}}
<div class="modal fade bs-example-modal-sm addgrnttype" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-m modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Add Grant Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmGrantType">
                    <div class="row mb-3">
                        <div class="col-sm-3 col-form-label">
                            <label for="granttype">Grant Type</label>
                        </div>
                        <div class="col-sm-9">
                            <select name="granttype" id="granttype" class="form-control" style="text-transform:uppercase">
                                <option value="AICS">AICS</option>
                                <option value="AKAP">AKAP</option>
                                <option value="CARD">CARD</option>
                                <option value="HEA">HEA</option>
                                <option value="SKOLAR">SKOLAR</option>
                                <option value="TUPAD">TUPAD</option>
                            </select>
                            {{-- <input type="text" name="granttype" id="granttype" class="form-control" style="text-transform:uppercase"> --}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-form-label">
                            <label for="granttype">Agency</label>
                        </div>
                        <div class="col-sm-9">
                            <select name="grant_agency" id="grant_agency" class="form-control" style="text-transform:uppercase">
                                <option value="PROVINCE">PROVINCE</option>
                                <option value="LGU">LGU</option>
                                <option value="DOLE">DOLE</option>
                            </select>

                            {{-- <input type="text" name="granttype" id="granttype" class="form-control" style="text-transform:uppercase"> --}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-form-label">
                            <label for="granttype">Batch No</label>
                        </div>
                        <div class="col-sm-9">
                            <select name="g_batch" id="g_batch" class="form-control" style="text-transform:uppercase">
                                <option disabled selected>Select Batch</option>
                                <option value="Batch 1">Batch 1</option>
                                <option value="Batch 2">Batch 2</option>
                                <option value="Batch 3">Batch 3</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-form-label">
                            <label for="ggdate">Date</label>
                        </div>
                        <div class="col-sm-9">
                            {{-- <input type="text" name="gdate" id="gdate" class="form-control"> --}}
                            <div class="input-group" id="datepicker2">
                                <input type="text" class="form-control" placeholder="dd M, yyyy" data-provide="datepicker"
                                    name="ggdate" id="ggdate" data-date-format="dd M, yyyy" data-date-autoclose="true">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-form-label">
                            <label for="gname">Amount</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="number" name="ggamount" id="ggamount" class="form-control" min="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 col-form-label">
                            <label for="gname">Remarks</label>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="ggremarks" id="ggremarks" cols="10" rows="2"></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class=" d-grid mb-3">
                        <button type="button" class="btn btn-primary" id="ggtypebtnSave">Save</button>
                    </div>

                </form>


            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": 300,
                "hideDuration": 1000,
                "timeOut": 5000,
                "extendedTimeOut": 1000,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
        }

    var grantTbl = $('#grantTbl').DataTable({
            "order": [[ 2, 'asc' ], [ 3, 'asc' ],[ 4, 'asc' ],[ 5, 'asc' ]],
            "ordering": true ,
            "pageLength": 15,
            "dom": 'Bfrtip',
            "ajax": "{{ route('district.grants') }}",
            "columns": [
                {"data": "id"}, //0
                {"data": "name"}, //1
                {"data": "muncit"},//2
                {"data": "barangay"},//3
                {"data": "grant"},//4
                {"data": "date"},//5
                {"data": "amount" },//6
                {"data": "remarks"},//7
                { "data": "action", orderable:false, searchable: false, "className": "text-center"} //8
            ],
            "columnDefs": [
                {"className": "align-middle", "targets": "_all"},
                {"className": "text-center", "targets": "_all"},
            ],
            "buttons": [
                {
                    text: 'excel',
                    extend: 'excelHtml5',
                    title: 'GRANT SUMMARY',
                    className: 'btn btn-success waves-effect waves-light',
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    }
                },
                {
                    text: 'print',
                    extend: 'print',
                    title: '',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0,1,3,4,5,6,7]
                    },
                    className: 'btn btn-success waves-effect waves-light',
                        messageTop: function () {
                            muncit = $('#grantMuncit').val();
                            hlbrgys = $('#hlbrgy').val();

                        if(muncit && hlbrgys) {
                            return '<h1 style="text-align:center;">GRANT SUMMARY</h1>' +
                            '<h2 style="text-align:center;">' + muncit + ' - ' + hlbrgys + '</h2>';
                        } else if (muncit && !hlbrgys){
                            return '<h1 style="text-align:center;">GRANT SUMMARY</h1>' +
                                '<h2 style="text-align:center;">' + muncit + '</h2>';
                        }else{
                            return '<h1 style="text-align:center;">GRANT SUMMARY - ALL</h1>';
                        }
                    }
                },
            ]
    });

    var grntsummary = $('#grntsumm').dataTable({
        "dom": "rtip",
        "dom": 'Bfrtip',
        "ajax": "{{ route('grant.grantsummary') }}",
        "columns":[
                    { "data": "barangay"}, //0
                    { "data": "AICS"}, //1
                    { "data": "AKAP"}, //2
                    { "data": "Scolarship"}, //3
                    { "data": "TUPAD"}, //4
                    { "data": "MSME"} //5
                ],
        "buttons":[
            {
                text: 'copy',
                    extend: 'copyHtml5',
                    title: 'GRANT SUMMARY',
                    orientation:'portrait',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0,1,2,3,4]
                    },
                    className: 'btn btn-success waves-effect waves-light'
            },
            {
                text: 'pdf',
                    extend: 'pdfHtml5',
                    title: 'GRANT SUMMARY REPORT',
                    orientation:'portrait',
                    pageSize: 'LEGAL',
                    customize: function (doc) {
                        doc.styles.tableHeader.alignment = 'center';
                        doc.defaultStyle.alignment = 'center';
                        doc.content[1].table.widths =
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4]
                    },
                    className: 'btn btn-success waves-effect waves-light',
            },
            {
                text: 'print',
                    extend: 'print',
                    title: '',
                    orientation:'portrait',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0,1,2,3,4]
                    },
                    className: 'btn btn-success waves-effect waves-light',
                        messageTop: function () {
                            muncit = $('#hlmun').val();
                        return '<h1 style="text-align:center;">GRANTS SUMMARY</h1><h2 style="text-align:center;">'+muncit+'</h2>';
                    }
            }
        ],
        "columnDefs": [
                {"className": "text-center", "targets": [1,2,3,4]},
                {"className": "dt-center", "targets": "_all"},
            ],
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Total the AICS column
            var totalAICS = api.column(1, {page: 'current'}).data()
                .reduce(function(a, b) {
                    return parseInt(a) + parseInt(b);
                }, 0);

            // Total the AKAP column
            var totalAKAP = api.column(2, {page: 'current'}).data()
                .reduce(function(a, b) {
                    return parseInt(a) + parseInt(b);
                }, 0);

            // Total the Scholarship column
            var totalScholarship = api.column(3, {page: 'current'}).data()
                .reduce(function(a, b) {
                    return parseInt(a) + parseInt(b);
                }, 0);

            // Total the TUPAD column
            var totalTUPAD = api.column(4, {page: 'current'}).data()
                .reduce(function(a, b) {
                    return parseInt(a) + parseInt(b);
                }, 0);

            // Total the MSME column
            var totalMSME = api.column(5, {page: 'current'}).data()
                .reduce(function(a, b) {
                    return parseInt(a) + parseInt(b);
                }, 0);

            // Update footer
            $(api.column(1).footer()).html(totalAICS);
            $(api.column(2).footer()).html(totalAKAP);
            $(api.column(3).footer()).html(totalScholarship);
            $(api.column(4).footer()).html(totalTUPAD);
            $(api.column(5).footer()).html(totalMSME);
        }

    });

    grantTbl.on('order.dt search.dt', function () {
        let i = 1;
        grantTbl.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();

    $('#hlmun').on('change', function(e){
        var selectMuncit = []
        $.each($('#hlmun'), function(i,elem){
            selectMuncit.push($(this).val())
        })
        grantTbl.column(2).search(selectMuncit).draw();

    });

    $('#hlbrgy').on('change', function(e){
        $('#typegrant, #fltrDate').val('').trigger('change');
        var selectBrgy = []
        $.each($('#hlbrgy'), function(i,elem){
            selectBrgy.push($(this).val())
        })
        grantTbl.column(3).sort().search('^' + selectBrgy + '$', true, false).order([5,'asc']).draw();
    });

    $('.refreshdata').on('click', function(){
        grantTbl.ajax.reload(null, false);
    });

    $('#gggrant').select2({
        placeholder: "Choose Grant",
        dropdownParent: $(".addgrnt"),
        allowClear: true,
        ajax:{
            url:"{{ route('grants.fetch') }}",
            type:"post",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                return{
                    search: params.term,
                };
            },
            processResults: function(data){
                var granttypes = data.items.map(function(item) {
                    return {
                        id: item.id,
                        text: item.grant_title,
                        date: item.date_of_grant,
                        gtype: item.grant_amount,
                        gremarks: item.g_remarks,
                        grttype: item.grant_type,
                        grtagency: item.grant_agency,
                    }
                });
                return {
                    results: granttypes
                };
            },
            cache: true
        }
    });

    $('#ggrant').select2({
        placeholder: "Choose Grants",
        dropdownParent: $(".grntedit"),
        allowClear: true,
        ajax:{
            url:"{{ route('grants.fetch') }}",
            type:"post",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                return{
                    search: params.term,
                };
            },
            processResults: function(data){
                var granttypes = data.items.map(function(item) {
                    return {
                        id: item.id,
                        text: item.grant_type,
                        date: item.date_of_grant,
                        gtype: item.grant_amount,
                        gremarks: item.g_remarks
                    }
                });
                return {
                    results: granttypes
                };
            },
            cache: true
        }
    });

    $('#agname').select2({
        placeholder: "Select Grantee",
        dropdownParent: $(".addgrnt"),
        tags: true,
        exact: true,
        allowClear: true,
        ajax:{
            url:"{{ route('grants.names') }}",
            type:"post",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                dist = $('#dist').val();
                muncit = $('#grantMuncit').val();
                muncit1 = $('#grantMuncit_1').val();
                brgy = $('#hlbrgy').val();
                return{
                    search: params.term,
                    dist: dist,
                    muncit: muncit,
                    muncit1:muncit1,
                    brgy: brgy
                };
            },
            processResults: function(data){
                console.log(data);
                var houseleaders = data.items.map(function(item) {
                    return {
                        id: item.id,
                        text: item.Name,
                        survey_stat: item.survey_stat
                    }
                });
                return {
                    results: houseleaders,
                };
            },
            cache: true
        },
        templateResult: formatState
    });

   $('#granttype').select2({
        placeholder: "Select Grant Type",
        dropdownParent: $(".addgrnttype"),
        tags:true,
        allowClear: true
   });

   $('#grant_agency').select2({
        placeholder: "Select Agency",
        dropdownParent: $(".addgrnttype"),
        tags:true,
        allowClear: true,
   });

    function formatState (emp_n) {
    if (!emp_n.id) {
            return emp_n.text;
        }
        if (emp_n.survey_stat === 1) {
            var $emp_n = $(
                '<span><i class="fa fa-user-check" style="color: green;"></i>&nbsp;&nbsp;'+ emp_n.text + '</span>');
        }else{
            var $emp_n = $(
                '<span>'+ emp_n.text +'</span>');
        };


        return $emp_n;
    };

    $('#agname').on('change', function(){
        var selectedData = $(this).select2('data')[0];
        if (selectedData) {
            $('#vuid').val(selectedData.id);
            $('#vuname').val(selectedData.text);
        } else {
            $('#vuid').val('');
            $('#vuname').val('');
        }
    });

    $('#gggrant').on('change', function(){
        var selectedData = $(this).select2('data')[0];
        if (selectedData) {
            $('#grnt_type').val(selectedData.text);
            $('#gdate').val(selectedData.date);
            $('#gamount').val(selectedData.gtype);
            $('#gremarks').val(selectedData.gremarks);
            $('#grttype').val(selectedData.grttype);
            $('#grtagency').val(selectedData.grtagency);
        } else {
            $('#grnt_type').val('');
            $('#gdate').val('');
            $('#gamount').val('');
            $('#gremarks').val('');
            $('#grttype').val('');
            $('#grtagency').val('');
        }
    });

    $('#typegrant').on('change', function(e){
        var selectGrant = []
        $.each($('#typegrant'), function(i,elem){
            selectGrant.push($(this).val())
        })
        grantTbl.column(4).sort().search(selectGrant).order([1,'asc']).draw();
    });

    $('#fltrDate').on('change', function () {
      var data = $.map( $(this).select2('data'), function( value, key ) {
            return value.text ? '^' + $.fn.dataTable.util.escapeRegex(value.text) + '$' : null;
        });

        if (data.length === 0) {
            data = [""];
        }

        //join array into string with regex or (|)
        var val = data.join('|');

        //search for the option(s) selected
        grantTbl.column(5).search( val ? val : '', true, false ).order([5,'asc']).draw();
    } );

    $(document).on('click','.gntdelete', function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
            Swal.fire({
                title:"Are you sure?",
                text:"You won't be able to revert this!",
                icon:"warning",
                showCancelButton:!0,
                confirmButtonColor:"#1cbb8c",
                cancelButtonColor:"#f32f53",
                confirmButtonText:"Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed){
                    $.ajax({
                        url: '{{ route('grant.delete') }}',
                        method: 'post',
                        data:{id:id},
                        success:function(res){
                            Swal.fire(
                                'Deleted!',
                                'Record deleted.',
                                'success'
                            )
                            grantTbl.ajax.reload(null, false);
                        }
                    });
                }
            });
    });

    $('#grantMuncit').select2({
        placeholder: "Select Municipality/City",
        minimumResultsForSearch: -1,
        allowClear: true,
        ajax:{
            url:"{{ route('grants.muncit') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                return{
                    search: params.term
                };
            },
            processResults: function(data){
                return{
                    results: $.map(data.items, function(obj,i) {
                        return {
                        id:i, text:obj
                        };
                    })
                }
            }
        }

    });

    $('#grantMuncit').on('change', function(){
        // alert('hello');
        $('#grantTbl').dataTable().fnDestroy();
        grantTbl.ajax.reload();
    });

    $('#grantMuncit_1').select2({
        placeholder: "Select District",
        minimumResultsForSearch: -1,
    });

    $('#dist').select2({
        placeholder: "Select District",
        minimumResultsForSearch: -1,
    });

    $('#gdist').select2({
        placeholder: "Select District",
        minimumResultsForSearch: -1
    });

    $('#hlbrgy').select2({
        placeholder: "Select Barangay",
        allowClear: true,
        ajax:{
            url:"{{ route('grants.brgy') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                muncit = $('#grantMuncit').val();
                return{
                    search: params.term,
                    muncit: muncit
                };
            },
            processResults: function(data){
                return{
                    results: $.map(data.items, function(obj,i) {
                        return {
                        id:i, text:obj
                        };
                    })
                }
            }
        }
    });

    $('#typegrant').on('change', function(){
        dist = $('#dist').val();
        dist2 = $('#gdist').val();
        muncit1 = $('#grantMuncit').val();
        muncit2 = $('#grantMuncit_1').val();
        barangay = $('#hlbrgy').val();

        // console.log(dist, dist2, muncit1, muncit2, barangay);
    });

    $('#typegrant').select2({
        placeholder: "Select Grant",
        allowClear: true,
        ajax:{
            url:"{{ route('grants.gtype') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                dist = $('#dist').val();
                dist2 = $('#gdist').val();
                muncit1 = $('#grantMuncit').val();
                muncit2 = $('#grantMuncit_1').val();
                barangay = $('#hlbrgy').val();
                return{
                    search: params.term,
                    dist: dist,
                    dist2:dist2,
                    muncit1:muncit1,
                    muncit2: muncit2,
                    barangay:barangay,
                };
            },
            processResults: function(data){
                console.log(data);
                return{
                    results: $.map(data.items, function(obj,i) {
                        return {
                        id:i, text:obj
                        };
                    })
                }
            }
        }
    });

    $('#fltrDate').select2({
        placeholder: "Filter Date",
        ajax:{
            url:"{{ route('grant.fltrdate') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            data: function(params){
                dist = $('#dist').val();
                muncit = $('#grantMuncit').val();
                barangay = $('#hlbrgy').val();
                typegrant = $('#typegrant').val();
                return{
                    search: params.term,
                    // "_token":"{{ csrf_token() }}",
                    dist:dist,
                    muncit:muncit,
                    // barangay:barangay,
                    typegrant:typegrant
                };
            },
            processResults: function(data){
                return{
                    results: $.map(data.item, function(item, i) {
                        return {
                            id:i,
                            text:item
                        };
                    })
                }
            }
        }
    });

    $('#fltrDate').on('select2:select', function (e) {
        var element = e.params.data.element;
        var $element = $(element);
        $element.detach();
        $(this).append($element);
        $(this).trigger('change');
    });

    $(document).on('click','.gntedit', function(){
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('grant.edit', '') }}" +'/'+id,
            method:'GET',
            success: function(response){
                $('.grntedit').modal('show');
                $('#gid').val(response.id);
                $('#gname').val(response.name);
                $('#gdates').val(response.date);
                $('#gamounts').val(response.amount);
                $('#gremarkss').val(response.remarks);
                $('#gntHolder').val(response.grant);
                $('#g_batch').val(response.g_batch);

                var newOptionsGT = new Option(response.grant, response.grant, true, true);
                $('#ggrant').append(newOptionsGT).trigger('change');

                // grantChange();
            },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });
    });

    $('#ggrant').on('select2:select', function(){
        var selectedData = $(this).select2('data')[0];
        if (selectedData) {
            $('#gntHolder').val(selectedData.text);
            $('#gdates').val(selectedData.date);
            $('#gamounts').val(selectedData.gtype);
            $('#gremarkss').val(selectedData.gremarks);
            $('#g_batch').val(selectedData.gbatch);
        }
    });




    $('#grntedit').on('click', function(){
        var formData = new FormData(frmGrantEdit);
        $.ajax({
            url: "{{ route('grant.update') }}" ,
            method: 'POST',
            processData: false,
            contentType: false,
            data: formData,

            success: function(response) {
                grantTbl.ajax.reload();
                if(response) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Grant type added!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                $('.grntedit').modal('toggle');
            },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });

    });

    $('.btnAddgrnt').click(function(){
        var getDistrict = $('#dist').val();
        var getMuncit = $('#grantMuncit').val();
        var checkBrgy = $('#hlbrgy').val();
        $('#grnt_brgy').val(checkBrgy);
        if( checkBrgy === null){
            Swal.fire({
                title: "Message",
                text: "Please Select Barangay First!",
                icon: "warning"
            });
        }else{
            $('#frmGrantAdd')[0].reset();
            $('.addgrnt').modal('show');
        }
    });

    $('.btngrnttype').click(function(){
        $('.addgrnttype').modal('show');
        $('#granttype').val('').trigger('change');
        $('#grant_agency').val('').trigger('change');
    });

    var formGT = $('#frmGrantType')[0];
    $('#ggtypebtnSave').on('click', function(){
        var formGrantType = new FormData(formGT);

        $.ajax({
            url:"{{ route('grants.addtype') }}" ,
            method: 'POST',
            processData: false,
            contentType: false,
            data: formGrantType,
            success: function(response) {
                $('#frmGrantType')[0].reset();
                $('.addgrnttype').modal('toggle');
                if(response.success) {
                        Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Grant type added!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });


    });

    var formGA = $('#frmGrantAdd')[0];
    $('#grntbtnAdd').on('click', function(){
    var grntAddRec = new FormData(formGA);
        $.ajax({
            url:"{{ route('grants.save') }}" ,
            method: 'POST',
            processData: false,
            contentType: false,
            data: grntAddRec,
            success: function(response) {
                $('#frmGrantAdd')[0].reset();
                $('.addgrnt').modal('toggle');
                grantTbl.ajax.reload();
                if(response.success) {
                        Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "New record added!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    // toastr.error(err.message);
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#frmGrantAdd')[0].reset();

                }
            }
        });
    });

    $('.addgrnt').modal({backdrop: 'static', keyboard: false})

    $('.btngrntview').on('click', function(){
        $('.grntViewModal').modal('show');

        var grantList = $('#grntviewtbl').DataTable({
                destroy: true, // Allows reinitializing the DataTable
                processing: true,
                searching: false,
                lengthChange: false,
                serverSide: false, // Assuming client-side processing
                data: [], // Initial empty data
                columns: [
                    { data: 'grant_type' },
                    { data: 'date_of_grant' },
                    { data: 'grant_amount' },
                    { data: 'g_remarks' },
                    { data: 'action', class:"text-center"}
                ]
            });
        $.ajax({
            url: "{{ route('grants.viewrecords') }}",
            method:'GET',
            success: function(response){

                grantList.clear().rows.add(response.data).draw();

                $('.grntViewModal').modal('show');

            },
            error: function(xhr, status, error){
            if(error) {
                var err = eval("(" + xhr.responseText + ")");
                    grantList.error(err.message);
                }
            }
        });
    });

    $(document).on('click','.gntlistdelete', function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
            Swal.fire({
                title:"Are you sure?",
                text:"You won't be able to revert this!",
                icon:"warning",
                showCancelButton:!0,
                confirmButtonColor:"#1cbb8c",
                cancelButtonColor:"#f32f53",
                confirmButtonText:"Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed){
                $.ajax({
                    url: '{{ route('grants.viewdelete') }}',
                    method: 'post',
                    data:{id:id},
                    success:function(res){
                        Swal.fire(
                            'Deleted!',
                            'Record deleted.',
                            'success'
                        )
                        $('.grntViewModal').modal('toggle');
                    }
                });
            }
        });
    });

    $('#g_batch').select2({
        placeholder: "Select Batch",
        dropdownParent: $(".addgrnttype"),
        tags:true,
        allowClear: true,
    });

    // $('.addgrnt').on('hidden.bs.modal', function (e) {
    //     document.getElementById("frmGrantAdd").reset();
    // })



});
</script>

@include('layouts.script')
@include('layouts.footer')
@endsection
