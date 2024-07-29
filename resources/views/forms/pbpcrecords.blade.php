@extends('layouts.auth')
@section('content')
<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Punong Barangay/Party Chairman List</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Data</a></li>
                                <li class="breadcrumb-item active">{{ Auth::User()->muncit }}</li>
                            </ol>
                        </div>
                    </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h2 class="card-title"><strong>Filter Barangay</strong></h2>
                                            <select name="selbrgy" id="selbrgy" class="form-control"></select>
                                        </div>
                                        {{-- <div class="col-6">
                                            <h2 class="card-title"><strong>Search</strong> </h2>
                                            <input type="text" name="csearch" id="csearch" class="form-control">
                                        </div> --}}
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <table id="hlRecordstbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:2rem;">ID</th>
                                                        <th style="width:15rem;">Name</th>
                                                        <th>Barangay</th>
                                                        <th>Status</th>
                                                        {{-- <th>Member Count</th> --}}
                                                        <th>Remarks</th>
                                                        <th style="width:2rem;">Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
</div>

<div class="modal fade bs-example-modal-lg pbpceditmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Houseleader Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  id="hlrecupdate" >
                    <input type="hidden" id="hlid" name="hlid">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="name" class="form-label">Barangay</label>
                            <input type="text" class="form-control"  id="hlBrgy"  name="hlBrgy"  data-placeholder="Select an Barangay" tabindex="-1" aria-hidden="true" readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="contno" class="form-label">PB/PC Leader</label>
                            <input type="text" class="form-control " id="hlName"  tabindex="-1" aria-hidden="true" name="hlName" readonly >
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="pbstatus" class="form-label">Status</label>
                            <select name="pbstatus" id="pbstatus" class="form-control">
                                <option Selected disabled>Select Status</option>
                                <option value="PULA">PULA</option>
                                <option value="DULAW">DULAW</option>
                            </select>
                            {{-- <input type="text" class="form-control" id="pbstatus"
                                placeholder="Status" name="pbstatus"> --}}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-1">
                            <label for="hlRemarks" class="form-label">Remarks</label>
                            <textarea class="form-control" rows="3" name="hlRemarks" id="hlRemarks"></textarea>
                        </div>
                    </div>
                    <div class="d-grid mb-3 my-4">
                        <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" id="btpbpcLSave">Save</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade bs-example-modal-lg viewMembers" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Houseleader Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="membersTable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Barangay</th>
                        <th>Grants</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>



