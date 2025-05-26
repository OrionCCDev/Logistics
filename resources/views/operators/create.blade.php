@extends('layouts.app')

@section('styles')
<!-- Remove Jasny Bootstrap CSS -->
@endsection

@section('page_title', 'Create Operator')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('operators.index') }}" type="button" class="btn btn-outline-primary">Operators</a>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Operator</h3>

                </div>
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

                    <form action="{{ route('operators.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="image">Profile Image</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" onchange="handleImagePreview(this, 'image-preview')" id="image" name="image" accept="image/*">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="text-center mt-2">
                                        <img id="image-preview" style="max-width: 200px; max-height: 200px;" src="{{ asset('dashAssets/dist/img/img-thumb.jpg') }}" class="img-fluid img-thumbnail" alt="Profile Preview">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="front_license_image">Front License Image</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" onchange="handleImagePreview(this, 'front_license_image-preview')" id="front_license_image" name="front_license_image" accept="image/*">
                                        <label class="custom-file-label" for="front_license_image">Choose file</label>
                                    </div>
                                    @error('front_license_image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="text-center mt-2">
                                        <img id="front_license_image-preview" style="max-width: 200px; max-height: 200px;" src="{{ asset('dashAssets/dist/img/img-thumb.jpg') }}" class="img-fluid img-thumbnail" alt="Front License Preview">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="back_license_image">Back License Image</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" onchange="handleImagePreview(this, 'back_license_image-preview')" id="back_license_image" name="back_license_image" accept="image/*">
                                        <label class="custom-file-label" for="back_license_image">Choose file</label>
                                    </div>
                                    @error('back_license_image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="text-center mt-2">
                                        <img id="back_license_image-preview" style="max-width: 200px; max-height: 200px;" src="{{ asset('dashAssets/dist/img/img-thumb.jpg') }}" class="img-fluid img-thumbnail" alt="Back License Preview">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="supplier_id">Supplier</label>
                                    <select class="form-control @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="vehicle_id">Vehicle</label>
                                    <select class="form-control @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id">
                                        <option value="">Select Vehicle</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->plate_number }} - {{ $vehicle->vehicle_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="license_number">License Number</label>
                                    <input type="text" class="form-control @error('license_number') is-invalid @enderror" id="license_number" name="license_number" value="{{ old('license_number') }}">
                                </div>

                                <div class="form-group">
                                    <label for="license_expiry_date">License Expiry Date</label>
                                    <input type="date" class="form-control @error('license_expiry_date') is-invalid @enderror" id="license_expiry_date" name="license_expiry_date" value="{{ old('license_expiry_date') }}">
                                </div>

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Create Operator
                                </button>
                                <a href="{{ route('operators.index') }}" class="btn btn-secondary ml-2">
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
    // Global function for handling image previews
    function handleImagePreview(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
                // Update the label with the file name
                input.nextElementSibling.textContent = input.files[0].name;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {
        // Initialize select2 for dropdowns
        $('#supplier_id, #vehicle_id').select2({
            theme: 'bootstrap4'
        });
    });
</script>
@endsection
