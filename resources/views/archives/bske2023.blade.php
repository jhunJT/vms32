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
                                        <div class="col-md-4">
                                             <div class="mb-1 ">
                                                <div class="input-group gap-2">
                                                    <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterMuncit" id="filterMuncit"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-1 ">
                                               <div class="input-group gap-2">
                                                   <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterBrgy" id="filterBrgy"></select>
                                               </div>
                                           </div>
                                       </div>
                                       <div class="col-md-4">
                                        <div class="mb-1 ">
                                           <div class="input-group gap-2">
                                               <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterPos" id="filterPos"></select>
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
                                    {{-- <th>Rank</th> --}}
                                    <th>Nickname</th>
                                    <th>Votes</th>
                                    {{-- <th>Party</th> --}}
                                    {{-- <th>Remarks</th> --}}
                                    <th class="text-center">Action</th>
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
            var bske2023 = $('#bske2023tbl').DataTable({
                "order": [[ 1, 'asc' ], [ 3, 'asc' ], [2, 'asc' ],[4, 'asc']],
                "ordering": true ,
                "pageLength": 15,
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
                    // { "data": "rank"},
                    { "data": "nickname" },
                    { "data": "vobtained" },
                    // { "data": "party" },
                    // { "data": "remarks" },
                    { "data": "action", orderable:false, searchable: false}
                ],
                "columnDefs": [
                    { "searchable": true, "visible": false,"targets": [1],}
                    ],

                // "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                //      $('td:eq(0)', nRow).html(iDisplayIndexFull +1);
                // },
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
                        pageSize: 'A4',
                        orientation: 'portrait',

                        exportOptions: {

                            columns: [0,3,4,5]
                            // ':visible:not(.not-export-col)'
                            // columns: ":not(.not-export-column)"
                        },
                        className: 'btn btn-success waves-effect waves-light',
                            messageTop: function () {
                                muncit = $('#hlmun').val();
                                hlbrgys = $('#hlbrgy').val();
                            return '<h1 style="text-align:center;">House Leader Summary </h1> <h2 style="text-align:center;">'+ muncit + '-' +hlbrgys+'</h2>';
                        }
                    },
                ]

        });

        // $.fn.dataTable.ext.errMode = 'throw';


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
    });
    </script>

    @include('layouts.script')
    @include('layouts.footer')

@endsection
