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
                                <li class="breadcrumb-item active">User List</li>
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

                            <div class="my-4 text-left">
                                <button type="button" id ="createNewUser" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center">Add User</button>
                            </div>

                            <table id="tbUserSuper" class="table table-striped table-bordered tbUserSuper" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">District</th>
                                        <th class="text-center">Municipality/City</th>
                                        <th class="text-center">Contact No</th>
                                        <th class="text-center">Level</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Status2</th>
                                        <th class="text-center">Action</th>
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
                        <form id = "postForm" name="postForm" method="POST" class="needs-validation"  enctype="multipart/form-data" novalidate>
                            <div id="error"></div>
                            <input type="hidden" name="id" id="id">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Username</label>
                                        <input type="email" class="form-control" id="email"
                                            placeholder="Username" name="email" required>
                                        <div class="valid-feedback">
                                            Looks good!
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
                                            <option value="supervisor">Supervisor</option>
                                            <option value="encoder">Encoder</option>
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
                                        <label for="username" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="username"
                                            placeholder="Email Address" name="username" required>
                                        <div class="valid-feedback">
                                            <x-input-error :messages="$errors->get('Email')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Date</label>
                                        <div class="input-group" id="datepicker2">
                                            <input type="text" class="form-control" placeholder="MM dd, yyyy" name="birthday" id="birthday"
                                                data-date-format="MM dd, yyyy" data-date-container='#datepicker2' data-provide="datepicker"
                                                data-date-autoclose="true" data-setDate="now">
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
                                            <option value="{{ $district }}" selected>{{ $district }}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a valid District.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="muncit" class="form-label">Municipality/City</label>
                                        <select class="form-select" type="text" name="muncit" id="muncit" required>
                                            <option value="{{ $muncit }}" selected>{{ $muncit }}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please provide a valid Municipality/City.
                                        </div>
                                    </div>
                                    <input type="hidden" name="tbname" id="tbname" value={{ $tbname }}>
                                    <input type="hidden" name="u_lat" id="u_lat" value={{ $ulat }}>
                                    <input type="hidden" name="u_long" id="u_long" value={{ $ulong }}>
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

    <script>
        var dashboardAdminView = "{{ route('supervisor.show') }}";
        var dashboardAdminEdit = "{{ route("supervisor.edit","")}}";
        var dashboardAdminStore = "{{ route('supervisor.store') }}";
        var dashboardAdminDelete = "{{ route("supervisor.delete","")}}";
    </script>

    <script src="{{ asset('assets/auth/js/suser.js') }}"></script>

    {{-- @include('layouts.script') --}}
    @include('layouts.footer')
@endsection

