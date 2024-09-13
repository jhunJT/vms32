@extends('layouts.auth')
@section('content')
<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0"> Command Vote Records -Fix</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Data</a></li>
                            <li class="breadcrumb-item active">{{ Auth::User()->muncit }}</li>
                        </ol>
                    </div>
                    {{-- <button onclick="printTable()">Print Table</button> --}}
                </div>
                <div class="card" id="refreshTB">
                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    {{-- <div class="col-2 p-3">
                                        <button type="button" class="btn btn-primary waves-effect waves-light hlsumm">House Leader Summary</button>
                                    </div> --}}
                                </div>
                                <div class="row">

                                    <div class="col-4">
                                        <select id="selectMuncitsu" style="width: 100%;" class="form-control" name="selectMuncitsu" ></select>
                                    </div>
                                    <div class="col-3">
                                        <select id="selbrgysu" style="width: 100%;" class="form-control" name="selbrgysu"></select>
                                    </div>
                                    {{-- <div class="col-3">
                                        <select id="selHL" style="width: 100%;" class="form-control" name="selHL"></select>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        <table id="fixhl_tb" class="table table-bordered nowrap cvrectbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead >
                            <tr>
                                <th>#</th>
                                <th >Houseleader</th>
                                <th>Purok</th>
                                <th>SQN No</th>
                                <th>Action</th>
                                <th >Barangay</th>
                                <th>Municipality</th>
                            </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
</div>

<div id="popup" style="display:none; position:fixed; top:20%; left:30%; padding:20px; background:white; border:1px solid black;">
    <p id="popupMessage"></p>
    <button onclick="$('#popup').hide()">Close</button>
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

    var cvrec = $('#fixhl_tb').DataTable({
        "ordering": false,
        "autoWidth" : true,
        "lengthMenu": [[15,25,50, -1], [15,25,50, "All"]],
        "pageLength": 15,
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('fixhl.fixhlindex') }}",
        "columns": [
            {"data": "cid", "searchable":false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;}}, //0
            {"data": "houseleader"}, //1
            {"data": "purok"},//2
            {"data": "sqn"},//3
            {"data": "action", "searchable":false, "orderable":false },//4
            {"data": "barangay"},//5
            {"data": "muncit"}, //6
        ],
        "columnDefs": [
            {"className": "text-center", "targets": [0,2,3,4]},
            {"targets": [5,6], "visible": false, "searchable": true }
        ]
    });

    $('#selDistsu').select2({
        minimumResultsForSearch: -1
    });

    $('#selDistsu').on('change', function(e){
        $('#selectMuncitsu, #selbrgysu').val('').trigger('change');
        var selectBrgy = $(this).val();
        cvrec.column(2).search('^' + selectBrgy + '$', true, false).draw();
    });

    $('#selectMuncitsu').select2({
        placeholder: "Select Municipality/City",
        allowClear: true,
        ajax:{
            url:"{{ route('fixhl-member.selmuncit') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                dist = $('#selDistsu').val();
                return{
                    search: params.term,
                    dist: dist
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

    $('#selectMuncitsu').on('change', function(){
        var selectMuncit = [];
        $.each($('#selectMuncitsu'), function(i,elem){
            selectMuncit.push($(this).val())
        })
        cvrec.column(6).search(selectMuncit).draw();
    });

    $('#selbrgysu').select2({
        placeholder: "Select Barangay",
        allowClear: true,
        ajax:{
            url:"{{ route('fixhl-member.selbrgy') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                dist = $('#selDistsu').val();
                muncit = $('#selectMuncitsu').val();
                return{
                    search: params.term,
                    dist: dist,
                    muncit: muncit,

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

    $('#selbrgysu').on('change', function(e){

        $('#selHL').val('').trigger('change');
        $('#sortPurok').val('').trigger('change');

        var selectBrgy = []
        $.each($('#selbrgysu'), function(i,elem){
            selectBrgy.push($(this).val())
        })
        cvrec.column(5).search( selectBrgy).draw();
        // cvrec.column(2).search('^' + selectBrgy + '$', true, false).draw();
    });

    $('#selHL').select2({
        placeholder: "Select Houseleader",
        allowClear: true,
        ajax:{
            url:"{{ route('fixhl-member.hleader') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){

                dist = $('#selDistsu').val();
                muncit = $('#selectMuncitsu').val();
                brgy = $('#selbrgysu').val();

                return{
                    search: params.term,
                    dist:dist,
                    muncit:muncit,
                    brgy:brgy
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



    $('.hlsumm').on('click',function(){
        var hlsummary = $('#hlsumm').dataTable({
            "ordering":false,
            "dom": "rtip",
            "dom": 'Bfrtip',
            "ajax": "{{ route('cvrecord.cvhlsumm') }}",
            "columns":[
                        { "data": "Barangay"}, //0
                        { "data": "HL"}, //1
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
                {
                    text: 'pdf',
                        extend: 'pdfHtml5',
                        title: 'HOUSELEADER SUMMARY',
                        orientation:'portrait',
                        pageSize: 'LEGAL',
                        customize: function (doc) {
                            doc.styles.tableHeader.alignment = 'center';
                            doc.defaultStyle.alignment = 'left';
                            doc.content[1].table.widths =
                                Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        },
                        exportOptions: {
                            columns: [0,1,2]
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
                            columns: [0,1,2,3]
                        },
                        className: 'btn btn-success waves-effect waves-light',
                            messageTop: function () {
                                muncit = $('#grantMuncit2').val();
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

    $('#gdist').select2({
        placeholder: "Select District",
        minimumResultsForSearch: -1,
        allowClear: true,
    });

    $('#selHL').on('change', function(e){
        var selectHL = []
        $.each($('#selHL'), function(i,elem){
            selectHL.push($(this).val())
        })
        cvrec.column(3).sort().search(selectHL).draw();
    });

    $('#sortPurok').select2({
        placeholder: "Purok",
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

    $('#hlbrgy').on('change', function (e) {
        $('#typegrant, #fltrDate').val('').trigger('change');
    });

    $(document).on('click','.recdelete', function(e){
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
                        url: '{{ route('fixhl.fixhldel') }}',
                        method: 'post',
                        data:{id:id},
                        success:function(res){
                            Swal.fire(
                                'Deleted!',
                                'Record deleted.',
                                'success'
                            )
                            cvrec.ajax.reload(null, false);
                        }
                    });
                }
            });
    });

    $('.dataTables_filter input[type="search"]').css(
        {'width':'350px','height':'40px','display':'inline-block'}
    );

});
</script>

@include('layouts.script')
@include('layouts.footer')
@endsection
