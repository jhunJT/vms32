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
                                        <div class="input-group" >
                                            <select id="select2-brgy" style="width: 80%;" class="form-control select2-brgy" name="select2-brgy"></select>
                                            <span class="">
                                                <a class="btn btn-success searchdata"><i class="fas fa-search-location" style="width: 50px;"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <input type="text" class="form-control" placeholder="Search..." id="customSearch" name="customSearch" >
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-2 mt-3">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-success offcanvastb" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" id="filterSupp" value="Supporter"><i class="fas fa-user-alt"></i> Show Records</button>
                                            <button type="button" class="btn btn-info" id="notfound"><i class="fas fa-user-alt-slash"></i>Not Found</button>
                                        </div>
                                    </div>
                                        {{-- <div class="col-4 mt-3">
                                            <select id="select2-school-filter" style="width: 100%;" class="form-control" name="select2-school-filter"></select>
                                        </div>--}}
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" data-bs-scroll="false" data-bs-backdrop="false" aria-labelledby="offcanvasRightLabel" aria-modal="true" role="dialog" style="width: 1000px;">
    <div class="offcanvas-header">
      <h5 id="offcanvasRightLabel">TEACHER - RECORDS</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-12">
                <div class="col-12 mb-3">
                    <select name="school" id="sschool" style="width: 100%;" class="form-select"></select>
                </div>
                <div class="col-12 mb-3">
                    <select id="select2-level-filter" style="width: 100%;" class="form-control ssfilter"  name="select2-level-filter" >
                        <option disabled selected>Filter Level</option>
                        <option value="PRIMARY">PRIMARY</option>
                        <option value="SECONDARY">SECONDARY</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <select name="status" id="sstatus[]" style="width: 100%;" class="form-select sstatus" data-placeholder="Select Status" multiple>
                        <option value="1">SUPPORTER</option>
                        <option value="3">NOT FOUND</option>
                        <option value="2">NOT SUPPORTER</option>
                    </select>
                </div>
            </div>


            <div class="col-12">
                <table class="table nowrap table-hover teacherSumm" id="teacherSumm" >
                    <thead>
                        <tr>
                            <th style="width:5%;">#</th>
                            <th style="width:80%;">Name</th>
                            <th style="width:15%;">Status</th>
                            <th>CV</th>
                            <th>school</th>
                            <th>level</th>
                        </tr>
                    </thead>
                </table>
                <div id="totalRecords" style="padding: 10px; font-weight: bold;"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddmanual" tabindex="-1" aria-labelledby="modalAddmanualLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddmanualLabel">Add Not Found</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="manForm">
                        <div class="col-12">
                            <label for="addName">Fullname</label>
                            <input type="text" id="addName" name="fullname" class="form-control" placeholder="surename, firstname middle name -->Dela Cruz, Juan Dalisay">
                        </div>
                        <div class="col-12">
                            <label for="level">Level</label>
                            <select name="level" id="level" class="form-select">
                                <option selected disabled>Select level</option>
                                <option value="PRIMARY">PRIMARY</option>
                                <option value="SECONDARY">SECONDARY</option>
                                <option value="PRIVATE">PRIVATE</option>
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="school">School/School District</label>
                            <select type="text" id="ssschool" name="school" class="form-select"></select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addManualEMployee">Add record</button>
            </div>
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

    let cvrec = $('#cvrectbl').DataTable({
            "language":{
               'processing': '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span>'},
            "ordering": true,
            "order": [[12,'asc']],
            "autoWidth" : true,
            "lengthMenu": [[15,25,50, -1], [15,25,50, "All"]],
            "dom": 'lrt',
            "pageLength": 15,
            "processing": true,
            "data": [],
            "deferRender": true,
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
                            selectOptions += '<option value="PRIVATE">PRIVATE</option>';
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
                            selectOptions += '<option value="Almagro">Almagro</option>';
                            selectOptions += '<option value="Basey I">Basey I</option>';
                            selectOptions += '<option value="Basey II">Basey II</option>';
                            selectOptions += '<option value="Calbiga I">Calbiga I</option>';
                            selectOptions += '<option value="Calbiga II">Calbiga II</option>';
                            selectOptions += '<option value="Daram I">Daram I</option>';
                            selectOptions += '<option value="Tarangnan">Tarangnan</option>';
                            selectOptions += '<option value="Tagapul-an">Tagapul-an</option>';
                            selectOptions += '<option value="Sto. Nino">Sto. Nino</option>';
                            selectOptions += '<option value="Daram II">Daram II</option>';
                            selectOptions += '<option value="Hinabangan I">Hinabangan I</option>';
                            selectOptions += '<option value="Hinabangan II">Hinabangan II</option>';
                            selectOptions += '<option value="Sta. Margarita I">Sta. Margarita I</option>';
                            selectOptions += '<option value="Sta. Margarita II">Sta. Margarita II</option>';
                            selectOptions += '<option value="Jiabong">Jiabong</option>';
                            selectOptions += '<option value="San Jorge">San Jorge</option>';
                            selectOptions += '<option value="Pagsanghan">Pagsanghan</option>';
                            selectOptions += '<option value="Marabut">Marabut</option>';
                            selectOptions += '<option value="Matuguinao">Matuguinao</option>';
                            selectOptions += '<option value="Motiong">Motiong</option>';
                            selectOptions += '<option value="Pinabacdao I">Pinabacdao I</option>';
                            selectOptions += '<option value="Pinabacdao II">Pinabacdao II</option>';
                            selectOptions += '<option value="San Sebastian">San Sebastian</option>';
                            selectOptions += '<option value="Sta. Rita I">Sta. Rita I</option>';
                            selectOptions += '<option value="Gandara II">Gandara II</option>';
                            selectOptions += '<option value="Gandara I">Gandara I</option>';
                            selectOptions += '<option value="Sta. Rita II">Sta. Rita II</option>';
                            selectOptions += '<option value="Sta. Rita III">Sta. Rita III</option>';
                            selectOptions += '<option value="Talalora">Talalora</option>';
                            selectOptions += '<option value="Villareal I">Villareal I</option>';
                            selectOptions += '<option value="Villareal II">Villareal II</option>';
                            selectOptions += '<option value="Wright I">Wright I</option>';
                            selectOptions += '<option value="Wright II">Wright II</option>';
                            selectOptions += '<option value="ZUMARRAGA">ZUMARRAGA</option>';
                            selectOptions += '<option value="CALBAYOG CITY">CALBAYOG CITY</option>';
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
                            selectOptions += '<option value="Samar Division Office - Personnel">Samar Division Office - Personnel</option>';
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
            url:"{{ route('cvrecord.datajson') }}",
            type:"post",
            dataType:"json",
            delay:150,
            quietMillis: 100,
            data: function(params){
                dist = $('#dist').val();
                return{
                    search: params.term,
                    dist: dist
                };
            },
            processResults: function(data) {
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

    $('.searchdata').on('click', function(){
        var selectDist = $('#dist').val();
        var selectMuncit = $('#select2-muncit').val();
        var selectBrgy = $('#select2-brgy').val();

        $.ajax({
            url: "{{ route('cvrecord.techearsRecord') }}",
            data: { selectSchool: selectSchool },
            method: 'GET',
            success: function(response) {
                teachRecords.clear().rows.add(response.data).draw();
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An error occurred while loading data. Please try again."
                });
            }
        });
    });


    // $('#dist').on('change', function(){
    //     var selectDist = $('#dist').val();
    //     cvrec.column(8).search('^' + selectDist + '$', true, false).draw();
    // });

    // $('#dist').on('select2:clear', function(){
    //     cvrec.ajax.reload();
    // });

    // $('#select2-muncit').on('change', function(){
    //     var selectDist = $('#dist').val();
    //     var selectMuncit = $(this).val();

    //     if(selectMuncit){
    //         cvrec.column(2).search('^' + selectMuncit + '$', true, false).draw();
    //     }else{
    //         cvrec.column(2).search('');
    //     }

    //     if (selectDist) {
    //         cvrec.search(selectDist);
    //     } else {
    //         cvrec.search('');
    //     };
    //     cvrec.draw();
    // });

    // $('#select2-brgy').on('change', function(e){
    //     var selectMuncit = $('#select2-muncit').val();
    //     var selectBrgy = $(this).val();

    //     if(selectBrgy){
    //         cvrec.column(3).search('^' + selectBrgy + '$', true, false).draw();
    //     }else{
    //         cvrec.column(3).search('');
    //     }

    //     if (selectMuncit) {
    //         cvrec.search(selectMuncit);
    //     } else {
    //         cvrec.search('');
    //     }
    //     cvrec.draw();
    // });

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
                            $('.cvrectbl').ajax.reload(null, false);
                        },
                        error: function(xhr, status, error){
                            // console.log(error);
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
                            $('.cvrectbl').ajax.reload();
                            cvrec.ajax.reload();

                        },
                        error: function(xhr, status, error){
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
        const searchTerm = this.value;
        if(searchTerm){
            loadDistrictData(searchTerm);
        }else{
            cvrec.clear().draw();
        }

    }, 300));

    function loadDistrictData(searchTerm) {
        $.ajax({
            url: "{{ route('cvrecord.loadDistrictData') }}",
            method: 'GET',
            data: { search: searchTerm }, // Pass the search term as a parameter
            success: function(response) {
                cvrec.clear().rows.add(response.data).draw();
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error
                });
            }
        });
    }

    function loadReloadData() {
        const selectSchool = $('#sschool').val();
        if(selectSchool)
        {
            $.ajax({
                url: "{{ route('cvrecord.techearsRecord') }}",
                data: { selectSchool: selectSchool },
                method: 'GET',
                success: function(response) {
                    teachRecords.clear().rows.add(response.data).draw();
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

    $(document).on('click','.depClear', function(){
        const dataId = $(this).data('id');
        Swal.fire({
            title:"Are you sure?",
            text:"You won't be able to revert this!",
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#1cbb8c",
            cancelButtonColor:"#f32f53",
            confirmButtonText:"Yes, CLEAR and DELETE it!"
        }).then((result) => {
            if (result.isConfirmed){
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
                        cvrec.clear().draw();
                        loadReloadData();
                        cvrec.ajax.reload();
                        $('#customSearch').val('');
                    },
                    error: function(xhr, status, error){
                           if(error) {
                            var err = eval("(" + xhr.responseText + ")");
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: err.message
                            });
                        }
                    }
                });
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
        $('#loading').show();
        cvrec.search('').columns().search('').draw();

        if (selectSchool) {
            cvrec.column(11).search('^' + selectSchool + '$', true, false);
        }

        cvrec.draw();
        var count1 = cvrec.column(12).search(1).rows({ search: '1' }).count();
        $('#loading').hide();
    })

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

    $('#ssschool').select2({
        placeholder: "Select School/District",
        allowClear: true,
        dropdownParent: $('#modalAddmanual'),
        tags: true,
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

    let teachRecords = $('#teacherSumm').DataTable({
        destroy: true,
        scrollY: true,
        searching: true,
        paging: false,
        processing: true,
        ordering: true,
        info: true,
        dom: '<"top"lBfr>t',
        autoWidth : false,
        deferRender: true,
        data: [],
        columns: [
            { "data": null, "className": "align-middle text-center",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;}
            }, //0
            { "data": "Name", "className": "align-middle"}, //1
            { "data": "is_depedEmployee", "visible": true, "searchable": true,
                "className": "align-middle text-center",
                "defaultContent": '',
                "render": function (data, type, row, meta)
                {
                    if(data === 1){
                        return '<button type="button" class="btn btn-danger btn-rounded waves-effect waves-light">Supporter</button>'
                    }else if(data === 2){
                        return '<button type="button" class="btn btn-warning btn-rounded waves-effect waves-light">Not Supporter</button>'
                    }else if(data === 3){
                        return '<button type="button" class="btn btn-info btn-rounded waves-effect waves-light">Not Found</button>'
                    }else{
                        return ''
                    }
                    return data;
                }
            }, //2
            { "data": "is_depedEmployee", "visible": false, "searchable": true }, //3
            { "data": "school", "visible": false, "searchable": true }, //4
            { "data": "level", "visible": false, "searchable": true } //4
        ],
        buttons: [
            {
                text: 'Copy to clipboard',
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0,1,2]
                },
                className: 'btn btn-success waves-effect waves-light',
                action: function(e, dt, button, config){
                    ifschool = $('#sschool').val();
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                    if(ifschool)
                    {
                        Swal.fire({
                            icon: "success",
                            title: "Successfully copied to clipboard!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    else{
                        Swal.fire({
                            icon: "error",
                            title: "Please select school!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    };
                }
            },
            {
                text: 'Export to Excel',
                extend: 'excelHtml5',
                title: $('#sschool option:selected').text(),
                filename: function() {
                    return fileExportFileName();
                },
                className: 'btn btn-success waves-effect waves-light',
                exportOptions: {
                    columns: ':visible:not(.not-export-col)'
                },
                action: function(e, dt, button, config) {
                    var ifschool = $('#sschool').val();

                    if (ifschool) {
                        // Call the export action
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);

                        // Show success message
                        Swal.fire({
                            icon: "success",
                            title: "Successfully exported to Excel!",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: "error",
                            title: "Please select a school!",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            }

        ],
        language: {
            'search': '', // Remove the default search label
            'processing': '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span>'
        },
        initComplete: function() {
            // Add placeholder to the search input
            $('.dataTables_filter input').attr('placeholder', 'Search...');
        }
    });

    $('.dataTables_filter input[type="search"]').css(
        {'width':'400px', 'height':'50px'}
    );


    $('#sschool').on('change', function() {
        const selectSchool = $(this).val();
        loadReloadData();
    });

    $('#sschool').on('select2:clearing', function(e){
        $('.sstatus').val('').trigger('change');
        $('.ssfilter').val('').trigger('change');
        teachRecords.clear().draw();
        loadReloadData();
    });

    // $('#sstatus').on('select2:clearing', function(e){
    //     loadReloadData();
    // });

    $(document).on('click', '#notfound', function(){
        $('#modalAddmanual').modal('show')
    });

    var manForms = $('#manForm')[0];
    $('#addManualEMployee').on('click', function(){
        var formAddManual = new FormData(manForms);
        $.ajax({
            url:"{{ route('cvrecord.techearsNotfound') }}" ,
            method: 'POST',
            processData: false,
            contentType: false,
            data: formAddManual,
            success: function(response) {
                $('#manForm')[0].reset();
                $('#ssschool').val('').trigger('change');
                $('#modalAddmanual').modal('toggle');
                $('.teacherSumm').dataTable().fnDestroy();
                // $('#teacherSumm').DataTable().clear().destroy();
                if(response.success) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        showConfirmButton: false,
                        title: "Record Added!",
                        timer: 1000
                    });
                }
                // $('.teacherSumm').DataTable().ajax.reload(null, false);
                loadReloadData();
                $('.cvrectbl').ajax.reload();
            },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: err.message
                    });
                }
            }
        });
    });

    $('.sstatus').on('change', function(){
        let selectedStatus = $(this).val();
        if (selectedStatus && selectedStatus.length) {
            // Join the selected statuses with a pipe (|) for regex search
            teachRecords.column(3).search(selectedStatus.join('|'), true, false).draw();
        } else {
            // Clear the search if no statuses are selected
            teachRecords.column(3).search('').draw();
        }
    });

    // $('#offcanvasRight').on('hide.bs.offcanvas', function (e) {
    //     $('#sschool').val('').trigger('change');
    //     $('#sstatus').val('').trigger('change');
    //     teachRecords.clear().draw();
    // });

    $('#modalAddmanual').modal({backdrop: 'static', keyboard: false});

    function fileExportFileName() {
        var myfile = $('#sschool option:selected').val(); // Get the selected option's value
        var d = new Date();
        var formattedDate = d.toISOString().split('T')[0];
        return formattedDate + "-" + myfile;
    }

    // var columnData = teachRecords.column(1).data().toArray();
    // console.log('Column 5 Data:', columnData);

    $('.ssfilter').select2({
        allowClear: true,
        placeholder: "Filter Level",
        dropdownParent: $('#offcanvasRight'),
    });

    $('.ssfilter').on('change', function(){
        var flevel = []
        $.each($('.ssfilter '), function(i,elem){
            flevel.push($(this).val())
        })
        teachRecords.column(5).search(flevel).draw();

        var filteredDataCount = teachRecords.rows({ filter: 'applied' }).count();

        Swal.fire({
        title: 'Total Filtered Data',
            text: 'Total: ' + filteredDataCount,
            icon: 'info',
            confirmButtonText: 'OK'
        });

    });


    //  $('.ssfilter').on('select2:clearing', function(e){
    //     const selectSchool = $('#sschool').val();
    //     $('.sstatus').val('').trigger('change');
    // });
});
</script>

@include('layouts.script')
@include('layouts.footer')
@endsection
