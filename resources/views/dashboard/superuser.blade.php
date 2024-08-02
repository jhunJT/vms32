@extends('layouts.auth')
@section('content')

<div class="page-content">
    <div class="content-fluid">
        <div class="row">
            <div class="col-12">

                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard - Superuser</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Main</a></li>
                            <li class="breadcrumb-item active">Dashboard->superuser</li>
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

                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <div class="col-xl-8">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="card-title"><strong>District I</strong></h3>
                                <table id="dist1" class="table table-centered mb-0 align-middle table-hover table-nowrap" style="vertical-align: middle">
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
                                                    class="btn btn-info viewSummary"><i class="mdi mdi-eye"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
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
                        </div>

                    </div>
                    <div class="col-xl-4">
                        <div class="card h-100">
                            <h4 class="card-title p-3"><strong>Statistics</strong></h4>
                            <div class="card-body stat-body">
                                <div class="chart-container">
                                    <canvas id="piestat1"></canvas>
                                    <canvas id="piestat2"></canvas>
                                </div>
                            </div>
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
                <h5 class="modal-title" id="myLargeModalLabel">CV Summarys</h5>
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
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#dist1').DataTable({
            "pageLength": 11,
            "bLengthChange": false,
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                var totalRV = api.column(1).data()
                    .reduce(function(a, b) {
                        return a + parseInt(b.replace(/,/g, ''), 10);}, 0);
                var totalHL = api.column(2).data()
                    .reduce(function(a, b) {
                        return a + parseInt(b.replace(/,/g, ''), 10);}, 0);
                var totalMembers = api.column(3).data()
                    .reduce(function(a, b) {
                        return a + parseInt(b.replace(/,/g, ''), 10);}, 0);
                var totalMA = api.column(4).data()
                    .reduce(function(a, b) {
                        return a + parseInt(b.replace(/,/g, ''), 10);}, 0);
                var totalCV = api.column(5).data()
                    .reduce(function(a, b) {
                        return a + parseInt(b.replace(/,/g, ''), 10);}, 0);

                function formatNumber(num) {
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }

                $(api.column(1).footer()).html('<div style="text-align: center;">' + formatNumber(totalRV) + '</div>');
                $(api.column(2).footer()).html('<div style="text-align: center;">' + formatNumber(totalHL) + '</div>');
                $(api.column(3).footer()).html('<div style="text-align: center;">' + formatNumber(totalMembers) + '</div>');
                $(api.column(4).footer()).html('<div style="text-align: center;">' + formatNumber(totalMA) + '</div>');
                $(api.column(5).footer()).html('<div style="text-align: center;">' + formatNumber(totalCV) + '</div>');
            }
        });


        var modalTable = $('#tblsumm').DataTable({
            destroy: true, // Destroy existing DataTable (if any)
            columns: [
                { data: 'Barangay', name: 'Barangay' },
                { data: 'RV', name: 'RV' },
                { data: 'HL', name: 'HL' },
                { data: 'Members', name: 'Members' },
                { data: 'MA', name: 'MA' },
                { data: 'CV', name: 'CV' }
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();

                // Check if the current page is the last page
                var lastPage = api.page.info().pages - 1;
                var currentPage = api.page.info().page;

                if (currentPage === lastPage) {
                    // Total the RV column
                    var totalRV = api.column(1).data()
                        .reduce(function(a, b) {
                            return parseInt(a) + parseInt(b);
                        }, 0);

                    // Total the HL column
                    var totalHL = api.column(2).data()
                        .reduce(function(a, b) {
                            return parseInt(a) + parseInt(b);
                        }, 0);

                    // Total the Members column
                    var totalMembers = api.column(3).data()
                        .reduce(function(a, b) {
                            return parseInt(a) + parseInt(b);
                        }, 0);

                    // Total the MA column
                    var totalMA = api.column(4).data()
                        .reduce(function(a, b) {
                            return parseInt(a) + parseInt(b);
                        }, 0);

                    // Total the CV column
                    var totalCV = api.column(5).data()
                        .reduce(function(a, b) {
                            return parseInt(a) + parseInt(b);
                        }, 0);

                    // Update footer
                    $(api.column(1).footer()).html(totalRV);
                    $(api.column(2).footer()).html(totalHL);
                    $(api.column(3).footer()).html(totalMembers);
                    $(api.column(4).footer()).html(totalMA);
                    $(api.column(5).footer()).html(totalCV);
                } else {
                    // Clear the footer if not on the last page
                    $(api.column(1).footer()).html('');
                    $(api.column(2).footer()).html('');
                    $(api.column(3).footer()).html('');
                    $(api.column(4).footer()).html('');
                    $(api.column(5).footer()).html('');
                }
            }
        });

        $('.viewSummary').on('click', function(){
            var sendmuncit = $(this).data('muncit'); // Get value of data-muncit attribute

            $.ajax({
                url: dashboardGetMuncit, // Replace with your controller URL
                method: 'POST',
                data: {
                    sendmuncit: sendmuncit
                },
                success: function(response) {
                    // console.log('Response from server:', response);
                    if (response && response.data && response.data.length > 0) {
                        // Clear existing rows and add new data to DataTable
                        modalTable.clear().rows.add(response.data).draw();

                        // Show the modal
                        $('#cvsumm-modal-lg').modal('show');
                    } else {
                        console.error('Empty or invalid data received.');
                        // Handle empty or invalid data scenario
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Status:', error);
                    console.error('Response:', error);
                    console.error('Error:', error);
                }
            });
        });

    });

var totalCV = "{{ ($totalCV) }}";
var totalRV = "{{ ($totalRV) }}";
var totalMA = "{{ ($totalMA) }}";

var dashboardGetMuncit = "{{ route('superuser.getmuncit') }}";
</script> =

<script src="{{ asset('assets/auth/libs/chart.js/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('assets/auth/js/charts/superuserchart.js') }}"></script>



@include('layouts.script')
@include('layouts.footer')
@endsection



