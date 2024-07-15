@extends('layouts.auth')
    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                             <div class="mb-1 ">
                                                <div class="input-group gap-2">
                                                    <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterMuncit" id="filterMuncit">
                                                        <option value="Calbayog City" selected>Calbayog City</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-1 ">
                                               <div class="input-group gap-2">
                                                   <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterBrgy" id="filterBrgy"></select>
                                               </div>
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                            <div class="mb-1 ">
                                                <div class="input-group gap-2">
                                                    <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterPos" id="filterPos"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        <div class="mb-1 ">
                                           <div class="input-group gap-2">
                                               <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterCan" id="filterCan"></select>
                                           </div>
                                       </div>
                                    </div>
                                </div>
                            </div>

                            <table id="nle2022tbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Barangay</th>
                                    <th>Registered Voters</th>
                                    <th>Command Votes</th>
                                    <th>Candidate Nickname</th>
                                    <th>Votes</th>
                                    <th hidden>position</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }); //initalize csrf

            var nle2022 = $('#nle2022tbl').DataTable({
                "order": [[ 1, 'asc' ], [ 5, 'desc' ]],
                "ordering": true ,
                "autoWidth": true,
                "dom": 'rtip',
                "pageLength": 15,
                // "dom": 'Bfrtip',
                "ajax": "{{ route('nle2022.index') }}",
                "columns":[
                    { "data": "id" },
                    { "data": "barangay" },
                    { "data": "rv" },
                    { "data": "cv"},
                    { "data": "candidate" },
                    { "data": "votes" },
                    { "data": "position" }
                ],
                "columnDefs": [
                    // {"targets": 0, "ckeckboxes": {"selectedRow": true}},
                    // {"className": "align-middle", "targets": "_all"},
                    {"className": "text-center", "targets": [0,2,3,4,5]},
                    // {"className": "dt-center", "targets": [0,3,4,6,7]},
                    // {"targets": [0], "visible": true, "bSortable": false, "width": '5%'},

                    // {"targets": [7,8,], "width": '5%'},
                    // {"targets": [9], "width": '5%'},
                    {"targets": [6], "visible": false, "searchable": true },
                ]
                // "buttons": [
                //     {
                //         text: 'excel',
                //         extend: 'excelHtml5',
                //         title: 'HOUSELEADER SUMMARY',
                //         className: 'btn btn-success waves-effect waves-light',
                //         exportOptions: {
                //             columns: ':visible:not(.not-export-col)'
                //         }
                //     },
                //     {
                //         text: 'print',
                //         extend: 'print',
                //         title: '',
                //         pageSize: 'A4',
                //         orientation: 'portrait',

                //         exportOptions: {

                //             columns: [0,3,4,5]
                //             // ':visible:not(.not-export-col)'
                //             // columns: ":not(.not-export-column)"
                //         },
                //         className: 'btn btn-success waves-effect waves-light',
                //             messageTop: function () {
                //                 muncit = $('#hlmun').val();
                //                 hlbrgys = $('#hlbrgy').val();
                //             return '<h1 style="text-align:center;">House Leader Summary </h1> <h2 style="text-align:center;">'+ muncit + '-' +hlbrgys+'</h2>';
                //         }
                //     },
                // ]

        });

        $('#filterBrgy').on('change', function(e){
            var selectBrgy = [];
            $.each($('#filterBrgy'), function(i,elem){
                selectBrgy.push($(this).val())
            })
            nle2022.column(1).search(selectBrgy).draw();
        });

        $('#filterPos').on('change', function(e){
            var selectPos = [];
            $.each($('#filterPos'), function(i,elem){
                selectPos.push($(this).val())
            })
            regex = '^'+selectPos;
            nle2022.column(6).search(regex, true, false ,false).draw();
        });

        $('#filterCan').on('change', function(e){
            var selectCan = [];
            $.each($('#filterCan'), function(i,elem){
                selectCan.push($(this).val())
            })
            nle2022.column(4).sort().search(selectCan).draw();
        });

        $('#filterBrgy').on('change', function(){
            $('#filterPos, #filterCan').val('').trigger('change');
        });
        $('#filterPos').on('change', function(){
            $('#filterCan').val('').trigger('change');
        });

        $('#filterMuncit').select2({
            placeholder: "Select Municipality",
            minimumResultsForSearch: -1,
        });

        $('#filterBrgy').select2({
            placeholder: "Select Barangay",
            allowClear: true,
            maximumSelectionLength: 1,
            ajax:{
                url:"{{ route('nle2022.fetchbrgyss') }}",
                type:"POST",
                dataType:"json",
                delay:250,
                quietMillis: 100,
                data: function(params){
                    muncitss = $('#filterMuncit').val();
                    return{
                        search: params.term,
                        muncitss: muncitss
                    };
                },
                processResults: function(data){
                    return{
                        results: $.map(data.items, function(obj,i) {
                            return {
                            id:obj, text:i
                            };
                        })
                    }
                }
            }
        });

        $('#filterPos').select2({
            placeholder: "Select Position",
            allowClear: true,
            minimumResultsForSearch: -1,
            ajax:{
                url:"{{ route('nle2022.fetchpositionss') }}",
                type:"POST",
                dataType:"json",
                delay:250,
                quietMillis: 100,
                data: function(params){
                    muncitss = $('#filterMuncit').val();
                    brgyss = $('#filterBrgy').val();
                    return{
                        search: params.term,
                        muncitss:muncitss,
                        brgyss:brgyss
                    };
                },
                processResults: function(data){
                    return{
                        results: $.map(data.items, function(obj,i) {
                            return {
                            id:obj, text:i
                            };
                        })
                    }
                }
            }
        });

        $('#filterCan').select2({
            placeholder: "Select Candidate",
            allowClear: true,
            minimumResultsForSearch: -1,
            ajax:{
                url:"{{ route('nle2022.fetchcandidate') }}",
                type:"POST",
                dataType:"json",
                delay:250,
                quietMillis: 100,
                data: function(params){
                    poss = $('#filterPos').val();
                    return{
                        search: params.term,
                        poss:poss
                    };
                },
                processResults: function(data){
                    return{
                        results: $.map(data.items, function(obj,i) {
                            return {
                            id:obj, text:i
                            };
                        })
                    }
                }
            }
        });
    });
    </script>

    @include('layouts.script')
    @include('layouts.footer')

@endsection
