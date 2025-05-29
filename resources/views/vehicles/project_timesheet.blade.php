@extends('layouts.app')

@section('page_title', 'Timesheet for Vehicle ' . $vehicle->plate_number . ' on Project ' . $project->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Timesheet: Vehicle <a href="{{ route('vehicles.show', $vehicle) }}">{{ $vehicle->plate_number }}</a>
                        on Project <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Vehicle Details
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($timesheets->isEmpty())
                        <div class="alert alert-info">
                            No timesheet entries found for this vehicle on this project.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Working Hours</th>
                                        <th>Odometer Start</th>
                                        <th>Odometer End</th>
                                        <th>Fuel Consumption</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($timesheets as $timesheet)
                                        <tr>
                                            <td>{{ $timesheet->date ? $timesheet->date->format('d M, Y') : 'N/A' }}</td>
                                            <td>{{ $timesheet->user->name ?? 'N/A' }}</td>
                                            <td>{{ $timesheet->working_hours ?? 'N/A' }}</td>
                                            <td>{{ $timesheet->odometer_start ?? 'N/A' }}</td>
                                            <td>{{ $timesheet->odometer_ends ?? 'N/A' }}</td>
                                            <td>{{ $timesheet->fuel_consumption ?? 'N/A' }} ({{ $timesheet->fuel_consumption_status ?? 'N/A' }})</td>
                                            <td>
                                                <span class="badge badge-{{ $timesheet->status === 'approved' ? 'success' : ($timesheet->status === 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($timesheet->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('timesheet.show', $timesheet) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                                {{-- Add other actions like edit/delete if needed --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $timesheets->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
