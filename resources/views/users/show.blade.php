@extends('layouts.app')

@section('page_title', 'User Details')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('users.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Users
    </a>
</div>
<a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-pencil"></i></span>
    <span class="btn-text">Edit User</span>
</a>
<button type="button" class="btn btn-sm btn-warning btn-rounded btn-wth-icon icon-wthot-bg mb-15 mx-2" data-toggle="modal" data-target="#resetPasswordModal">
    <span class="icon-label"><i class="fa fa-key"></i></span>
    <span class="btn-text">Reset Password</span>
</button>
<button type="button" class="btn btn-sm btn-danger btn-rounded btn-wth-icon icon-wthot-bg mb-15 mx-2" data-toggle="modal" data-target="#deleteUserModal">
    <span class="icon-label"><i class="fa fa-trash"></i></span>
    <span class="btn-text">Delete User</span>
</button>
@endsection

@section('content')
<div class="hk-row" id="user-show-page">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center mb-4">
                    <div class="profile-image-container">
                        @if($user->employee && $user->employee->image)
                            <img src="{{ asset($user->employee->image) }}" alt="{{ $user->name }}" class="rounded-circle profile-image">
                        @else
                            <img src="{{ asset('dashAssets/img/avatar-placeholder.png') }}" alt="{{ $user->name }}" class="rounded-circle profile-image">
                        @endif
                        <h2 class="mt-4 mb-2">{{ $user->name }}</h2>
                        <p class="text-muted mb-4">{{ $user->role }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-user text-primary me-2"></i>
                                User Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group mb-4">
                                        <h6 class="text-muted mb-2">Basic Information</h6>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-lg avatar-primary">
                                                    <i class="fa fa-envelope"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Email Address</h6>
                                                <p class="mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($user->employee)
                                <div class="col-md-6">
                                    <div class="info-group mb-4">
                                        <h6 class="text-muted mb-2">Employee Details</h6>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-lg avatar-primary">
                                                    <i class="fa fa-hashtag"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Employee Code</h6>
                                                <p class="mb-0">{{ $user->employee->emp_code }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-lg avatar-primary">
                                                    <i class="fa fa-phone"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Mobile Number</h6>
                                                <p class="mb-0">{{ $user->employee->mobile }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-lg avatar-primary">
                                                    <i class="fa fa-line-chart"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Status</h6>
                                                <span class="badge {{ $user->employee->is_active ? 'badge-success' : 'badge-danger' }} badge-pill">
                                                    {{ $user->employee->is_active ? 'Active' : 'Inactive' }}
                                                </span>
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

            @if($user->employee)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-building text-primary me-2"></i>
                                Branch Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-lg avatar-primary">
                                                    <i class="fa fa-building"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Branch Name</h6>
                                                <p class="mb-0">{{ $user->employee->branch->name ?? 'Not Assigned' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-briefcase text-primary me-2"></i>
                                Assigned Projects
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($user->employee->projects->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Project Name</th>
                                                <th>Code</th>
                                                <th>Branch</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->employee->projects as $project)
                                                <tr>
                                                    <td>{{ $project->name }}</td>
                                                    <td>{{ $project->code }}</td>
                                                    <td>{{ $project->branch->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'info' : 'warning') }}">
                                                            {{ ucfirst($project->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $project->start_date ? $project->start_date->format('Y-m-d') : 'N/A' }}</td>
                                                    <td>{{ $project->end_date ? $project->end_date->format('Y-m-d') : 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="fa fa-info-circle me-2"></i>
                                    No projects assigned to this user.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </section>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the user <strong>"{{ $user->name }}"</strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">
                    <i class="fa fa-key text-warning me-2"></i>
                    Reset Password
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reset the password for user <strong>"{{ $user->name }}"</strong>?</p>
                <p class="text-warning mb-0">The password will be reset to: <strong>Orion@123</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <form action="{{ route('users.reset-password', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-warning">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #527fdf;
    --primary-rgb: 82, 127, 223;
}

#user-show-page .profile-image-container {
    padding: 2rem 0;
}

#user-show-page .profile-image {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border: 4px solid var(--primary-color);
    box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.2);
}

#user-show-page .info-group {
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

#user-show-page .info-group:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

#user-show-page .avatar {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(var(--primary-rgb), 0.1);
    color: var(--primary-color);
    border-radius: 50%;
}

#user-show-page .badge {
    padding: 0.5em 1em;
    font-weight: 500;
}

#user-show-page .badge-success {
    background-color: #28a745;
}

#user-show-page .badge-danger {
    background-color: #dc3545;
}

#user-show-page .card {
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
}

#user-show-page .card-header {
    background: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
}

#user-show-page .card-body {
    padding: 1.5rem;
}

#user-show-page .text-primary {
    color: var(--primary-color) !important;
}

#user-show-page .btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

#user-show-page .btn-primary:hover {
    background-color: darken(var(--primary-color), 10%);
    border-color: darken(var(--primary-color), 10%);
}

#user-show-page .btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

#user-show-page .btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    color: #000;
}

#user-show-page .text-warning {
    color: #ffc107 !important;
}
</style>
@endsection

