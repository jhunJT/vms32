@extends('layouts.auth')
@section('content')
<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0"> Coordinates Setup</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Data</a></li>
                            <li class="breadcrumb-item active">{{ Auth::User()->muncit }}</li>
                        </ol>
                    </div>
                </div>
                <div class="card" id="refreshTB">
                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2 p-3">
                                        <button type="button" class="btn btn-primary waves-effect waves-light addcoords">Add Coordinates</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <select id="dist2" class="form-control" name="dist2">
                                            <option value="{{ Auth::user()->district}}" selected>{{ Auth::user()->district}}</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select id="grantMuncit2" class="form-control" name="grantMuncit2" >
                                            <option value="{{ Auth::user()->muncit}}" selected>{{ Auth::user()->muncit}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id="coordstbl" class="table table-bordered dt-responsive nowrap cvrectbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead >
                                <tr>
                                    <th>#</th>
                                    <th style="width:15rem;">Barangay</th>
                                    <th>Longitude</th>
                                    <th>Latitude</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                    <th></th>
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

<div id="addcoordsModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Coordinates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addcoordinatesForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="Barangay" class="form-label">Barangay</label>
                                <select class="form-control"  id="Barangay"  name="barangay"  data-placeholder="Select an Barangay" tabindex="-1" aria-hidden="true"></select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" class="form-control" id="longitude"
                                    placeholder="Longitude" name="longitude">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" class="form-control" id="latitude"
                                    placeholder="Latitude" name="latitude">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-1">
                                <label for="coord_remarks" class="form-label">Remarks</label>
                                <textarea required class="form-control" rows="3" name="coord_remarks" id="coord_remarks"></textarea>
                            </div>
                        </div>
                        <div class="d-grid mb-3 my-4">
                            <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" id="btCoordsLSave">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="coordsModalUpdate" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Coordinates Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="UcoordinatesForm">
                    <input type="hidden" class="form-control" id="cid" name="cid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="ubarangay" class="form-label">Barangay</label>
                                {{-- <select class="form-control"  id="ubarangay"  name="barangay"  data-placeholder="Select an Barangay" tabindex="-1" aria-hidden="true"></select> --}}
                                <input type="text" class="form-control"  id="ubarangay"  name="barangay" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" class="form-control" id="ulongitude"
                                    placeholder="Longitude" name="ulongitude">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" class="form-control" id="ulatitude"
                                    placeholder="Latitude" name="ulatitude">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-1">
                                <label for="coord_remarks" class="form-label">Remarks</label>
                                <textarea required class="form-control" rows="3" name="ucoord_remarks" id="ucoord_remarks"></textarea>
                            </div>
                        </div>
                        <div class="d-grid mb-3 my-4">
                            <button type="button" class="btn btn-primary btn-lg waves-effect waves-light" id="btCoordsLUpdt">Update</button>
                        </div>
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

    var coords = $('#coordstbl').DataTable({
        "order": [[ 6, 'asc' ], [ 7, 'asc' ],[ 1, 'asc' ]],
        "ordering": true ,
        "pageLength": 15,
        "ajax": "{{ route('coordinates.index') }}",
        "columns": [
            {"data": "id",
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;}, "className": "tcenter"}, //0
            {"data": "barangay", "className": "tleft"},//1
            {"data": "longitude","className": "tcenter"},//2
            {"data": "latitude","className": "tcenter"},//3
            {"data": "remarks"},//4
            { "data": "action", orderable:false, searchable: false, "className": "tcenter"}, //5
            {"data": "district"},//6
            {"data": "muncit"},//7
        ],
        "columnDefs": [
            {"className": "align-middle", "targets": "_all"},
            {"visible": false, "searchable":false, "targets": [6,7]},
        ]

    });


    $('#Barangay').select2({
        placeholder: "Select Barangay",
        dropdownParent: $("#addcoordsModal"),
        allowClear: true,
        ajax:{
            url:"{{ route('coordinates.getbrgy') }}",
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

    $('#grantMuncit2').select2({
        minimumResultsForSearch: -1
    });

    $('#dist2').select2({
        minimumResultsForSearch: -1
    });

    $('.addcoords').on('click', function(){
        $('#addcoordsModal').modal('show');
    });

    var formCoords = $('#addcoordinatesForm')[0];
    $('#btCoordsLSave').on('click', function(){
        var formlatlong = new FormData(formCoords);
        $.ajax({
            url:"{{ route('coordinates.coordsave') }}" ,
            method: 'POST',
            processData: false,
            contentType: false,
            data: formlatlong,
            success: function(response) {
                $('#addcoordinatesForm')[0].reset();
                $('#addcoordsModal').modal('toggle');
                if(response.success) {
                        Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Coordinates added!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                coords.ajax.reload();
            },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });
    });

    $('.dataTables_filter input[type="search"]').css(
        {'width':'350px','display':'inline-block'}
    );

    $(document).on('click','.cdelete', function(e){
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
                        url: "{{ route('coordinates.coorddelete') }}",
                        method: 'post',
                        data:{id:id},
                        success:function(res){
                            Swal.fire(
                                'Deleted!',
                                'Record deleted.',
                                'success'
                            )
                            coords.ajax.reload(null, false);
                        }
                    });
                }
        });
    });

    $(document).on('click','.cedit', function(){
        var id = $(this).data('id');
        $.ajax({
            url: "{{ route('coordinates.coordedit', '') }}" +'/'+id,
            method:'GET',
            success: function(response){
                $('#coordsModalUpdate').modal('show');
                $('#cid').val(response.id);
                $('#ubarangay').val(response.barangay);
                $('#ulongitude').val(response.longitude);
                $('#ulatitude').val(response.latitude);
                $('#ucoord_remarks').val(response.remarks);
            },
        error: function(xhr, status, error){
            if(error) {
                var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });
    });

    var uformCoords = $('#UcoordinatesForm')[0];
    $('#btCoordsLUpdt').on('click', function(){
        var uformlatlong = new FormData(uformCoords);
        $.ajax({
            url:"{{ route('coordinates.coordupdate') }}" ,
            method: 'POST',
            processData: false,
            contentType: false,
            data: uformlatlong,
            success: function(response) {
                $('#UcoordinatesForm')[0].reset();
                $('#coordsModalUpdate').modal('toggle');
                if(response.success) {
                        Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Coordinates updated!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                coords.ajax.reload();
            },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: err.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // toastr.error(err.message);
                }
            }
        });
    });

});
</script>

@include('layouts.script')
@include('layouts.footer')
@endsection
