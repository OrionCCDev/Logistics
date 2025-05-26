@extends('layouts.app')

@section('page_title', 'Edit Vehicle')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('vehicles.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Vehicles
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Vehicle</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('vehicles.update', $vehicle) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="plate_number">Plate Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('plate_number') is-invalid @enderror" id="plate_number" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required>
                                        @error('plate_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type', $vehicle->vehicle_type) }}" required>
                                        @error('vehicle_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_model">Vehicle Model <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('vehicle_model') is-invalid @enderror" id="vehicle_model" name="vehicle_model" value="{{ old('vehicle_model', $vehicle->vehicle_model) }}" required>
                                        @error('vehicle_model')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_year">Vehicle Year <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('vehicle_year') is-invalid @enderror" id="vehicle_year" name="vehicle_year" value="{{ old('vehicle_year', $vehicle->vehicle_year) }}" required>
                                        @error('vehicle_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control @error('vehicle_status') is-invalid @enderror" id="vehicle_status" name="vehicle_status" required>
                                            <option value="">Select Status</option>
                                            <option value="active" {{ old('vehicle_status', $vehicle->vehicle_status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('vehicle_status', $vehicle->vehicle_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="maintenance" {{ old('vehicle_status', $vehicle->vehicle_status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        </select>
                                        @error('vehicle_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_lpo_number">LPO Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('vehicle_lpo_number') is-invalid @enderror" id="vehicle_lpo_number" name="vehicle_lpo_number" value="{{ old('vehicle_lpo_number', $vehicle->vehicle_lpo_number) }}" required>
                                        @error('vehicle_lpo_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="operator_id" class="form-label">
                                            Assign Operator/Driver <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control custom-select form-control-lg select2-livewire @error('operator_id') is-invalid @enderror"
                                                id="operator_id"
                                                name="operator_id">
                                            <option value="">Select Operator</option>
                                            @foreach($operators as $operator)
                                                <option value="{{ $operator->id }}" {{ old('operator_id', $vehicle->operators->first()?->id) == $operator->id ? 'selected' : '' }}>
                                                    {{ $operator->name }} ({{ $operator->license_number }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('operator_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="project_id" class="form-label">
                                            Assign to Project <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control custom-select form-control-lg select2-livewire @error('project_id') is-invalid @enderror"
                                                id="project_id"
                                                name="project_id">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" {{ old('project_id', $vehicle->projects->first()?->id) == $project->id ? 'selected' : '' }}>
                                                    {{ $project->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('project_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="supplier_id" class="form-label">
                                            Choose Supplier <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control custom-select form-control-lg select2-livewire @error('supplier_id') is-invalid @enderror"
                                                id="supplier_id"
                                                name="supplier_id">
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $vehicle->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="vehicle_image">Vehicle Image</label>
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div class="text-center">
                                                    <img id="vehicle_image-preview" style="max-width: 200px; max-height: 200px;"
                                                         src="{{ $vehicle->vehicle_image ? asset( $vehicle->vehicle_image) : asset('dashAssets/dist/img/img-thumb.jpg') }}"
                                                         class="img-fluid img-thumbnail" alt="Vehicle Image Preview">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Upload</span>
                                                    </div>
                                                    <div class="form-control text-truncate @error('vehicle_image') is-invalid @enderror" data-trigger="fileinput">
                                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                        <span class="fileinput-filename"></span>
                                                    </div>
                                                    <span class="input-group-append">
                                                        <span class="btn btn-primary btn-file">
                                                            <span class="fileinput-new">Select file</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input type="file" name="vehicle_image" id="vehicle_image" accept="image/*">
                                                        </span>
                                                        <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                    </span>
                                                </div>
                                                @error('vehicle_image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_lpo_document">LPO Document <span class="text-danger">*</span></label>
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div id="vehicle_lpo_document-preview" class="preview-container">
                                                    <div id="vehicle_lpo_document-placeholder" class="text-center p-3 border {{ $vehicle->vehicle_lpo_document ? 'd-none' : '' }}">
                                                        <i class="icon-file-text" style="font-size: 48px;"></i>
                                                        <p class="mt-2">No file selected</p>
                                                    </div>
                                                    @if($vehicle->vehicle_lpo_document)
                                                        <div class="text-center p-3">
                                                            <i class="icon-file-text" style="font-size: 48px;"></i>
                                                            <p class="mt-2">Document Available</p>
                                                            <a href="{{ asset($vehicle->vehicle_lpo_document) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="fa fa-eye"></i> View Document
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <iframe id="vehicle_lpo_document-iframe" style="width: 100%; height: 200px; border: 1px solid #ddd; display: none;"></iframe>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Upload</span>
                                                    </div>
                                                    <div class="form-control text-truncate @error('vehicle_lpo_document') is-invalid @enderror" data-trigger="fileinput">
                                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                        <span class="fileinput-filename"></span>
                                                    </div>
                                                    <span class="input-group-append">
                                                        <span class="btn btn-primary btn-file">
                                                            <span class="fileinput-new">Select file</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input type="file" name="vehicle_lpo_document" id="vehicle_lpo_document" accept=".pdf,.doc,.docx">
                                                        </span>
                                                        <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                    </span>
                                                </div>
                                                @error('vehicle_lpo_document')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_mulkia_document">Mulkia Document <span class="text-danger">*</span></label>
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div id="vehicle_mulkia_document-preview" class="preview-container">
                                                    <div id="vehicle_mulkia_document-placeholder" class="text-center p-3 border {{ $vehicle->vehicle_mulkia_document ? 'd-none' : '' }}">
                                                        <i class="icon-file-text" style="font-size: 48px;"></i>
                                                        <p class="mt-2">No file selected</p>
                                                    </div>
                                                    @if($vehicle->vehicle_mulkia_document)
                                                        <div class="text-center p-3">
                                                            <i class="icon-file-text" style="font-size: 48px;"></i>
                                                            <p class="mt-2">Document Available</p>
                                                            <a href="{{ asset($vehicle->vehicle_mulkia_document) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="fa fa-eye"></i> View Document
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <iframe id="vehicle_mulkia_document-iframe" style="width: 100%; height: 200px; border: 1px solid #ddd; display: none;"></iframe>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Upload</span>
                                                    </div>
                                                    <div class="form-control text-truncate @error('vehicle_mulkia_document') is-invalid @enderror" data-trigger="fileinput">
                                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                        <span class="fileinput-filename"></span>
                                                    </div>
                                                    <span class="input-group-append">
                                                        <span class="btn btn-primary btn-file">
                                                            <span class="fileinput-new">Select file</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input type="file" name="vehicle_mulkia_document" id="vehicle_mulkia_document" accept=".pdf,.doc,.docx">
                                                        </span>
                                                        <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                    </span>
                                                </div>
                                                @error('vehicle_mulkia_document')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Vehicle</button>
                            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Function to handle file previews
    function handleFilePreview(input, previewId, placeholderId, iframeId) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileType = file.type;

            // For images
            if (fileType.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(`#${previewId}`).attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
            // For PDFs and documents
            else if (fileType === 'application/pdf' || fileType.includes('document')) {
                const url = URL.createObjectURL(file);
                $(`#${placeholderId}`).addClass('d-none');
                $(`#${previewId}`).find('.text-center').hide();
                $(`#${iframeId}`).show().attr('src', url);
            }
        } else {
            // Reset preview if no file selected
            if (previewId.includes('vehicle_image')) {
                $(`#${previewId}`).attr('src', '{{ asset('dashAssets/dist/img/img-thumb.jpg') }}');
            } else {
                $(`#${placeholderId}`).removeClass('d-none');
                $(`#${previewId}`).find('.text-center').show();
                $(`#${iframeId}`).hide().attr('src', '');
            }
        }
    }

    // Handle vehicle image preview
    $('#vehicle_image').on('change', function() {
        handleFilePreview(this, 'vehicle_image-preview');
    });

    // Handle LPO document preview
    $('#vehicle_lpo_document').on('change', function() {
        handleFilePreview(this, 'vehicle_lpo_document-preview', 'vehicle_lpo_document-placeholder', 'vehicle_lpo_document-iframe');
    });

    // Handle Mulkia document preview
    $('#vehicle_mulkia_document').on('change', function() {
        handleFilePreview(this, 'vehicle_mulkia_document-preview', 'vehicle_mulkia_document-placeholder', 'vehicle_mulkia_document-iframe');
    });

    // Handle file input clear
    $('.fileinput-exists[data-dismiss="fileinput"]').on('click', function() {
        const input = $(this).closest('.fileinput').find('input[type="file"]');
        const inputId = input.attr('id');

        if (inputId === 'vehicle_image') {
            $('#vehicle_image-preview').attr('src', '{{ asset('dashAssets/dist/img/img-thumb.jpg') }}');
        } else if (inputId === 'vehicle_lpo_document') {
            $('#vehicle_lpo_document-placeholder').removeClass('d-none');
            $('#vehicle_lpo_document-preview').find('.text-center').show();
            $('#vehicle_lpo_document-iframe').hide().attr('src', '');
        } else if (inputId === 'vehicle_mulkia_document') {
            $('#vehicle_mulkia_document-placeholder').removeClass('d-none');
            $('#vehicle_mulkia_document-preview').find('.text-center').show();
            $('#vehicle_mulkia_document-iframe').hide().attr('src', '');
        }
    });
</script>

<style>
    .preview-container {
        min-height: 200px;
        background: #f8f9fa;
        border-radius: 4px;
    }
    .preview-container .icon-file-text {
        color: #6c757d;
    }
</style>
@endsection
