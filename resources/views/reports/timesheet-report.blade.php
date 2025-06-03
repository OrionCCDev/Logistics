@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Filters Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title"><i class="fa fa-filter"></i> Report Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.timesheet') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="date_from">Date From</label>
                                <input type="date" class="form-control" name="date_from"
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="date_to">Date To</label>
                                <input type="date" class="form-control" name="date_to"
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="project_id">Project</label>
                                <select class="form-control" name="project_id">
                                    <option value="">All Projects</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}"
                                                {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="user_id">User</label>
                                <select class="form-control" name="user_id">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="supplier_id">Supplier</label>
                                <select class="form-control" name="supplier_id">
                                    <option value="">All Suppliers</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                                {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fa fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('reports.timesheet') }}" class="btn btn-secondary btn-block mt-1">
                                        <i class="fa fa-refresh"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Total Entries</h5>
                            <h3>{{ $timesheets->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>Total Hours</h5>
                            <h3>{{ number_format($timesheets->sum('working_hours'), 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5>Total Fuel</h5>
                            <h3>{{ number_format($timesheets->sum('fuel_consumption'), 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5>Avg Efficiency</h5>
                            <h3>{{ $timesheets->sum('working_hours') > 0 ? number_format($timesheets->sum('fuel_consumption') / $timesheets->sum('working_hours'), 2) : '0' }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Table -->










































            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa fa-table"></i> Timesheet Report</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="timesheetReportTable" class="table table-hover table-bordered align-middle datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Project</th>
                                    <th>Vehicle</th>
                                    <th>Work Hours</th>
                                    <th>Fuel Consumption</th>
                                    <th>Efficiency</th>
                                    <th>Supplier</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($timesheets as $timesheet)
                                    <tr data-search="{{ strtolower(($timesheet->project?->name ?? '') . ' ' . ($timesheet->vehicle?->plate_number ?? '') . ' ' . ($timesheet->vehicle?->supplier?->name ?? '') . ' ' . ($timesheet->user?->name ?? '')) }}">
                                        <td data-order="{{ $timesheet->date ? $timesheet->date->format('Y-m-d') : '1900-01-01' }}">
                                            {{ $timesheet->date ? $timesheet->date->format('d M, Y') : 'N/A' }}
                                        </td>
                                        <td>{{ $timesheet->user?->name ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->project?->name ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->vehicle?->plate_number ?? 'N/A' }}</td>
                                        <td class="text-right">{{ $timesheet->working_hours ?? 'N/A' }}</td>
                                        <td class="text-right">{{ $timesheet->fuel_consumption ?? 'N/A' }}</td>
                                        <td class="text-right">
                                            {{ ($timesheet->working_hours > 0) ? number_format($timesheet->fuel_consumption / $timesheet->working_hours, 2) : 'N/A' }}
                                        </td>
                                        <td>{{ $timesheet->vehicle?->supplier?->name ?? 'N/A' }}</td>
                                        <td title="{{ $timesheet->note ?? '' }}">
                                            {{ $timesheet->note ? Str::limit($timesheet->note, 30) : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No timesheet data found for the selected criteria.</td>
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
    // Custom configuration for timesheet report table
    window.dataTableConfigs = window.dataTableConfigs || {};
    window.dataTableConfigs['timesheetReportTable'] = {
        order: [[0, 'desc']], // Sort by date descending
        columnDefs: [
            {
                targets: [4, 5, 6], // Numeric columns
