<div>
    <form wire:submit.prevent="save">
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
            <!-- Project Selection -->
            <div class="col-md-4">
                <div class="form-group">
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
            </div>

            <!-- Vehicle Selection -->
            <div class="col-md-4">
                <div class="form-group">
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
            </div>

            <!-- Date -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="date_livewire">Date</label>
                    <input type="date" class="form-control" id="date_livewire" wire:model.live="date">
                    @error('date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Working Start Hour -->
            <div class="col-md-3 mt-3">
                <div class="form-group">
                    <label for="working_start_hour_livewire">Working Start</label>
                    <input type="datetime-local"
                           class="form-control"
                           id="working_start_hour_livewire"
                           wire:model.live="working_start_hour">
                    @error('working_start_hour') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Working End Hour -->
            <div class="col-md-3 mt-3">
                <div class="form-group">
                    <label for="working_end_hour_livewire">Working End</label>
                    <input type="datetime-local"
                           class="form-control"
                           id="working_end_hour_livewire"
                           wire:model.live="working_end_hour">
                    @error('working_end_hour') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Added Break Duration Input -->
            <div class="col-md-3 mt-3">
                <div class="form-group">
                    <label for="break_duration_hours_livewire">Break Duration (Hours)</label>
                    <input type="number" step="0.01" min="0" max="24"
                           class="form-control"
                           id="break_duration_hours_livewire"
                           wire:model.live="break_duration_hours">
                    @error('break_duration_hours') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Working Hours (Calculated) -->
            <div class="col-md-4 mt-3">
                <div class="form-group">
                    <label for="working_hours_livewire">Working Hours</label>
                    <div class="position-relative">
                        <input type="text"
                               class="form-control bg-light"
                               id="working_hours_livewire"
                               wire:model.live="working_hours"
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
            </div>

            <!-- Odometer Start -->
            <div class="col-md-4 mt-3">
                <div class="form-group">
                    <label for="odometer_start_livewire">Odometer Start</label>
                    <input type="number" class="form-control" id="odometer_start_livewire"
                           wire:model.live="odometer_start" step="0.01">
                    @error('odometer_start') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Odometer End -->
            <div class="col-md-4 mt-3">
                <div class="form-group">
                    <label for="odometer_ends_livewire">Odometer Ends</label>
                    <input type="number" class="form-control" id="odometer_ends_livewire"
                           wire:model.live="odometer_ends" step="0.01">
                    @error('odometer_ends') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Fuel Consumption Status -->
            <div class="col-md-4 mt-3">
                <div class="form-group">
                    <label for="fuel_consumption_status_livewire">Fuel Consumption Status</label>
                    <select class="form-control custom-select" id="fuel_consumption_status_livewire"
                            wire:model.live="fuel_consumption_status">
                        <option value="by_hours">By Hour</option>
                        <option value="by_odometer">By Odometer</option>
                    </select>
                    @error('fuel_consumption_status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Fuel Consumption -->
            <div class="col-md-4 mt-3">
                <div class="form-group">
                    <label for="fuel_consumption_livewire">Fuel Consumption</label>
                    <input type="number" class="form-control" id="fuel_consumption_livewire"
                           wire:model.live="fuel_consumption" step="0.01">
                    @error('fuel_consumption') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Deduction Amount -->
            <div class="col-md-4 mt-3">
                <div class="form-group">
                    <label for="deduction_amount_livewire">Deduction Amount</label>
                    <input type="number" class="form-control" id="deduction_amount_livewire"
                           wire:model.live="deduction_amount" step="0.01">
                    @error('deduction_amount') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="col-md-4 mt-3">
                <div class="form-group">
                    <label for="status_livewire">Status</label>
                    <select class="form-control custom-select" id="status_livewire"
                            wire:model.live="status">
                        <option value="draft">Draft</option>
                        <option value="submitted">Submitted</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Note -->
            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <label for="note_livewire">Note</label>
                    <textarea class="form-control" id="note_livewire" wire:model.live="note" rows="3"></textarea>
                    @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-group mt-4">
            <div class="d-flex justify-content-between">
                <a href="{{ route('timesheet.index') }}" class="btn btn-secondary">
                    <i class="fa fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">
                        <i class="fa fa-save"></i> Update Entry
                    </span>
                    <span wire:loading wire:target="save">
                        <i class="fa fa-spinner fa-spin"></i> Updating...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function initSelect2Livewire() {
        console.log('initSelect2Livewire called');
        $('.select2-livewire').each(function() {
            let $select = $(this);
            let model = $select.attr('wire:model.live') || $select.attr('wire:model');

            if ($select.data('select2')) {
                $select.select2('destroy');
            }

            $select.select2().off('change').on('change', function (e) {
                if (model) {
                    @this.set(model, $(this).val());
                }
            });
        });
    }

    document.addEventListener('livewire:init', function () {
        console.log('Livewire initialized for timesheet edit form');

        // Initialize Select2
        initSelect2Livewire();

        // Reinitialize after Livewire updates
        Livewire.hook('morph.updated', () => {
            initSelect2Livewire();
        });

        // Add event listeners to time inputs for calculation
        const timeInputs = document.querySelectorAll('input[type="datetime-local"]');
        timeInputs.forEach(input => {
            input.addEventListener('change', function() {
                console.log('Time input changed:', this.id, this.value);
                setTimeout(() => {
                    @this.call('calculateWorkingHours');
                }, 150);
            });

            input.addEventListener('blur', function() {
                console.log('Time input blur:', this.id, this.value);
                setTimeout(() => {
                    @this.call('calculateWorkingHours');
                }, 150);
            });
        });
    });

    // Listen for timesheetUpdated event
    document.addEventListener('livewire:init', () => {
        Livewire.on('timesheetUpdated', () => {
            console.log('Timesheet updated successfully');
            // Optional: Show a toast notification or redirect
        });
    });
</script>
@endpush
