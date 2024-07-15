@extends('layouts.auth')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">User List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                <li class="breadcrumb-item active">Encoder List</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="col-md-10">
                                            <div class="mb-1 ">
                                                <div class="input-group mx-5 gap-2">
                                                    <select class="form-control " style="width:40%;"  tabindex="-1" aria-hidden="true" name="filterEncoder[]" id="filterEncoder" multiple="multiple" ></select>
                                                    <select class="form-control " style="width: 30%;" id="filterBrgy"  tabindex="-1" aria-hidden="true" name="filterBrgy" multiple="multiple"></select>
                                                    <select class="form-control " style="width: 20%;" id="filterMonthYear"  tabindex="-1" aria-hidden="true" name="filterMonth" multiple="multiple"></select>
                                                    {{-- <select class="form-control " style="width: 30%;" id="houseleaderSelect"   tabindex="-1" aria-hidden="true" name="houseleaderSelect" multiple="multiple"></select> --}}
                                                    {{-- <input type="text" class="form-control w-s" id="setPurok" placeholder="Purok" name="setPurok"  > --}}
                                                    {{-- <input type="text" class="form-control w-s" id="seqno" placeholder="SQ#" name="seqno" > --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                                {{-- <div class="btn-group w-lg mx-5">
                                                    <button type="button" class="btn btn-primary w-s waves-effect waves-light" name="newList" id="newList"><i class="mdi mdi-account-child-outline"></i></button>
                                                    <button type="button" class="btn btn-danger w-l waves-effect waves-light" name="clearText" id="clearText"><i class="mdi mdi-eraser-variant"></i></button>
                                                </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table id="tbUserPerformance" class="table table-striped table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                {{-- id="selection-datatable" --}}
                                <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Municipality</th>
                                        <th class="text-center">Barangay</th>
                                        <th class="text-center">Encoded</th>
                                        <th class="text-center">Month-Year</th>
                                        {{-- <th class="text-center">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" style="text-align:center">Total:</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                         <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->
        </div> <!-- container-fluid -->
    </div>


    <!-- End Page-content -->

    {{-- modal add user --}}
    <div class="modal fade bs-example-modal-center" id= "postmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="card">
                    <div class="card-body">

                            @if(Session::has('success'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('success')}}
                            </div>
                            @endif

                        <form id = "postForm" name="postForm" method="POST" class="needs-validation"  enctype="multipart/form-data" novalidate>
                            <div id="error"></div>
                            <input type="hidden" name="id" id="id">

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">User Name</label>
                                        <input type="text" class="form-control" id="username"
                                            placeholder="User Name" name="username" required>
                                        <div class="valid-feedback">
                                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password"
                                            placeholder="Password" name="password" required autocomplete="new-password">
                                        <div class="valid-feedback">
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            placeholder="Confirm Password" name="password_confirmation" required autocomplete="new-password">
                                        <div class="valid-feedback">
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="level" class="form-label">User Type</label>
                                        <select class="form-select" id="level" name="level" required>
                                            <option selected disabled value="">Choose...</option>
                                            <option value="Administrator">Administrator</option>
                                            <option value="Supervisor">Supervisor</option>
                                            <option value="Encoder">Encoder</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a valid Type.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Fullname</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="First name" name="name" required>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email"
                                            placeholder="Email" name="email" required>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Birth Date</label>
                                        <div class="input-group" id="datepicker2">
                                            <input type="text" class="form-control" placeholder="dd M, yyyy" name="birthday" id="birthday"
                                                data-date-format="dd M, yyyy" data-date-container='#datepicker2' data-provide="datepicker"
                                                data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="district" class="form-label">District</label>
                                        <select class="form-select" id="district" name="district" required>
                                            <option selected disabled value="">Choose...</option>
                                            <option value="District I">District I</option>
                                            <option value="District II">District II</option>

                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a valid District.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="muncit" class="form-label">Municipality/City</label>
                                        <input type="text" class="form-control" id="muncit"
                                            placeholder="Municipality/City" name="muncit" required>
                                        <div class="invalid-feedback">
                                            Please provide a valid Municipality/City.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="contno" class="form-label">Contact No</label>
                                        <input type="text" class="form-control" id="contno"
                                            placeholder="Contact No" name="contno" required>
                                        <div class="invalid-feedback">
                                            Please provide a valid Contact No.
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button  type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button id ="savedata" class="btn btn-primary" type="submit"></button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


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

            var tbencoder = $('#tbUserPerformance').DataTable({
            // dom: 'ftip',
            "order" : [[5,'desc']],
            "lengthMenu": [10, 15,30, 50, 100],
            "pageLength": 10,
            "processing": false,
            "ajax": "{{ route('allusers/performance') }}",
            "columns":[
                { "data": "id" }, //0
                { "data": "name" }, //1
                { "data": "muncit" }, //2
                { "data": "brgy" }, //3
                { "data": "encoded" }, //4
                { "data": "monthyear" }, //5
            ],
            "columnDefs": [
                // {"targets": 0, "ckeckboxes": {"selectedRow": true}},
                    {"className": "text-center", "targets": [0,4]},
                    {"className": "dt-center", "targets": [0,4]},
                    {"targets": [5], "visible": false, "bSortable": false }
                    // {"targets": [10,11], "visible": false, "searchable": true },
                ],
            "footerCallback": function (row, data, start, end, display) {
                let api = this.api();
                // Remove the formatting to get integer data for summation
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[\$,]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };

                // Total over all pages
                    total = api
                        .column(4)
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);

                    // Total over this page
                    pageTotal = api
                        .column(4, { page: 'current' })
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);
                    // Update footer
                    var totalPages = api.page.info().pages; // Total number of pages
                    var currentPage = api.page.info().page; // Current page number (zero-indexed)

                    if (currentPage < totalPages - 1) {
                        // For all pages except the last one, display page total
                        api.column(4).footer().innerHTML = pageTotal + ' CV';
                    } else {
                        // For the last page, display overall total
                        api.column(4).footer().innerHTML = pageTotal + ' ('+ total + ' Overall CV)';
                    }

                    // api.column(4).footer().innerHTML = pageTotal + ' ( $' + total + ' total)';
                }
            });

            $('#filterEncoder').select2({
                placeholder: "Select Encoder",
                allowClear: true,
                maximumSelectionLength: 1,
                ajax:{
                    url:"{{ route('allusers/performance/fetch-encoder') }}",
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
                                        id:obj, text:obj
                                    };
                                })
                            }
                        }
                    }
                });

                $('#filterEncoder').on('change', function(e){
                    $('#filterBrgy, #filterMonthYear').val('').trigger('change');
                    var selectEncoder = []
                    $.each($('#filterEncoder'), function(i,elem){
                        selectEncoder.push($(this).val())
                    })
                    tbencoder.column(1).search(selectEncoder).draw();
                });

                $('#filterBrgy').on('change', function(e){
                    $('#filterMonthYear').val('').trigger('change');
                    var selectEncBrgy = []
                    $.each($('#filterBrgy'), function(i,elem){
                        selectEncBrgy.push($(this).val())
                    })
                    tbencoder.column(3).search(selectEncBrgy).draw();
                });

                $('#filterMonthYear').on('change', function(e){
                    var selectEncMY = []
                    $.each($('#filterMonthYear'), function(i,elem){
                        selectEncMY.push($(this).val())
                    })
                    tbencoder.column(5).search(selectEncMY).draw();
                });

                $('#filterMonthYear').select2({
                placeholder: "Filter Month Year",
                allowClear: true,
                maximumSelectionLength: 1,
                ajax:{
                    url:"{{ route('allusers/performance/fetch-monthyear') }}",
                    type:"POST",
                    dataType:"json",
                    delay:250,
                    quietMillis: 100,
                    data: function(params){
                        fltBrgy = $('#filterBrgy').val();
                        fltEncoder = $('#filterEncoder').val();
                        return{
                            fltBrgy: fltBrgy,
                            fltEncoder: fltEncoder
                        };
                    },
                    processResults: function(data){
                        return{
                            results: $.map(data.items, function(obj,i) {
                                return {
                                        id:obj, text:obj
                                    };
                                })
                            }
                        }
                    }
                });

                $('#filterBrgy').select2({
                placeholder: "Filter Barangay",
                allowClear: true,
                maximumSelectionLength: 1,
                ajax:{
                    url:"{{ route('allusers/performance/fetch-brgy') }}",
                    type:"POST",
                    dataType:"json",
                    delay:250,
                    quietMillis: 100,
                    data: function(params){
                        fltEncoder = $('#filterEncoder').val();
                        return{
                            search: params.term,
                            fltEncoder: fltEncoder
                        };
                    },
                    processResults: function(data){
                        return{
                            results: $.map(data.items, function(obj,i) {
                                return {
                                        id:obj, text:obj
                                    };
                                })
                            }
                        }
                    }
                });

                // var tbuser =  $('#tbUser').DataTable({
                //     ajax: "{{ route('allusers') }}",
                //     columns: [
                //         {data: 'id', name: 'id'},
                //         {data: 'name', name: 'name'},
                //         {data: 'email', name: 'email'},
                //         {data: 'district', name: 'district'},
                //         {data: 'muncit', name: 'muncit'},
                //         {data: 'contno', name: 'contno'},
                //         {data: 'level', name: 'level' },
                //         {data: 'action', name: 'action', orderable: false, searchable: false, class:'text-center'},
                //     ],
                //     columnDefs: [
                //         {className: 'align-middle', targets: '_all'},
                //         {className: 'dt-center', targets: '-1'}
                //     ]

                // }

                // $('#createNewUser').click(function () {
                //     $('#savedata').html('Save New User');
                //     $('#id').val('');
                //     $('#postForm').trigger("reset");
                //     $('#modelHeading').html("Register New User");
                //     $('#postmodal').modal('show');
                //     $('#error').html('');
                // });

                // $('body').on('click', '.userEdit', function () {
                //     $('#savedata').html('Update User');
                //     var ids = $(this).data("id");

                //     $.ajax({
                //         url: '{{ url("allusers",'')}}' +'/'+ ids + '/edit',
                //         method:'GET',
                //         dataType: 'json',
                //         success: function(data){
                //             $('#modelHeading').html("Update User");
                //             $('#postmodal').modal('show');
                //             $('#id').val(data.id);
                //             $('#username').val(data.username);
                //             $('#password').val(data.Password);
                //             $('#password_confirmation').val(data.password);
                //             // $('#password').attr('readonly', true);
                //             // $('#password_confirmation').attr('readonly', true);
                //             $('#level').val(data.level);
                //             $('#name').val(data.name);
                //             $('#email').val(data.email);
                //             $('#birthday').val(data.birthday);
                //             $('#district').val(data.district);
                //             $('#muncit').val(data.muncit);
                //             $('#contno').val(data.contno);
                //             $('#error').html('');
                //         },
                //             error: function(error){
                //                 toastr.error('Something went wrong!','ERROR');
                //         }
                //     });
                // });

                // $('#savedata').click(function (e) {
                //     e.preventDefault();
                //     $(this).html('Sending..');
                //     $.ajax({
                //         data: $('#postForm').serialize(),
                //         url: "{{ route('allusers.store') }}",
                //         type: "POST",
                //         dataType: 'json',
                //         success: function (data) {
                //             $('#postForm').trigger("reset");
                //             $('#postmodal').modal('hide');
                //             toastr.success('Data saved successfully','Success');
                //             tbuser.ajax.reload(null, false);
                //         },
                //         error: function (data) {
                //             console.log('Error:', data);
                //             $('#error').html("<div class='alert alert-danger'>"+data['responseJSON']['message'] + "</div>");
                //             $('#savedata').html('Save New User');
                //         }
                //     });
                // });

                // $('body').on('click', '.userDelete', function () {
                //     var id = $(this).data("id");

                //     Swal.fire({
                //     title: "Are you sure?",
                //     text: "You won't be able to revert this!",
                //     icon: "warning",
                //     showCancelButton: !0,
                //     confirmButtonColor: "#1cbb8c",
                //     cancelButtonColor: "#f32f53",
                //     confirmButtonText: "Yes, delete it!"
                //     }).then((result) => {
                //         if(result.isConfirmed) {
                //             $.ajax({
                //             url: '{{ url("allusers/delete",'')}}' +'/'+ id,
                //             method:'GET',
                //             dataType: 'json',
                //             success: function (data) {
                //                 tbuser.ajax.reload(null, false);
                //             },
                //             error: function (data) {
                //                 console.log('Error:', data);
                //             }
                //         });
                //             Swal.fire(
                //                 'Deleted',
                //                 'User has been deleted!',
                //                 'success'
                //             )
                //         }
                //     })

                // });

                // new DataTable('#tbUserPerformance', {
                //     footerCallback: function (row, data, start, end, display) {
                //         let api = this.api();

                //         // Remove the formatting to get integer data for summation
                //         let intVal = function (i) {
                //             return typeof i === 'string'
                //                 ? i.replace(/[\$,]/g, '') * 1
                //                 : typeof i === 'number'
                //                 ? i
                //                 : 0;
                //         };

                //         // Total over all pages
                //         total = api
                //             .column(4)
                //             .data()
                //             .reduce((a, b) => intVal(a) + intVal(b), 0);

                //         // Total over this page
                //         pageTotal = api
                //             .column(4, { page: 'current' })
                //             .data()
                //             .reduce((a, b) => intVal(a) + intVal(b), 0);

                //         // Update footer
                //         api.column(4).footer().innerHTML =
                //             '$' + pageTotal + ' ( $' + total + ' total)';
                //     }
                // });
        });
    </script>

    @include('layouts.script')
    @include('layouts.footer')
@endsection

