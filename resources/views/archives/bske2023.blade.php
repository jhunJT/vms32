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
                                                    <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterMuncit" id="filterMuncit"></select>
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
                                                    <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterParty" id="filterParty">
                                                        <option selected disabled>Filter Party</option>
                                                        <option value="PULA">PULA</option>
                                                        <option value="DULAW">DULAW</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table id="bske2023tbl" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width:2rem;">No</th>
                                    <th hidden>Muncipality</th>
                                    <th style="width:15rem;">Fullname</th>
                                    <th>Barangay</th>
                                    <th>Position</th>
                                    <th>Votes</th>
                                    <th>Party</th>
                                    <th hidden class="text-center">Action</th>
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
            });

            var bske2023 = $('#bske2023tbl').DataTable({
                "order": [[ 1, 'asc' ], [ 3, 'asc' ], [4, 'desc' ]],
                "ordering": true ,
                "pageLength": 10,
                "dom": 'Bfrtip',
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('bske2023.index') }}",
                "columns":[
                    { "data": "id" },
                    { "data": "municipality" },
                    { "data": "fullname" },
                    { "data": "barangay" },
                    { "data": "position" },
                    { "data": "vobtained" },
                    { "data": "party",
                        "defaultContent": '',
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                var selectOptions = '<select class="form-select btnParty">';
                                selectOptions += '<option selected>NONE</option>';
                                selectOptions += '<option value="PULA">PULA</option>';
                                selectOptions += '<option value="DULAW">DULAW</option>';
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
                    },
                    { "data": "action", orderable:false, searchable: false}
                ],
                "columnDefs": [
                    { "searchable": true, "visible": false,"targets": [1,7]},
                    ],
                "buttons": [
                    {
                        text: 'EXCEL',
                        extend: 'excelHtml5',
                        title: 'BSKE 2023 RESULT SUMMARY',
                        className: 'btn btn-success waves-effect waves-light',
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        }
                    },
                    {
                        text: 'PRINT',
                        extend: 'print',
                        title: '',
                        pageSize: 'A4',
                        orientation: 'LANDSCAPE',

                        exportOptions: {
                            columns: [0,3,4,5,6]
                        },
                        className: 'btn btn-success waves-effect waves-light',
                            messageTop: function () {
                                muncit = $('#filterMuncit').val();
                                hlbrgys = $('#filterBrgy').val();

                                if (muncit && hlbrgys) {
                                    return '<h1 style="text-align:center;">BSKE 2023 RESULT SUMMARY</h1>' +
                                        '<h2 style="text-align:center;">' + muncit + ' - ' + hlbrgys + '</h2>';
                                } else if (muncit && !hlbrgys) {
                                    return '<h1 style="text-align:center;">BSKE 2023 RESULT SUMMARY</h1>' +
                                        '<h2 style="text-align:center;">' + muncit + '</h2>';
                                } else {
                                    return '<h1 style="text-align:center;">BSKE 2023 RESULT SUMMARY - ALL</h1>';
                                }
                        }
                    }
                ]

        });

        // $.fn.dataTable.ext.errMode = 'throw';

        $(document).on('change','.btnParty', function(){
            var selectedPartyVal = $(this).val();

            var closestRow = $(this).closest('tr');
            var rowData = bske2023.row(closestRow).data();
            var selectedRowId = rowData.id;

            $.ajax({
                url: '{{ route("archives.updtparty") }}' ,
                method: 'POST',
                data: {selectedPartyVal: selectedPartyVal, selectedRowId: selectedRowId},
                success: function(response) {
                    if(response) {
                        toastr.success(response.success);
                    }
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


        $('#filterMuncit').on('change', function(e){
            var selectMuncit = []
            $.each($('#filterMuncit'), function(i,elem){
                selectMuncit.push($(this).val())
            })
            bske2023.column(1).search(selectMuncit).draw();

        });

        $('#filterBrgy').on('change', function(e){
            var selectBrgy = []
            $.each($('#filterBrgy'), function(i,elem){
                selectBrgy.push($(this).val())
            })
            bske2023.column(3).sort().search(selectBrgy).draw();
        });

        $('#filterPos').on('change', function(e){
            var selectPos = []
            $.each($('#filterPos'), function(i,elem){
                selectPos.push($(this).val())
            })
            bske2023.column(4).sort().search(selectPos).draw();
        });

        $('#filterParty').on('change', function(e){
            var selectParty = []
            $.each($('#filterParty'), function(i,elem){
                selectParty.push($(this).val())
            })
            bske2023.column(6).sort().search(selectParty, true).draw();
        });

        $(document).on('click','.delRec', function(e){
            e.preventDefault();
            let id = $(this).attr('id');
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
                            url: '',
                            method: 'post',
                            data:{id:id},
                            success:function(res){
                                Swal.fire(
                                    'Deleted!',
                                    'Record deleted.',
                                    'success'
                                )
                                $('#tr_'+id).hide(1500);
                            }
                        });
                    }
                });
        });

        $('#filterMuncit').select2({
            placeholder: "Select Municipality",
            allowClear: true,
            maximumSelectionLength: 1,
            ajax:{
                url:"{{ route('stamargarita.fetchmuncitss') }}",
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
                            id:obj, text:i
                            };
                        })
                    }
                }
            }
        });

        $('#filterBrgy').select2({
            placeholder: "Select Barangay",
            allowClear: true,
            maximumSelectionLength: 1,
            ajax:{
                url:"{{ route('stamargarita.fetchbrgyss') }}",
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
            maximumSelectionLength: 1,
            ajax:{
                url:"{{ route('stamargarita.fetchpositionss') }}",
                type:"POST",
                dataType:"json",
                delay:250,
                quietMillis: 100,
                data: function(params){
                    return{
                        search: params.term,
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

        $('#filterParty').select2({
            placeholder: "Filter Party",
            allowClear: true,
            minimumResultsForSearch: -1
        });
    });
    </script>

    @include('layouts.script')
    @include('layouts.footer')

@endsection
