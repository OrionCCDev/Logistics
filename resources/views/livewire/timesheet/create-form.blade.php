<form wire:submit.prevent="save">
    <div class="modal-body">
        <!-- Session Notification -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
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

        <!-- Validation Summary -->
        @if(!empty($validation_errors['working_times']) || !empty($validation_errors['odometer']))
        <div class="alert alert-warning" role="alert">
            <h6><i class="fa fa-exclamation-triangle"></i> Validation Errors:</h6>
            <ul class="mb-0">
                @if(!empty($validation_errors['working_times']))
                    <li>{{ $validation_errors['working_times'] }}</li>
                @endif
                @if(!empty($validation_errors['odometer']))
                    <li>{{ $validation_errors['odometer'] }}</li>
                @endif
            </ul>
        </div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <label for="project_id_livewire">Project</label>
                <div wire:ignore>
                    @if(auth()->user()->role === 'orionDC')
                        <select class="form-control custom-select form-control-lg" id="project_id_livewire" wire:model.live="project_id" disabled>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" selected>{{ $project->name }}-{{ $project->code }}</option>
                            @endforeach
                        </select>
                    @else
                        <select class="form-control custom-select form-control-lg select2-livewire"
                            id="project_id_livewire" wire:model.live="project_id" data-model="project_id">
                            <option value="">Select Project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}-{{ $project->code }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                @error('project_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4">
                <label for="vehicle_id_livewire">Vehicle</label>
                <div wire:ignore>
                    <select class="form-control custom-select form-control-lg select2-livewire"
                        id="vehicle_id_livewire" wire:model.live="vehicle_id" data-model="vehicle_id">
                        <option value="">Select Vehicle</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->plate_number }}-{{ $vehicle->vehicle_type }}</option>
                        @endforeach
                    </select>
                </div>
                @error('vehicle_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4">
                <label for="date_livewire">Date</label>
                <input type="date" class="form-control" id="date_livewire" wire:model.live="date">
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- TIME INPUTS - These are the key ones for calculation -->
            <div class="col-md-4 mt-3">
                <label for="working_start_hour_livewire">Working Start</label>
                <input type="datetime-local"
                       class="form-control"
                       id="working_start_hour_livewire"
                       wire:model.live="working_start_hour">
                @error('working_start_hour') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 mt-3">
                <label for="working_end_hour_livewire">Working End</label>
                <input type="datetime-local"
                       class="form-control"
                       id="working_end_hour_livewire"
                       wire:model.live="working_end_hour">
                @error('working_end_hour') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- Added Break Duration Input -->
            <div class="col-md-4 mt-3">
                <label for="break_duration_hours_livewire">Break Duration (Hours)</label>
                <input type="number" step="0.01" min="0" max="24"
                       class="form-control"
                       id="break_duration_hours_livewire"
                       wire:model.live="break_duration_hours">
                @error('break_duration_hours') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- CALCULATED WORKING HOURS -->
            <div class="col-md-4 mt-3">
                <label for="working_hours_livewire">Working Hours</label>
                <div class="position-relative">
                    <input type="text"
                           class="form-control bg-light"
                           id="working_hours_livewire"
                           value="{{ $working_hours }}"
                           readonly
                           style="font-weight: bold; background-color: #e9ecef !important; color: #495057;">

                    <!-- Loading indicator -->
                    <div wire:loading
                         wire:target="working_start_hour,working_end_hour,break_duration_hours,calculateWorkingHours,triggerCalculation"
                         class="position-absolute"
                         style="top: 50%; right: 10px; transform: translateY(-50%);">
                        <span class="spinner-border spinner-border-sm text-primary"
                              role="status"
                              aria-hidden="true"></span>
                    </div>
                </div>
                <small class="form-text text-muted">
                    Auto-calculated (Format: H.MM - e.g., 8.30 = 8 hours 30 minutes)
                </small>
                @error('working_hours') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 mt-3">
                <label for="odometer_start_livewire">Odometer Start</label>
                <input type="number" class="form-control" id="odometer_start_livewire" wire:model.live="odometer_start" step="0.01">
                @error('odometer_start') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 mt-3">
                <label for="odometer_ends_livewire">Odometer Ends</label>
                <input type="number" class="form-control" id="odometer_ends_livewire" wire:model.live="odometer_ends" step="0.01">
                @error('odometer_ends') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{--  <div class="col-md-4 mt-3">
                <label for="fuel_consumption_status_livewire">Fuel Consumption Status</label>
                <select class="form-control custom-select" id="fuel_consumption_status_livewire" wire:model.live="fuel_consumption_status">
                    <option value="by_hours">By Hour</option>
                    <option value="by_odometer">By Odometer</option>
                </select>
                @error('fuel_consumption_status') <span class="text-danger">{{ $message }}</span> @enderror
            </div>  --}}

            <div class="col-md-4 mt-3">
                <label for="fuel_consumption_livewire">Fuel Consumption</label>
                <input type="number" class="form-control" id="fuel_consumption_livewire" wire:model.live="fuel_consumption" step="0.01">
                @error('fuel_consumption') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 mt-3">
                <label for="deduction_amount_livewire">Deduction Amount</label>
                <input type="number" class="form-control" id="deduction_amount_livewire" wire:model.live="deduction_amount" step="0.01">
                @error('deduction_amount') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-12 mt-3">
                <label for="note_livewire">Note</label>
                <textarea class="form-control" id="note_livewire" wire:model.live="note" rows="3"></textarea>
                @error('note') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove>Save changes</span>
            <span wire:loading>Saving...</span>
        </button>
    </div>
