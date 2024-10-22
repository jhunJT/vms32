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
                                    <div class="col-5">
                                        <input type="text" class="form-control" placeholder="Search..." id="customSearch" name="customSearch">
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
                                <th>Status</th>
                                <th>CV</th>
                                <th style="width: 140px;">Level</th>
                                <th style="width: 150px;">School</th>
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
            "language":{
               'processing': '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span>'
            },
            "ordering": true,
            "autoWidth" : true,
            "lengthMenu": [[15,25,50, -1], [15,25,50, "All"]],
            "dom": 'lrt',
            "pageLength": 15,
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('match-depedemployees') }}",
            "columns": [
                {"data": "id_main", "className": "text-center align-middle"}, //0
                {"data": "Name", "className": "align-middle",
                    render: function (data, type, row, meta) {
                    var prefix = "HL: ";
                        if(data == row.HL){
                            return prefix + data;
                        }
                            return data;}}, //1
                {"data": "Municipality", "className": "text-center align-middle"}, //2
                {"data": "Barangay", "className": "text-center align-middle"}, //3
                {"data": "is_depedEmployee", "className": "text-center align-middle",
                    "defaultContent": '',
                    "render": function (data, type, row, meta)
                    {
                        if(data){
                            return '<button type="button" class="btn btn-danger btn-rounded waves-effect waves-light">Supporter</button>'
                        }else{
                            return '<button type="button" class="btn btn-warning btn-rounded waves-effect waves-light">No Supporter</button>'
                        }
                        return '';
                    }
                },//4
                {"data": "survey_stat", "className": "text-center align-middle",
                    "defaultContent": '',
                    "render": function (data, type, row, meta)
                    {
                        if(data){
                            return '<button type="button" class="btn btn-danger btn-rounded waves-effect waves-light">YES</button>'
                        }else{
                            return '<button type="button" class="btn btn-warning btn-rounded waves-effect waves-light">NO</button>'
                        }
                        return '';
                    }
                },//5
                { "data": "level", "className": "text-center align-middle",
                    "defaultContent": '',
                    "render": function (data, type, row, meta) {
                        // console.log(row);
                        if (type === 'display') {
                            var selectOptions = '<select class="form-select btnLevel" style="width: 140px;" data-id="">';
                            selectOptions += '<option selected>NONE</option>';
                            selectOptions += '<option value="PRIMARY">PRIMARY</option>';
                            selectOptions += '<option value="SECONDARY">SECONDARY</option>';
                            selectOptions += '</select>';

                            if (data) {
                                return selectOptions.replace('value="' + data + '"', 'value="' + data + '" selected');
                            } else {
                                return selectOptions;
                            }
                        } else {
                            return data;
                        }
                    }
                }, //6
                {"data": "school", "className": "text-center align-middle", "searchable": false, "orderable": false,
                    "defaultContent": '',
                    "render": function (data, type, row, meta) {
                        if (type === 'display') {
                            var selectOptions = '<select class="form-select btnSchool" style="width: 150px;">';
                            selectOptions += '<option selected>NONE</option>';
                            selectOptions += '<option value="ALMAGRO">Almagro</option>';
                            selectOptions += '<option value="GANDARA I">GANDARA I</option>';
                            selectOptions += '<option value="GANDARA II">GANDARA II</option>';
                            selectOptions += '<option value="PAGSANGHAN">PAGSANGHAN</option>';
                            selectOptions += '<option value="SAN JORGE">SAN JORGE</option>';
                            selectOptions += '<option value="STA. MARGARITA I">STA. MARGARITA I</option>';
                            selectOptions += '<option value="STA. MARGARITA II">STA. MARGARITA II</option>';
                            selectOptions += '<option value="STO. NINO">STO. NINO</option>';
                            selectOptions += '<option value="TAGAPUL-AN">TAGAPUL-AN</option>';
                            selectOptions += '</select>';

                            if (data) {
                                // If data is available, select the appropriate option
                                return selectOptions.replace('value="' + data + '"', 'value="' + data + '" selected');
                            } else {
                                // If data is blank, default to the first option (NONE)
                                return selectOptions;
                            }
                        } else {
                            // For non-display type or when data is not available
                            return data;
                        }
                    }
                 }, //7
                {"data": "action", "className": "text-center align-middle"}, //8
                {"data": "HL"},//9
                {"data": "district", "visible": false, "searchable": true}//10
            ],
            "columnDefs": [
                {"className": "text-center", "targets": [0,2,3,4,5]},
                {"targets": [9], "visible": false, "searchable": false },
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

    $('.select2-dist').on('change', function(){
        $('#select2-muncit').val(null).trigger('change');
        $('#select2-brgy').val(null).trigger('change');
    });

    $('.select2-muncit').on('change', function(){
        $('#select2-brgy').trigger('change');
    });

    $('#dist').select2({
        placeholder: "Select District",
        minimumResultsForSearch: -1,
        allowClear: true
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

    $('#dist').on('change', function(){
        var selectDist = $('#dist').val();
        cvrec.column(8).search('^' + selectDist + '$', true, false).draw();
    });

    $('#dist').on('select2:clear', function(){
        cvrec.ajax.reload();
    });

    $('#select2-muncit').on('change', function(){
        var selectDist = $('#dist').val();
        var selectMuncit = $(this).val();

        console.log(selectDist,selectMuncit);

        if(selectMuncit){
            cvrec.column(2).search('^' + selectMuncit + '$', true, false).draw();
        }else{
            cvrec.column(2).search('');
        }

        if (selectDist) {
            cvrec.search(selectDist);
        } else {
            cvrec.search('');
        };
        cvrec.draw();
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

    $(document).on('click', '.depEmp', function(){
        const dataId = $(this).data('id');
        Swal.fire({
            title:"Are you sure?",
            text:"You won't be able to revert this!",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#1cbb8c",
            cancelButtonColor:"#f32f53",
            confirmButtonText:"Yes, submit it!"
        }).then((result) => {
            if (result.isConfirmed){
                $.ajax({
                    url: '{{ route("cvrecord.employeeSave") }}' ,
                    method: 'post',
                    data: {dataId:dataId},
                    success:function(res){
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Your work has been saved",
                            showConfirmButton: false,
                            timer: 1500
                            }
                        )
                        cvrec.ajax.reload();

                    },
                    error: function(xhr, status, error){
                        console.log(error);
                        if(error) {
                            var err = eval("(" + xhr.responseText + ")");
                            toastr.error(err.message);
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.notSupp', function(){

        const dataId = $(this).data('id');
        Swal.fire({
            title:"Are you sure?",
            text:"You won't be able to revert this!",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#1cbb8c",
            cancelButtonColor:"#f32f53",
            confirmButtonText:"Yes, submit it!"
        }).then((result) => {
            if (result.isConfirmed){
                $.ajax({
                    url: '{{ route("cvrecord.notsupporterSave") }}' ,
                    method: 'post',
                    data: {dataId:dataId},
                    success:function(res){
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Your work has been saved",
                            showConfirmButton: false,
                            timer: 1500
                            }
                        )
                        cvrec.ajax.reload();

                    },
                    error: function(xhr, status, error){
                        console.log(error);
                        if(error) {
                            var err = eval("(" + xhr.responseText + ")");
                            toastr.error(err.message);
                        }
                    }
                });
            }
        });
    });

    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function customSearch(value) {
        cvrec.column(1).search(value).draw();
    }

    $('#customSearch').on('input', debounce(function() {
        customSearch(this.value);
    }, 300));

    $(document).on('change','.btnLevel', function(){
        const dataId = $(this).data('id');
        var selectedLevelVal = $(this).val();
        $.ajax({
            url: '{{ route("cvrecord.levelSave") }}' ,
            method: 'post',
            data: {dataId:dataId, selectedLevelVal:selectedLevelVal},
            success:function(res){
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Your work has been saved",
                    showConfirmButton: false,
                    timer: 1500
                    }
                )
                cvrec.ajax.reload();

            },
            error: function(xhr, status, error){
                console.log(error);
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
