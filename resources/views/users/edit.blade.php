@extends('layouts.app')

@section('page_title', 'Edit User')

@section('page_actions')
<a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-arrow-left"></i></span>
    <span class="btn-text">Back to Users</span>
</a>
@endsection

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-sm">
                    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group text-center">
                            @if($user->employee && $user->employee->image)
                                <img id="img-preview" src="{{ asset($user->employee->image) }}" class="img-fluid img-thumbnail" alt="img" style="max-width: 200px; max-height: 200px;">
                            @else
                                <img id="img-preview" src="{{ asset('dashAssets/dist/img/img-thumb.jpg') }}" class="img-fluid img-thumbnail" alt="img" style="max-width: 200px; max-height: 200px;">
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label mb-10" for="profile_image">User Image</label>
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Upload</span>
                                </div>
                                <div class="form-control text-truncate @error('profile_image') is-invalid @enderror" data-trigger="fileinput">
                                    <i class="fa fa-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-append">
                                    <span class="btn btn-primary btn-file">
                                        <span class="fileinput-new">Select file</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="profile_image" id="profile_image" accept="image/*">
                                    </span>
                                    <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </span>
                            </div>
                            @error('profile_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="name">Username</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $user->name) }}" placeholder="Username">
                            </div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="emp_code">Orion ID</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                                </div>
                                <input type="text" class="form-control @error('emp_code') is-invalid @enderror" name="emp_code" id="emp_code" value="{{ old('emp_code', $user->employee ? $user->employee->emp_code : '') }}" placeholder="Orion ID">
                            </div>
                            @error('emp_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="mobile">Orion Mobile</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                </div>
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" id="mobile" value="{{ old('mobile', $user->employee ? $user->employee->mobile : '') }}" placeholder="Orion Mobile">
                            </div>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="role">Level</label>
                            <select class="form-control custom-select mt-15 @error('role') is-invalid @enderror" name="role" id="role">
                                @if(auth()->user()->role == 'orionAdmin')
                                <option value="orionAdmin" {{ old('role', $user->role) == 'orionAdmin' ? 'selected' : '' }}>Admin</option>
                                <option value="orionManager" {{ old('role', $user->role) == 'orionManager' ? 'selected' : '' }}>Manager</option>
                                @endif
                                @if(auth()->user()->role == 'orionManager' || auth()->user()->role == 'orionAdmin')
                                <option value="orionDC" {{ old('role', $user->role) == 'orionDC' ? 'selected' : '' }}>Document Controller</option>
                                <option value="orionUser" {{ old('role', $user->role) == 'orionUser' ? 'selected' : '' }}>Employee</option>
                                @endif
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="email">Email address</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email', $user->email) }}" placeholder="Enter email">
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="password">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Leave blank to keep current password">
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Leave blank to keep current password</small>
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="branch_id">Branch</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-building"></i></span>
                                </div>
                                <select class="form-control @error('branch_id') is-invalid @enderror" name="branch_id" id="branch_id">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->employee ? $user->employee->branch_id : '') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="projects">Projects</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-briefcase"></i></span>
                                </div>
                                <select class="form-control select2 @error('projects') is-invalid @enderror" name="projects[]" id="projects" multiple>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ in_array($project->id, old('projects', $user->employee ? $user->employee->projects->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ $project->branch->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('projects')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="is_active">Status</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-flag"></i></span>
                                </div>
                                <select class="form-control @error('is_active') is-invalid @enderror" name="is_active" id="is_active">
                                    <option value="1" {{ old('is_active', $user->employee ? $user->employee->is_active : 1) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', $user->employee ? $user->employee->is_active : 1) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mr-10">Update User</button>
                        <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview image before upload
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('img-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Initialize Select2 for projects
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select projects",
            allowClear: true
        });
    });
</script>
@endsection
