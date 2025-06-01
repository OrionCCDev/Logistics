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
                @livewire('timesheet.timesheet-table')
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
