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
                                        <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">Grant Summary</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2">
                                        <input class="form-control" type="text" id="dist" placeholder="District I" value="District I" readonly>
                                        {{-- <select id="gdist" class="form-control" name="gdist"></select> --}}
                                    </div>
                                    <div class="col-2">
                                        {{-- <input class="form-control" type="text" id="hlmun"  value="CALBAYOG CITY" readonly> --}}
                                        <select id="grantMuncit" class="form-control" name="grantMuncit" >
                                            <option value="{{ Auth::user()->muncit}}" selected>{{ Auth::user()->muncit}}</option>
                                        </select>

                                    </div>
                                    <div class="col-2">
                                        <select id="hlbrgy" class="form-control" name="hlbrgy"></select>
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
                        <th>CASH</th>
                        <th>Scholarship</th>
                        <th>TUPAD</th>
                        <th>MSME</th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

{{-- gntedit --}}
<div class="modal fade bs-example-modal-sm grntedit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-m modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Grant Edit</h5>
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
                            {{-- <input type="text" name="ggrant" id="ggrant" class="form-control"> --}}
                            <select class="form-select" name="ggrant" id="ggrant">
                                <option selected disabled value="">Choose...</option>
                                <option value="AICS">AICS</option>
                                <option value="Cash">Cash</option>
                                <option value="Scolarship">Scolarship</option>
                                <option value="TUPAD">TUPAD</option>
                                <option value="MSME GRANT">MSME Grant</option>
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
                                <input type="text" class="form-control" placeholder="dd M, yyyy" name="gdate" id="gdate"
                                    data-date-format="dd M, yyyy" data-date-container='#datepicker2' data-provide="datepicker"
                                    data-date-autoclose="true">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gname">Amount</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" name="gamount" id="gamount" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2 col-form-label">
                            <label for="gname">Remarks</label>
                        </div>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="gremarks" id="gremarks" cols="10" rows="2"></textarea>
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
            "order": [[ 1, 'asc' ], [ 3, 'asc' ],[ 4, 'asc' ],[ 5, 'asc' ]],
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
                // {"targets": 6, "render": $.fn.dataTable.render.number(',', '.', 2, '')}
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
                        // ':visible:not(.not-export-col)'
                        // columns: ":not(.not-export-column)"
                    },
                    className: 'btn btn-success waves-effect waves-light',
                        messageTop: function () {
                            muncit = $('#hlmun').val();
                        return '<h1 style="text-align:center;">GRANTS SUMMARY</h1><h2 style="text-align:center;">'+muncit+'</h2>';
                    }
                },
            ]
    });

    var grntsummary = $('#grntsumm').dataTable({
        "dom": "rtip",
        "dom": 'Bfrtip',
        "ajax": "{{ route('grant.grantsummary') }}",
        "columns":[
                    { "data": "barangay"}, //1
                    { "data": "AICS"}, //2
                    { "data": "Cash"}, //3
                    { "data": "Scolarship"}, //4
                    { "data": "TUPAD"}, //5
                    { "data": "MSME"} //6
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
                    // {"targets": 0, "ckeckboxes": {"selectedRow": true}},
                    // {"className": "align-middle", "targets": "_all"},
                    {"className": "text-center", "targets": [1,2,3,4]},
                    {"className": "dt-center", "targets": "_all"},
                ],
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
        grantTbl.column(3).sort().search(selectBrgy).order([5,'asc']).draw();
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
        // console.log(id);
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
        placeholder: "Select Barangay",
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
                muncit = $('#grantMuncit').val();
                barangay = $('#hlbrgy').val();
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
                    barangay:barangay,
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
                $('#ggrant').val(response.grant).change();
                $('#gdate').val(response.date);
                $('#gamount').val(response.amount);
                $('#gremarks').val(response.remarks);

            },
            error: function(xhr, status, error){
                //console.log(error);
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });
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

                $('.grntedit').modal('toggle');

                if(response) {
                    toastr.success(response.success);
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

});
</script>

@include('layouts.script')
@include('layouts.footer')
@endsection
