<div wire:key="create-vehicle-timesheet-form-root">
    <form wire:submit.prevent="saveTimesheet">
        @csrf
        {{-- Display general session messages --}}
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" wire:model.live="date">
                        @error('date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="project_id">Project</label>
                        <div wire:ignore>
                            <select class="form-control @error('project_id') is-invalid @enderror" id="project_id_select2" data-placeholder="Select Project">
                                <option value="">Select Project</option>
                                @foreach($projects as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('project_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" wire:key="working-hours-group">
                        <label for="working_hours_display">Working Hours (Calculated)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="working_hours_display" wire:model="working_hours_display" readonly wire:key="working-hours-input">
                            <div class="input-group-append">
                                <span class="input-group-text" wire:loading wire:target="working_start_hour, working_end_hour, break_duration_hours">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="working_start_hour">Work Start Time</label>
                        <input type="datetime-local" class="form-control @error('working_start_hour') is-invalid @enderror" id="working_start_hour" wire:model.live="working_start_hour">
                        @error('working_start_hour') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="working_end_hour">Work End Time</label>
                        <input type="datetime-local" class="form-control @error('working_end_hour') is-invalid @enderror" id="working_end_hour" wire:model.live="working_end_hour">
                        @error('working_end_hour') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="break_duration_hours">Break Duration (Hours)</label>
                        <input type="number" step="0.01" min="0" max="24" class="form-control @error('break_duration_hours') is-invalid @enderror" id="break_duration_hours" wire:model.live="break_duration_hours">
                        @error('break_duration_hours') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="odometer_start">Odometer Start</label>
                        <input type="number" step="0.01" class="form-control @error('odometer_start') is-invalid @enderror" id="odometer_start" wire:model="odometer_start">
                        @error('odometer_start') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="odometer_ends">Odometer End</label>
                        <input type="number" step="0.01" class="form-control @error('odometer_ends') is-invalid @enderror" id="odometer_ends" wire:model="odometer_ends">
                        @error('odometer_ends') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fuel_consumption_status">Fuel Consumption Calc</label>
                        <select class="form-control @error('fuel_consumption_status') is-invalid @enderror" id="fuel_consumption_status" wire:model="fuel_consumption_status">
                            <option value="">Select Method</option>
                            <option value="by_hours">By Hours</option>
                            <option value="by_odometer">By Odometer</option>
                        </select>
                        @error('fuel_consumption_status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fuel_consumption">Fuel Consumption</label>
                        <input type="number" step="0.01" class="form-control @error('fuel_consumption') is-invalid @enderror" id="fuel_consumption" wire:model="fuel_consumption">
                        @error('fuel_consumption') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="deduction_amount">Deduction Amount</label>
                        <input type="number" step="0.01" class="form-control @error('deduction_amount') is-invalid @enderror" id="deduction_amount" wire:model="deduction_amount">
                        @error('deduction_amount') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" wire:model="notes" rows="2"></textarea>
                        @error('notes') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal" wire:click="resetForm">Cancel</button>
            <button type="submit" class="btn btn-success">
                Create Timesheet
                <span wire:loading wire:target="saveTimesheet" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        console.log('Select2_Debug: create-vehicle-timesheet-form script block is present.');

        function initializeProjectSelect2() {
            const $currentProjectSelect = $('#project_id_select2');
            if (!$currentProjectSelect.length) {
                console.error('Select2_Debug: #project_id_select2 NOT FOUND during init call.');
                return;
            }
            console.log('Select2_Debug: Init function called for:', $currentProjectSelect[0]);

            const optionCount = $currentProjectSelect.find('option').length;
            console.log(`Select2_Debug: Found ${optionCount} <option> elements.`);
            if (optionCount <= 1 && $currentProjectSelect.find('option[value=""]').length === 1) {
                console.warn('Select2_Debug: Only placeholder/empty option found. Ensure $projects is populated in Blade.');
            }

            if ($currentProjectSelect.data('select2')) {
                console.log('Select2_Debug: Destroying existing Select2 instance.');
                try { $currentProjectSelect.select2('destroy'); } catch (e) { console.error('Select2_Debug: Error destroying:', e); }
            }

            let dropdownParentEl = $currentProjectSelect.closest('.modal-body').length ?
                                 $currentProjectSelect.closest('.modal-body') :
                                 ($currentProjectSelect.closest('.modal').length ?
                                  $currentProjectSelect.closest('.modal') : $('body'));
            console.log('Select2_Debug: Using dropdownParent:', dropdownParentEl[0]);

            const placeholderText = $currentProjectSelect.attr('data-placeholder') || 'Select Project';
            console.log('Select2_Debug: Initializing Select2 with placeholder:', placeholderText);

            try {
                $currentProjectSelect.select2({
                    theme: 'bootstrap4',
                    dropdownParent: dropdownParentEl,
                    width: '100%',
                    placeholder: placeholderText,
                    minimumResultsForSearch: 0,
                    dropdownCssClass: 'select2-dropdown-atop'
                });
                console.log('Select2_Debug: Select2 initialized successfully.');
            } catch (e) {
                console.error('Select2_Debug: Error during Select2 initialization:', e);
            }
        }

        function setupSelect2Listeners() {
            const $projectSelectGlobal = $('#project_id_select2');
            if (!$projectSelectGlobal.length) {
                console.warn('Select2_Debug: #project_id_select2 not found for binding listeners.');
                return;
            }

            console.log('Select2_Debug: Binding change event to #project_id_select2.');
            $projectSelectGlobal.off('change.select2custom').on('change.select2custom', function (e) {
                var data = $(this).val();
                console.log('Select2_Debug: #project_id_select2 value changed to:', data);
                if (typeof Livewire !== 'undefined') {
                    // Ensure Livewire component context is correct if possible
                    const wireId = $projectSelectGlobal.closest('*[wire\\:id]').attr('wire:id');
                    if (wireId) {
                        console.log('Select2_Debug: Found wire:id - ', wireId, ' - attempting to set project_id');
                        Livewire.find(wireId).set('project_id', data);
                    } else {
                        console.warn('Select2_Debug: Could not find parent wire:id to set Livewire property.');
                    }
                } else {
                    console.warn('Select2_Debug: Livewire not available for .set()');
                }
            });

            if (typeof Livewire !== 'undefined') {
                Livewire.on('resetForm', () => {
                    console.log('Select2_Debug: Livewire "resetForm" event received.');
                    const $currentSelect = $('#project_id_select2');
                    if ($currentSelect.data('select2')) {
                        $currentSelect.val(null).trigger('change.select2');
                        console.log('Select2_Debug: #project_id_select2 reset via Select2.');
                    } else {
                        $currentSelect.val(null);
                        console.log('Select2_Debug: #project_id_select2 reset via .val().');
                    }
                });
            } else {
                console.warn('Select2_Debug: Livewire not available for resetForm listener.');
            }

            const $containingModal = $projectSelectGlobal.closest('.modal');
            if ($containingModal.length) {
                console.log('Select2_Debug: #project_id_select2 is inside a modal. Attaching "shown.bs.modal" listener.');
                $containingModal.off('shown.bs.modal.select2init').on('shown.bs.modal.select2init', function () {
                    console.log('Select2_Debug: Modal "shown.bs.modal" event triggered. Re-initializing.');
                    initializeProjectSelect2();
                });
            }
        }

        // Attempt to initialize Select2
        function attemptSelect2Initialization() {
            if (typeof jQuery === 'undefined') {
                console.error('Select2_Debug: jQuery not available for init attempt.');
                return;
            }

            if ($('#project_id_select2').length) {
                console.log('Select2_Debug: #project_id_select2 found. Proceeding with initialization & listeners.');
                initializeProjectSelect2();
                setupSelect2Listeners();
            } else {
                console.warn('Select2_Debug: #project_id_select2 not found yet on this attempt, will retry if Alpine is present.');
            }
        }

        // Use Alpine's ready event if available, otherwise a delayed jQuery ready
        if (typeof Alpine !== 'undefined' && typeof Alpine. wydarzenia !== 'undefined' && typeof Alpine.wydarzenia.init === 'function') {
            console.log('Select2_Debug: Alpine detected. Waiting for Alpine:init.');
            document.addEventListener('alpine:init', () => {
                console.log('Select2_Debug: Alpine:init event fired.');
                // Delay slightly to ensure Livewire components are fully processed by Alpine
                setTimeout(attemptSelect2Initialization, 50);

                // Add the new event listener here
                if (typeof Livewire !== 'undefined') {
                    console.log('JS_Debug: Setting up listener for updateWorkingHoursDisplay event (Alpine path).');
                    Livewire.on('updateWorkingHoursDisplay', event => {
                        // Check if the event object and value property exist to prevent errors
                        if (event && typeof event.value !== 'undefined') {
                            console.log('JS_Debug: Alpine path - Received updateWorkingHoursDisplay with value:', event.value);
                            $('#working_hours_display').val(event.value);
                        } else {
                             console.warn('JS_Debug: Alpine path - updateWorkingHoursDisplay event received, but event or value property missing.', event);
                        }
                    });
                }
            });
        } else if (typeof jQuery !== 'undefined') {
            console.log('Select2_Debug: Alpine not detected or alpine:init not available. Using jQuery ready with a delay.');
            $(document).ready(function() {
                console.log('Select2_Debug: jQuery document.ready fired.');
                // Delay slightly to ensure Livewire components might be ready
                setTimeout(attemptSelect2Initialization, 100);

                // Add the new event listener here
                if (typeof Livewire !== 'undefined') {
                    console.log('JS_Debug: Setting up listener for updateWorkingHoursDisplay event (jQuery path).');
                    Livewire.on('updateWorkingHoursDisplay', event => {
                        // Check if the event object and value property exist
                         if (event && typeof event.value !== 'undefined') {
                            console.log('JS_Debug: jQuery path - Received updateWorkingHoursDisplay with value:', event.value);
                            $('#working_hours_display').val(event.value);
                        } else {
                             console.warn('JS_Debug: jQuery path - updateWorkingHoursDisplay event received, but event or value property missing.', event);
                        }
                    });
                }
            });
        } else {
            console.error('Select2_Debug: Neither Alpine nor jQuery available for initial setup.');
        }

    </script>
    <style>
        .select2-dropdown-atop {
            z-index: 99999 !important;
        }
    </style>
    @endpush
</div>
