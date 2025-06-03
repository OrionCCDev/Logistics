@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Reports Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Timesheets</h5>
                            <h3 class="mb-0">{{ number_format($totalTimesheets) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Projects</h5>
                            <h3 class="mb-0">{{ number_format($totalProjects) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-project-diagram fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Vehicles</h5>
                            <h3 class="mb-0">{{ number_format($totalVehicles) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-truck fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Users</h5>
                            <h3 class="mb-0">{{ number_format($totalUsers) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Reports -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa fa-chart-bar"></i> Available Reports</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fa fa-clock fa-3x text-primary mb-3"></i>
                                    <h5>Timesheet Report</h5>
                                    <p class="text-muted">Detailed timesheet entries with filters</p>
                                    <a href="{{ route('reports.timesheet') }}" class="btn btn-primary">
                                        <i class="fa fa-eye"></i> View Report
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fa fa-truck fa-3x text-success mb-3"></i>
                                    <h5>Vehicle Utilization</h5>
                                    <p class="text-muted">Vehicle usage and efficiency metrics</p>
                                    <a href="{{ route('reports.vehicle-utilization') }}" class="btn btn-success">
                                        <i class="fa fa-eye"></i> View Report
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fa fa-project-diagram fa-3x text-warning mb-3"></i>
                                    <h5>Project Summary</h5>
                                    <p class="text-muted">Project-wise resource allocation</p>
                                    <a href="{{ route('reports.project-summary') }}" class="btn btn-warning">
                                        <i class="fa fa-eye"></i> View Report
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fa fa-gas-pump fa-3x text-info mb-3"></i>
                                    <h5>Fuel Consumption</h5>
                                    <p class="text-muted">Fuel usage analysis and trends</p>
                                    <a href="{{ route('reports.fuel-consumption') }}" class="btn btn-info">
                                        <i class="fa fa-eye"></i> View Report
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa fa-history"></i> Recent Timesheet Entries</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="recentActivityTable" class="table table-hover table-bordered datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Project</th>
                                    <th>Vehicle</th>
                                    <th>Hours</th>
                                    <th>Fuel</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTimesheets as $timesheet)
                                    <tr>
                                        <td>{{ $timesheet->date ? $timesheet->date->format('d M, Y') : 'N/A' }}</td>
                                        <td>{{ $timesheet->user?->name ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->project?->name ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->vehicle?->plate_number ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->working_hours ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->fuel_consumption ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No recent activity found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize DataTable for recent activity
    window.dataTableConfigs = window.dataTableConfigs || {};
    window.dataTableConfigs['recentActivityTable'] = {
        order: [[0, 'desc']], // Sort by date descending
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fa fa-copy"></i> Copy',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'csv',
                text: '<i class="fa fa-file-csv"></i> CSV',
                className: 'btn btn-success btn-sm',
                filename: 'recent_activity_' + new Date().toISOString().slice(0,10)
            },
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                filename: 'recent_activity_' + new Date().toISOString().slice(0,10)
            },
            {
                extend: 'pdf',
                text: '<i class="fa fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                filename: 'recent_activity_' + new Date().toISOString().slice(0,10),
                title: 'Recent Timesheet Activity'
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Print',
                className: 'btn btn-info btn-sm',
                title: 'Recent Timesheet Activity'
            }
        ],
        language: {
            search: "Search Recent Activity:",
            emptyTable: "No recent timesheet entries found",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            lengthMenu: "Show _MENU_ entries",
            loadingRecords: "Loading...",
            processing: "Processing...",
            zeroRecords: "No matching records found"
        },
        responsive: true,
        dom: 'Bfrtip'
    };
</script>
@endpush
