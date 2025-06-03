<div>
    {{-- Session Messages for Table Actions --}}
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="table-responsive">
        <table id="timesheetTable" class="table table-hover table-bordered align-middle datatable">
            <thead class="thead-light">
                <tr>
                    <th>Created By</th>
                    <th>Date</th>
                    <th>Project</th>
                    <th>Vehicle</th>
                    <th>Work Hours</th>
                    <th>Fuel Consumption</th>
                    <th>Average</th>
                    <th>Remarks</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($timesheets as $timesheet)
                    <tr wire:key="timesheet-row-{{ $timesheet->id }}"
                        data-search="{{ strtolower(($timesheet->project?->name ?? '') . ' ' . ($timesheet->vehicle?->plate_number ?? '') . ' ' . ($timesheet->vehicle?->supplier?->name ?? '') . ' ' . ($timesheet->user?->name ?? '')) }}">
                        <td>{{ $timesheet->user?->name ?? 'N/A' }}</td>
                        <td data-order="{{ $timesheet->date ? $timesheet->date->format('Y-m-d') : '1900-01-01' }}">
                            {{ $timesheet->date ? $timesheet->date->format('d M, Y') : 'N/A' }}
                        </td>
                        <td>{{ $timesheet->project?->name ?? 'N/A' }}</td>
                        <td>{{ $timesheet->vehicle?->plate_number ?? 'N/A' }}</td>
                        <td>{{ $timesheet->working_hours ?? 'N/A' }}</td>
                        <td>{{ $timesheet->fuel_consumption ?? 'N/A' }}</td>
                        <td>{{ ($timesheet->working_hours > 0) ? number_format($timesheet->fuel_consumption / $timesheet->working_hours, 2) : 'N/A' }}</td>
                        <td title="{{ $timesheet->note ?? '' }}">
                            {{ $timesheet->note ? Str::limit($timesheet->note, 30) : 'N/A' }}
                        </td>
                        <td>{{ $timesheet->vehicle?->supplier?->name ?? 'N/A' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('timesheet.show', $timesheet->id) }}"
                                    class="btn btn-success btn-sm" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('timesheet.edit', $timesheet->id) }}" class="btn btn-info btn-sm"
                                    title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                @if ($timesheetIdToDelete === $timesheet->id)
                                    <button class="btn btn-danger btn-sm" wire:click="deleteTimesheet">Confirm?</button>
                                    <button class="btn btn-secondary btn-sm" wire:click="cancelDelete">Cancel</button>
                                @else
                                    <button type="button" class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $timesheet->id }})" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No timesheet entries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    function initializeTimesheetTable() {
        // Check if DataTable already exists and destroy it
        if ($.fn.DataTable.isDataTable('#timesheetTable')) {
            $('#timesheetTable').DataTable().destroy();
        }

        // Initialize DataTable
        var table = $('#timesheetTable').DataTable({
            // Basic layout
            dom: 'Blfrtip',

            // Export buttons
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i> Copy',
                    className: 'btn btn-secondary btn-sm'
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-csv"></i> CSV',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    orientation: 'landscape'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    className: 'btn btn-info btn-sm'
                }
            ],

            // Pagination
            pageLength: 25,
            lengthMenu: [
                [10, 25, 50, 100, 250, 500, -1],
                [10, 25, 50, 100, 250, 500, "All"]
            ],

            // Sorting - default by date descending
            order: [[1, 'desc']],

            // Column settings
            columnDefs: [
                {
                    targets: [9], // Actions column
                    orderable: false,
                    searchable: false
                },
                {
                    targets: [4, 5, 6], // Numeric columns
                    className: 'text-right'
                }
            ],

            // Display settings
            responsive: true,
            autoWidth: false,
            processing: false,

            // Enhanced search settings for global search
            search: {
                smart: true,
                regex: false,
                caseInsensitive: true
            },

            // Custom search function to include data-search attributes
            searchCols: [
                null, // Created By
                null, // Date
                null, // Project
                null, // Vehicle
                null, // Work Hours
                null, // Fuel Consumption
                null, // Average
                null, // Remarks
                null, // Supplier
                null  // Actions
            ],

            // Language
            language: {
                search: "Search (Project, Vehicle, Supplier, User):",
                searchPlaceholder: "Type to search...",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ timesheets",
                infoEmpty: "Showing 0 to 0 of 0 timesheets",
                infoFiltered: "(filtered from _MAX_ total timesheets)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                },
                emptyTable: "No timesheet data available",
                zeroRecords: "No matching timesheets found"
            },

            // Custom search functionality
            initComplete: function() {
                var api = this.api();

                // Override the default search to include data-search attributes
                $('#timesheetTable_filter input').off().on('keyup change', function() {
                    var searchTerm = this.value.toLowerCase();

                    api.rows().every(function() {
                        var row = this.node();
                        var rowData = $(row).data('search') || '';
                        var visible = rowData.includes(searchTerm) ||
                                    $(row).text().toLowerCase().includes(searchTerm);

                        if (visible) {
                            $(row).show();
                        } else {
                            $(row).hide();
                        }
                    });

                    // Use DataTable's built-in search for other columns
                    api.search(this.value).draw();
                });

                console.log('DataTable initialized with enhanced search');
            }
        });

        // Style buttons
        $('#timesheetTable_wrapper .dt-buttons .dt-button').addClass('mr-1 mb-1');

        console.log('DataTable initialized with ' + table.rows().count() + ' rows');
    }

    // Initialize on document ready
    $(document).ready(function () {
        setTimeout(function() {
            initializeTimesheetTable();
        }, 100);
    });

    // Reinitialize after Livewire updates
    document.addEventListener('livewire:updated', function () {
        setTimeout(function() {
            initializeTimesheetTable();
        }, 200);
    });
</script>
@endpush
