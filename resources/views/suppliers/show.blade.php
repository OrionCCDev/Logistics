@extends('layouts.app')

@section('page_title', 'Supplier Details')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('suppliers.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Suppliers
    </a>
</div>
<a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-pencil"></i></span>
    <span class="btn-text">Edit Supplier</span>
</a>
<button type="button" class="btn btn-sm btn-danger btn-rounded btn-wth-icon icon-wthot-bg mb-15 mx-2" data-toggle="modal" data-target="#deleteSupplierModal">
    <span class="icon-label"><i class="fa fa-trash"></i></span>
    <span class="btn-text">Delete Supplier</span>
</button>
@endsection

@section('content')
<div class="hk-row" id="supplier-show-page">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center mb-4">
                    <div class="profile-image-container">
                        @if($supplier->logo_path)
                            <img src="{{ asset($supplier->logo_path) }}" alt="{{ $supplier->name }}" width="200" class="rounded-circle profile-image">
                        @else
                            <div class="rounded-circle profile-image bg-light d-flex align-items-center justify-content-center">
                                <span class="text-muted display-4">{{ substr($supplier->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h2 class="mt-4 mb-2">{{ $supplier->name }}</h2>

                        <span class="badge {{ $supplier->status === 'active' ? 'badge-success' : 'badge-danger' }} badge-pill">
                            {{ ucfirst($supplier->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-info-circle text-primary me-2"></i>
                                Contact Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-lg avatar-primary">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Contact Name</h6>
                                                <p class="mb-0">{{ $supplier->contact_name }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-lg avatar-primary">
                                                    <i class="fa fa-envelope"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Email Address</h6>
                                                <p class="mb-0">{{ $supplier->contact_email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-lg avatar-primary">
                                                    <i class="fa fa-phone"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Phone Number</h6>
                                                <p class="mb-0">{{ $supplier->phone ?? 'Not provided' }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-lg avatar-primary">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Address</h6>
                                                <p class="mb-0">{{ $supplier->address }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-lg avatar-primary">
                                                    <i class="fa fa-tag"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Category</h6>
                                                <p class="mb-0">
                                                    @if($supplier->category)
                                                        <span class="badge badge-info">{{ $supplier->category->name }}</span>
                                                    @else
                                                        <span class="text-muted">No category</span>
                                                    @endif
                                                </p>
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
                                <i class="fa fa-file-text text-primary me-2"></i>
                                Documents
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class="fa fa-file-pdf-o text-danger fa-3x mb-3"></i>
                                            <h6 class="card-title">Trade License</h6>
                                            @if($supplier->trade_license_path)
                                                <a href="{{ asset($supplier->trade_license_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="fa fa-eye"></i> View Document
                                                </a>
                                            @else
                                                <p class="text-muted mb-0">Not uploaded</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class="fa fa-file-pdf-o text-danger fa-3x mb-3"></i>
                                            <h6 class="card-title">VAT Certificate</h6>
                                            @if($supplier->vat_certificate_path)
                                                <a href="{{ asset($supplier->vat_certificate_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="fa fa-eye"></i> View Document
                                                </a>
                                            @else
                                                <p class="text-muted mb-0">Not uploaded</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class="fa fa-file-pdf-o text-danger fa-3x mb-3"></i>
                                            <h6 class="card-title">Statement</h6>
                                            @if($supplier->statement_path)
                                                <a href="{{ asset($supplier->statement_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="fa fa-eye"></i> View Document
                                                </a>
                                            @else
                                                <p class="text-muted mb-0">Not uploaded</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($supplier->description)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fa fa-align-left text-primary me-2"></i>
                                Additional Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $supplier->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </section>
    </div>
</div>

<!-- Delete Supplier Modal -->
<div class="modal fade" id="deleteSupplierModal" tabindex="-1" role="dialog" aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSupplierModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the supplier <strong>"{{ $supplier->name }}"</strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Supplier</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

