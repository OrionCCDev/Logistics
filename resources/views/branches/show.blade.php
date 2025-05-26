@extends('layouts.app')

@section('page_title', 'Branch Details')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('branches.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Branches
    </a>
</div>
<a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-sm btn-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-pencil"></i></span>
    <span class="btn-text">Edit Branch</span>
</a>
<button type="button" class="btn btn-sm btn-danger btn-rounded btn-wth-icon icon-wthot-bg mb-15 mx-2" data-toggle="modal" data-target="#deleteBranchModal">
    <span class="icon-label"><i class="fa fa-trash"></i></span>
    <span class="btn-text">Delete Branch</span>
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
                                <i class="fa fa-map-marker"></i>
                                Branch Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="branch-info">
                                        <div class="row">
                                            <div class="info-group mb-4 col-md-6">
                                                <h6 class="text-muted mb-2">Basic Information</h6>
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar avatar-lg avatar-primary">
                                                            <i class="fa fa-home"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h4 class="mb-1">{{ $branch->name }}</h4>
                                                        <p class="text-muted mb-0">Branch Code: {{ $branch->code ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-group mb-4 col-md-6">
                                                <h6 class="text-muted mb-2">Contact Details</h6>
                                                <div class="contact-info">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-map text-primary me-2 mx-2"></i>
                                                        <span>{{ $branch->address ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-phone text-primary me-2 mx-2"></i>
                                                        <span>{{ $branch->phone ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-envelope text-primary me-2 mx-2"></i>
                                                        <span>{{ $branch->email ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-group mb-4 col-md-6">
                                                <h6 class="text-muted mb-2">Location</h6>
                                                <div class="d-flex align-items-center">
                                                    <i class="fa fa-globe text-primary me-2 mx-2"></i>
                                                    <span>{{ $branch->country->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>

                                            <div class="info-group mb-4 col-md-6">
                                                <h6 class="text-muted mb-2">Status & Timeline</h6>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge {{ $branch->is_active ? 'badge-success' : 'badge-danger' }} badge-pill">
                                                        {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                                <div class="timeline-info">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fa fa-clock-o text-primary me-2 mx-2"></i>
                                                        <span>Created: {{ $branch->created_at->format('M d, Y H:i A') }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-refresh text-primary me-2 mx-2"></i>
                                                        <span>Updated: {{ $branch->updated_at->format('M d, Y H:i A') }}</span>
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

<!-- Delete Branch Modal -->
<div class="modal fade" id="deleteBranchModal" tabindex="-1" role="dialog" aria-labelledby="deleteBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBranchModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the branch <strong>"{{ $branch->name }}"</strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Branch</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.branch-info .info-group {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
}

.branch-info .avatar {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--primary-rgb), 0.1);
    color: var(--primary);
    border-radius: 50%;
}

.branch-info .contact-info {
    padding: 0.5rem 0;
}

.branch-info .timeline-info {
    padding: 0.5rem 0;
}

.badge {
    padding: 0.5em 1em;
    font-weight: 500;
}

.badge-success {
    background-color: #28a745;
}

.badge-danger {
    background-color: #dc3545;
}
</style>
@endsection
