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
                                        <input type="text" class="form-control" placeholder="Search..." id="customSearch" name="customSearch" >
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-2 mt-3">
                                        <div class="btn-group" role="group">
                                            <button type="submit" class="btn btn-success offcanvastb"  data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" id="filterSupp" value="Supporter"><i class="fas fa-user-alt"></i> Show Summary</button>
                                            <button type="submit" class="btn btn-warning" id="filterSupp" value="Not Supporter"><i class="fas fa-user-alt-slash"></i></button>
                                        </div>
                                        </div>
                                        {{-- <div class="col-4 mt-3">
                                            <select id="select2-school-filter" style="width: 100%;" class="form-control" name="select2-school-filter"></select>
                                        </div>
                                        <div class="col-3 mt-4 align-middle">
                                            <h3>Total Supporter:<span> 123</span></h3>
                                        </div>
                                        <div class="col-3 mt-4 align-middle">
                                            <h3>Total Not Supporter:<span> 123</span></h3>
                                        </div> --}}
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
                                <th style="width: 180px;">School</th>
                                <th>Tag</th>
                                <th>Houseleader</th>
                                <th>District</th>
                                <th>School2</th>
                                <th>status</th>
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" aria-modal="true" role="dialog">
    <div class="offcanvas-header">
      <h5 id="offcanvasRightLabel">SUMMARY</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-12">
                <select name="status" id="sstatus" class="form-select">
                    <option disabled selected>Select Status</option>
                    <option value="1">SUPPORTER</option>
                    <option value="2">NOT SUPPORTER</option>
                </select>
            </div>
            <div class="col-12 mt-2">
                <select name="school" id="sschool" class="form-select"></select>
                <hr>
            </div>
            <table class="table nowrap" id="teacherSumm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>is_depEmp</th>
                        <th>CV</th>
                        <th>school</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
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
            "order": [[12,'asc']],
            "autoWidth" : true,
            "lengthMenu": [[15,25,50, -1], [15,25,50, "All"]],
            "dom": 'lrt',
            "pageLength": 15,
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('match-depedemployees') }}",
            "columns": [
                {"data": "id_main", "className": "text-center align-middle"}, //0
                {"data": "Name", "className": "align-middle"}, //1
                {"data": "Municipality", "className": "text-center align-middle"}, //2
                {"data": "Barangay", "className": "text-center align-middle"}, //3
                {"data": "is_depedEmployee", "className": "text-center align-middle", "orderable":true,
                    "defaultContent": '',
                    "render": function (data, type, row, meta)
                    {
                        if(data == 1){
                            return '<button type="button" class="btn btn-danger btn-rounded waves-effect waves-light">Supporter</button>'
                        }else if(data == 2){
                            return '<button type="button" class="btn btn-warning btn-rounded waves-effect waves-light">Not Supporter</button>'
                        }else{
                            return ''
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
                        // console.log(row);
                        if (type === 'display') {
                            var selectOptions = '<select class="form-select btnLevel" style="width: 140px;" data-id="">';
                            selectOptions += '<option value="NONE" selected>NONE</option>';
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
                {"data": "school", "className": "align-middle", "searchable": false, "orderable": true,
                    "defaultContent": '',
                    "render": function (data, type, row, meta) {
                        if (type === 'display') {
                            var selectOptions = '<select class="form-select btnSchool" style="width: 180px;">';
                            selectOptions += '<option selected>NONE</option>';
                            selectOptions += '<option value="ALMAGRO">Almagro</option>';
                            selectOptions += '<option value="CALBAYOG CITY">CALBAYOG CITY</option>';
                            selectOptions += '<option value="GANDARA I">GANDARA I</option>';
                            selectOptions += '<option value="GANDARA II">GANDARA II</option>';
                            selectOptions += '<option value="PAGSANGHAN">PAGSANGHAN</option>';
                            selectOptions += '<option value="SAN JORGE">SAN JORGE</option>';
                            selectOptions += '<option value="STA. MARGARITA I">STA. MARGARITA I</option>';
                            selectOptions += '<option value="STA. MARGARITA II">STA. MARGARITA II</option>';
                            selectOptions += '<option value="STO. NINO">STO. NINO</option>';
                            selectOptions += '<option value="TARANGNAN">TARANGNAN</option>';
                            selectOptions += '<option value="TAGAPUL-AN">TAGAPUL-AN</option>';
                            selectOptions += '<option value="FUN AND LEARN SCHOOL INC">FUN AND LEARN SCHOOL INC</option>';
                            selectOptions += '<option value="CATB DIV-1">CATB DIV-1</option>';
                            selectOptions += '<option value="CATB DIV-2">CATB DIV-2</option>';
                            selectOptions += '<option value="CATB DIV-3">CATB DIV-3</option>';
                            selectOptions += '<option value="CATB DIV-4">CATB DIV-4</option>';
                            selectOptions += '<option value="CATB DIV-5">CATB DIV-5</option>';
                            selectOptions += '<option value="CATB DIV-6">CATB DIV-6</option>';
                            selectOptions += '<option value="CATB DIV-7">CATB DIV-7</option>';
                            selectOptions += '<option value="CATB DIV-8">CATB DIV-8</option>';
                            selectOptions += '<option value="CATB DIV-9">CATB DIV-9</option>';
                            selectOptions += '<option value="CATB DIV-10">CATB DIV-10</option>';
                            selectOptions += '<option value="CCS">CCS</option>';
                            selectOptions += '<option value="DORCAS">DORCAS</option>';
                            selectOptions += '<option value="SMCDC">SMCDC</option>';
                            selectOptions += '<option value="Samar College">Samar College</option>';
                            selectOptions += '<option value="Samar Division Office">Samar Division Office</option>';
                            selectOptions += '<option value="Almagro National High School">Almagro National High School</option>';
                            selectOptions += '<option value="Anibongon Integrated School">Anibongon Integrated School</option>';
                            selectOptions += '<option value="Apolonia Integrated School">Apolonia Integrated School</option>';
                            selectOptions += '<option value="Astorga Integrated School">Astorga Integrated School</option>';
                            selectOptions += '<option value="Babaclayon Integrated School">Babaclayon Integrated School</option>';
                            selectOptions += '<option value="Baclayan National High School">Baclayan National High School</option>';
                            selectOptions += '<option value="Bagacay National High School">Bagacay National High School</option>';
                            selectOptions += '<option value="Bakhaw National High School">Bakhaw National High School</option>';
                            selectOptions += '<option value="Balocawe Integrated School">Balocawe Integrated School</option>';
                            selectOptions += '<option value="Baquiw National High School">Baquiw National High School</option>';
                            selectOptions += '<option value="Baras National High School">Baras National High School</option>';
                            selectOptions += '<option value="Basey National High School">Basey National High School</option>';
                            selectOptions += '<option value="Bioso Integrated School">Bioso Integrated School</option>';
                            selectOptions += '<option value="Bonga National High School">Bonga National High School</option>';
                            selectOptions += '<option value="Buao Integrated School">Buao Integrated School</option>';
                            selectOptions += '<option value="Buenos Aires Integrated School">Buenos Aires Integrated School</option>';
                            selectOptions += '<option value="Cabiton-An National High School">Cabiton-An National High School</option>';
                            selectOptions += '<option value="Cabunga-An Integrated School">Cabunga-An Integrated School</option>';
                            selectOptions += '<option value="Calapi National High School">Calapi National High School</option>';
                            selectOptions += '<option value="Calbiga National High School">Calbiga National High School</option>';
                            selectOptions += '<option value="Camayse Integrated School">Camayse Integrated School</option>';
                            selectOptions += '<option value="Caparangasan Integrated School">Caparangasan Integrated School</option>';
                            selectOptions += '<option value="Casapa National High School">Casapa National High School</option>';
                            selectOptions += '<option value="Clarencio Calagos Memorial School Of Fisheries">Clarencio Calagos Memorial School Of Fisheries</option>';
                            selectOptions += '<option value="Curry National High School">Curry National High School</option>';
                            selectOptions += '<option value="Dampigan National High School">Dampigan National High School</option>';
                            selectOptions += '<option value="Daram National High School">Daram National High School</option>';
                            selectOptions += '<option value="Guintarcan National High School">Guintarcan National High School</option>';
                            selectOptions += '<option value="Hampton Integrated School">Hampton Integrated School</option>';
                            selectOptions += '<option value="Hinabangan National High School">Hinabangan National High School</option>';
                            selectOptions += '<option value="Hinangutdan National High School">Hinangutdan National High School</option>';
                            selectOptions += '<option value="Jiabong National High School">Jiabong National High School</option>';
                            selectOptions += '<option value="Kerikite Integrated School">Kerikite Integrated School</option>';
                            selectOptions += '<option value="Lamingao National High School">Lamingao National High School</option>';
                            selectOptions += '<option value="Lokilokon Integrated School">Lokilokon Integrated School</option>';
                            selectOptions += '<option value="Mabini National High School">Mabini National High School</option>';
                            selectOptions += '<option value="Majacob Integrated School">Majacob Integrated School</option>';
                            selectOptions += '<option value="Marabut National High School">Marabut National High School</option>';
                            selectOptions += '<option value="Matuguinao National High School">Matuguinao National High School</option>';
                            selectOptions += '<option value="Motiong National High School">Motiong National High School</option>';
                            selectOptions += '<option value="Napuro National High School">Napuro National High School</option>';
                            selectOptions += '<option value="Oeste National High School">Oeste National High School</option>';
                            selectOptions += '<option value="Old San Agustin National High School">Old San Agustin National High School</option>';
                            selectOptions += '<option value="Osmena National High School">Osmena National High School</option>';
                            selectOptions += '<option value="Pagsanghan National High School">Pagsanghan National High School</option>';
                            selectOptions += '<option value="Parasan National High School">Parasan National High School</option>';
                            selectOptions += '<option value="Parasanon National High School">Parasanon National High School</option>';
                            selectOptions += '<option value="Patong National High School">Patong National High School</option>';
                            selectOptions += '<option value="Pinabacdao National High School">Pinabacdao National High School</option>';
                            selectOptions += '<option value="Pinaplata Integrated School">Pinaplata Integrated School</option>';
                            selectOptions += '<option value="Primitivo T. Torrechiva National High School">Primitivo T. Torrechiva National High School</option>';
                            selectOptions += '<option value="Quintin Quijano Sr. Agricultural School">Quintin Quijano Sr. Agricultural School</option>';
                            selectOptions += '<option value="Ramon T. Diaz National High School">Ramon T. Diaz National High School</option>';
                            selectOptions += '<option value="Rizal Integrated School">Rizal Integrated School</option>';
                            selectOptions += '<option value="Sabang Integrated School">Sabang Integrated School</option>';
                            selectOptions += '<option value="San Andres National High School">San Andres National High School</option>';
                            selectOptions += '<option value="San Fernando National High School">San Fernando National High School</option>';
                            selectOptions += '<option value="San Isidro National High School">San Isidro National High School</option>';
                            selectOptions += '<option value="San Jorge National High School">San Jorge National High School</option>';
                            selectOptions += '<option value="San Jose De Buan National High School">San Jose De Buan National High School</option>';
                            selectOptions += '<option value="San Sebastian National High School">San Sebastian National High School</option>';
                            selectOptions += '<option value="Sevilla Integrated School">Sevilla Integrated School</option>';
                            selectOptions += '<option value="Simeon Ocdol National High School">Simeon Ocdol National High School</option>';
                            selectOptions += '<option value="Sta. Elena Integrated School">Sta. Elena Integrated School</option>';
                            selectOptions += '<option value="Sta. Margarita National High School">Sta. Margarita National High School</option>';
                            selectOptions += '<option value="Sta. Rita National High School">Sta. Rita National High School</option>';
                            selectOptions += '<option value="Sto. Nino Integrated School">Sto. Nino Integrated School</option>';
                            selectOptions += '<option value="Sto. Nino National High School">Sto. Nino National High School</option>';
                            selectOptions += '<option value="Sua National High School">Sua National High School</option>';
                            selectOptions += '<option value="Tagapul-An National High School">Tagapul-An National High School</option>';
                            selectOptions += '<option value="Talalora National High School">Talalora National High School</option>';
                            selectOptions += '<option value="Tarangnan National High School">Tarangnan National High School</option>';
                            selectOptions += '<option value="Tatabunan Integrated School">Tatabunan Integrated School</option>';
                            selectOptions += '<option value="Tizon National High School">Tizon National High School</option>';
                            selectOptions += '<option value="Tominamos Integrated School">Tominamos Integrated School</option>';
                            selectOptions += '<option value="Valeriano C. Yancha Mas">Valeriano C. Yancha Mas</option>';
                            selectOptions += '<option value="Villahermosa Integrated School">Villahermosa Integrated School</option>';
                            selectOptions += '<option value="Villahermosa National High School">Villahermosa National High School</option>';
                            selectOptions += '<option value="Villareal National High School">Villareal National High School</option>';
                            selectOptions += '<option value="Wright National High School">Wright National High School</option>';
                            selectOptions += '<option value="Zumarraga National High School">Zumarraga National High School</option>';
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
                    },
                    "createdCell": function (td, cellData, rowData, row, col) {
                        // Initialize Select2 after the cell is created
                        $(td).find('.btnSchool').select2({
                            width: '100%',  // Customize width or other options
                            tags: true,
                        });
                    }
                 }, //7
                {"data": "action", "className": "text-center align-middle"}, //8
                {"data": "HL"},//9
                {"data": "district", "visible": false, "searchable": true},//10
                {"data": "school", "visible": false, "searchable": true }, //11
                {"data": "is_depedEmployee", "visible": false, "searchable": true } //12
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
            // "drawCallback": function () {
            //     // Reinitialize Select2 for any newly drawn rows
            //     $('.btnSchool').select2({
            //         width: '100%'
            //     });

            //     $('.btnLevel').on('change', function() {
            //     var rowId = $(this).data('id');  // Get row id (from the data-id attribute)
            //     var selectedLevel = $(this).val();

            //     // Find the corresponding school select
            //     var schoolSelect = $('.btnSchool[data-id="' + rowId + '"]');
            //     schoolSelect.empty();  // Clear existing options

            //     if (selectedLevel === 'SECONDARY') {
            //         // Add Secondary school options
            //         const secondarySchools = [
            //             { id: 'Bagacay National High School', text: 'Bagacay National High School - Daram I' },
            //             { id: 'Daram National High School', text: 'Daram National High School - Daram I' }
            //         ];

            //         secondarySchools.forEach(function(school) {
            //             var newOption = new Option(school.text, school.id, false, false);
            //             schoolSelect.append(newOption);
            //         });
            //     } else if (selectedLevel === 'PRIMARY') {
            //         // Add Primary options (if needed)
            //         schoolSelect.append('<option value="">Select a schoolss</option>');
            //     }

            //     // Refresh the Select2 dropdown
            //     schoolSelect.trigger('change');
            // });


            // }
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

        // console.log(selectDist,selectMuncit);

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
        // console.log(filtSupp);
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

        if(school === "NONE" || level === "NONE"){
            Swal.fire({
                icon: "error",
                title: "Oops!Something went wrong!",
                text: "Please select LEVEL and SCHOOL",
            });
        }else{
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
        }


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

    // const secondarySchools = [
    //     { id: 'Bagacay National High School', text: 'Bagacay National High School - Daram I' },
    //     { id: 'Daram National High School', text: 'Daram National High School - Daram I' }
    // ];

    // $('.btnLevel').off('change').on('change', function() {
    //     var rowId = $(this).data('id');
    //     var selectedLevel = $(this).val();

    //     // Find the matching school select for the same row
    //     var schoolSelect = $('.btnSchool[data-id="' + rowId + '"]');

    //     // Clear the current options in the school select
    //     schoolSelect.empty();

    //     if (selectedLevel === 'SECONDARY') {
    //         // Populate secondary schools
    //         const secondarySchools = [
    //             { id: 'Bagacay National High School', text: 'Bagacay National High School - Daram I' },
    //             { id: 'Daram National High School', text: 'Daram National High School - Daram I' }
    //         ];

    //         secondarySchools.forEach(function(school) {
    //             var newOption = new Option(school.text, school.id, false, false);
    //             schoolSelect.append(newOption);
    //         });
    //     } else if (selectedLevel === 'PRIMARY') {
    //         // You can also add primary schools or leave it blank.
    //         schoolSelect.append('<option value="">Select a school</option>');
    //     }

    //     // Trigger Select2 to refresh
    //     schoolSelect.trigger('change');
    // });



    // $('.btnLevel').change(function() {
    //     const selectedLevel = $(this).val();
    //     console.log(selectedLevel);
    //     $('.btnSchool').empty(); // Clear current options

    //     if (selectedLevel === 'secondary') {
    //         // Add Secondary options
    //         secondarySchools.forEach(function(school) {
    //             const newOption = new Option(school.text, school.id, false, false);
    //             $('.btnSchool').append(newOption);
    //         });
    //     } else {
    //         // Default or primary options (if needed)
    //         $('.btnSchool').append('<option value="">Select a school</option>');
    //     }

    //     // Refresh Select2
    //     $('.btnSchool').trigger('change');
    // });

    $(document).on('click','.depClear', function(){
        const dataId = $(this).data('id');
        $.ajax({
            url: '{{ route("cvrecord.depclear") }}' ,
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
    });

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    $('#select2-school-filter').select2({
        placeholder: "Select School",
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

    $('#select2-school-filter').on('change', function() {
        var selectSchool = $(this).val();

        // Show loading indicator
        $('#loading').show();

        // Reset search to show all entries before applying the new filter
        cvrec.search('').columns().search('').draw();

        // Apply new filter based on selected school
        if (selectSchool) {
            cvrec.column(11).search('^' + selectSchool + '$', true, false);
        }

        cvrec.draw();

        // Count entries in column 12 where the value is 1
        var count1 = cvrec.column(12).search(1).rows({ search: '1' }).count();
        console.log(count1);

        // Hide loading indicator after update
        $('#loading').hide();
    })

    $('#sstatus').select2({
        placeholder: "Select Status",
        dropdownParent: $('#offcanvasRight'),
        minimumResultsForSearch: -1
    });

    $('#sschool').select2({
        placeholder: "Select School/District",
        allowClear: true,
        dropdownParent: $('#offcanvasRight'),
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


// Trigger DataTable load when school filter is selected
let teachRecords = $('#teacherSumm').DataTable({
    scrollY: true,
    searching: false,
    paging: false,
    ordering: false,
    dom: 'rtip',
    data: [], // Initially empty
    columns: [
        { "data": "Name", "className": "align-middle" },
        { "data": "is_depedEmployee", "visible": false, "searchable": true },
        { "data": "survey_stat", "visible": false, "searchable": true },
        { "data": "school", "visible": false, "searchable": true }
    ]
});

// Trigger DataTable load when school filter is selected
    // $('#sschool').on('change', function() {
    // const selectStatus = $('#sstatus').val();
    // const selectSchool = $(this).val();

    // // Check if Status is selected
    // if (!selectStatus) {
    //     Swal.fire({
    //         icon: "error",
    //         title: "Oops...",
    //         text: "Please select Status!"
    //     });
    //     return;
    // }

    // // Fetch data based on selected filters and load into DataTable
    // $.ajax({

    //     data: { selectStatus: selectStatus, selectSchool: selectSchool },
    //     method: 'GET',
    //     success: function(response) {
    //         // Clear existing data and add new data to the DataTable
    //         teachRecords.clear().rows.add(response.data).draw();
    //     },
    //     error: function(xhr, status, error) {
    //         Swal.fire({
    //             icon: "error",
    //             title: "Error",
    //             text: "An error occurred while loading data. Please try again."
    //         });
    //     }
    // });
    // });
    // $('#offcanvasRight').on('shown.bs.offcanvas', function() {

    // });
});
</script>

@include('layouts.script')
@include('layouts.footer')
@endsection
