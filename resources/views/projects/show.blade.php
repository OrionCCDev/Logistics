@extends('layouts.app')

@section('page_title', 'Project Details')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('projects.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Projects
    </a>
</div>
<a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-pencil"></i></span>
    <span class="btn-text">Edit Project</span>
</a>
<button type="button" class="btn btn-sm btn-danger btn-rounded btn-wth-icon icon-wthot-bg mb-15 mx-2" data-toggle="modal" data-target="#deleteProjectModal">
    <span class="icon-label"><i class="fa fa-trash"></i></span>
    <span class="btn-text">Delete Project</span>
</button>
@endsection

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <div class="row">
                <div class="col-sm">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-briefcase"></i>
                                Project Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="project-info">
                                        <div class="row">
                                            <div class="info-group mb-4 col-md-6">
                                                <h6 class="text-muted mb-2">Basic Information</h6>
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar avatar-lg avatar-primary">
                                                            <i class="fa fa-briefcase"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h4 class="mb-1">{{ $project->name }}</h4>
                                                        <p class="text-muted mb-0">Project Code: {{ $project->code ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-group mb-4 col-md-6">
                                                <h6 class="text-muted mb-2">Project Details</h6>
                                                <div class="project-details">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-building text-primary me-2 mx-2"></i>
                                                        <span>Branch: {{ $project->branch->name ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-flag text-primary me-2 mx-2"></i>
                                                        <span>Status:
                                                            <span class="badge badge-{{ $project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'info' : 'warning') }}">
                                                                {{ ucfirst($project->status) }}
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-file-text text-primary me-2 mx-2"></i>
                                                        <span>Description: {{ $project->description ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-group mb-4 col-md-6">
                                                <h6 class="text-muted mb-2">Timeline</h6>
                                                <div class="timeline-info">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-calendar text-primary me-2 mx-2"></i>
                                                        <span>Start Date: {{ $project->start_date ? $project->start_date->format('M d, Y') : 'N/A' }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-calendar text-primary me-2 mx-2"></i>
                                                        <span>End Date: {{ $project->end_date ? $project->end_date->format('M d, Y') : 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-group mb-4 col-md-6">
                                                <h6 class="text-muted mb-2">System Information</h6>
                                                <div class="timeline-info">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-clock-o text-primary me-2 mx-2"></i>
                                                        <span>Created: {{ $project->created_at ? $project->created_at->format('M d, Y H:i A') : 'N/A' }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-refresh text-primary me-2 mx-2"></i>
                                                        <span>Updated: {{ $project->updated_at ? $project->updated_at->format('M d, Y H:i A') : 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Delete Project Modal -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProjectModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the project <strong>"{{ $project->name }}"</strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Project</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.project-info .info-group {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
}

.project-info .avatar {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--primary-rgb), 0.1);
    color: var(--primary);
    border-radius: 50%;
}

.project-info .project-details {
    padding: 0.5rem 0;
}

.project-info .timeline-info {
    padding: 0.5rem 0;
}

.badge {
    padding: 0.5em 1em;
    font-weight: 500;
}

.badge-success {
    background-color: #28a745;
}

.badge-warning {
    background-color: #ffc107;
}

.badge-info {
    background-color: #17a2b8;
}
</style>
@endsection
