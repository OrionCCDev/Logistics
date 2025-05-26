@extends('layouts.app')

@section('page_title', 'Daily Timesheets')

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
                <h2 class="mb-0">Daily Timesheet Entries</h2>
                {{-- <a href="{{ route('timesheet.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add New Entry
                </a> --}}
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLarge01">
                    <i class="fa fa-plus"></i> New Vehicle Timesheet
                </button>
                <!-- Modal -->
                <div class="modal fade" id="exampleModalLarge01" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLarge01" aria-hidden="true" wire:ignore.self>
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
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="thead-light">
                        <tr>

                            <th>Created By</th>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Vehicle</th>
                            <th>Work Hours</th>
                            <th>Supplier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timesheets as $timesheet)
                        <tr>

                            <td>{{ $timesheet->user?->name ?? 'N/A' }}</td>
                            <td>{{ $timesheet->date ? $timesheet->date->format('d M, Y') : 'N/A' }}</td>
                            <td>{{ $timesheet->project?->name ?? 'N/A' }}</td>
                            <td>{{ $timesheet->vehicle?->plate_number ?? 'N/A' }}</td>
                            <td>{{ $timesheet->working_hours ?? 'N/A' }}</td>
                            <td>{{ $timesheet->vehicle?->supplier?->name ?? 'N/A' }}</td>
                            </td>
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
                                    <button type="button" class="btn btn-danger btn-sm" title="Delete"
                                        data-toggle="modal" data-target="#deleteTimesheetModal{{ $timesheet->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteTimesheetModal{{ $timesheet->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="deleteTimesheetModalLabel{{ $timesheet->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="deleteTimesheetModalLabel{{ $timesheet->id }}">Confirm Delete
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this timesheet entry for {{
                                                $timesheet->date->format('d M, Y') }}?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <form action="{{ route('timesheet.destroy', $timesheet->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No timesheet entries found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $timesheets->links() }}
            </div>
        </section>
    </div>
</div>
@endsection

@section('page_actions')
<a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary btn-rounded btn-wth-icon icon-wthot-bg mb-15">
    <span class="icon-label"><i class="fa fa-arrow-left"></i></span>
    <span class="btn-text">Back to Dashboard</span>
</a>
@endsection

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {

        // Function to initialize Select2
        function initSelect2(elementId, componentInstance, propertyName) {
            const selectElement = $(elementId);
            if (selectElement.data('select2')) { // Destroy if already initialized
                selectElement.select2('destroy');
            }
            selectElement.select2({
                dropdownParent: $('#exampleModalLarge01')
            }).on('change', function (e) {
                const value = $(this).val();
                componentInstance.set(propertyName, value);
            });
        }

        $('#exampleModalLarge01').on('shown.bs.modal', function () {
            // Find the Livewire component instance within the modal
            // This assumes the component is the first/only one inside the modal content,
            // or you might need a more specific selector for `modalLivewireComponentEl`
            const modalLivewireComponentEl = $(this).find('[wire\\:id]');
            if (modalLivewireComponentEl.length) {
                const componentId = modalLivewireComponentEl.attr('wire:id');
                const livewireComponentInstance = Livewire.find(componentId);

                if (livewireComponentInstance) {
                    // Initialize Select2 for project_id
                    if ($('#project_id_livewire').length) {
                        initSelect2('#project_id_livewire', livewireComponentInstance, 'project_id');
                    }
                    // Initialize Select2 for vehicle_id
                    if ($('#vehicle_id_livewire').length) {
                        initSelect2('#vehicle_id_livewire', livewireComponentInstance, 'vehicle_id');
                    }
                } else {
                    console.warn('Livewire component instance not found in modal.');
                }
            }
        });

        Livewire.on('timesheetSaved', () => {
            $('#exampleModalLarge01').modal('hide');
            // Optionally, you can dispatch an event to refresh a list component
            // Livewire.dispatch('refreshTimesheetList');
            // For now, reloading the page:
            window.location.reload();
        });

        // If you need to reset select2 values when the form is reset by Livewire
        Livewire.on('resetTimesheetFormSelects', () => {
            // This event would need to be dispatched from your component's resetForm method
            // e.g., $this->dispatch('resetTimesheetFormSelects');
            if ($('#project_id_livewire').data('select2')) {
                $('#project_id_livewire').val(null).trigger('change');
            }
            if ($('#vehicle_id_livewire').data('select2')) {
                $('#vehicle_id_livewire').val(null).trigger('change');
            }
        });

    });
</script>
@endpush
