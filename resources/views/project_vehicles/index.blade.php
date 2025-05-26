@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Project Vehicle Assignments</h3>
                    <div class="card-tools">
                        <a href="{{ route('project-vehicles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Assign Vehicle to Project
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Vehicle</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($projectVehicles as $projectVehicle)
                                    <tr>
                                        <td><a href="{{ route('projects.show', $projectVehicle->project_id) }}">{{ $projectVehicle->project->name ?? 'N/A' }}</a></td>
                                        <td><a href="{{ route('vehicles.show', $projectVehicle->vehicle_id) }}">{{ $projectVehicle->vehicle->plate_number ?? 'N/A' }}</a></td>
                                        <td>{{ $projectVehicle->start_date ? $projectVehicle->start_date->format('d M Y') : 'N/A' }}</td>
                                        <td>{{ $projectVehicle->end_date ? $projectVehicle->end_date->format('d M Y') : 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $projectVehicle->status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($projectVehicle->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $projectVehicle->notes ?? 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('project-vehicles.show', $projectVehicle) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('project-vehicles.edit', $projectVehicle) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('project-vehicles.destroy', $projectVehicle) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this vehicle from the project?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No project vehicles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $projectVehicles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
