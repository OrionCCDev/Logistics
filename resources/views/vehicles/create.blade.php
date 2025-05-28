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

                                    {{--  <div class="form-group col-md-4">
                                        <label for="vehicle_year">Vehicle Year <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('vehicle_year') is-invalid @enderror" id="vehicle_year" name="vehicle_year" value="{{ old('vehicle_year') }}" >
                                        @error('vehicle_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>  --}}

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

        // Handle vehicle image preview using Jasny Bootstrap event
        $('#vehicle_image').closest('.fileinput').on('change.bs.fileinput', function() {
            console.log("PREVIEW: #vehicle_image .fileinput change.bs.fileinput event fired.");
            const fileInput = $(this).find('input[type="file"]').get(0);
            if (fileInput) {
                handleImagePreview(fileInput, 'vehicle_image-preview');
            } else {
                console.error("PREVIEW: Could not find file input for vehicle_image.");
            }
        });

        // Handle LPO document preview using Jasny Bootstrap event
        $('#vehicle_lpo_document').closest('.fileinput').on('change.bs.fileinput', function() {
            console.log("PREVIEW: #vehicle_lpo_document .fileinput change.bs.fileinput event fired.");
            const fileInput = $(this).find('input[type="file"]').get(0);
            if (fileInput) {
                handleDocumentPreview(fileInput, 'vehicle_lpo_document-placeholder', 'vehicle_lpo_document-iframe');
            } else {
                console.error("PREVIEW: Could not find file input for vehicle_lpo_document.");
            }
        });

        // Handle Mulkia document preview using Jasny Bootstrap event
        $('#vehicle_mulkia_document').closest('.fileinput').on('change.bs.fileinput', function() {
            console.log("PREVIEW: #vehicle_mulkia_document .fileinput change.bs.fileinput event fired.");
            const fileInput = $(this).find('input[type="file"]').get(0);
            if (fileInput) {
                handleDocumentPreview(fileInput, 'vehicle_mulkia_document-placeholder', 'vehicle_mulkia_document-iframe');
            } else {
                console.error("PREVIEW: Could not find file input for vehicle_mulkia_document.");
            }
        });
    });

    // Function to handle image previews
    function handleImagePreview(input, previewImageId) {
        console.log("PREVIEW: handleImagePreview called for ", previewImageId);
        const previewImage = $(`#${previewImageId}`);
        if (input.files && input.files[0]) {
            console.log("PREVIEW: File selected for image: ", input.files[0].name, input.files[0].type);
            const reader = new FileReader();
            reader.onload = function(e) {
                console.log("PREVIEW: FileReader onload, setting image src.");
                previewImage.attr('src', e.target.result);
            }
            reader.onerror = function(e) {
                console.error("PREVIEW: FileReader error: ", e);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            console.log("PREVIEW: No file selected for image, resetting to default.");
            previewImage.attr('src', '{{ asset('dashAssets/dist/img/img-thumb.jpg') }}');
        }
    }

    // Function to handle document previews (PDF, DOC, DOCX)
    function handleDocumentPreview(input, placeholderId, iframeId) {
        console.log("PREVIEW: handleDocumentPreview called for ", placeholderId);
        const placeholder = $(`#${placeholderId}`);
        const iframe = $(`#${iframeId}`);

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileType = file.type;
            console.log("PREVIEW: File selected for document: ", file.name, fileType);

            if (fileType === 'application/pdf') {
                console.log("PREVIEW: PDF selected, creating object URL and setting iframe src.");
                const url = URL.createObjectURL(file);
                placeholder.addClass('d-none');
                iframe.attr('src', url).removeClass('d-none');
            } else if (fileType.includes('msword') || fileType.includes('officedocument') || fileType.includes('document')) {
                console.log("PREVIEW: DOC/DOCX or other document selected. Attempting to set iframe src (may not render).");
                const url = URL.createObjectURL(file);
                placeholder.addClass('d-none');
                iframe.attr('src', url).removeClass('d-none');
                // Note: Direct rendering of DOC/DOCX in iframe is often not supported by browsers.
                // Consider alternative for these types if direct preview is essential (e.g., just showing filename).
            } else {
                console.log("PREVIEW: Unsupported document type: ", fileType);
                placeholder.removeClass('d-none');
                iframe.addClass('d-none').attr('src', '');
                alert('Unsupported file type for document preview. Please select a PDF, DOC, or DOCX file.');
            }
        } else {
            console.log("PREVIEW: No file selected for document, resetting.");
            placeholder.removeClass('d-none');
            iframe.addClass('d-none').attr('src', '');
        }
    }

    // Handle file input clear (jasny-bootstrap event)
    $(document).on('filecleared.bs.fileinput', '.fileinput', function() {
        const input = $(this).find('input[type="file"]');
        const inputId = input.attr('id');
        console.log("PREVIEW: filecleared.bs.fileinput event fired for input: ", inputId);

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
