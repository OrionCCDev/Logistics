@extends('layouts.app')

@section('page_title', 'Edit Operator')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('operators.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Operators
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('operators.update', $operator) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $operator->name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                                    <select class="form-control custom-select form-control-lg select2-livewire @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" >
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $operator->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="vehicle_id">Vehicle <span class="text-danger">*</span></label>
                                    <select class="form-control custom-select form-control-lg select2-livewire @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id" >
                                        <option value="">Select Vehicle</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $operator->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->plate_number }} - {{ $vehicle->vehicle_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="license_number">License Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('license_number') is-invalid @enderror" id="license_number" name="license_number" value="{{ old('license_number', $operator->license_number) }}" >
                                </div>

                                <div class="form-group">
                                    <label for="license_expiry_date">License Expiry Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('license_expiry_date') is-invalid @enderror" id="license_expiry_date" name="license_expiry_date" value="{{ old('license_expiry_date', $operator->license_expiry_date ? $operator->license_expiry_date->format('Y-m-d') : '') }}" >
                                </div>

                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control custom-select form-control-lg select2-livewire @error('status') is-invalid @enderror" id="status" name="status" >
                                        <option value="active" {{ old('status', $operator->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $operator->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Profile Image Upload -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="image">Profile Image</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                                <div class="form-control text-truncate @error('image') is-invalid @enderror" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-append">
                                                    <span class="btn btn-primary btn-file">
                                                        <span class="fileinput-new">Select file</span>
                                                        <span class="fileinput-exists">Change</span>
                                                        <input onchange="handleFilePreview(this, 'profileImagePreview')" type="file" name="image" id="image" accept="image/*">
                                                    </span>
                                                    <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                </span>
                                            </div>
                                            @error('image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <img id="profileImagePreview" style="max-width: 200px; max-height: 200px;"
                                                     src="{{ $operator->image ? asset($operator->image) : asset('dashAssets/dist/img/img-thumb.jpg') }}"
                                                     class="img-fluid img-thumbnail" alt="Profile Image Preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Front License Image Upload -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="front_license_image">Front License Image</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                                <div class="form-control text-truncate @error('front_license_image') is-invalid @enderror" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-append">
                                                    <span class="btn btn-primary btn-file">
                                                        <span class="fileinput-new">Select file</span>
                                                        <span class="fileinput-exists">Change</span>
                                                        <input onchange="handleFilePreview(this, 'frontLicensePreview')" type="file" name="front_license_image" id="front_license_image" accept="image/*">
                                                    </span>
                                                    <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                </span>
                                            </div>
                                            @error('front_license_image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <img id="frontLicensePreview" style="max-width: 200px; max-height: 200px;"
                                                     src="{{ $operator->front_license_image ? asset($operator->front_license_image) : asset('dashAssets/dist/img/img-thumb.jpg') }}"
                                                     class="img-fluid img-thumbnail" alt="Front License Preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Back License Image Upload -->
                                <div class="form-group">
                                    <label class="control-label mb-10" for="back_license_image">Back License Image</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                                <div class="form-control text-truncate @error('back_license_image') is-invalid @enderror" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-append">
                                                    <span class="btn btn-primary btn-file">
                                                        <span class="fileinput-new">Select file</span>
                                                        <span class="fileinput-exists">Change</span>
                                                        <input onchange="handleFilePreview(this, 'backLicensePreview')" type="file" name="back_license_image" id="back_license_image" accept="image/*">
                                                    </span>
                                                    <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                </span>
                                            </div>
                                            @error('back_license_image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <img id="backLicensePreview" style="max-width: 200px; max-height: 200px;"
                                                     src="{{ $operator->back_license_image ? asset($operator->back_license_image) : asset('dashAssets/dist/img/img-thumb.jpg') }}"
                                                     class="img-fluid img-thumbnail" alt="Back License Preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Operator
                                </button>
                                <a href="{{ route('operators.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
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
    $(document).ready(function() {
        // Initialize select2 for dropdowns
        $('select.select2-livewire').select2({
            theme: 'bootstrap4'
        });

        // Initialize fileinput for all file inputs
        $('.fileinput').fileinput({
            showUpload: false,
            showCaption: false,
            browseClass: "btn btn-primary",
            removeClass: "btn btn-secondary",
            previewFileIcon: '<i class="fa fa-file"></i>',
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            maxFileSize: 2048, // 2MB
            msgSizeTooLarge: 'File size is too large. Maximum allowed size is 2MB.',
            msgInvalidFileExtension: 'Invalid file extension. Allowed extensions are jpg, jpeg, png, gif.',
            previewFileType: 'image',
            initialPreviewAsData: true,
            overwriteInitial: true,
            showRemove: true,
            showClose: false,
            showBrowse: true,
            browseLabel: 'Select file',
            removeLabel: 'Remove',
            browseIcon: '<i class="fa fa-folder-open"></i>',
            removeIcon: '<i class="fa fa-times"></i>',
            removeTitle: 'Cancel or reset changes',
            elErrorContainer: '#errorBlock',
            msgErrorClass: 'alert alert-block alert-danger',
            defaultPreviewContent: '<img src="{{ asset("dashAssets/dist/img/img-thumb.jpg") }}" alt="Your Avatar" style="width:160px">',
            layoutTemplates: {
                main1: '{preview}\n' +
                    '<div class="kv-upload-progress hide"></div>\n' +
                    '<div class="input-group {class}">\n' +
                    '   {caption}\n' +
                    '   <div class="input-group-append">\n' +
                    '       {remove}\n' +
                    '       {cancel}\n' +
                    '       {browse}\n' +
                    '   </div>\n' +
                    '</div>',
                caption: '<div class="file-caption form-control kv-fileinput-caption">\n' +
                    '   <span class="file-caption-icon"></span>\n' +
                    '   <div class="file-caption-name"></div>\n' +
                    '</div>'
            }
        });
    });

    // Function to handle file previews
    function handleFilePreview(input, previewId, placeholderId = null, iframeId = null) {
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

    // Handle profile image preview
    $('#image').on('change', function() {
        handleFilePreview(this, 'profileImagePreview');
    });

    // Handle front license preview
    $('#front_license_image').on('change', function() {
        handleFilePreview(this, 'frontLicensePreview');
    });

    // Handle back license preview
    $('#back_license_image').on('change', function() {
        handleFilePreview(this, 'backLicensePreview');
    });

    // Handle file input clear
    $('.fileinput-exists[data-dismiss="fileinput"]').on('click', function() {
        const input = $(this).closest('.fileinput').find('input[type="file"]');
        const inputId = input.attr('id');

        if (inputId === 'image') {
            $('#profileImagePreview').attr('src', '{{ asset('dashAssets/dist/img/img-thumb.jpg') }}');
        } else if (inputId === 'front_license_image') {
            $('#frontLicensePreview').attr('src', '{{ asset('dashAssets/dist/img/img-thumb.jpg') }}');
        } else if (inputId === 'back_license_image') {
            $('#backLicensePreview').attr('src', '{{ asset('dashAssets/dist/img/img-thumb.jpg') }}');
        }
    });
</script>
@endsection
