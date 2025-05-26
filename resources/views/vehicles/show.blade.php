@extends('layouts.app')

@section('page_title', 'Vehicle Details')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('vehicles.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Vehicles
    </a>
</div>
<a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-pencil"></i></span>
    <span class="btn-text">Edit Vehicle</span>
</a>
<button type="button" class="btn btn-sm btn-danger btn-rounded btn-wth-icon icon-wthot-bg mb-15 mx-2" data-toggle="modal" data-target="#deleteVehicleModal">
    <span class="icon-label"><i class="fa fa-trash"></i></span>
    <span class="btn-text">Delete Vehicle</span>
</button>
<button type="button" class="btn btn-sm btn-success btn-rounded btn-wth-icon icon-wthot-bg mb-15" data-toggle="modal" data-target="#createTimesheetModal">
    <span class="icon-label"><i class="fa fa-plus"></i></span>
    <span class="btn-text">Create Timesheet</span>
</button>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 200px;">Plate Number</th>
                                            <td>{{ $vehicle->plate_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Vehicle Type</th>
                                            <td>{{ $vehicle->vehicle_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Vehicle Model</th>
                                            <td>{{ $vehicle->vehicle_model }}</td>
                                        </tr>
                                        <tr>
                                            <th>Vehicle Year</th>
                                            <td>{{ $vehicle->vehicle_year }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge badge-{{ $vehicle->vehicle_status === 'active' ? 'success' : ($vehicle->vehicle_status === 'maintenance' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($vehicle->vehicle_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>LPO Number</th>
                                            <td>{{ $vehicle->vehicle_lpo_number }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Documents & Images</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">Vehicle Image</h6>
                                                    @if($vehicle->vehicle_image)
                                                        <img src="{{ asset($vehicle->vehicle_image) }}" alt="Vehicle Image" class="img-fluid mb-2" style="max-height: 200px;">
                                                    @else
                                                        <div class="text-muted">No image available</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">LPO Document</h6>
                                                    @if($vehicle->vehicle_lpo_document)
                                                        <a href="{{ asset($vehicle->vehicle_lpo_document) }}" target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fa fa-file"></i> View LPO Document
                                                        </a>
                                                    @else
                                                        <div class="text-muted">No document available</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">Mulkia Document</h6>
                                                    @if($vehicle->vehicle_mulkia_document)
                                                        <a href="{{ asset( $vehicle->vehicle_mulkia_document) }}" target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fa fa-file"></i> View Mulkia Document
                                                        </a>
                                                    @else
                                                        <div class="text-muted">No document available</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($vehicle->projectVehicles->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Project Assignments</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Project</th>

                                                        <th>Status</th>
                                                        <th>Notes</th>
                                                        <th>Timesheet</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($vehicle->projectVehicles as $projectVehicle)
                                                        <tr>
                                                            <td><a href="{{ route('projects.show', $projectVehicle->project->id) }}">{{ $projectVehicle->project->name }}</a></td>

                                                            <td>
                                                                <span class="badge badge-{{ $projectVehicle->status === 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($projectVehicle->status) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $projectVehicle->notes ?? 'N/A' }}</td>
                                                            <td>
                                                                <a href="{{ route('vehicles.project.timesheet', ['vehicle' => $vehicle->id, 'project' => $projectVehicle->project->id]) }}" class="btn btn-xs btn-outline-primary">
                                                                    <i class="fa fa-clock-o"></i> View Timesheet
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Timesheet Modal -->
<div class="modal fade" id="createTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="createTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTimesheetModalLabel">Create New Timesheet for {{ $vehicle->plate_number }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('timesheets.storeForVehicle', $vehicle) }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required value="{{ old('date') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if($vehicle->projectVehicles->count() > 0)
                                <div class="form-group">
                                    <label for="project_id">Project</label>
                                    <select class="form-control" id="project_id" name="project_id">
                                        <option value="">Select Project (Optional)</option>
                                        @foreach($vehicle->projectVehicles as $projectVehicle)
                                            <option value="{{ $projectVehicle->project->id }}" {{ old('project_id') == $projectVehicle->project->id ? 'selected' : '' }}>
                                                {{ $projectVehicle->project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="project_id">Project</label>
                                    <select class="form-control" id="project_id" name="project_id" disabled>
                                        <option value="">No projects assigned to this vehicle</option>
                                    </select>
                                    <small class="form-text text-muted">Assign this vehicle to projects to select one here.</small>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="working_hours">Working Hours (Manual)</label>
                                <input type="number" step="0.01" class="form-control" id="working_hours" name="working_hours" value="{{ old('working_hours') }}" placeholder="e.g., 8.5" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="working_start_hour">Work Start Time</label>
                                <input type="datetime-local" class="form-control" id="working_start_hour" name="working_start_hour" value="{{ old('working_start_hour') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="working_end_hour">Work End Time</label>
                                <input type="datetime-local" class="form-control" id="working_end_hour" name="working_end_hour" value="{{ old('working_end_hour') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="break_start_at">Break Start Time</label>
                                <input type="datetime-local" class="form-control" id="break_start_at" name="break_start_at" value="{{ old('break_start_at') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="break_ends_at">Break End Time</label>
                                <input type="datetime-local" class="form-control" id="break_ends_at" name="break_ends_at" value="{{ old('break_ends_at') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="odometer_start">Odometer Start</label>
                                <input type="number" step="0.01" class="form-control" id="odometer_start" name="odometer_start" value="{{ old('odometer_start') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="odometer_ends">Odometer End</label>
                                <input type="number" step="0.01" class="form-control" id="odometer_ends" name="odometer_ends" value="{{ old('odometer_ends') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fuel_consumption_status">Fuel Consumption Calc</label>
                                <select class="form-control" id="fuel_consumption_status" name="fuel_consumption_status">
                                    <option value="">Select Method</option>
                                    <option value="by_hours" {{ old('fuel_consumption_status') == 'by_hours' ? 'selected' : '' }}>By Hours</option>
                                    <option value="by_odometer" {{ old('fuel_consumption_status') == 'by_odometer' ? 'selected' : '' }}>By Odometer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fuel_consumption">Fuel Consumption</label>
                                <input type="number" step="0.01" class="form-control" id="fuel_consumption" name="fuel_consumption" value="{{ old('fuel_consumption') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="deduction_amount">Deduction Amount</label>
                                <input type="number" step="0.01" class="form-control" id="deduction_amount" name="deduction_amount" value="{{ old('deduction_amount') }}">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create Timesheet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Vehicle Modal -->
<div class="modal fade" id="deleteVehicleModal" tabindex="-1" role="dialog" aria-labelledby="deleteVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVehicleModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the vehicle with plate number <strong>"{{ $vehicle->plate_number }}"</strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Vehicle</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const workingStartHourInput = document.getElementById('working_start_hour');
        const workingEndHourInput = document.getElementById('working_end_hour');
        const breakStartAtInput = document.getElementById('break_start_at');
        const breakEndsAtInput = document.getElementById('break_ends_at');
        const workingHoursInput = document.getElementById('working_hours');

        function calculateWorkingHours() {
            const workingStart = workingStartHourInput.value ? new Date(workingStartHourInput.value) : null;
            const workingEnd = workingEndHourInput.value ? new Date(workingEndHourInput.value) : null;
            const breakStart = breakStartAtInput.value ? new Date(breakStartAtInput.value) : null;
            const breakEnd = breakEndsAtInput.value ? new Date(breakEndsAtInput.value) : null;

            if (workingStart && workingEnd) {
                let workDurationMs = workingEnd - workingStart;

                if (breakStart && breakEnd && breakStart < breakEnd && breakStart > workingStart && breakEnd < workingEnd) {
                    const breakDurationMs = breakEnd - breakStart;
                    workDurationMs -= breakDurationMs;
                }

                if (workDurationMs > 0) {
                    const hours = workDurationMs / (1000 * 60 * 60);
                    workingHoursInput.value = hours.toFixed(2);
                } else {
                    workingHoursInput.value = '';
                }
            } else {
                workingHoursInput.value = '';
            }
        }

        [workingStartHourInput, workingEndHourInput, breakStartAtInput, breakEndsAtInput].forEach(input => {
            if (input) {
                input.addEventListener('change', calculateWorkingHours);
            }
        });

        // Initial calculation on page load if values are pre-filled (e.g., from old input)
        calculateWorkingHours();
    });
</script>
@endpush

