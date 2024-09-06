@extends('layouts.auth')
@section('content')

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Main</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-8 mb-2">Total RV</p>
                                <h4 class="mb-2">{{  number_format($totalRV)  }}</h4>
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
                                <p class="text-truncate font-size-14 mb-2">Manual Added Voter</p>
                                <h4 class="mb-2">{{ number_format($totalMA) }}</h4>
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
                <div class="card" style="height: 700px;">
                    <div class="card-body">
                        <div id="map" style="height: 650px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card" style="height: 700px;">
                    <div class="card-body">
                        <div  style="height: 650px;">
                            <table class="table" id="tblsumm">
                                <thead>
                                    <tr>
                                        <th>Barangay</th>
                                        <th style="text-align: center">Registered Voter</th>
                                        <th style="text-align: center">Houseleader</th>
                                        <th style="text-align: center">Members</th>
                                        <th style="text-align: center">CV</th>
                                    </tr>
                                    <tbody>
                                    @foreach ($CVSummary as $data )
                                        <tr>
                                            <td>{{ $data->Barangay}}</td>
                                            <td style="text-align: center">{{ $data->RV}}</td>
                                            <td style="text-align: center">{{ $data->HL}}</td>
                                            <td style="text-align: center">{{ $data->Members}}</td>
                                            <td style="text-align: center">{{ $data->CV}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>

    var ulat = "{{ Auth::User()->ulat }}";
    var ulong = "{{ Auth::User()->ulong }}";
    var uloc = "{{ Auth::User()->muncit }}";

</script>

<script src="{{ asset('assets/auth/js/supervisor.js') }}"></script>

@include('layouts.script')
@include('layouts.footer')
@endsection



