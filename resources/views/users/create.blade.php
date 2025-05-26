@extends('layouts.app')

@section('page_title', 'Orion Users Create')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('users.index') }}" type="button" class="btn btn-outline-primary">Users</a>
</div>
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
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group text-center">
                            <img id="img-preview" style="text-align: center; max-width: 200px; max-height: 200px;" src="{{ asset('dashAssets/dist/img/img-thumb.jpg') }}" class="img-fluid img-thumbnail" alt="img">
                        </div>
                        <div class="form-group">
                            <label class="control-label mb-10" for="profile_image">User Image</label>
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Upload</span>
                                </div>
                                <div class="form-control text-truncate @error('profile_image') is-invalid @enderror" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
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
                                    <span class="input-group-text"><i class="icon-user"></i></span>
                                </div>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" placeholder="Username">
                            </div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="control-label mb-10" for="emp_code">Orion ID</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-user"></i></span>
                                </div>
                                <input type="text" class="form-control @error('emp_code') is-invalid @enderror" name="emp_code" id="emp_code" value="{{ old('emp_code') }}" placeholder="Orion ID">
                            </div>
                            @error('emp_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="control-label mb-10" for="mobile">Orion Mobile</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-user"></i></span>
                                </div>
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" id="mobile" value="{{ old('mobile') }}" placeholder="Orion Mobile">
                            </div>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="role">Level</label>
                            <select class="form-control custom-select mt-15 @error('role') is-invalid @enderror" name="role" id="role">
                                @if(auth()->user()->role == 'orionAdmin')
                                <option value="orionAdmin" {{ old('role') == 'orionAdmin' ? 'selected' : '' }}>Admin</option>
                                <option value="orionManager" {{ old('role') == 'orionManager' ? 'selected' : '' }}>Manager</option>
                                @endif
                                @if(auth()->user()->role == 'orionManager' || auth()->user()->role == 'orionAdmin')
                                <option value="orionDC" {{ old('role') == 'orionDC' ? 'selected' : '' }}>Document Controller</option>
                                <option value="orionUser" {{ old('role') == 'orionUser' ? 'selected' : '' }}>Employee</option>
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
                                    <span class="input-group-text"><i class="icon-envelope-open"></i></span>
                                </div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" placeholder="Enter email">
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="control-label mb-10" for="password">Password ( Orion@123 )</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-lock"></i></span>
                                </div>
                                <input type="password" value="Orion@123" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter Password">
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="control-label mb-10" for="branch_id">Branch</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-building"></i></span>
                                </div>
                                <select class="form-control @error('branch_id') is-invalid @enderror" name="branch_id" id="branch_id">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                                    <span class="input-group-text"><i class="icon-briefcase"></i></span>
                                </div>
                                <select class="form-control @error('projects') is-invalid @enderror" name="projects[]" id="projects" multiple>

                                    @forelse($projects as $project)
                                        <option value="{{ $project->id }}" {{ in_array($project->id, old('projects', [])) ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ $project->branch->name ?? 'No Branch' }})
                                        </option>
                                    @empty
                                        <option value="" disabled>No projects available</option>
                                    @endforelse
                                </select>
                                @error('projects')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Total projects: {{ $projects->count() }}</small>
                        </div>

                        <div class="form-group">
                            <label class="control-label mb-10" for="is_active">Status</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-flag"></i></span>
                                </div>
                                <select class="form-control @error('is_active') is-invalid @enderror" name="is_active" id="is_active">
                                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mr-10">Submit</button>
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
    $('#profile_image').on('change', function(event) {
        if (event.target.files.length > 0) {
            let src = URL.createObjectURL(event.target.files[0]);
            let preview = document.getElementById('img-preview');
            preview.src = src;
        }
    });

    // Store all projects data
    const allProjects = @json($projects);
    console.log('All projects:', allProjects);

    // Function to filter projects based on branch
    function filterProjectsByBranch(branchId) {
        const projectSelect = $('#projects');
        projectSelect.empty();

        // Add default option
        projectSelect.append(new Option('Select Projects', ''));

        if (!branchId) {
            return;
        }

        const filteredProjects = allProjects.filter(project => project.branch_id == branchId);
        console.log('Filtered projects for branch ' + branchId + ':', filteredProjects);

        if (filteredProjects.length === 0) {
            projectSelect.append(new Option('No projects available for this branch', '', true, true));
        } else {
            filteredProjects.forEach(project => {
                projectSelect.append(new Option(project.name, project.id));
            });
        }
    }

    // Handle branch change
    $('#branch_id').on('change', function() {
        const selectedBranch = $(this).val();
        console.log('Selected branch:', selectedBranch);
        filterProjectsByBranch(selectedBranch);
    });

    // Initialize projects on page load if branch is selected
    $(document).ready(function() {
        const selectedBranch = $('#branch_id').val();
        console.log('Initial branch selection:', selectedBranch);
        if (selectedBranch) {
            filterProjectsByBranch(selectedBranch);
        }
    });
</script>
@endsection