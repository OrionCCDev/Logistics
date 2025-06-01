@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">&larr; Back to Project Reports</a>
        <a href="{{ route('projects.print_fuel_consumption_summary', ['project' => $project->id, 'fromDate' => $fromDate, 'toDate' => $toDate]) }}" target="_blank" class="btn btn-info">Print</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title mb-2">Timesheet Data for Project: <strong>{{ $project->name }} ({{ $project->code ?? '' }})</strong></h4>
            <div class="mb-2">
                <span class="badge bg-info p-3">From: {{ $fromDate }}</span>
                <span class="badge bg-info p-3">To: {{ $toDate }}</span>
            </div>
            <div class="mb-4">
                @php
                    $totalFuel = $timesheets->getCollection()->sum('fuel_consumption');
                    $totalHours = $timesheets->getCollection()->sum('working_hours');
                @endphp
                <span class="fs-4 fw-bold px-4 py-2 rounded bg-primary text-white me-2">Total Fuel Consumption: {{ number_format($totalFuel, 2) }}</span>
                <span class="fs-4 fw-bold px-4 py-2 rounded bg-success text-white">Total Working Hours: {{ number_format($totalHours, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Date</th>
                            <th>Working Start</th>
                            <th>Working End</th>
                            <th>Working Hours</th>
                            <th>Fuel Consumption</th>
                            <th>Break Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timesheets as $ts)
                            <tr>
                                <td>{{ $ts->vehicle ? $ts->vehicle->plate_number : 'N/A' }}</td>
                                <td>{{ $ts->date ? \Carbon\Carbon::parse($ts->date)->format('Y-m-d') : '' }}</td>
                                <td>{{ $ts->working_start_hour ? \Carbon\Carbon::parse($ts->working_start_hour)->format('Y-m-d H:i') : '' }}</td>
                                <td>{{ $ts->working_end_hour ? \Carbon\Carbon::parse($ts->working_end_hour)->format('Y-m-d H:i') : '' }}</td>
                                <td>{{ number_format($ts->working_hours, 2) }}</td>
                                <td>{{ number_format($ts->fuel_consumption, 2) }}</td>
                                <td>
                                    @if($ts->break_duration_minutes)
                                        {{ floor($ts->break_duration_minutes / 60) }}:{{ str_pad($ts->break_duration_minutes % 60, 2, '0', STR_PAD_LEFT) }}
                                    @else
                                        0:00
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No timesheet data found for this project and date range.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
                {{ $timesheets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