<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var hlTbl = $('#hlRecordstbl').DataTable({
            "order": [[ 2, 'asc' ], [ 1, 'asc' ]],
            "ordering": true ,
            "pageLength": 10,
            "lengthMenu": [[10,25,50, -1], [10,25,50, "All"]],
            "dom": '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<"dt-filter-spacer"f><ip>',
            // "searching": false,
            // "dom": 'Bfrtip',
            "serverSide": true,
            "processing": true,
            "ajax": "{{ route('pbpcrecords.index') }}",
            "columns": [
                {"data": "mid",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;}}, //0
                {"data": "purok_leader"}, //1
                {"data": "barangay"},//2
                {"data": "status"},//3
                // {"data": "count_house_leader"},
                {"data": "remarks"},//4
                {"data": "action", orderable:false, searchable: false, "className": "text-center"} //5
            ],
            "columnDefs": [
                {"className": "align-middle", "targets": "_all"},
                {"className": "text-center", "targets": "_all"},
            ],
            "buttons": [
                {
                    text: 'excel',
                    extend: 'excelHtml5',
                    title: 'PB/PC SUMMARY',
                    className: 'btn btn-success waves-effect waves-light',
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    }
                },
                {
                    text: 'print',
                    extend: 'print',
                    title: '',
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0,1,3,4],
                        modifier: {
                            page: 'all'
                        }
                    },
                    className: 'btn btn-success waves-effect waves-light',
                        messageTop: function () {
                            muncit = "{{ Auth::user()->muncit }}";
                            hlbrgys = $('#selbrgy').val();
                            if(muncit && hlbrgys) {
                                return '<h1 style="text-align:center;">PB/PC SUMMARY</h1>' +'<h2 style="text-align:center;">' + muncit + ' - ' + hlbrgys + '</h2>';
                            }else if(muncit && !hlbrgys ){
                                return '<h1 style="text-align:center;">PB/PC SUMMARY</h1><h2 style="text-align:center;">'+muncit+'</h2>';
                            }else{
                                return '<h1 style="text-align:center;">PB/PC SUMMARY - ALL</h1>';
                            }

                    }
                }
                // {
                //     extend: 'colvis',
                //     columns: 'th:nth-child(n+2)',
                //     className: 'btn btn-success waves-effect waves-light'

                // }
            ]

        });


        $('.dataTables_filter input[type="search"]').css(
            {'width':'350px','display':'inline-block'}
        );

        $('#selbrgy').select2({
            placeholder: "Select Barangay",
            minimumResultsForSearch: -1,
            allowClear: true,
            ajax:{
                url:"{{ route('hlrecords.selbrgy') }}",
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

        $('#pbstatus').select2({
            placeholder: "Select Status",
            dropdownParent: $(".pbpceditmodal"),
            minimumResultsForSearch: -1,
            allowClear: true,
        });

        $('#selbrgy').on('change', function(){
            var selectBrgy = []
            $.each($('#selbrgy'), function(i,elem){
                selectBrgy.push($(this).val())
            })
            hlTbl.column(2).search(selectBrgy).draw();
        })

        // $('#csearch').on('keyup change', function(){
        //     hlTbl.columns([1,2,4]).search(this.value).draw();
        // });


        $(document).on('click','.pbpcdelete', function(e){
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
                            url: "{{ route('pbpcrecords.pbpcdelete') }}",
                            method: 'post',
                            data:{id:id},
                            success:function(res){
                                Swal.fire(
                                    'Deleted!',
                                    'Record deleted.',
                                    'success'
                                )
                                hlTbl.ajax.reload(null, false);
                            }
                        });
                    }
            });
        });

        $(document).on('click','.pbpcedit', function(){
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('pbpcrecords.pbpcedit', '') }}" +'/'+id,
                method:'GET',
                success: function(response){
                    $('.pbpceditmodal').modal('show');
                    $('#hlid').val(response.id);
                    $('#hlBrgy').val(response.barangay);
                    $('#hlName').val(response.purok_leader);
                    $('#hlPurok').val(response.purok);
                    $('#seqNum').val(response.sqn);
                    $('#hlRemarks').val(response.remarks);
                },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                        toastr.error(err.message);
                    }
                }
            });
        });

        $('#btpbpcLSave').on('click', function(){
            var formData = new FormData(hlrecupdate);
            $.ajax({
                url: "{{ route('pbpcrecords.pbpcupdate') }}" ,
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,

            success: function(response) {
                hlTbl.ajax.reload();
                if(response) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "PB/PC type updated!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                $('.pbpceditmodal ').modal('toggle');
            },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                        toastr.error(err.message);
                    }
                }
            });
        });

        $(document).on('click','.hlmemview', function(){
            var id = $(this).data('id');
            var hlname = $(this).data('name');

            var membersTable = $('#membersTable').DataTable({
                destroy: true, // Allows reinitializing the DataTable
                processing: true,
                serverSide: false, // Assuming client-side processing
                data: [], // Initial empty data
                columns: [
                    { data: 'Name' },
                    { data: 'barangay' },
                    { data: 'grant_rv' },
                    { data: 'remarks' }
                ]
            });

            // $('.viewMembers').modal('show');
            $.ajax({
                url: "{{ route('hlrecords.vmembers') }}",
                method:'GET',
                data: {id:id,hlname:hlname },
                success: function(response){

                    membersTable.clear().rows.add(response.data).draw();

                    $('.viewMembers').modal('show');

                },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                        toastr.error(err.message);
                    }
                }
            });
        });
    });
</script>


@include('layouts.script')
@include('layouts.footer')
@endsection
