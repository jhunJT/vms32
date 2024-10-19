@extends('layouts.auth')
@section('content')

<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">

                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard-admins</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Main</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-truncate font-size-8 mb-2">Total Voters</p>
                                        <h4 class="mb-2">{{ number_format($totalRV) }}</h4>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-light text-primary rounded-3">
                                            <i class="ri-team-fill font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end cardbody -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-truncate font-size-14 mb-2">Total CV</p>
                                        <h4 class="mb-2">{{ number_format($totalCV) }}</h4>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-light text-success rounded-3">
                                            <i class="ri-group-fill font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end cardbody -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-truncate font-size-14 mb-2">Total Household</p>
                                        <h4 class="mb-2">{{ number_format($totalHL) }}</h4>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-light text-primary rounded-3">
                                            <i class="ri-user-heart-fill font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end cardbody -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-truncate font-size-14 mb-2">Total Members</p>
                                        <h4 class="mb-2">{{ number_format($totalCV - $totalHL) }}</h4>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-light text-success rounded-3">
                                            <i class="ri-group-2-fill font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end cardbody -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->
                <div class="row">
                    <div class="col-lg-6">

                        <div class="card card-body">
                            <h3 class="card-title"><strong>District I</strong></h3>
                            <table id="dist1" class=" table table-hover table-light " style="vertical-align: middle">
                                <thead>
                                    <tr>
                                        <th>Municipality</th>
                                        <th>RV</th>
                                        <th>HL</th>
                                        <th>Members</th>
                                        <th>MA</th>
                                        <th>Total CV</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dist1 as $data)
                                    <tr>
                                        <td>{{ $data->Municipality }}</td>
                                        <td class="text-center middle-align">{{ number_format($data->RV) }}</td>
                                        <td class="text-center">{{ number_format($data->HL) }}</td>
                                        <td class="text-center">{{ number_format($data->Members) }}</td>
                                        <td class="text-center">{{ number_format($data->MA) }}</td>
                                        <td class="text-center">{{ number_format($data->CV) }}</td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                data-muncit = "{{ $data->Municipality }}"
                                                class="btn btn-info viewSummary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="col-lg-6">

                        <div class="card card-body">
                            <h3 class="card-title"><strong>District II</strong></h3>
                            <table id="dist2" class=" table table-hover table-light " style="vertical-align: middle; width:100%" >
                                <thead>
                                    <tr>
                                        <th>Municipality</th>
                                        <th>RV</th>
                                        <th>HL</th>
                                        <th>Members</th>
                                        <th>MA</th>
                                        <th>Total CV</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dist2 as $data)
                                    <tr>
                                        <td>{{ $data->Municipality }}</td>
                                        <td>{{ $data->RV }}</td>
                                        <td>{{ $data->HL }}</td>
                                        <td>{{ $data->Members }}</td>
                                        <td>{{ $data->MA }}</td>
                                        <td>{{ $data->CV }}</td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                data-muncit = "{{ $data->Municipality }}"
                                                    class="btn btn-info viewSummary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div><!-- end row -->
        </div>
    </div>
</div>

<div id="cvsumm-modal-lg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">CV Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table" id="tblsumm">
                    <thead>
                        <tr>
                            <th>Barangay</th>
                            <th style="text-align: center">Registered Voter</th>
                            <th style="text-align: center">Houseleader</th>
                            <th style="text-align: center">Members</th>
                            <th style="text-align: center">MA</th>
                            <th style="text-align: center">CV</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Total:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    var dashboardGetMuncit = "{{ route('dashboard.getmuncit') }}";
    var dashboardGetSelectedMuncit = "{{ route('rv.manage.index','') }}";
</script>

<script src="{{ asset('assets/auth/js/admin.js') }}"></script>


@include('layouts.script')
@include('layouts.footer')
@endsection



