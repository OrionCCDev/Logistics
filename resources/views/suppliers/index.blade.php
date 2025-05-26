@extends('layouts.app')

@section('page_title', 'Suppliers')

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Suppliers</h2>
                <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add New Supplier
                </a>
            </div>

            <!-- Add search form here -->
            <form method="GET" action="{{ route('suppliers.index') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by supplier name..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                            <tr>
                                <td>
                                    @if($supplier->logo_path)
                                        <img src="{{ $supplier->logo_path }}" alt="{{ $supplier->name }}" class="rounded-circle" style="height:40px;width:40px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="height:40px;width:40px;">
                                            <span class="text-secondary">{{ substr($supplier->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $supplier->name }}</div>

                                </td>
                                <td>
                                    <div>{{ $supplier->contact_name }}</div>
                                    <div class="text-muted small">{{ $supplier->contact_email }}</div>
                                </td>
                                <td>
                                    @if($supplier->category)
                                        <span class="badge badge-info">{{ $supplier->category->name }}</span>
                                    @else
                                        <span class="text-muted">No category</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $supplier->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-success btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-warning btn-sm view-vehicles-btn" data-supplier-id="{{ $supplier->id }}" data-supplier-name="{{ $supplier->name }}">
                                            <i class="fa fa-truck"></i>
                                        </button>
                                        <button  data-toggle="modal" data-target="#deleteSupplierModal{{ $supplier->id }}" class="btn btn-danger btn-sm" >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <div class="modal fade" id="deleteSupplierModal{{ $supplier->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
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
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $suppliers->links() }}
            </div>
        </section>
    </div>
</div>

<!-- Vehicle Modal -->
<div class="modal fade" id="vehiclesModal" tabindex="-1" role="dialog" aria-labelledby="vehiclesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vehiclesModalLabel">Vehicles for <span id="modalSupplierName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Plate Number</th>
                                <th>Type</th>
                                <th>Project(s)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="vehiclesTableBody">
                            <!-- Vehicle data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $('.view-vehicles-btn').on('click', function() {
        var supplierId = $(this).data('supplier-id');
        var supplierName = $(this).data('supplier-name');
        $('#modalSupplierName').text(supplierName);
        $('#vehiclesTableBody').html('<tr><td colspan="4" class="text-center">Loading vehicles...</td></tr>');
        $('#vehiclesModal').modal('show');

        $.ajax({
            url: '/suppliers/' + supplierId + '/get-vehicles',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var vehiclesTableBody = $('#vehiclesTableBody');
                vehiclesTableBody.empty(); // Clear previous data
                if(response.length > 0){
                    var badgeClasses = ['badge-primary', 'badge-secondary', 'badge-success', 'badge-danger', 'badge-warning', 'badge-info', 'badge-dark'];
                    $.each(response, function(index, vehicle){
                        var projectsHtml = 'N/A';
                        if (vehicle.projects && vehicle.projects.length > 0) {
                            projectsHtml = vehicle.projects.map(function(project, idx) {
                                return '<span class="badge ' + badgeClasses[idx % badgeClasses.length] + ' mr-1">' + project.name + '</span>';
                            }).join('');
                        }
                        var vehicleUrl = "{{ url('vehicles') }}/" + vehicle.id;
                        var row = '<tr>' +
                                    '<td>' + vehicle.plate_number + '</td>' +
                                    '<td>' + (vehicle.vehicle_type || 'N/A') + '</td>' +
                                    '<td>' + projectsHtml + '</td>' +
                                    '<td><a href="' + vehicleUrl + '" class="btn btn-sm btn-primary">View</a></td>' +
                                  '</tr>';
                        vehiclesTableBody.append(row);
                    });
                } else {
                    vehiclesTableBody.html('<tr><td colspan="4" class="text-center">No vehicles found for this supplier.</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = '<tr><td colspan="4" class="text-center">Error loading vehicles. ';
                if (xhr.responseText) {
                    try {
                        var responseJson = JSON.parse(xhr.responseText);
                        if (responseJson.message) {
                            errorMessage += 'Message: ' + responseJson.message;
                        }
                    } catch (e) {
                        // Not a JSON response, display a snippet of the text
                        errorMessage += 'Details: <pre>' + xhr.responseText.substring(0, 200) + '...</pre>';
                    }
                }
                errorMessage += '</td></tr>';
                $('#vehiclesTableBody').html(errorMessage);
                console.error("Error: ", error, "Status: ", status);
                console.error("Response Text: ", xhr.responseText);
            }
        });
    });
});
</script>
@endsection

@section('page_actions')
<div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
    <button type="button" class="btn btn-outline-primary">Suppliers</button>
</div>
<a href="{{ route('suppliers.create') }}"
    class="btn btn-sm btn-outline-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-plus"></i></span>
    <span class="btn-text">New Supplier</span>
</a>
@endsection
