@extends('layouts.auth')
@section('content')

<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Welcome</h4>
                    <p class="card-title-desc">One People! One Samar!
                         <button class="btn btn-primary" style="float: right;"
                             data-bs-toggle="modal" data-bs-target="#changePass" >Change Password</button>
                    </p>

                    <form class="custom-validation" id="postForm">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control"  placeholder="Fullname" id="fname" name="fname" value="{{ $users->name }}">
                        </div>
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" class="form-control" id="email" name="email" size="20" readonly value="{{ $users->email }}">
                        </div>
                        <div class="mb-3">
                            <label>District</label>
                            <div>
                                <select class="form-select" id="district" name="district" placeholder="District"  disabled>
                                    <option value="{{ $users->district }}" selected >{{ $users->district }}</option>
                                    <option value="District I">District I</option>
                                    <option value="District II">District II</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Municipalit</label>
                            <div>
                                <select class="form-select" name="muncit" id="muncit"  disabled>
                                    <option value="{{ $users->muncit }}" selected >{{ $users->muncit }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Date Created</label>
                            <div>
                                <input type="text" class="form-control" name="ceated_at" readonly id="ceated_at"
                                    value={{ $users->created_at }} >
                                {{-- <input type="text" class="form-control" placeholder="dd M, yyyy" data-date-format="M dd, yyyy" data-date-container="#datepicker2" data-provide="datepicker" data-date-autoclose="true"> --}}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Contact No</label>
                            <input type="text" class="form-control" id="contno" name="contno" placeholder="Mobile Number" value="{{ $users->contno }}">
                        </div>
                        <hr>
                        <div class="mb-3">
                            <div>
                                <button class="btn btn-primary waves-effect waves-light me-1 userupdate">Update</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div> <!-- end row -->
    </div>
</div>

<div id="changePass" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Update Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('change.password.post') }}" id="changeP">
                    <div class="row">
                        <div class="mb-3">
                            <input type="text" id="current_password"  name="current_password"  class="form-control" placeholder="Old Password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <input type="password" id="password" name="password" class="form-control"  placeholder="New Password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"  placeholder="Confirm Password">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="d-grid mb-3">
                            <button class="btn btn-primary waves-effect waves-light me-1 updtpass">Change Password</button>
                        </div>
                    </div>


                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<script>

$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.userupdate').on('click',function(e){
        e.preventDefault();
        Swal.fire({
        title: "Update User?",
        text: "You won't be able to revert this!",
        icon: "info",
        showCancelButton: !0,
        confirmButtonColor: "#1cbb8c",
        cancelButtonColor: "#f32f53",
        confirmButtonText: "Yes, Update it!"
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                data: $('#postForm').serialize(),
                url: "{{ route('encoder.updateuser') }}",
                method:'post',
                dataType: 'json',
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
                Swal.fire(
                    'Profile',
                    'User Updated!',
                    'success'
                )
            }
        })
    });

    function updateDiv() {
        $(".userHeader").load(window.location.href + " .userHeader");
    }

    $('.updtpass').on('click', function(e){
        e.preventDefault();
        Swal.fire({
        title: "Update User?",
        text: "You won't be able to revert this!",
        icon: "info",
        showCancelButton: !0,
        confirmButtonColor: "#1cbb8c",
        cancelButtonColor: "#f32f53",
        confirmButtonText: "Yes, Update it!"
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                data: $('#changeP').serialize(),
                url: "{{ route('change.password.post') }}",
                method:'post',
                dataType: 'json',
                success: function (data) {
                    toastr.success('success');
                    location.reload();
                },
                error: function (data) {
                    toastr.error(data['responseJSON']['message']);
                    // console.log('Error:', data);
                    }
                });
            }
            // Swal.fire(
            //         'Password',
            //         'Password Updated!',
            //         'success'
            //     )

        })
    });
});
</script>


<script src="{{ asset('assets/auth/js/locationfiler.js') }}"></script>

@include('layouts.footer')
@include('layouts.script')
@endsection



