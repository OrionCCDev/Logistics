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
                        <table id="projectVehiclesTable" class="table table-bordered table-striped">
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
                        {{ $projectVehicles->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#projectVehiclesTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column
                    },
                    text: 'Copy' // Keep original text for script to identify
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column
                    },
                    text: 'CSV' // Keep original text for script to identify
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column
                    },
                    text: 'Excel' // Keep original text for script to identify
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column
                    },
                    text: 'PDF' // Keep original text for script to identify
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column
                    },
                    text: 'Print' // Keep original text for script to identify
                }
            ],
            // Add options here if needed
        });

        // Apply Bootstrap button classes and icons to the generated buttons
        $('#projectVehiclesTable').closest('.dataTables_wrapper').find('.dt-buttons .dt-button').each(function() {
            // Remove any existing inline styles or default classes
            $(this).removeAttr('style').removeClass('btn btn-primary btn-secondary btn-info btn-success btn-danger');

            // Get the button text to determine type
            const buttonText = $(this).find('span').text();
            let buttonClass = 'btn ';
            let iconHtml = '';

            if (buttonText === 'Copy') {
                buttonClass += 'btn-secondary';
                iconHtml = '<i class="fas fa-copy"></i> ';
            } else if (buttonText === 'CSV') {
                buttonClass += 'btn-info';
                iconHtml = '<i class="fas fa-file-csv"></i> ';
            } else if (buttonText === 'Excel') {
                buttonClass += 'btn-success';
                iconHtml = '<i class="fas fa-file-excel"></i> ';
            } else if (buttonText === 'PDF') {
                buttonClass += 'btn-danger';
                iconHtml = '<i class="fas fa-file-pdf"></i> ';
            } else if (buttonText === 'Print') {
                buttonClass += 'btn-primary';
                iconHtml = '<i class="fas fa-print"></i> ';
            }

            // Add the determined Bootstrap classes
            $(this).addClass(buttonClass);

            // Prepend the icon to the button text
            $(this).find('span').prepend(iconHtml);
        });
    });
</script>
@endpush
