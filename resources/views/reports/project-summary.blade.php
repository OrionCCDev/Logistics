@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Filters Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Project Summary Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.project-summary') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="project_id">Select Project</label>
                                <select class="form-control" name="project_id" id="project_id">
                                    <option value="">-- Select Project --</option>
                                    {{-- TODO: Populate this select dynamically with projects --}}
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date_from">Date From</label>
                                <input type="date" class="form-control" name="date_from"
                                       value="{{ request('date_from', date('Y-m-01')) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="date_to">Date To</label>
                                <input type="date" class="form-control" name="date_to"
                                       value="{{ request('date_to', date('Y-m-t')) }}">
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-cogs"></i> Generate Report
                                </button>
                                {{--  <a href="{{ route('reports.project-summary') }}" class="btn btn-secondary">
                                    <i class="fa fa-refresh"></i> Reset Filters
                                </a>  --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TODO: Add a section here to display the datatable with timesheet details for the selected project and date range --}}
            @if(isset($timesheets) && $timesheets->count() > 0)
                <div class="alert alert-info" role="alert">
                    Total Records: {{ $timesheets->count() }}
                </div>
            @endif

            <!-- Example Datatable Structure (you will need to populate this dynamically) -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Timesheet Details</h5>
                </div>
                <div class="card-body">
                    <table id="timesheet-table" class="table table-bordered table-hover w-100">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Vehicle</th>
                                <th>Supplier</th>
                                <th>Created By</th>
                                <th>Hours</th>
                                <th>Fuel Consumption</th>
                                <th>Description</th>
                                {{-- Add other relevant columns --}}
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data rows will go here --}}
                            @if(isset($timesheets))
                                @foreach($timesheets as $timesheet)
                                    <tr>
                                        <td>{{ $timesheet->date }}</td>
                                        <td>{{ $timesheet->project->name ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->vehicle->plate_number ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->vehicle->supplier->name ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->user->name ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->working_hours }}</td>
                                        <td>{{ $timesheet->fuel_consumption }}</td>
                                        <td>{{ $timesheet->note }}</td>
                                        {{-- Add data for other relevant columns --}}
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        @if(isset($timesheets) && $timesheets->count() > 0)
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Total:</th>
                                    <th>{{ $timesheets->sum('working_hours') }}</th>
                                    <th>{{ $timesheets->sum('fuel_consumption') }}</th>
                                    <th colspan="1"></th> {{-- Adjust colspan based on your column count --}}
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- TODO: Add DataTables JavaScript initialization script here --}}
            {{-- Example: --}}
            {{-- @push('scripts') --}}
            {{-- <script> --}}
            {{--    $(document).ready(function() { --}}
            {{--        $('#timesheet-table').DataTable(); --}}
            {{--    }); --}}
            {{-- </script> --}}
            {{-- @endpush --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Get selected project name and dates from Blade variables
        var projectName = "{{ $projectName ?? 'Report' }}";
        var dateFrom = "{{ request('date_from', date('Y-m-01')) }}";
        var dateTo = "{{ request('date_to', date('Y-m-t')) }}";

        // Format the date range for the filename
        var dateRange = '';
        if (dateFrom && dateTo) {
            dateRange = '_' + dateFrom + '_to_' + dateTo;
        } else if (dateFrom) {
            dateRange = '_from_' + dateFrom;
        } else if (dateTo) {
            dateRange = '_to_' + dateTo;
        }

        var exportFileName = projectName.replace(/[^a-z0-9]+/gi, '_') + dateRange;

        $('#timesheet-table').DataTable({
            dom: 'Bfrtip', // Show buttons, filtering, pagination, and table information
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: exportFileName
                },
                {
                    extend: 'excelHtml5',
                    title: exportFileName
                },
                {
                    extend: 'csvHtml5',
                    title: exportFileName
                },
                {
                    extend: 'pdfHtml5',
                    title: exportFileName
                },
                {
                    extend: 'print',
                    title: exportFileName
                }
            ],
            // Add other DataTables options here if needed, e.g., for server-side processing, column definitions, etc.
            // "processing": true,
            // "serverSide": true,
            // "ajax": "{{ route('reports.project-summary') }}", // Example if using server-side
            // "columns": [
            //     { data: 'date', name: 'date' },
            //     // Define other columns
            // ]
            "pageLength": 31, // Set default number of records per page
            "lengthMenu": [[10, 25, 50, 100, -1], ['10', '25', '50', '100', 'All']] // Provide options for number of records per page
        });
    });
</script>
@endpush
