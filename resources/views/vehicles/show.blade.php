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
                                                        <th>Timesheet</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($vehicle->projectVehicles as $projectVehicle)
                                                        <tr>
                                                            <td><a href="{{ route('projects.show', $projectVehicle->project->id) }}">{{ $projectVehicle->project->name }}</a></td>


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

    {{-- Fuel Consumption History --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Fuel Consumption History</h5>
                </div>
                <div class="card-body">
                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('vehicles.show', $vehicle) }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from">From</label>
                                    <input type="date" id="date_from" name="date_from" class="form-control"
                                        value="{{ $date_from ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to">To</label>
                                    <input type="date" id="date_to" name="date_to" class="form-control"
                                        value="{{ $date_to ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="period">Period</label>
                                    <select id="period" name="period" class="form-control">
                                        <option value="">Select Period</option>
                                        <option value="this_week" {{ ($period ?? '') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                        <option value="this_month" {{ ($period ?? '') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                        <option value="this_year" {{ ($period ?? '') == 'this_year' ? 'selected' : '' }}>This Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="project_id">Project</label>
                                    <select id="project_id" name="project_id" class="form-control">
                                        <option value="">All Projects</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ (request('project_id', $project_id ?? '') == $project->id) ? 'selected' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 align-self-end">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- End Filter Form --}}

                    @if($fuelConsumptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Project</th>

                                        <th>Total Working Hours</th>
                                        <th>Fuel Consumption</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fuelConsumptions as $timesheet)
                                        <tr>
                                            <td>{{ $timesheet->date->format('d M Y') }}</td>
                                            <td>{{ $timesheet->project->name ?? 'N/A' }}</td>

                                            <td>
                                                {{ number_format($timesheet->working_hours, 2) }}
                                            </td>
                                            <td>{{ number_format($timesheet->fuel_consumption, 2) }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $fuelConsumptions->withQueryString()->links('pagination::bootstrap-4') }}
                    @else
                        <p class="text-muted mb-0">No fuel consumption data found for the selected period.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- End Fuel Consumption History --}}

    {{-- Full Timesheet History (Livewire) --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Full Timesheet History</h5>
                </div>
                <div class="card-body">
                    @livewire('vehicle-timesheet-table', ['vehicle' => $vehicle])
                </div>
            </div>
        </div>
    </div>
    {{-- End Full Timesheet History --}}

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
            @livewire('create-vehicle-timesheet-form', ['vehicle' => $vehicle])
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
        // Remove the old JavaScript calculation logic for working_hours
        // as it's now handled by the Livewire component.

        // Optional: Listen for Livewire event to close modal or refresh parts of the page
        Livewire.on('timesheetCreated', () => {
            // Close the modal
            $('#createTimesheetModal').modal('hide');
            // Optionally, you might want to refresh the Livewire timesheet table if it's on this page
            // Livewire.dispatch('refreshVehicleTimesheetTable'); // Assuming your table component listens for this
            // Or show a success message using a toast library if you have one
        });
    });
</script>
@endpush

