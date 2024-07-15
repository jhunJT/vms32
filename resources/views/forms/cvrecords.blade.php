@extends('layouts.auth')
@section('content')
<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card" id="refreshTB">
                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2 p-3">
                                        <button type="button" class="btn btn-primary waves-effect waves-light hlsumm">House Leader Summary</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2">
                                        <input class="form-control" type="text" id="dist" placeholder="District I" value="District I" readonly>
                                        {{-- <select id="gdist" class="form-control" name="gdist"></select> --}}
                                    </div>
                                    <div class="col-2">
                                        {{-- <input class="form-control" type="text" id="hlmun"  value="CALBAYOG CITY" readonly> --}}
                                        <select id="grantMuncit" style="width: 100%;" class="form-control" name="grantMuncit" >
                                            <option value="{{ Auth::User()->muncit }}" selected>{{ Auth::User()->muncit }}</option>
                                        </select>

                                    </div>
                                    <div class="col-3">
                                        <select id="selbrgy" style="width: 100%;" class="form-control" name="selbrgy"></select>
                                    </div>
                                    <div class="col-3">
                                        <select id="selHL" style="width: 100%;" class="form-control" name="selHL"></select>
                                    </div>
                                    <div class="col-2">
                                        <select id="sortPurok" style="width: 100%;" class="form-control" name="sortPurok"></select>
                                    </div>
                                    {{-- <div class="col-4">
                                        <select id="fltrDate" class="form-control fltrDate" name="fltrDate[]" multiple="multiple"></select>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        <table id="cvrectbl" class="table table-bordered dt-responsive nowrap cvrectbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead >
                            <tr>
                                <th>#</th>
                                <th style="width:2rem;">Name</th>
                                <th style="width:15rem;">Barangay</th>
                                <th>Houseleader</th>
                                <th>Purok</th>
                                <th>Sequence</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
</div>

<div id="hlsumm-modal-lg" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">CV Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="hlsumm" class="table table-bordered dt-responsive nowrap grantTbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th style="width:15rem;">Barangay</th>
                        <th>HL</th>
                        <th>Members</th>
                        <th>Total CV</th>
                    </tr>
                    </thead>
                </table>

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

    var cvrec = $('#cvrectbl').DataTable({
            // "order": [[ 1, 'asc' ], [ 4, 'asc' ]],
            "ordering": false,
            "autoWidth" : true,
            "pageLength": 15,
            "processing": true,
            "serverSide": true,
            "dom": 'Bfrtip',
            "ajax": "{{ route('cvrecord.index') }}",
            "columns": [
                {"data": "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;}},
                {"data": "Name"}, //1
                {"data": "Barangay"}, //2
                {"data": "HL"},//3
                {"data": "purok_rv"},//4
                {"data": "sqn"},//5
                {"data": "sethl"},//6
            ],
            "columnDefs": [
                {"className": "text-center", "targets": [0,2,3,4,5]},
                {"targets": 6, "visible": false, "searchable": false }
            ],
            "buttons": [
                {
                    text: 'excel',
                    extend: 'excelHtml5',
                    title: 'COMMAND VOTES',
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
                        columns: [0,1,2,3,4]
                        // ':visible:not(.not-export-col)'
                        // columns: ":not(.not-export-column)"
                    },
                    className: 'btn btn-success waves-effect waves-light',
                        messageTop: function () {
                            muncit = $('#grantMuncit').val();
                        return '<h1 style="text-align:center;">COMMAND VOTES</h1><h2 style="text-align:center;">'+muncit+'</h2>';
                    }
                },
            ],
            "fnRowCallback": function( row, data, index ) {
                if ( (data.sethl  === 1) ) {$(row).addClass('green');}
                // else if ( data.survey_stat == 1 ) {$(row).addClass('green');}
            }
        });

    // $(cvrec.table().header()).addClass('highlight');

    $('#grantMuncit').select2({
        minimumResultsForSearch: -1
    });

    $('.hlsumm').on('click',function(){
        var hlsummary = $('#hlsumm').dataTable({
            "ordering":false,
            "dom": "rtip",
            "dom": 'Bfrtip',
            "ajax": "{{ route('cvrecord.cvhlsumm') }}",
            "columns":[
                        { "data": "Barangay"}, //1
                        { "data": "HL"}, //2
                        { "data": "Members"},
                        { "data": "totalCV"}],
            "buttons":[
                {
                    text: 'copy',
                        extend: 'copyHtml5',
                        title: 'HOUSELEADER SUMMARY',
                        orientation:'portrait',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [0,1,2,3]
                        },
                        className: 'btn btn-success waves-effect waves-light'
                },
                // {
                //     text: 'pdf',
                //         extend: 'pdfHtml5',
                //         title: 'HOUSELEADER SUMMARY',
                //         orientation:'portrait',
                //         pageSize: 'LEGAL',
                //         customize: function (doc) {
                //             doc.styles.tableHeader.alignment = 'center';
                //             doc.defaultStyle.alignment = 'center';
                //             doc.content[1].table.widths =
                //                 Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                //         },
                //         exportOptions: {
                //             columns: [0,1,2]
                //         },
                //         className: 'btn btn-success waves-effect waves-light',
                // },
                {
                    text: 'print',
                        extend: 'print',
                        title: '',
                        orientation:'portrait',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [0,1,2,3]
                        },
                        className: 'btn btn-success waves-effect waves-light',
                            messageTop: function () {
                                muncit = $('#grantMuncit').val();
                                console.log(muncit);
                            return '<h1 style="text-align:center;">HOUSELEADER SUMMARY</h1><h2 style="text-align:center;">'+muncit+'</h2>';
                        }
                }
            ],
            "columnDefs": [
                    {"className": "text-center", "targets": [1,2,3]},
                    {"className": "dt-center", "targets": "_all"},
                ]
        });

        $('#hlsumm-modal-lg').modal('show');
    });

    $('#hlsumm-modal-lg').on('hidden.bs.modal', function () {
        $('#hlsumm').dataTable().fnDestroy();
    });

    $('#selbrgy').on('change', function(e){
        $('#selHL').val('').trigger('change');
        $('#sortPurok').val('').trigger('change');
        var selectBrgy = []
        $.each($('#selbrgy'), function(i,elem){
            selectBrgy.push($(this).val())
        })
        cvrec.column(2).sort().search(selectBrgy).draw();
    });

    // sortPurok


    $('#selHL').on('change', function(e){
        var selectHL = []
        $.each($('#selHL'), function(i,elem){
            selectHL.push($(this).val())
        })
        cvrec.column(3).sort().search(selectHL).draw();
    });

    $('#selbrgy').select2({
        placeholder: "Select Barangay",
        allowClear: true,
        ajax:{
            url:"{{ route('cvrecord.brgy') }}",
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

    $('#selHL').select2({
        placeholder: "Select Houseleader",
        allowClear: true,
        ajax:{
            url:"{{ route('cvrecord.selHL') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                dist = $('#dist').val();
                muncit = $('#grantMuncit').val();
                barangay = $('#selbrgy').val();
                return{
                    search: params.term,
                    dist:dist,
                    muncit:muncit,
                    barangay:barangay
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

    $('#sortPurok').select2({
        placeholder: "Sort by purok",
        allowClear: true,
        minimumResultsForSearch: -1,
        ajax:{
            url:"{{ route('cvrecord.sortPurok') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                dist = $('#dist').val();
                muncit = $('#grantMuncit').val();
                barangay = $('#selbrgy').val();
                return{
                    search: params.term,
                    dist:dist,
                    muncit:muncit,
                    barangay:barangay
                };
            },
            processResults: function(data){
                return{
                    results: $.map(data.items, function(obj,i) {
                        return {
                            id:i,
                            text:obj
                        };
                    })
                }
            },
            cache: true
        }
    });

    $('#sortPurok').on('change', function(e){
        // $('#sortPurok').val('').trigger('change');
        var sortPurok = []
        $.each($('#sortPurok'), function(i,elem){
            sortPurok.push($(this).val())
        })
        cvrec.column(4).sort().search(sortPurok).draw();
    });

    // $('#selHL').on('change',function(){
    // });

    $('#hlbrgy').on('change', function (e) {
        $('#typegrant, #fltrDate').val('').trigger('change');
    });
});
</script>

@include('layouts.script')
@include('layouts.footer')
@endsection
