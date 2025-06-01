<div>
    <div class="container">
        <div class="mb-3">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">&larr; Back to Project Reports</a>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title mb-2">Timesheet Data for Project: <strong>{{ $project->name }} ({{ $project->code ?? '' }})</strong></h4>
                <div class="mb-2">
                    <span class="badge bg-info">From: {{ $fromDate }}</span>
                    <span class="badge bg-info">To: {{ $toDate }}</span>
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
</div>
