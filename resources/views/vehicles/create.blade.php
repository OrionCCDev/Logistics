@extends('layouts.app')

@section('page_title', 'Create Vehicle')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('vehicles.index') }}" type="button" class="btn btn-outline-primary">Vehicles</a>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Vehicle</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="project_id" class="form-label">
                                            Assign to Project <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control custom-select form-control-lg select2-livewire @error('project_id') is-invalid @enderror"
                                                id="project_id"
                                                name="project_id">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="plate_number">Plate Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('plate_number') is-invalid @enderror" id="plate_number" name="plate_number" value="{{ old('plate_number') }}" required>
                                        @error('plate_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type') }}" >
                                        @error('vehicle_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 ">
                                        <label for="vehicle_model">Vehicle Model <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('vehicle_model') is-invalid @enderror" id="vehicle_model" name="vehicle_model" value="{{ old('vehicle_model') }}" >
                                        @error('vehicle_model')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_year">Vehicle Year <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('vehicle_year') is-invalid @enderror" id="vehicle_year" name="vehicle_year" value="{{ old('vehicle_year') }}" >
                                        @error('vehicle_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control @error('vehicle_status') is-invalid @enderror" id="vehicle_status" name="vehicle_status" >
                                            <option value="">Select Status</option>
                                            <option value="active" {{ old('vehicle_status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('vehicle_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="maintenance" {{ old('vehicle_status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        </select>
                                        @error('vehicle_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="vehicle_lpo_number">LPO Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('vehicle_lpo_number') is-invalid @enderror" id="vehicle_lpo_number" name="vehicle_lpo_number" value="{{ old('vehicle_lpo_number') }}" >
                                        @error('vehicle_lpo_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="form-group col-md-4 ">
                                        <label for="vehicle_image">Vehicle Image</label>

                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div class="text-center">
                                                    <img id="vehicle_image-preview" style="max-width: 200px; max-height: 200px;" src="{{ asset('dashAssets/dist/img/img-thumb.jpg') }}" class="img-fluid img-thumbnail" alt="Vehicle Image Preview">
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
                                                    <iframe id="vehicle_lpo_document-iframe" style="width: 100%; height: 200px; border: 1px solid #ddd;" class="d-none"></iframe>
                                                    <div id="vehicle_lpo_document-placeholder" class="text-center p-3 border">
                                                        <i class="icon-file-text" style="font-size: 48px;"></i>
                                                        <p class="mt-2">No file selected</p>
                                                    </div>
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
                                                    <iframe id="vehicle_mulkia_document-iframe" style="width: 100%; height: 200px; border: 1px solid #ddd;" class="d-none"></iframe>
                                                    <div id="vehicle_mulkia_document-placeholder" class="text-center p-3 border">
                                                        <i class="icon-file-text" style="font-size: 48px;"></i>
                                                        <p class="mt-2">No file selected</p>
                                                    </div>
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
                                                <option value="{{ $operator->id }}" {{ old('operator_id') == $operator->id ? 'selected' : '' }}>
                                                    {{ $operator->name }} ({{ $operator->license_number }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('operator_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Vehicle</button>
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
    // Update custom file input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });

    // Initialize select2 for operator, project, and supplier
    $(document).ready(function() {
        $('#operator_id, #project_id, #supplier_id').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        });
    });

    // Function to handle file previews
    function handleFilePreview(input, previewId, placeholderId, iframeId = null) {
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
                $(`#${iframeId}`).removeClass('d-none').attr('src', url);
            }
        } else {
            // Reset preview if no file selected
            if (iframeId) {
                $(`#${placeholderId}`).removeClass('d-none');
                $(`#${iframeId}`).addClass('d-none').attr('src', '');
            } else {
                $(`#${previewId}`).attr('src', '{{ asset('dashAssets/dist/img/img-thumb.jpg') }}');
            }
        }
    }

    // Handle vehicle image preview
    $('#vehicle_image').on('change', function() {
        handleFilePreview(this, 'vehicle_image-preview');
    });

    // Handle trade license preview
    $('#vehicle_lpo_document').on('change', function() {
        handleFilePreview(this, null, 'vehicle_lpo_document-placeholder', 'vehicle_lpo_document-iframe');
    });

    // Handle VAT certificate preview
    $('#vehicle_mulkia_document').on('change', function() {
        handleFilePreview(this, null, 'vehicle_mulkia_document-placeholder', 'vehicle_mulkia_document-iframe');
    });

    // Handle statement preview

    // Handle file input clear
    $('.fileinput-exists[data-dismiss="fileinput"]').on('click', function() {
        const input = $(this).closest('.fileinput').find('input[type="file"]');
        const inputId = input.attr('id');

        if (inputId === 'vehicle_image') {
            $('#vehicle_image-preview').attr('src', '{{ asset('dashAssets/dist/img/img-thumb.jpg') }}');
        } else if (inputId === 'vehicle_lpo_document') {
            $('#vehicle_lpo_document-placeholder').removeClass('d-none');
            $('#vehicle_lpo_document-iframe').addClass('d-none').attr('src', '');
        } else if (inputId === 'vehicle_mulkia_document') {
            $('#vehicle_mulkia_document-placeholder').removeClass('d-none');
            $('#vehicle_mulkia_document-iframe').addClass('d-none').attr('src', '');
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
    .custom-bordered-select, .select2-container--bootstrap4 .select2-selection {
        border: 2px solid #527fdf !important;
        border-radius: 6px !important;
        min-height: 45px;
        font-size: 1rem;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        line-height: 2.2;
    }
</style>
@endsection
