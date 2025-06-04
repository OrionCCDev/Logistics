@extends('layouts.app')

@section('page_title', 'My Timesheets')

@section('page_actions')
<a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-arrow-left"></i></span>
    <span class="btn-text">Back to Dashboard</span>
</a>
<button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#exampleModalLarge01">
    <i class="fa fa-plus"></i> New Vehicle Timesheet
</button>
@endsection

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            <h5 class="hk-sec-title">My Timesheet Entries</h5>
            <div class="table-responsive">
                <table id="myTimesheetsTable" class="table table-hover table-bordered align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Vehicle</th>
                            <th>Work Hours</th>
                            <th>Fuel Consumption</th>
                            <th>Average</th>
                            <th>Remarks</th>
                            @if(auth()->user()->role == 'orionAdmin' || auth()->user()->role == 'orionManager')
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timesheets as $timesheet)
                        <tr>
                            <td>{{ $timesheet->date ? $timesheet->date->format('d M, Y') : 'N/A' }}</td>
                            <td>{{ $timesheet->project?->name ?? $timesheet->project?->project_name ?? 'N/A' }}</td>
                            <td>{{ $timesheet->vehicle?->plate_number ?? 'N/A' }}</td>
                            <td>{{ $timesheet->working_hours ?? 'N/A' }}</td>
                            <td>{{ $timesheet->fuel_consumption ?? 'N/A' }}</td>
                            <td>{{ ($timesheet->working_hours > 0) ? number_format($timesheet->fuel_consumption / $timesheet->working_hours, 2) : 'N/A' }}</td>
                            <td title="{{ $timesheet->note ?? '' }}">{{ $timesheet->note ?? 'N/A' }}</td>
                            @if(auth()->user()->role == 'orionAdmin' || auth()->user()->role == 'orionManager')
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('timesheet.show', $timesheet->id) }}" class="btn btn-success btn-sm" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('timesheet.edit', $timesheet->id) }}" class="btn btn-info btn-sm" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" title="Delete" data-toggle="modal" data-target="#deleteTimesheetModal{{ $timesheet->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteTimesheetModal{{ $timesheet->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteTimesheetModalLabel{{ $timesheet->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteTimesheetModalLabel{{ $timesheet->id }}">Confirm Delete</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this timesheet entry for {{ $timesheet->date->format('d M, Y') }}?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <form action="{{ route('timesheet.destroy', $timesheet->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="@if(auth()->user()->role == 'orionAdmin' || auth()->user()->role == 'orionManager') 6 @else 5 @endif" class="text-center">No timesheet entries found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $timesheets->links('pagination::bootstrap-4') }}
            </div>
        </section>
    </div>
</div>
<!-- Modal for New Vehicle Timesheet -->
<div class="modal fade" id="exampleModalLarge01" tabindex="-1" role="dialog" aria-labelledby="exampleModalLarge01" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Vehicle Timesheet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- Simplified Livewire component call --}}
            @livewire('timesheet.create-form')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Remove this listener to prevent the modal from closing after save
    // Livewire.on('timesheet-saved', function () {
    //     console.log('[DEBUG] Timesheet saved event received. Attempting to close modal and reload.');
    //     var modalElement = $('#exampleModalLarge01');
    //     console.log('[DEBUG] Modal element found:', modalElement.length > 0);
    //     if (modalElement.length > 0 && modalElement.hasClass('show')) {
    //          console.log('[DEBUG] Modal found and is visible. Hiding modal.');
    //         modalElement.modal('hide');
    //         setTimeout(function() {
    //             console.log('[DEBUG] Reloading page...');
    //             window.location.reload();
    //         }, 400); // Increased delay slightly
    //     } else {
    //         console.log('[DEBUG] Modal not found or not currently visible. Proceeding to reload page.');
    //          window.location.reload();
    //     }
    // });

    // Optional: Re-initialize any necessary JS (like Select2) when modal is shown
    $('#exampleModalLarge01').on('shown.bs.modal', function () {
        console.log('Modal shown.');
        // You might add logic here to wait for the Livewire component to be ready
        // if initialization issues persist.
    });

    // Optional: Log when the modal is fully hidden after clicking close or saving
    $('#exampleModalLarge01').on('hidden.bs.modal', function () {
         console.log('Modal hidden.');
    });

    // Ensure the event listener is attached even if Livewire navigates or initializes
    document.addEventListener('livewire:navigated', function () {
        console.log('Livewire navigated event.');
    });

    document.addEventListener('livewire:init', () => {
        console.log('Livewire init event.');
    });

    $(document).ready(function() {
        $('#myTimesheetsTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column
                    }
                }
            ],
            columns: [
                { data: null }, // Date
                { data: null }, // Project
                { data: null }, // Vehicle
                { data: null }, // Work Hours
                { data: null }, // Fuel Consumption
                { data: null }, // Average
                { data: null }, // Remarks
                // Actions column handled implicitly or by exportOptions
            ],
            // Add options here if needed
        });

        // Apply Bootstrap button classes and icons to the generated buttons
        $('#myTimesheetsTable').closest('.dataTables_wrapper').find('.dt-buttons .dt-button').each(function() {
            // Remove any existing inline styles or default classes
            $(this).removeAttr('style').removeClass('btn btn-secondary btn-info btn-success btn-danger btn-primary');

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
</script>
@endpush
