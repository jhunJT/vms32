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
                                    <div class="col-1">
                                    <select id="selDistsu" style="width: 100%;" class="form-select" name="selDistsu">
                                        <option disabled></option>
                                        <option value="District I">District I</option>
                                        <option value="District II">District II</option>
                                    </select>
                                    </div>
                                    <div class="col-2">
                                        <select id="selectMuncitsu" style="width: 100%;" class="form-control" name="selectMuncitsu" ></select>
                                    </div>
                                    <div class="col-3">
                                        <select id="selbrgysu" style="width: 100%;" class="form-control" name="selbrgysu"></select>
                                    </div>
                                    <div class="col-3">
                                        <select id="selHL" style="width: 100%;" class="form-control" name="selHL"></select>
                                    </div>
                                    <div class="col-1">
                                        <select id="sortPurok" style="width: 100%;" class="form-control" name="sortPurok"></select>
                                    </div>
                                    <div class="col-2 text-center">
                                        <button class = "btn btn-info ">Load Data</button>
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
                                <th>is_Leader</th>
                                <th>is_Member</th>
                                <th>Purok</th>
                                <th>SQN</th>
                                <th></th>
                                <th></th>
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

    var cvrec = $('#cvrectbl').DataTable({
            "ordering": false,
            // "order": [[8, 'asc'],[2, 'asc'],[7, 'asc']],
            "autoWidth" : true,
            "lengthMenu": [[15,25,50, -1], [15,25,50, "All"]],
            "dom": '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<"dt-filter-spacer"f><ip>',
            "pageLength": 15,
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('fixhl-member.index') }}",
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
                            return data;}}, //1
                {"data": "Barangay"}, //2
                {"data": "HL"},//3
                {"data": "sethl",
                "defaultContent": '',
                    "render": function (data, type, row, meta) {
                        if (type === 'display') {
                            var selectOptions = '<select class="form-select btnSethl">';
                            selectOptions += '<option value="1"' + (data == 1 ? ' selected' : '') + '>Yes</option>';
                            selectOptions += '<option value="0"' + (data == 0 ? ' selected' : '') + '>No</option>';
                            selectOptions += '</select>';

                            return selectOptions;

                            // if(data == 1){
                            //     var r = 'Yes'
                            // }else{
                            //     var r= 'No'
                            // }
                            // if (data) {
                            //     // If data is available, select the appropriate option
                            //     return selectOptions.replace('value="' + data + '"', 'value="' + r + '" selected');
                            // } else {
                            //     // If data is blank, default to the first option (NONE)
                            //     return selectOptions;
                            // }
                        } else {
                            // For non-display type or when data is not available
                            return data;
                        }
                    }},//4
                {"data": "is_member"},//5
                {"data": "purok_rv"},//6
                {"data": "sqn"},//7
                {"data": "Municipality"} //8
            ],
            "columnDefs": [
                {"className": "text-center", "targets": [0,2,3,4,5]},
                {"targets": [8], "visible": false, "searchable": true }
            ],
            "fnRowCallback": function( row, data, index ) {
                if  (data.Name  === data.HL) {
                    $(row).addClass('green');
                }
            },
            "footerCallback": function (row, data, start, end, display) {
                // Count the number of rows with Name equal to HL
                var countHL = data.reduce(function (count, row) {
                    return count + (row.Name === row.HL ? 1 : 0);
                }, 0);

                // Update the footer
                $(this.api().column(1).footer()).html('HL Count: ' + countHL);
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
        cvrec.column(8).search(selectMuncit).draw();
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
        cvrec.column(2).search( selectBrgy).draw();
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

});
</script>

@include('layouts.script')
@include('layouts.footer')
@endsection
