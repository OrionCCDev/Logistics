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
                    <select class="form-control custom-select form-control-lg select2-livewire"
                        id="project_id_livewire" wire:model.live="project_id">
                        <option value="">Select Project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}-{{ $project->code }}</option>
                        @endforeach
                    </select>
                </div>
                @error('project_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4">
                <label for="vehicle_id_livewire">Vehicle</label>
                <div wire:ignore>
                    <select class="form-control custom-select form-control-lg select2-livewire"
                        id="vehicle_id_livewire" wire:model.live="vehicle_id">
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

            <div class="col-md-4 mt-3">
                <label for="fuel_consumption_status_livewire">Fuel Consumption Status</label>
                <select class="form-control custom-select" id="fuel_consumption_status_livewire" wire:model.live="fuel_consumption_status">
                    <option value="by_hours">By Hour</option>
                    <option value="by_odometer">By Odometer</option>
                </select>
                @error('fuel_consumption_status') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

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
        $('.select2-livewire').select2({
            dropdownParent: $('#exampleModalLarge01')
        }).off('change').on('change', function (e) {
            let model = $(this).attr('wire:model.live') || $(this).attr('wire:model');
            if (model) {
                @this.set(model, $(this).val());
            }
        });
    }

    document.addEventListener('livewire:load', function () {
        initSelect2Livewire();
        Livewire.hook('message.processed', (message, component) => {
            initSelect2Livewire();
        });
    });

    // Simplified approach - just trigger calculation on input changes
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
    });

    // Ensure calculation runs when modal opens
    $('#exampleModalLarge01').on('shown.bs.modal', function () {
        console.log('Modal shown, triggering initial calculation');
        setTimeout(() => {
            if (typeof @this !== 'undefined') {
                @this.call('calculateWorkingHours');
            }
        }, 300);
    });
</script>
@endpush
