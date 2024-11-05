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
                                    <div class="col-6">
                                        <select name="school" id="sschool" style="width: 100%;" class="form-select"></select>
                                    </div>
                                    <div class="col-3">
                                        <select id="select2-level-filter" style="width: 100%;" class="form-control ssfilter"  name="select2-level-filter" >
                                            <option disabled selected>Filter Level</option>
                                            <option value="PRIMARY">PRIMARY</option>
                                            <option value="SECONDARY">SECONDARY</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <select name="status" id="sstatus[]" style="width: 100%;" class="form-select sstatus" data-placeholder="Select Status" multiple>
                                            <option value="1">SUPPORTER</option>
                                            <option value="3">NOT FOUND</option>
                                            <option value="2">NOT SUPPORTER</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id="cvrectblview" class="table table-bordered nowrap cvrectbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead >
                            <tr>
                                <th>#</th>
                                <th style="width:2rem;">Name</th>
                                <th style="width:15rem;">Municipality</th>
                                <th style="width:15rem;">Barangay</th>
                                <th>Status</th>
                                <th>CV</th>
                                <th style="width: 140px;">Level</th>
                                <th style="width: 180px;">School</th>
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

<div id="depedEmployeeCount" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="hlsumm" class="table table-bordered dt-responsive nowrap grantTbl" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th style="width:15rem;">Name</th>
                        <th>Barangay</th>
                        <th>School  </th>
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

    var cvrecview = $('#cvrectblview').DataTable({
        "language":{
            'processing': '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span>'
        },
        // "ordering": true,
        "autoWidth" : false,
        "scrollY": true,
        "paging": false,
        "lengthMenu": [[15,25,50, -1], [15,25,50, "All"]],
        // "dom": 'lrt',
        "processing": true,
        "data": [],
        "columns": [
            {"data": "id_main", "className": "text-center align-middle",
            render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;}}, //0
            {"data": "Name", "className": "align-middle"}, //1
            {"data": "Municipality", "className": "text-center align-middle"}, //2
            {"data": "Barangay", "className": "text-center align-middle"}, //3
            {"data": "is_depedEmployee", "className": "text-center align-middle",
                "defaultContent": '',
                "render": function (data, type, row, meta)
                {
                    if(data == 1){
                        return '<button type="button" class="btn btn-danger btn-rounded waves-effect waves-light">Supporter</button>'
                    }else if(data == 2){
                        return '<button type="button" class="btn btn-warning btn-rounded waves-effect waves-light">Not Supporter</button>'
                    }else{
                        return '<button type="button" class="btn btn-info btn-rounded waves-effect waves-light">Manual Add</button>'
                    }
                    return data;
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
            { "data": "level", "className": "text-center align-middle","searchable": true, "orderable": true,
                "defaultContent": '',
                "render": function (data, type, row, meta) {
                    if(data === "PRIMARY"){
                        return '<button type="button" class="btn btn-primary btn-rounded waves-effect waves-light">PRIMARY</button>'
                        }else if(data === "SECONDARY"){
                            return '<button type="button" class="btn btn-secondary btn-rounded waves-effect waves-light">SECONDARY</button>'
                        }else{
                            return ''
                        }
                    return data;
                }
            }, //6
            {"data": "school", "className": "align-middle","visible": false, "searchable": true, "orderable": false}, //7
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
        "drawCallback": function () {
            // Reinitialize Select2 for any newly drawn rows
            $('.btnSchool').select2({
                width: '100%'
            });

            $('.btnLevel').on('change', function() {
            var rowId = $(this).data('id');  // Get row id (from the data-id attribute)
            var selectedLevel = $(this).val();

            // Find the corresponding school select
            var schoolSelect = $('.btnSchool[data-id="' + rowId + '"]');
            schoolSelect.empty();  // Clear existing options

            if (selectedLevel === 'SECONDARY') {
                // Add Secondary school options
                const secondarySchools = [
                    { id: 'Bagacay National High School', text: 'Bagacay National High School - Daram I' },
                    { id: 'Daram National High School', text: 'Daram National High School - Daram I' }
                ];

                secondarySchools.forEach(function(school) {
                    var newOption = new Option(school.text, school.id, false, false);
                    schoolSelect.append(newOption);
                });
            } else if (selectedLevel === 'PRIMARY') {
                // Add Primary options (if needed)
                schoolSelect.append('<option value="">Select a schoolss</option>');
            }

            // Refresh the Select2 dropdown
            schoolSelect.trigger('change');
        });


        }
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

    $('#filterSupp').on('click', function(){
        var filtSupp = $(this).val();
        console.log(filtSupp);
        // if(filtSupp == "Supporter"){
        //     cvrec.coulmn(4).search().draw();
        // }

        // var filtSupp = []
        // $.each($('#filterSupp'), function(i,elem){
        //     filtSupp.push($(this).val())
        // })
        // grantTbl.column(4).search(filtSupp).draw();
    });

    $(document).on('click', '.depEmp', function(){
        const dataId = $(this).data('id');
        var $row = $(this).closest('tr');

        var school = $row.find('.btnSchool').val();
        var level = $row.find('.btnLevel').val();
        // console.log(school,level);

        if(school === "NONE" || level === "NONE"){
            Swal.fire({
                icon: "error",
                title: "Oops!Something went wrong!",
                text: "Please select LEVEL and SCHOOL",
            });
        }
        else{
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
                        data: {dataId:dataId,school:school, level:level},
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
        }


    });

    $(document).on('click', '.notSupp', function(){

        const dataId = $(this).data('id');
        var $row = $(this).closest('tr');

        var school = $row.find('.btnSchool').val();
        var level = $row.find('.btnLevel').val();
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
                    data: {dataId:dataId,school:school,level:level},
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

    $('.sstatus').select2({
        allowClear: true,
        placeholder:  function(){
            $(this).data('placeholder')
        },
        dropdownParent: $('#offcanvasRight'),
    });

    $('#sschool').select2({
        placeholder: "Select School/District",
        allowClear: true,
        ajax:{
            url:"{{ route('cvrecord.schoolList') }}",
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

    $('#sschool').on('change', function() {
        var selectSchool = [];
        $.each($('#sschool'), function(i,elem){
            selectSchool.push($(this).val())
        });
        loadReloadData();
        // cvrecview.column(7).search('^' + selectSchool + '$', true, false).draw();
    });

    $('.ssfilter').select2({
        allowClear: true,
        placeholder: "Filter Level"
    });

    $('.ssfilter').on('change', function(){
        var flevel = [];
        $.each($('.ssfilter'), function(i, elem){
            flevel.push($(this).val());
        });

        // Apply the filter to the specific column (in this case, column 6)
        cvrecview.column(6).search(flevel.join('|'), true, false).draw();

        // Get the total number of filtered records across all pages
        var filteredDataCount = cvrecview.rows({ search: 'applied' }).count();

        // Show the result in a SweetAlert modal
        Swal.fire({
            title: 'Total Filtered Data',
            text: 'Total: ' + filteredDataCount,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    });

    function loadReloadData() {
        const selectSchool = $('#sschool').val();
        if(selectSchool)
        {
            $.ajax({
                url: "{{ route('cvrecord.loadDistrictDataView') }}",
                data: { selectSchool: selectSchool },
                method: 'GET',
                success: function(response) {
                    cvrecview.clear().rows.add(response.data).draw();
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "An error occurred while loading data. Please try again."
                    });
                }
            });
        }
    }





});

</script>

@include('layouts.script')
@include('layouts.footer')
@endsection
