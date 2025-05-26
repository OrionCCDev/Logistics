@extends('layouts.app')

@section('page_title', 'Operator Details')

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <a href="{{ route('operators.index') }}" type="button" class="btn btn-outline-primary">
        <i class="fa fa-arrow-left"></i> Back to Operators
    </a>
</div>
<a href="{{ route('operators.edit', $operator) }}" class="btn btn-sm btn-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-pencil"></i></span>
    <span class="btn-text">Edit Operator</span>
</a>
<button type="button" class="btn btn-sm btn-danger btn-rounded btn-wth-icon icon-wthot-bg mb-15 mx-2" data-toggle="modal" data-target="#deleteSupplierModal">
    <span class="icon-label"><i class="fa fa-trash"></i></span>
    <span class="btn-text">Delete Operator</span>
</button>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center mb-4">
                                @if($operator->image)
                                    <img src="{{ asset($operator->image) }}" alt="Operator Profile" class="img-thumbnail" style="max-width: 200px;">
                                @else
                                    <div class="img-thumbnail" style="width: 200px; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa fa-user" style="font-size: 64px; color: #6c757d;"></i>
                                    </div>
                                @endif
                            </div>

                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">Name</th>
                                    <td>{{ $operator->name }}</td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td>{{ $operator->supplier?->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Vehicle</th>
                                    <td>{{ $operator->vehicle ? ($operator->vehicle->plate_number . ' - ' . $operator->vehicle->vehicle_type) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>License Number</th>
                                    <td>{{ $operator->license_number }}</td>
                                </tr>
                                <tr>
                                    <th>License Expiry Date</th>
                                    <td>{{ $operator->license_expiry_date ? $operator->license_expiry_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $operator->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($operator->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Front License</h5>
                                        </div>
                                        <div class="card-body text-center">
                                            @if($operator->front_license_image)
                                                <img src="{{ asset($operator->front_license_image) }}" alt="Front License" class="img-fluid">
                                            @else
                                                <div class="text-muted">
                                                    <i class="fa fa-image" style="font-size: 48px;"></i>
                                                    <p class="mt-2">No image available</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Back License</h5>
                                        </div>
                                        <div class="card-body text-center">
                                            @if($operator->back_license_image)
                                                <img src="{{ asset($operator->back_license_image) }}" alt="Back License" class="img-fluid">
                                            @else
                                                <div class="text-muted">
                                                    <i class="fa fa-image" style="font-size: 48px;"></i>
                                                    <p class="mt-2">No image available</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                <p>Are you sure you want to delete the operator <strong>"{{ $operator->name }}"</strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <form action="{{ route('operators.destroy', $operator) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Operator</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

