@extends('layouts.auth')
@section('content')
<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0"> Command Vote Records - 2022 NLE</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">Data</li>
                            <li class="breadcrumb-item active"><a href="javascript: void(0);">DEPED Employees Matching</a></li>
                        </ol>
                    </div>
                    {{-- <button onclick="printTable()">Print Table</button> --}}
                </div>
                <div class="card" id="refreshTB">
                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2">
                                        <select id="dist" class="form-control select2-dist" name="dist">
                                            <option selected disabled>Select District</option>
                                            <option value="District I">District I</option>
                                            <option value="District II">District II</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <select id="select2-muncit" class="form-control select2-muncit" name="select2-muncit"></select>
                                    </div>
                                    <div class="col-3">
                                        <select id="select2-brgy" style="width: 100%;" class="form-control select2-brgy" name="select2-brgy"></select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id="cvrectbl" class="table table-bordered nowrap cvrectbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead >
                            <tr>
                                <th>#</th>
                                <th style="width:2rem;">Name</th>
                                <th style="width:15rem;">Municipality</th>
                                <th style="width:15rem;">Barangay</th>
                                <th>Purok</th>
                                <th>Status</th>
                                <th>Tag</th>
                                <th>Houseleader</th>
                                <th>District</th>
                            </tr>
                            </thead>
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
            "ordering": true,
            // "order": [[8, 'asc'],[2, 'asc'],[7, 'asc']],
            "autoWidth" : true,
            "lengthMenu": [[15,25,50, -1], [15,25,50, "All"]],
            // "dom": '<"dt-top-container"<l><"dt-center-in-div"B><f>r>t<"dt-filter-spacer"f><ip>',
            "pageLength": 15,
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('match-depedemployees') }}",
            "columns": [
                {"data": "id_main"}, //0
                {"data": "Name",
                    render: function (data, type, row, meta) {
                    var prefix = "HL: ";
                        if(data == row.HL){
                            return prefix + data;
                        }
                            return data;}}, //1
                {"data": "Municipality"}, //2
                {"data": "Barangay"}, //3
                {"data": "purok_rv"},//4
                {"data": "survey_stat",
                    "defaultContent": '',
                    "render": function (data, type, row, meta)
                    {
                        console.log(data);
                        if(data){
                            return '<button type="button" class="btn btn-danger btn-rounded waves-effect waves-light">Supporter</button>'
                        }else{
                            return '<button type="button" class="btn btn-warning btn-rounded waves-effect waves-light">Not Supporter</button>'
                        }
                        return '';
                    }
                },//5
                {"data": "action", "className": "text-center align-middle"}, //6
                {"data": "HL"},//7
                {"data": "district", "visible": false, "searchable": true}//8
            ],
            "columnDefs": [
                {"className": "text-center", "targets": [0,2,3,4,5]},
                {"targets": [7], "visible": false, "searchable": false },
                {"searchable": false, "targets": [0,4]}
            ],
            "fnRowCallback": function( row, data, index ) {
                if  (data.Name  === data.HL) {
                    $(row).addClass('green');
                }
            },
        });

    $('.dataTables_filter input[type="search"]').css(
        {'width':'350px','height':'40px', 'display':'inline-block'}
    );

    $('#dist').on('change', function(){
        var selectDist = [];
        $.each($('#dist'), function(i,elem){
            selectDist.push($(this).val())
        })
        cvrec.column(8).search('^' + selectDist + '$', true, false).draw();
    });

    $('.select2-dist').on('change', function(){
        $('#select2-muncit').val(null).trigger('change');
        $('#select2-brgy').val(null).trigger('change');
    });

    $('.select2-muncit').on('change', function(){
        $('#select2-brgy').trigger('change');
    });

    $('#select2-muncit').select2({
        placeholder: "Select Municipality/City",
        allowClear: true,
        ajax:{
            url:"{{ route('cvrecord.smuncit') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                dist = $('#dist').val();
                return{
                    search: params.term,
                    dist: dist
                };
            },
            processResults: function(data){
                // console.log(data);
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

    $('#select2-brgy').select2({
        placeholder: "Select Barangay",
        allowClear: true,
        ajax:{
            url:"{{ route('cvrecord.sbrgy') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                dist = $('#dist').val();
                muncit = $('#select2-muncit').val();
                return{
                    search: params.term,
                    dist: dist,
                    muncit:muncit
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

    $('#dist').select2({
        placeholder: "Select District",
        minimumResultsForSearch: -1,
        allowClear: true
    });

    $('#select2-brgy').on('change', function(e){
        var selectMuncit = $('#select2-muncit').val();
        var selectBrgy = $(this).val();

        if(selectBrgy){
            cvrec.column(3).search('^' + selectBrgy + '$', true, false).draw();
        }else{
            cvrec.column(3).search('');
        }

        if (selectMuncit) {
            cvrec.search(selectMuncit);
        } else {
            cvrec.search('');
        }
        cvrec.draw();
    });

    $('#select2-muncit').on('change', function(){
        // var selectDist = $('#dist').val();
        // var selectMuncit = $(this).val();

        // if(selectMuncit){
        //     cvrec.column(2).search('^' + selectMuncit + '$', true, false).draw();
        // }else{
        //     cvrec.column(2).search('');
        // }

        // if (selectDist) {
        //     cvrec.search(selectDist);
        // } else {
        //     cvrec.search('');
        // }
        // cvrec.draw();
    });

    $(document).on('click', '.depEdEmployee', function(){
        alert('hello world!')
    });
});
</script>

@include('layouts.script')
@include('layouts.footer')
@endsection
