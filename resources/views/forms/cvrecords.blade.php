@extends('layouts.auth')
@section('content')
<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0"> Command Vote Records</h4>
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
                                    <div class="col-2 p-3">
                                        <button type="button" class="btn btn-primary waves-effect waves-light hlsumm">House Leader Summary</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2">

                                        @if (Auth::user()->role == 'encoder' || Auth::user()->role == 'supervisor' )
                                                <select id="dist2" class="form-control" name="dist2">
                                                    <option value="{{ Auth::user()->district}}" selected>{{ Auth::user()->district}}</option>
                                                </select>
                                            @elseif (Auth::user()->role == 'admin' || Auth::user()->role == 'superuser')
                                                <select id="dist" class="form-control" name="dist">
                                                    <option selected disabled>Select District</option>
                                                    <option value="District I">District I</option>
                                                    <option value="District II">District II</option>
                                                </select>
                                            @endif
                                        {{-- <input class="form-control" type="text" id="dist" placeholder="District I" value="District I" readonly> --}}
                                        {{-- <select id="gdist" class="form-control" name="gdist"></select> --}}
                                    </div>
                                    <div class="col-2">
                                        @if (Auth::user()->role == 'encoder' || Auth::user()->role == 'supervisor')
                                            <select id="grantMuncit2" class="form-control" name="grantMuncit2" >
                                                <option value="{{ Auth::user()->muncit}}" selected>{{ Auth::user()->muncit}}</option>
                                            </select>
                                        @elseif (Auth::user()->role == 'admin' || Auth::user()->role == 'superuser')
                                                <select id="grantMuncit" class="form-control" name="grantMuncit"></select>
                                        @endif

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

                        <table id="cvrectbl" class="table table-bordered nowrap cvrectbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead >
                            <tr>
                                <th>#</th>
                                <th style="width:2rem;">Name</th>
                                <th style="width:15rem;">Barangay</th>
                                <th>Houseleader</th>
                                <th>Purok</th>
                                <th>SQN</th>
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
            "ordering": false,
            "autoWidth" : true,
            "lengthMenu": [[15,25,50, -1], [15,25,50, "All"]],
            "dom": '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<"dt-filter-spacer"f><ip>',
            "pageLength": 15,
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('cvrecord.index') }}",
            "columns": [
                {"data": "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;}}, //0
                {"data": "Name",
                    render: function (data, type, row, meta) {
                    var prefix = "HL: ";
                        if(data == row.HL){
                            return prefix + data;
                        }
                            return data;}},
                {"data": "Barangay"}, //2
                {"data": "HL"},//3
                {"data": "purok_rv"},//4
                {"data": "sqn"},//5
                {"data": "sethl"},//6
                {"data": "Municipality"} //7
            ],
            "columnDefs": [
                {"className": "text-center", "targets": [0,2,3,4,5]},
                {"targets": [6,7], "visible": false, "searchable": false }
            ],
            "fnRowCallback": function( row, data, index ) {
                if  (data.Name  === data.HL) {
                    $(row).addClass('green');
                }
            },
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
                    text: 'pdf',
                        extend: 'pdfHtml5',
                        title: 'CV SUMMARY',
                        orientation:'portrait',
                        pageSize: 'LEGAL',
                        customize: function (doc) {
                            var columnAlignments = ['center', 'left', 'center', 'center'];
                            var columnWidths = ['10%', '70%', '10%', '10%'];
                            var sequenceCounter = 1; // Initialize the sequence counter

                            if (doc.content && doc.content[1] && doc.content[1].table) {
                                doc.content[1].table.widths = columnWidths;

                                doc.content[1].table.body.forEach(function (row, rowIndex) {
                                    row.forEach(function (cell, cellIndex) {
                                        if (rowIndex === 0) {
                                            // Header row: center-align all cells
                                            cell.alignment = 'center';
                                        } else {
                                            // Data rows
                                            cell.alignment = columnAlignments[cellIndex] || 'left';

                                            // Check if the cell in the second column does not start with 'HL'
                                            if (cellIndex === 1 && !cell.text.startsWith('HL')) {
                                                cell.margin = [17, 0, 0, 0];
                                                // Add sequence number to the first cell of the row
                                                if (cellIndex === 0) {
                                                    cell.text = sequenceCounter + '. ' + cell.text;
                                                }
                                                sequenceCounter++; // Increment the sequence counter
                                            } else if (cellIndex === 1 && cell.text.startsWith('HL')) {
                                                // Reset sequence counter when 'HL' is encountered
                                                sequenceCounter = 1;
                                            }
                                        }
                                    });
                                });
                            }

                            doc.pageMargins = [40, 40, 40, 40];
                        },
                        exportOptions: {
                            columns: [0,1,4,5]
                        },
                        className: 'btn btn-success waves-effect waves-light'
                },
                {
                    text: 'print',
                    extend: 'print',
                    title: '',
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0,1,4,5],
                        modifier: {
                            page: 'all'
                        }
                    },
                    className: 'btn btn-success waves-effect waves-light',
                        messageTop: function () {
                            muncit = $('#grantMuncit2').val();
                            hlbrgys = $('#selbrgy').val();
                            if(muncit && hlbrgys) {
                                return '<h1 style="text-align:center;">CV SUMMARY</h1>' +'<h2 style="text-align:center;">' + muncit + ' - ' + hlbrgys + '</h2>';
                            }else if(muncit && !hlbrgys ){
                                return '<h1 style="text-align:center;">CV Records</h1><h2 style="text-align:center;">'+muncit+'</h2>';
                            }else{
                                return '<h1 style="text-align:center;">CV SUMMARY - ALL</h1>';
                        }
                    },
                    customize: function (win) {
                        $(win.document.body).find('td').each(function() {
                            var cellHtml = $(this).html();
                            if ($(this).text().startsWith('HL: ')) {
                                $(this).css('font-weight', 'bold');
                                $(this).css('color', 'red');
                            }
                        });
                    }
                },
            ]
        });

    $('#grantMuncit').select2({
        placeholder: "Select Municipality/City",
        allowClear: true,
        ajax:{
            url:"{{ route('cvrecord.cvmuncit') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                // muncit = $('#grantMuncit').val();
                dist = $('#dist').val();
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

    $('#grantMuncit2').select2({
        minimumResultsForSearch: -1
    });

    $('#dist2').select2({
        minimumResultsForSearch: -1
    });

    $('#dist').select2({
        minimumResultsForSearch: -1
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

    $('#selbrgy').on('change', function(e){
        $('#selHL').val('').trigger('change');
        $('#sortPurok').val('').trigger('change');

        var selectBrgy = []
        $.each($('#selbrgy'), function(i,elem){
            selectBrgy.push($(this).val())
        })
        cvrec.column(2).search('^' + selectBrgy + '$', true, false).draw();
    });

    $('#grantMuncit').on('change', function(){
        var selectMuncit = []
        $.each($('#grantMuncit'), function(i,elem){
            selectMuncit.push($(this).val())
        })
        cvrec.column(7).search(selectMuncit).draw();
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
                muncit2 = $('#grantMuncit2').val();
                // console.log('m1' + muncit,'m2' + muncit2);
                return{
                    search: params.term,
                    muncit: muncit,
                    muncit2: muncit2
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
                dist2 = $('#dist2').val();
                muncit = $('#grantMuncit').val();
                muncit2 = $('#grantMuncit2').val();

                barangay = $('#selbrgy').val();

                return{
                    search: params.term,
                    dist:dist,
                    dist2:dist2,
                    muncit:muncit,
                    muncit2:muncit2,

                    barangay:barangay,
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
