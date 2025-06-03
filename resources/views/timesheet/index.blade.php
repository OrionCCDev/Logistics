@extends('layouts.app')

@section('page_title', 'Daily Timesheets')

@section('content')
<div class="hk-row">
    <div class="col-xl-12">
        <section class="hk-sec-wrapper">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Daily Timesheet Entries</h2>
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

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<style>
    /* Export buttons styling */
    .dt-buttons {
        margin-bottom: 1rem;
    }

    .dt-button {
        margin-right: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        border: 1px solid #dee2e6 !important;
    }

    #exportButtonsContainer {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        margin-bottom: 1rem;
    }

    #exportButtonsContainer:empty {
        display: none;
    }

    #exportButtonsContainer .dt-buttons {
        margin-bottom: 0;
    }

    /* Table styling */
    .table th[style*="cursor: pointer"]:hover {
        background-color: #f8f9fa;
    }

    .fas.fa-sort,
    .fas.fa-sort-up,
    .fas.fa-sort-down {
        font-size: 0.8rem;
        margin-left: 0.5rem;
        opacity: 0.7;
    }

    .alert {
        border-radius: 0.375rem;
    }

    .table-responsive {
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .btn-group-sm > .btn, .btn-sm {
        font-size: 0.875rem;
    }

    /* Loading state */
    .loading-overlay {
        position: relative;
    }

    .loading-overlay::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        z-index: 1000;
    }

    .loading-overlay.loading::after {
        display: block;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #exportButtonsContainer {
            padding: 0.5rem;
        }

        .dt-button {
            font-size: 0.875rem !important;
            padding: 0.375rem 0.75rem !important;
            margin-right: 0.25rem !important;
        }

        .table-responsive {
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@push('scripts')
<!-- DataTables Scripts -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    document.addEventListener('livewire:init', () => {

        // Function to initialize Select2
        function initSelect2(elementId, componentInstance, propertyName) {
            const selectElement = $(elementId);
            if (selectElement.data('select2')) { // Destroy if already initialized
                selectElement.select2('destroy');
            }
            selectElement.select2({
                dropdownParent: $('#exampleModalLarge01'),
                width: '100%',
                placeholder: 'Select an option...',
                allowClear: true
            }).on('change', function (e) {
                const value = $(this).val();
                componentInstance.set(propertyName, value);
            });
        }

        // Handle modal events
        $('#exampleModalLarge01').on('shown.bs.modal', function () {
            console.log('Modal shown, initializing Select2...');

            // Find the Livewire component instance within the modal
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

        // Handle modal hidden event
        $('#exampleModalLarge01').on('hidden.bs.modal', function () {
            console.log('Modal hidden, cleaning up Select2...');

            // Clean up Select2 instances
            if ($('#project_id_livewire').data('select2')) {
                $('#project_id_livewire').select2('destroy');
            }
            if ($('#vehicle_id_livewire').data('select2')) {
                $('#vehicle_id_livewire').select2('destroy');
            }
        });

        // Listen for form reset events from Livewire
        Livewire.on('resetTimesheetFormSelects', () => {
            console.log('Resetting Select2 values...');

            // Reset select2 values when the form is reset by Livewire
            if ($('#project_id_livewire').data('select2')) {
                $('#project_id_livewire').val(null).trigger('change');
            }
            if ($('#vehicle_id_livewire').data('select2')) {
                $('#vehicle_id_livewire').val(null).trigger('change');
            }
        });

        // Listen for successful form submission
        Livewire.on('timesheetCreated', () => {
            console.log('Timesheet created successfully, closing modal...');
            $('#exampleModalLarge01').modal('hide');
        });

        // Handle any Bootstrap alerts auto-dismiss
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Add loading indication during Livewire requests
        Livewire.hook('request', ({ succeed, fail }) => {
            // Add loading class to table container
            const tableContainer = document.querySelector('.table-responsive');
            if (tableContainer) {
                tableContainer.classList.add('loading-overlay', 'loading');
            }

            succeed(() => {
                // Remove loading class
                if (tableContainer) {
                    tableContainer.classList.remove('loading');
                }
            });

            fail(() => {
                // Remove loading class on failure too
                if (tableContainer) {
                    tableContainer.classList.remove('loading');
                }
            });
        });
    });

    // Additional global handlers for better UX
    $(document).ready(function() {
        console.log('Document ready');

        // Add loading state to buttons when form is being submitted
        $(document).on('click', '[wire\\:click]', function() {
            const $btn = $(this);
            if (!$btn.hasClass('btn-secondary') && !$btn.hasClass('btn-danger')) {
                $btn.prop('disabled', true);
                const originalText = $btn.html();
                $btn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');

                setTimeout(() => {
                    $btn.prop('disabled', false);
                    $btn.html(originalText);
                }, 3000);
            }
        });

        // Enhanced table responsiveness
        $(window).on('resize', function() {
            if ($.fn.DataTable.isDataTable('#timesheetTable')) {
                try {
                    $('#timesheetTable').DataTable().columns.adjust().responsive.recalc();
                } catch (error) {
                    console.warn('Error adjusting DataTable columns:', error);
                }
            }
        });

        // Add debugging for DataTables
        $.fn.dataTable.ext.errMode = 'throw';
    });
</script>
@endpush