@extends('layouts.app')

@section('page_title', 'Timesheet Details')

@section('page_actions')
<a href="{{ route('timesheet.index') }}" class="btn btn-outline-primary btn-sm mr-2">
    <i class="fa fa-chevron-left"></i> Back to Timesheets
</a>
<a href="{{ route('timesheet.edit', $timesheet->id) }}" class="btn btn-info btn-sm mr-2">
    <i class="fa fa-pencil"></i> Edit Entry
</a>
<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteTimesheetModal{{ $timesheet->id }}">
    <i class="fa fa-trash"></i> Delete Entry
</button>
@endsection

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="hk-sec-title">
                    Timesheet for {{ $timesheet->user?->name ?? 'N/A' }} on {{ $timesheet->date ? $timesheet->date->format('F j, Y') : 'N/A' }}
                </h5>
                 <span class="badge badge-lg badge-{{ strtolower($timesheet->status) == 'approved' ? 'success' : (strtolower($timesheet->status) == 'rejected' ? 'danger' : (strtolower($timesheet->status) == 'submitted' ? 'info' : 'secondary')) }}">
                    Status: {{ ucfirst($timesheet->status) }}
                </span>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">General Information</h6>
                            <table class="table table-sm table-borderless info-table">
                                <tr>
                                    <td width="30%"><strong>User Create This Timesheet:</strong></td>
                                    <td>{{ $timesheet->user?->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $timesheet->date ? $timesheet->date->format('d M, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Project:</strong></td>
                                    <td>{{ $timesheet->project?->project_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Vehicle:</strong></td>
                                    <td>{{ $timesheet->vehicle?->plate_number ?? 'N/A' }} ({{ $timesheet->vehicle?->make }} {{ $timesheet->vehicle?->model }})</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Working Hours & Breaks</h6>
                             <table class="table table-sm table-borderless info-table">
                                <tr>
                                    <td width="40%"><strong>Work Started:</strong></td>
                                    <td>{{ $timesheet->working_start_hour ? $timesheet->working_start_hour->format('h:i A') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Work Ended:</strong></td>
                                    <td>{{ $timesheet->working_end_hour ? $timesheet->working_end_hour->format('h:i A') : 'N/A' }}</td>
                                </tr>
                                 <tr>
                                    <td><strong>Break Started:</strong></td>
                                    <td>{{ $timesheet->break_start_at ? $timesheet->break_start_at->format('h:i A') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Break Ended:</strong></td>
                                    <td>{{ $timesheet->break_ends_at ? $timesheet->break_ends_at->format('h:i A') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Working Hours:</strong></td>
                                    <td>{{ $timesheet->working_hours ?? 'N/A' }} hrs</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Vehicle & Fuel Details</h6>
                            <table class="table table-sm table-borderless info-table">
                                <tr>
                                    <td width="40%"><strong>Odometer Start:</strong></td>
                                    <td>{{ $timesheet->odometer_start ? number_format($timesheet->odometer_start, 2) . ' km' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Odometer End:</strong></td>
                                    <td>{{ $timesheet->odometer_ends ? number_format($timesheet->odometer_ends, 2) . ' km' : 'N/A' }}</td>
                                </tr>
                                 <tr>
                                    <td><strong>Distance Travelled:</strong></td>
                                    <td>
                                        @if($timesheet->odometer_start && $timesheet->odometer_ends)
                                            {{ number_format($timesheet->odometer_ends - $timesheet->odometer_start, 2) }} km
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Fuel Consumption:</strong></td>
                                    <td>{{ $timesheet->fuel_consumption ? number_format($timesheet->fuel_consumption, 2) . ' gallons' : 'N/A' }}</td> <!-- Assuming Liters -->
                                </tr>
                                 <tr>
                                    <td><strong>Fuel Calc. Basis:</strong></td>
                                    <td>{{ $timesheet->fuel_consumption_status ? ucfirst(str_replace('_', ' ', $timesheet->fuel_consumption_status)) : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Financials & Notes</h6>
                            <table class="table table-sm table-borderless info-table">
                                <tr>
                                    <td width="40%"><strong>Deduction Amount:</strong></td>
                                    <td>{{-- Currency Symbol --}} {{ $timesheet->deduction_amount ? number_format($timesheet->deduction_amount, 2) : '0.00' }}</td>
                                </tr>
                                 <tr>
                                    <td colspan="2">
                                        <strong>Notes / Remarks:</strong><br>
                                        <p class="mb-0">{{ $timesheet->note ?? 'No notes provided.' }}</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-12">
                             <p class="small text-muted">
                                Created at: {{ $timesheet->created_at ? $timesheet->created_at->format('d M, Y h:i A') : 'N/A' }} |
                                Last updated: {{ $timesheet->updated_at ? $timesheet->updated_at->format('d M, Y h:i A') : 'N/A' }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTimesheetModal{{ $timesheet->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteTimesheetModalLabel{{ $timesheet->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTimesheetModalLabel{{ $timesheet->id }}">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this timesheet entry for {{ $timesheet->date ? $timesheet->date->format('d M, Y') : 'this entry' }}?
                This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('timesheet.destroy', $timesheet->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Timesheet</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.info-table td {
    padding-top: .25rem;
    padding-bottom: .25rem;
}
.info-table strong {
    font-weight: 500;
}
</style>
@endsection

