@extends('layouts.app')

@section('page_title', 'Edit Supplier')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('suppliers.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Suppliers
    </a>
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
                    <form action="{{ route('suppliers.update', $supplier) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Company Name -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="name">Company Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-building"></i></span>
                                </div>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $supplier->name) }}" placeholder="Enter company name">
                            </div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Logo Upload -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="logo">Company Logo</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                        <div class="form-control text-truncate @error('logo') is-invalid @enderror" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-append">
                                            <span class="btn btn-primary btn-file">
                                                <span class="fileinput-new">Select file</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="logo" id="logo" accept="image/*">
                                            </span>
                                            <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                        </span>
                                    </div>
                                    @error('logo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <img id="logo-preview" style="max-width: 200px; max-height: 200px;"
                                             src="{{ $supplier->logo_path ? asset($supplier->logo_path) : asset('dashAssets/dist/img/img-thumb.jpg') }}"
                                             class="img-fluid img-thumbnail" alt="Logo Preview">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trade License Upload -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="trade_license">Trade License</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                        <div class="form-control text-truncate @error('trade_license') is-invalid @enderror" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-append">
                                            <span class="btn btn-primary btn-file">
                                                <span class="fileinput-new">Select file</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="trade_license" id="trade_license" accept=".pdf,.doc,.docx">
                                            </span>
                                            <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                        </span>
                                    </div>
                                    @error('trade_license')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div id="trade_license-preview" class="preview-container">
                                        <iframe id="trade_license-iframe" style="width: 100%; height: 200px; border: 1px solid #ddd;" class="d-none"></iframe>
                                        <div id="trade_license-placeholder" class="text-center p-3 border">
                                            @if($supplier->trade_license_path)
                                                <div class="mb-3">
                                                    <a href="{{ asset($supplier->trade_license_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ asset($supplier->trade_license_path) }}" download class="btn btn-sm btn-outline-success">
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                </div>
                                            @else
                                                <i class="icon-file-text" style="font-size: 48px;"></i>
                                                <p class="mt-2">No file selected</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- VAT Certificate Upload -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="vat_certificate">VAT Certificate</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                        <div class="form-control text-truncate @error('vat_certificate') is-invalid @enderror" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-append">
                                            <span class="btn btn-primary btn-file">
                                                <span class="fileinput-new">Select file</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="vat_certificate" id="vat_certificate" accept=".pdf,.doc,.docx">
                                            </span>
                                            <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                        </span>
                                    </div>
                                    @error('vat_certificate')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div id="vat_certificate-preview" class="preview-container">
                                        <iframe id="vat_certificate-iframe" style="width: 100%; height: 200px; border: 1px solid #ddd;" class="d-none"></iframe>
                                        <div id="vat_certificate-placeholder" class="text-center p-3 border">
                                            @if($supplier->vat_certificate_path)
                                                <div class="mb-3">
                                                    <a href="{{ asset($supplier->vat_certificate_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ asset($supplier->vat_certificate_path) }}" download class="btn btn-sm btn-outline-success">
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                </div>
                                            @else
                                                <i class="icon-file-text" style="font-size: 48px;"></i>
                                                <p class="mt-2">No file selected</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statement Upload -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="statement">Statement</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                        <div class="form-control text-truncate @error('statement') is-invalid @enderror" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-append">
                                            <span class="btn btn-primary btn-file">
                                                <span class="fileinput-new">Select file</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="statement" id="statement" accept=".pdf,.doc,.docx">
                                            </span>
                                            <a href="#" class="btn btn-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                        </span>
                                    </div>
                                    @error('statement')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div id="statement-preview" class="preview-container">
                                        <iframe id="statement-iframe" style="width: 100%; height: 200px; border: 1px solid #ddd;" class="d-none"></iframe>
                                        <div id="statement-placeholder" class="text-center p-3 border">
                                            @if($supplier->statement_path)
                                                <div class="mb-3">
                                                    <a href="{{ asset($supplier->statement_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ asset($supplier->statement_path) }}" download class="btn btn-sm btn-outline-success">
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                </div>
                                            @else
                                                <i class="icon-file-text" style="font-size: 48px;"></i>
                                                <p class="mt-2">No file selected</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Name -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="contact_name">Contact Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-user"></i></span>
                                </div>
                                <input type="text" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" id="contact_name" value="{{ old('contact_name', $supplier->contact_name) }}" placeholder="Enter contact name">
                            </div>
                            @error('contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="contact_email">Contact Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-envelope-open"></i></span>
                                </div>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" name="contact_email" id="contact_email" value="{{ old('contact_email', $supplier->contact_email) }}" placeholder="Enter contact email">
                            </div>
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="phone">Phone Number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-phone"></i></span>
                                </div>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" value="{{ old('phone', $supplier->phone) }}" placeholder="Enter phone number">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="address">Address</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-location-pin"></i></span>
                                </div>
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3" placeholder="Enter address">{{ old('address', $supplier->address) }}</textarea>
                            </div>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="description">Description</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </div>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="3" placeholder="Enter description">{{ old('description', $supplier->description) }}</textarea>
                            </div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="category_id">Category</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-tag"></i></span>
                                </div>
                                <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $supplier->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label class="control-label mb-10" for="status">Status</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-check"></i></span>
                                </div>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                                    <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Supplier</button>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@section('scripts')
<script>
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

    // Handle logo preview
    $('#logo').on('change', function() {
        handleFilePreview(this, 'logo-preview');
    });

    // Handle trade license preview
    $('#trade_license').on('change', function() {
        handleFilePreview(this, null, 'trade_license-placeholder', 'trade_license-iframe');
    });

    // Handle VAT certificate preview
    $('#vat_certificate').on('change', function() {
        handleFilePreview(this, null, 'vat_certificate-placeholder', 'vat_certificate-iframe');
    });

    // Handle statement preview
    $('#statement').on('change', function() {
        handleFilePreview(this, null, 'statement-placeholder', 'statement-iframe');
    });

    // Handle file input clear
    $('.fileinput-exists[data-dismiss="fileinput"]').on('click', function() {
        const input = $(this).closest('.fileinput').find('input[type="file"]');
        const inputId = input.attr('id');

        if (inputId === 'logo') {
            $('#logo-preview').attr('src', '{{ asset('dashAssets/dist/img/img-thumb.jpg') }}');
        } else {
            $(`#${inputId}-placeholder`).removeClass('d-none');
            $(`#${inputId}-iframe`).addClass('d-none').attr('src', '');
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