</form>

@push('scripts')
<script>
    // Function to initialize Select2 on relevant elements and bind Livewire events
    function initSelect2Livewire() {
        console.log('initSelect2Livewire called');

        $('.select2-livewire').each(function() {
            let $selectElement = $(this);
            let model = $selectElement.data('model');

            // Destroy existing Select2 instances first
            if ($selectElement.hasClass('select2-hidden-accessible')) {
                 $selectElement.select2('destroy');
             }

            // Initialize Select2
            $selectElement.select2({
                 dropdownParent: $('#exampleModalLarge01'),
                 placeholder: 'Please select...',
                 allowClear: false,
                 width: '100%'
            });

            // Bind change event directly to Livewire.set only if model is defined
            if (model) {
                 $selectElement.off('change.select2lw').on('change.select2lw', function (e) {
                     let value = $(this).val();
                     console.log('Select2 change [' + model + ']:', value);
                     // Use Livewire's set method directly to update backend property
                     @this.set(model, value);
                 });
            }
        });

        // After initializing, Select2 should pick up the value from the underlying <select> element,
        // which is controlled by wire:model. The explicit setting is handled in shown.bs.modal.
    }

    // Initialize when Livewire loads (for initial render if modal is open by default)
    document.addEventListener('livewire:init', function () {
        console.log('Livewire init event fired');
        // Use a slight delay to ensure DOM is ready after initial load
        setTimeout(initSelect2Livewire, 50);
    });

    // Reinitialize and set values when modal opens
    $('#exampleModalLarge01').on('shown.bs.modal', function () {
        console.log('Modal shown, reinitializing Select2 and setting initial values.');

        // Initialize Select2 instances
        initSelect2Livewire();

        // Explicitly set initial values from Livewire properties
        // This is crucial for wire:ignore elements within modals.
        const projectSelect = $('#project_id_livewire');
        if (projectSelect.length && @this.project_id) {
             projectSelect.val(@this.project_id).trigger('change'); // Trigger Select2 to update display
             console.log('Set Project Select2 value on modal shown to:', @this.project_id);
        }
        const vehicleSelect = $('#vehicle_id_livewire');
        if (vehicleSelect.length && @this.vehicle_id) {
             vehicleSelect.val(@this.vehicle_id).trigger('change');
             console.log('Set Vehicle Select2 value on modal shown to:', @this.vehicle_id);
        }
         // If vehicle_id is initially empty, ensure Select2 is cleared visually
        if (vehicleSelect.length && !@this.vehicle_id) {
             vehicleSelect.val(null).trigger('change');
             console.log('Cleared Vehicle Select2 value on modal shown.');
        }

        // Also trigger initial calculation if times are pre-filled
        if (typeof @this.calculateWorkingHours === 'function') {
             @this.call('calculateWorkingHours');
         }
    });

    // Reset specific fields (vehicle select2, fuel, deduction, note) after save
    document.addEventListener('livewire:init', () => {
        Livewire.on('resetTimesheetFormSelects', () => {
            console.log('Resetting specific fields after save (Vehicle Select2, fuel, deduction, note).');

            // Reset the vehicle select2 visual display to empty
            const vehicleSelect = $('#vehicle_id_livewire');
            if (vehicleSelect.data('select2')) {
                // Use null to clear the selection for allowClear: false
                vehicleSelect.val(null).trigger('change');
                console.log('Reset Vehicle Select2 value after save.');
            }

            // Explicitly reset the fuel consumption input value
            const fuelInput = $('#fuel_consumption_livewire');
            if (fuelInput.length) {
                fuelInput.val('0');
                console.log('Reset fuel consumption input after save.');
            }

            // Explicitly reset deduction amount input value
            const deductionInput = $('#deduction_amount_livewire');
             if (deductionInput.length) {
                 deductionInput.val('0');
                 console.log('Reset deduction amount input after save.');
             }

            // Explicitly reset note textarea value
            const noteTextarea = $('#note_livewire');
             if (noteTextarea.length) {
                 noteTextarea.val('');
                 console.log('Reset note textarea after save.');
             }

            // Project Select2 is intentionally NOT reset here.
            // Its value is maintained by wire:model and should be picked up on next morph/update.
            // If it still resets, the issue is likely in Select2/Livewire morphing interaction.

            // Explicitly re-select the project using the current Livewire property value
            const projectSelect = $('#project_id_livewire');
            const currentProjectId = @this.project_id;
            console.log('Attempting to re-select project with ID from Livewire property:', currentProjectId);

            if (projectSelect.length && projectSelect.data('select2')) {
                // Destroy and re-initialize Select2 might be necessary for wire:ignore elements
                // to pick up the wire:model value correctly after a Livewire update.
                // However, let's try just setting the value first.

                if (currentProjectId !== '' && currentProjectId !== null) {
                     // Use a slight delay to ensure DOM is ready and Select2 is ready
                    setTimeout(() => {
                         projectSelect.val(currentProjectId).trigger('change.select2');
                         console.log('Successfully set and triggered Select2 update for project_id_livewire.');
                    }, 50); // Short delay
                } else {
                     // If project_id is null or empty, clear the selection
                     setTimeout(() => {
                         projectSelect.val(null).trigger('change.select2');
                         console.log('Cleared Project Select2 value.');
                     }, 50);
                }

            } else {
                 console.log('Project Select2 element not found or not initialized when trying to re-select.');
            }
        });
    });

    // Time input calculation handlers (keep these)
    document.addEventListener('livewire:init', () => {
        console.log('Livewire initialized for timesheet form time handlers'); // Added specific log

        // Add event listeners to time inputs
        const timeInputs = document.querySelectorAll('input[type="datetime-local"]');

        timeInputs.forEach(input => {
            input.addEventListener('change', function() {
                console.log('Time input changed:', this.id, this.value);
                // Trigger calculation after a short delay to ensure Livewire has updated
                setTimeout(() => {
                    // Check if @this is defined before calling method
                    if (typeof @this !== 'undefined' && typeof @this.calculateWorkingHours === 'function') {
                         @this.call('calculateWorkingHours');
                     }
                }, 150);
            });

            // Also trigger on blur (when user finishes editing)
            input.addEventListener('blur', function() {
                console.log('Time input blur:', this.id, this.value);
                setTimeout(() => {
                     // Check if @this is defined before calling method
                    if (typeof @this !== 'undefined' && typeof @this.calculateWorkingHours === 'function') {
                         @this.call('calculateWorkingHours');
                     }
                }, 150);
            });
        });

        // Break duration input handler
        const breakInput = document.querySelector('#break_duration_hours_livewire');
        if (breakInput) {
            breakInput.addEventListener('change', function() {
                console.log('Break duration changed:', this.value);
                setTimeout(() => {
                     // Check if @this is defined before calling method
                    if (typeof @this !== 'undefined' && typeof @this.calculateWorkingHours === 'function') {
                         @this.call('calculateWorkingHours');
                     }
                }, 150);
            });
        }
    });

    // Listener to restore Project Select2 selection after save
    document.addEventListener('livewire:init', () => {
        Livewire.on('restoreProjectSelection', (projectId) => {
            console.log('restoreProjectSelection event received with project_id:', projectId);
            const projectSelect = $('#project_id_livewire');

            // Ensure the element exists and Select2 is initialized
            if (projectSelect.length && projectSelect.data('select2')) {
                console.log('Restoring Project Select2 value to:', projectId);
                projectSelect.val(projectId).trigger('change.select2');
            } else {
                console.log('Project Select2 element not found or not initialized when trying to restore selection.');
            }
        });
    });
</script>
@endpush
