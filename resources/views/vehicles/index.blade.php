@extends('layouts.app')

@section('page_title', 'Vehicles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vehicles</h3>
                    <div class="card-tools">
                        <a href="{{ route('vehicles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add New Vehicle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Plate Number</th>
                                    <th>Type</th>
                                    <th>Project</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($vehicles as $vehicle)
                                    <tr>
                                        <td>
                                            {{ $vehicle->supplier->name ?? 'N/A' }}
                                        </td>
                                        <td>{{ $vehicle->plate_number }}</td>
                                        <td>{{ $vehicle->vehicle_type ?? 'N/A' }}</td>
                                        <td>{{ $vehicle->projects->last()->name ?? 'N/A' }}</td>


                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteVehicleModal{{ $vehicle->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <div class="modal fade" id="deleteVehicleModal{{ $vehicle->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteVehicleModalLabel" aria-hidden="true">
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
                                                                <p>Are you sure you want to delete the vehicle <strong>"{{ $vehicle->plate_number }}"</strong>?</p>
                                                                <p class="text-danger mb-0">This action cannot be undone.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Delete Vehicle</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $vehicles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <button type="button" class="btn btn-outline-primary">Vehicles</button>
</div>
<a href="{{ route('vehicles.create') }}"
    class="btn btn-sm btn-outline-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-plus"></i></span>
    <span class="btn-text">New Vehicle</span>
</a>
@endsection
