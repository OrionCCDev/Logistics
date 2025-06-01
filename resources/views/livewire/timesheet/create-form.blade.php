<form wire:submit.prevent="save">
    <div class="modal-body">
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
    function initSelect2Livewire() {
        console.log('initSelect2Livewire called');

        // Destroy existing Select2 instances first
        $('.select2-livewire').each(function() {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }
        });

        $('.select2-livewire').select2({
            dropdownParent: $('#exampleModalLarge01'),
            placeholder: 'Please select...',
            allowClear: false,
            width: '100%'
        }).off('change.select2livewire').on('change.select2livewire', function (e) {
            let model = $(this).data('model');
            let value = $(this).val();

            console.log('Select2 changed:', model, value);

            if (model) {
                // Ensure we pass the value even if empty
                if (value === null || value === undefined) {
                    value = '';
                }

                // Use Livewire's set method
                @this.set(model, value);

                // Also dispatch a custom event to ensure Livewire updates
                @this.dispatch('select2-updated', { field: model, value: value });
            }
        });

        // Set initial values from Livewire data after a short delay
        setTimeout(() => {
            // Only set values if the elements exist and have Livewire data
            if ($('#project_id_livewire').length && @this.project_id) {
                $('#project_id_livewire').val(@this.project_id).trigger('change.select2');
            }
            if ($('#vehicle_id_livewire').length && @this.vehicle_id) {
                $('#vehicle_id_livewire').val(@this.vehicle_id).trigger('change.select2');
            }
        }, 200);
    }

    // Initialize when Livewire loads
    document.addEventListener('livewire:init', function () {
        console.log('Livewire init event fired');
        setTimeout(initSelect2Livewire, 100);
    });

    // Reinitialize after navigation (for SPA-like behavior)
    document.addEventListener('livewire:navigated', function () {
        console.log('Livewire navigated event fired');
        setTimeout(initSelect2Livewire, 100);
    });

    // Reinitialize after Livewire updates the DOM
    Livewire.hook('morph.updated', ({ el, component }) => {
        console.log('Livewire morph updated');
        setTimeout(initSelect2Livewire, 100);
    });

    // Ensure Select2 and calculation runs when modal opens
    $('#exampleModalLarge01').on('shown.bs.modal', function () {
        console.log('Modal shown, reinitializing Select2 and triggering calculation');
        setTimeout(() => {
            initSelect2Livewire();
            if (typeof @this !== 'undefined') {
                @this.call('calculateWorkingHours');
            }
        }, 300);
    });

    // Reset Select2 when form is reset
    document.addEventListener('livewire:init', () => {
        Livewire.on('resetTimesheetFormSelects', () => {
            console.log('Resetting Select2 selects');
            $('.select2-livewire').val('').trigger('change.select2');
            setTimeout(initSelect2Livewire, 100);
        });
    });

    // Time input calculation handlers
    document.addEventListener('livewire:init', () => {
        console.log('Livewire initialized for timesheet form');

        // Add event listeners to time inputs
        const timeInputs = document.querySelectorAll('input[type="datetime-local"]');

        timeInputs.forEach(input => {
            input.addEventListener('change', function() {
                console.log('Time input changed:', this.id, this.value);
                // Trigger calculation after a short delay to ensure Livewire has updated
                setTimeout(() => {
                    @this.call('calculateWorkingHours');
                }, 150);
            });

            // Also trigger on blur (when user finishes editing)
            input.addEventListener('blur', function() {
                console.log('Time input blur:', this.id, this.value);
                setTimeout(() => {
                    @this.call('calculateWorkingHours');
                }, 150);
            });
        });

        // Break duration input handler
        const breakInput = document.querySelector('#break_duration_hours_livewire');
        if (breakInput) {
            breakInput.addEventListener('change', function() {
                console.log('Break duration changed:', this.value);
                setTimeout(() => {
                    @this.call('calculateWorkingHours');
                }, 150);
            });
        }
    });

    window.addEventListener('timesheet-saved', function () {
        window.location.reload();
    });
</script>
@endpush
