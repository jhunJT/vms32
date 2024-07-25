@extends('layouts.auth')
@section('content')
<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="card-title"><strong>Grant</strong> </h2>
                                    <hr>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <table id="hlRecordstbl" class="table table-striped table-bordered dt-responsive nowrap grantTbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:2rem;">ID</th>
                                                        <th style="width:15rem;">Name</th>
                                                        <th>Barangay</th>
                                                        <th>Remarks</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3" style="text-align:right">Total:</th>
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
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
</div>

<div id="selbrgy" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">CV Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <select name="gbrgy" id="gbrgy" class="form-control">
                    <option value="1">1</option>
                    <option value="1">1</option>
                    <option value="1">1</option>

                </select>
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

        var grantTbl = $('#hlRecordstbl').DataTable({
            "order": [[ 2, 'asc' ], [ 1, 'asc' ]],
            "ordering": true ,
            "pageLength": 10,
            "dom": 'Bfrtip',
            "ajax": "{{ route('hlrecords.index') }}",
            "columns": [
                {"data": "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;}
                },
                {"data": "houseleader"}, //1
                {"data": "barangay"},//2
                {"data": "remarks"},//3
                {"data": "action", orderable:false, searchable: false, "className": "text-center"} //4
            ],
            "columnDefs": [
                {"className": "align-middle", "targets": "_all"},
                {"className": "text-center", "targets": "_all"},
            ],
            "drawCallback": function(settings) {
                var api = this.api();
                var startIndex = api.context[0]._iDisplayStart; // Get the index of the first row displayed on the current page
                api.column(0, {order:'current'}).nodes().each(function(cell, i) {
                    cell.innerHTML = startIndex + i + 1; // Update the row number
                });
            },
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();
                var totalCount = api.rows().count();
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[\$,]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };

                total = api
                        .column(3)
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);

                    // Total over this page
                pageTotal = api
                    .column(3, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Update footer
                api.column(3).footer().innerHTML =  totalCount   ;

            },
            "buttons": [
                {
                    text: 'excel',
                    extend: 'excelHtml5',
                    title: 'HOUSELEADER SUMMARY',
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
                        columns: [0,1,2,3]
                    },
                    className: 'btn btn-success waves-effect waves-light',
                        messageTop: function () {
                            muncit = "{{ Auth::user()->muncit }}";
                            return '<h1 style="text-align:center;">HOUSELEADER SUMMARY</h1><h2 style="text-align:center;">'+muncit+'</h2>';
                    }
                },
            ]
        });


    });
</script>




@include('layouts.script')
@include('layouts.footer')
@endsection
