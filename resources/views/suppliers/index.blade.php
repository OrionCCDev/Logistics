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
                <table id="suppliersTable" class="table table-hover table-bordered align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Contact</th>

                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                            <tr>
                                <td>
                                    @if($supplier->logo_path)
                                        <img src="{{ asset($supplier->logo_path) }}" alt="{{ $supplier->name }}" class="rounded-circle" style="height:40px;width:40px;object-fit:cover;" onerror="handleImageError(this, '{{ htmlspecialchars($supplier->name, ENT_QUOTES, 'UTF-8') }}')">
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
                {{ $suppliers->links('pagination::bootstrap-4') }}
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

@push('scripts')
<script>
console.log("Page script executing: suppliers/index.blade.php");

if (typeof $ === 'undefined') {
    console.error("jQuery is NOT loaded when this script runs.");
} else {
    console.log("jQuery is loaded. Version: " + $.fn.jquery);
    if (typeof $.fn.modal === 'undefined') {
        console.error("Bootstrap Modal component is NOT loaded onto jQuery.");
    } else {
        console.log("Bootstrap Modal component IS loaded onto jQuery.");
    }
}

$(document).ready(function(){
    console.log("Document ready state reached.");

    var viewButtons = $('.view-vehicles-btn');
    console.log("Found " + viewButtons.length + " elements with class 'view-vehicles-btn'.");

    if (viewButtons.length === 0) {
        console.warn("No '.view-vehicles-btn' buttons found. Click events will not be attached.");
    }

    viewButtons.on('click', function() {
        console.log("'.view-vehicles-btn' clicked.");
        var supplierId = $(this).data('supplier-id');
        var supplierName = $(this).data('supplier-name');
        console.log("Supplier ID: " + supplierId + ", Supplier Name: " + supplierName);

        if ($('#vehiclesModal').length === 0) {
            console.error("Modal with ID '#vehiclesModal' NOT FOUND in the DOM.");
            return;
        } else {
            console.log("Modal with ID '#vehiclesModal' IS found in the DOM.");
        }

        $('#modalSupplierName').text(supplierName);
        console.log("Set modalSupplierName text to: " + supplierName);

        $('#vehiclesTableBody').html('<tr><td colspan="4" class="text-center">Loading vehicles...</td></tr>');
        console.log("Set vehiclesTableBody to loading state.");

        try {
            console.log("Attempting to show modal: $('#vehiclesModal').modal('show')");
            $('#vehiclesModal').modal('show');
        } catch (e) {
            console.error("Error when trying to show modal: ", e);
        }

        $.ajax({
            url: '/suppliers/' + supplierId + '/get-vehicles',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log("AJAX success. Response: ", response);
                var vehiclesTableBody = $('#vehiclesTableBody');
                vehiclesTableBody.empty();
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
                console.log("Vehicles table populated.");
            },
            error: function(xhr, status, error) {
                console.error("AJAX error. Status: " + status + ", Error: " + error);
                console.error("Response Text: ", xhr.responseText);
                var errorMessage = '<tr><td colspan="4" class="text-center">Error loading vehicles. ';
                if (xhr.responseText) {
                    try {
                        var responseJson = JSON.parse(xhr.responseText);
                        if (responseJson.message) {
                            errorMessage += 'Message: ' + responseJson.message;
                        }
                    } catch (e) {
                        errorMessage += 'Details: <pre>' + xhr.responseText.substring(0, 200) + '...</pre>';
                    }
                }
                errorMessage += '</td></tr>';
                $('#vehiclesTableBody').html(errorMessage);
            }
        });
    });

    console.log("Click handler for '.view-vehicles-btn' attached.");

    $('#suppliersTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column
                },
                text: 'Copy' // Keep original text for script to identify
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column
                },
                text: 'CSV' // Keep original text for script to identify
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column
                },
                text: 'Excel' // Keep original text for script to identify
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column
                },
                text: 'PDF' // Keep original text for script to identify
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column
                },
                text: 'Print' // Keep original text for script to identify
            }
        ],
        // Add options here if needed
    });

    // Apply Bootstrap button classes and icons to the generated buttons
    $('#suppliersTable').closest('.dataTables_wrapper').find('.dt-buttons .dt-button').each(function() {
        // Remove any existing inline styles or default classes
        $(this).removeAttr('style').removeClass('btn btn-primary btn-secondary btn-info btn-success btn-danger');

        // Get the button text to determine type
        const buttonText = $(this).find('span').text();
        let buttonClass = 'btn ';
        let iconHtml = '';

        if (buttonText === 'Copy') {
            buttonClass += 'btn-secondary';
            iconHtml = '<i class="fas fa-copy"></i> ';
        } else if (buttonText === 'CSV') {
            buttonClass += 'btn-info';
            iconHtml = '<i class="fas fa-file-csv"></i> ';
        } else if (buttonText === 'Excel') {
            buttonClass += 'btn-success';
            iconHtml = '<i class="fas fa-file-excel"></i> ';
        } else if (buttonText === 'PDF') {
            buttonClass += 'btn-danger';
            iconHtml = '<i class="fas fa-file-pdf"></i> ';
        } else if (buttonText === 'Print') {
            buttonClass += 'btn-primary';
            iconHtml = '<i class="fas fa-print"></i> ';
        }

        // Add the determined Bootstrap classes
        $(this).addClass(buttonClass);

        // Prepend the icon to the button text
        $(this).find('span').prepend(iconHtml);
    });
});

function handleImageError(imgElement, supplierName) {
    console.log("handleImageError called for supplier: " + supplierName);
    const fallbackDiv = document.createElement('div');
    fallbackDiv.className = 'rounded-circle bg-light d-flex align-items-center justify-content-center';
    fallbackDiv.style.height = '40px';
    fallbackDiv.style.width = '40px';

    const span = document.createElement('span');
    span.className = 'text-secondary';
    if (supplierName && typeof supplierName === 'string' && supplierName.trim().length > 0) {
        span.textContent = supplierName.trim().substring(0, 1).toUpperCase();
    } else {
        span.textContent = '';
    }
    fallbackDiv.appendChild(span);

    if (imgElement.parentNode) {
        imgElement.parentNode.replaceChild(fallbackDiv, imgElement);
    }
}
console.log("End of page script: suppliers/index.blade.php");
</script>
@endpush

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
