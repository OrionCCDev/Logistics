@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Project Vehicle Assignment Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('project-vehicles.edit', $projectVehicle) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('project-vehicles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Project Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 200px;">Project Name</th>
                                            <td>{{ $projectVehicle->project->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Project Status</th>
                                            <td>
                                                <span class="badge badge-{{ $projectVehicle->project->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($projectVehicle->project->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Vehicle Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 200px;">Plate Number</th>
                                            <td>{{ $projectVehicle->vehicle->plate_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Vehicle Type</th>
                                            <td>{{ $projectVehicle->vehicle->vehicle_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Vehicle Model</th>
                                            <td>{{ $projectVehicle->vehicle->vehicle_model }}</td>
                                        </tr>
                                        <tr>
                                            <th>Vehicle Year</th>
                                            <td>{{ $projectVehicle->vehicle->vehicle_year }}</td>
                                        </tr>
                                        <tr>
                                            <th>Vehicle Status</th>
                                            <td>
                                                <span class="badge badge-{{ $projectVehicle->vehicle->vehicle_status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($projectVehicle->vehicle->vehicle_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Assignment Details</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 200px;">Start Date</th>
                                            <td>{{ $projectVehicle->start_date ? $projectVehicle->start_date->format('d M Y') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>End Date</th>
                                            <td>{{ $projectVehicle->end_date ? $projectVehicle->end_date->format('d M Y') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge badge-{{ $projectVehicle->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($projectVehicle->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Notes</th>
                                            <td>{{ $projectVehicle->notes ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
