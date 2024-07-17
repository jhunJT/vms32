@extends('layouts.auth')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">User Login Information</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                <li class="breadcrumb-item active">User Logs</li>
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
                            <table id="tbUserlogs" class="table table-striped table-bordered tbUserlogs" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th >ID</th>
                                        <th >Name</th>
                                        <th >Activity</th>
                                        <th >Details</th>
                                        <th >Date</th>
                                        <th >Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                         <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <script>
        $(document).ready(function(e) {

            $('.tbUserlogs').DataTable({
                "lengthMenu": [10, 15,30, 50, 100],
                "ordering": true,
                // "order": [[11,'asc'],[12,'asc'],[2,'asc'],[1,'asc'],[3,'asc']],
                "autoWidth" : true,
                "pageLength": 10,
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('allusers.userlogs') }}",
                "columns":[
                    {"data": "id",
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;}}, //0
                    { "data": "name" }, //1
                    { "data": "descrption" }, //2
                    { "data": "details"}, //3
                    { "data": "date_time" }, //4
                    { "data": "role" }, //5
                ],
                "columnDefs": [
                    {"className": "align-middle", "targets": "_all"},
                ]
            });
        });
    </script>

    @include('layouts.script')
    @include('layouts.footer')
@endsection

