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
                                        <div id="logo-placeholder-container" class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; border: 1px solid #ddd; margin: auto; display: none;">
                                            <span id="logo-placeholder-text" class="text-secondary display-4"></span>
                                        </div>
                                        <img id="logo-preview" style="max-width: 200px; max-height: 200px; display: block;"
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
$(document).ready(function() {
    // Initialize Select2 for category dropdown
    $('#category_id').select2({
        placeholder: "Select a category",
        allowClear: true,
        // theme: "bootstrap4" // Optional: if you want Bootstrap 4 styling
    });

    // Function to update logo placeholder/preview
    function updateLogoDisplay() {
        var companyName = $('#name').val();
        var logoPreview = $('#logo-preview');
        var logoPlaceholderContainer = $('#logo-placeholder-container');
        var logoPlaceholderText = $('#logo-placeholder-text');
        var fileInput = $('#logo');
        var existingLogoPath = "{{ $supplier->logo_path ? asset($supplier->logo_path) : '' }}";
        var defaultThumb = "{{ asset('dashAssets/dist/img/img-thumb.jpg') }}";

        if (fileInput[0].files && fileInput[0].files[0]) {
            // A new file is selected for upload
            let reader = new FileReader();
            reader.onload = function(event) {
                logoPreview.attr('src', event.target.result).show();
                logoPlaceholderContainer.hide();
            }
            reader.readAsDataURL(fileInput[0].files[0]);
        } else if (existingLogoPath) {
            // No new file, but an existing logo exists
            logoPreview.attr('src', existingLogoPath).show();
            logoPlaceholderContainer.hide();
        } else if (companyName && companyName.trim() !== "") {
            // No new file, no existing logo, but company name is present
            logoPlaceholderText.text(companyName.trim().charAt(0).toUpperCase());
            logoPreview.hide();
            logoPlaceholderContainer.css('display', 'flex');
        } else {
            // No new file, no existing logo, no company name - show default thumbnail
            logoPreview.attr('src', defaultThumb).show();
            logoPlaceholderContainer.hide();
        }
    }

    // Handle logo file input change
    $('#logo').change(function() {
        updateLogoDisplay(); // This will handle showing the new preview
    });

    // Handle company name input change
    $('#name').on('input', function() {
        // Update display only if no file is selected and no existing logo path
        // Otherwise, file preview or existing logo takes precedence
        if (!($('#logo')[0].files && $('#logo')[0].files[0]) && !"{{ $supplier->logo_path }}") {
            updateLogoDisplay();
        }
    });

    // Handle fileinput remove (jasny-bootstrap specific)
    // This covers clicking the 'Remove' button for the logo
    $('.fileinput[data-provides="fileinput"]').on('clear.bs.fileinput', function () {
        // When logo is cleared, decide what to show based on company name
        // We need to reset the src of logo-preview to avoid it showing old blob url
        var defaultThumb = "{{ asset('dashAssets/dist/img/img-thumb.jpg') }}";
        $('#logo-preview').attr('src', defaultThumb); // Reset to placeholder or default
        // Then call updateLogoDisplay to correctly show text or thumb
        updateLogoDisplay();
    });

    // Initial call to set the correct state on page load
    updateLogoDisplay();

    // File preview for PDF/DOC files (existing logic)
    function setupFilePreview(inputId, previewId, placeholderId, iframeId, currentPath) {
        const inputElement = $('#' + inputId);
        const previewContainer = $('#' + previewId);
        const placeholder = $('#' + placeholderId);
        const iframe = $('#' + iframeId);

        function displayFilePreview(file) {
            if (file.type === "application/pdf" || file.type.startsWith("application/msword") || file.type.startsWith("application/vnd.openxmlformats-officedocument.wordprocessingml")) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    iframe.attr('src', event.target.result).removeClass('d-none');
                    placeholder.addClass('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                placeholder.html('<p class="mt-2">Preview not available for this file type.</p>').removeClass('d-none');
                iframe.addClass('d-none');
            }
        }

        if (currentPath) {
            // If there's an existing file, attempt to show its name or a link.
            // For simplicity, the original code showed view/download buttons; we can keep that behavior.
            // The placeholder div already contains the logic for showing existing file links.
            // So, if currentPath is set, the initial state is handled by the Blade template.
            // We only need to handle changes.
        } else {
             placeholder.removeClass('d-none').html('<i class="icon-file-text" style="font-size: 48px;"></i><p class="mt-2">No file selected</p>');
             iframe.addClass('d-none');
        }

        inputElement.change(function() {
            const file = this.files[0];
            if (file) {
                displayFilePreview(file);
            } else {
                // If file is cleared via input, reset to initial state (considering currentPath)
                iframe.addClass('d-none').attr('src', '');
                if (!currentPath) {
                    placeholder.removeClass('d-none').html('<i class="icon-file-text" style="font-size: 48px;"></i><p class="mt-2">No file selected</p>');
                } else {
                    // If there was a current path, clearing the input means we revert to showing that info (handled by Blade or needing specific JS to re-render that part)
                    // For now, we'll assume the placeholder logic in Blade for currentPath is sufficient if not re-rendering with JS.
                     // To ensure the placeholder with links shows up if a *new* file is selected then *removed*:
                    if ($('#' + placeholderId + ' a').length === 0) { // Check if links are not there
                         placeholder.removeClass('d-none').html('<i class="icon-file-text" style="font-size: 48px;"></i><p class="mt-2">No file selected</p>');
                    }
                }
            }
        });

        // Handle fileinput plugin's clear event for these document inputs
        inputElement.closest('.fileinput').on('clear.bs.fileinput', function() {
            iframe.addClass('d-none').attr('src', '');
            // Check if the original placeholder (with potential links) should be shown
            var initialPlaceholderContent = $('<div>').html($('#' + placeholderId).html()); // Get a copy
            if (currentPath) {
                // If there was an existing file, the placeholder already has the links.
                // Just ensure it's visible and iframe is hidden.
                placeholder.removeClass('d-none');
            } else {
                placeholder.removeClass('d-none').html('<i class="icon-file-text" style="font-size: 48px;"></i><p class="mt-2">No file selected</p>');
            }
        });
    }

    setupFilePreview('trade_license', 'trade_license-preview', 'trade_license-placeholder', 'trade_license-iframe', "{{ $supplier->trade_license_path }}");
    setupFilePreview('vat_certificate', 'vat_certificate-preview', 'vat_certificate-placeholder', 'vat_certificate-iframe', "{{ $supplier->vat_certificate_path }}");
    setupFilePreview('statement', 'statement-preview', 'statement-placeholder', 'statement-iframe', "{{ $supplier->statement_path }}");
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
