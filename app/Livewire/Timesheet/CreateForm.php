<?php

namespace App\Livewire\Timesheet;

use Carbon\Carbon;
use App\Models\Project;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\TimesheetDaily;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateForm extends Component
{
    public $projects = [];
    public $vehicles = [];

    #[Rule('required|exists:projects,id')]
    public $project_id = '';

    #[Rule('required|exists:vehicles,id')]
    public $vehicle_id = '';

    #[Rule('required|date')]
    public $date = '';

    #[Rule('required|date_format:Y-m-d\\TH:i')]
    public $working_start_hour = '';

    #[Rule('required|date_format:Y-m-d\\TH:i')]
    public $working_end_hour = '';

    #[Rule('nullable|numeric|min:0|max:24')]
    public $break_duration_hours = '1.0';

    public $working_hours = '0.00';

    #[Rule('required|numeric|min:0')]
    public $odometer_start = '0';

    #[Rule('required|numeric|min:0')]
    public $odometer_ends = '0';

    // Validation error messages
    public $validation_errors = [
        'working_times' => '',
        'odometer' => ''
    ];

    #[Rule('required|in:by_hours,by_odometer')]
    public $fuel_consumption_status = 'by_hours';

    #[Rule('required|numeric|min:0')]
    public $fuel_consumption = '0';

    #[Rule('nullable|numeric|min:0')]
    public $deduction_amount = '0';

    #[Rule('nullable|string')]
    public $note = '';

    // Add listeners for Select2 events
    protected $listeners = [
        'select2-updated' => 'handleSelect2Update',
        'resetTimesheetFormSelects' => '$refresh'
    ];

    public function mount()
    {
        $user = Auth::user();
        if ($user->role === 'orionDC') {
            // Assuming a user has one assigned project via a relationship or attribute
            $assignedProject = $user->employee->projects->first() ?? null;
            if ($assignedProject) {
                $this->projects = collect([$assignedProject]);
                $this->project_id = $assignedProject->id;
            } else {
                $this->projects = collect();
                $this->project_id = '';
            }
        } else {
            $this->projects = Project::orderBy('name')->get();
        }
        $this->vehicles = Vehicle::orderBy('plate_number')->get();
        $this->date = now()->format('Y-m-d');
        $this->working_start_hour = now()->setHour(6)->setMinute(0)->setSecond(0)->format('Y-m-d\TH:i');
        $this->working_end_hour = now()->addHours(8)->format('Y-m-d\TH:i');
        $this->break_duration_hours = '1.0';
        $this->calculateWorkingHours();
    }

    // Specific updaters for Select2 fields
    public function updatedProjectId($value)
    {
        Log::info('Project ID updated via Livewire:', ['value' => $value, 'type' => gettype($value)]);

        if ($value !== '' && $value !== null) {
            $this->project_id = (int) $value;
        } else {
            $this->project_id = '';
        }

        $this->clearValidationError('project_id');
        Log::info('Project ID after processing:', ['value' => $this->project_id]);
    }

    public function updatedVehicleId($value)
    {
        Log::info('Vehicle ID updated via Livewire:', ['value' => $value, 'type' => gettype($value)]);

        if ($value !== '' && $value !== null) {
            $this->vehicle_id = (int) $value;
        } else {
            $this->vehicle_id = '';
        }

        $this->clearValidationError('vehicle_id');
        Log::info('Vehicle ID after processing:', ['value' => $this->vehicle_id]);
    }

    // Handle Select2 updates
    public function handleSelect2Update($data = null)
    {
        if (!$data) return;

        if ($data['field'] === 'project_id') {
            $this->project_id = $data['value'] !== '' ? (int) $data['value'] : '';
            $this->clearValidationError('project_id');
        }

        if ($data['field'] === 'vehicle_id') {
            $this->vehicle_id = $data['value'] !== '' ? (int) $data['value'] : '';
            $this->clearValidationError('vehicle_id');
        }
    }

    // Use updated() method instead of individual updatedProperty methods
    public function updated($propertyName)
    {
        Log::info("Property updated: $propertyName");

        // Skip if already handled by specific updaters
        if (in_array($propertyName, ['project_id', 'vehicle_id'])) {
            return;
        }

        // Clear previous validation errors for the updated field
        $this->clearValidationError($propertyName);

        // Only recalculate when time-related properties change
        if (in_array($propertyName, ['working_start_hour', 'working_end_hour', 'break_duration_hours'])) {
            $this->validateTimes();
            $this->calculateWorkingHours();
        }

        // Validate odometer when odometer fields change
        if (in_array($propertyName, ['odometer_start', 'odometer_ends'])) {
            $this->validateOdometer();
        }
    }

    public function clearValidationError($propertyName)
    {
        if (in_array($propertyName, ['working_start_hour', 'working_end_hour'])) {
            $this->validation_errors['working_times'] = '';
        }
        if ($propertyName === 'break_duration_hours') {
            // $this->validation_errors['break_times'] = ''; // Or a new error key for duration
        }
        if (in_array($propertyName, ['odometer_start', 'odometer_ends'])) {
            $this->validation_errors['odometer'] = '';
        }
        if (in_array($propertyName, ['project_id', 'vehicle_id'])) {
            // Clear Laravel validation errors for these fields
            $this->resetErrorBag($propertyName);
        }
    }

    public function validateTimes()
    {
        try {
            // Validate working hours
            if (!empty($this->working_start_hour) && !empty($this->working_end_hour)) {
                $startTime = Carbon::parse($this->working_start_hour);
                $endTime = Carbon::parse($this->working_end_hour);

                if ($endTime->lessThanOrEqualTo($startTime)) {
                    $this->validation_errors['working_times'] = 'Working end time must be after start time';
                    return false;
                }
            }
            $this->validation_errors['working_times'] = '';

            return empty($this->validation_errors['working_times']);

        } catch (\Exception $e) {
            Log::error('Time validation error: ' . $e->getMessage());
            $this->validation_errors['working_times'] = 'Invalid time input.';
            return false;
        }
    }

    public function validateOdometer()
    {
        try {
            $start = (float) $this->odometer_start;
            $end = (float) $this->odometer_ends;

            if ($start < 0) {
                $this->validation_errors['odometer'] = 'Odometer start cannot be negative';
                return false;
            }

            if ($end < 0) {
                $this->validation_errors['odometer'] = 'Odometer end cannot be negative';
                return false;
            }

            if ($end <= $start && $end > 0) {
                $this->validation_errors['odometer'] = 'Odometer end must be greater than odometer start';
                return false;
            }

            // Clear odometer errors if validation passes
            $this->validation_errors['odometer'] = '';
            return true;

        } catch (\Exception $e) {
            Log::error('Odometer validation error: ' . $e->getMessage());
            $this->validation_errors['odometer'] = 'Invalid odometer values';
            return false;
        }
    }

    public function calculateWorkingHours()
    {
        try {
            Log::info('Starting calculation', [
                'start' => $this->working_start_hour,
                'end' => $this->working_end_hour,
                'break_duration_hours' => $this->break_duration_hours
            ]);

            // Reset if no start or end time
            if (empty($this->working_start_hour) || empty($this->working_end_hour)) {
                $this->working_hours = '0.00';
                Log::info('Missing start or end time');
                return;
            }

            // Don't calculate if there are validation errors
            if (!empty($this->validation_errors['working_times'])) {
                $this->working_hours = '0.00';
                Log::info('Validation errors present, not calculating');
                return;
            }

            $startTime = Carbon::parse($this->working_start_hour);
            $endTime = Carbon::parse($this->working_end_hour);

            if (!$startTime || !$endTime) {
                $this->working_hours = '0.00';
                Log::error('Failed to parse start/end times');
                return;
            }

            Log::info('Times parsed successfully', [
                'start_parsed' => $startTime->format('Y-m-d H:i:s'),
                'end_parsed' => $endTime->format('Y-m-d H:i:s')
            ]);

            if ($endTime->lessThanOrEqualTo($startTime)) {
                $this->working_hours = '0.00';
                Log::error('End time is not after start time');
                return;
            }

            $totalMinutes = $startTime->diffInMinutes($endTime);
            Log::info("Total minutes calculated: $totalMinutes");

            // Calculate break time from break_duration_hours
            $breakMinutes = 0;
            if (!empty($this->break_duration_hours) && is_numeric($this->break_duration_hours)) {
                $breakDurationInHours = (float) $this->break_duration_hours;
                if ($breakDurationInHours > 0) {
                    $breakMinutes = round($breakDurationInHours * 60);
                    Log::info("Break duration in hours: $breakDurationInHours, minutes calculated: $breakMinutes");
                }
            } else {
                Log::info('Break duration is empty or not numeric.');
            }

            $netMinutes = $totalMinutes - $breakMinutes;
            if ($netMinutes < 0) $netMinutes = 0;

            $hours = floor($netMinutes / 60);
            $remainingMinutes = $netMinutes % 60;
            $this->working_hours = sprintf('%d.%02d', $hours, $remainingMinutes);

            Log::info('Calculation completed', [
                'total_minutes' => $totalMinutes,
                'break_minutes' => $breakMinutes,
                'net_minutes' => $netMinutes,
                'result' => $this->working_hours
            ]);

        } catch (\Exception $e) {
            $this->working_hours = '0.00';
            Log::error('Calculation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    // Add a manual trigger method for testing
    public function triggerCalculation()
    {
        $this->calculateWorkingHours();
        Log::info('Manual calculation triggered');
    }

    // Debug method to check current values
    public function debugFormData()
    {
        $debugData = [
            'project_id' => $this->project_id,
            'vehicle_id' => $this->vehicle_id,
            'project_id_type' => gettype($this->project_id),
            'vehicle_id_type' => gettype($this->vehicle_id),
            'project_id_empty' => empty($this->project_id),
            'vehicle_id_empty' => empty($this->vehicle_id),
            'date' => $this->date,
            'working_start_hour' => $this->working_start_hour,
            'working_end_hour' => $this->working_end_hour,
            'break_duration_hours' => $this->break_duration_hours,
            'working_hours' => $this->working_hours,
        ];

        Log::info('Debug form data:', $debugData);

        // Also dispatch to frontend console
        $this->dispatch('console-log', $debugData);

        return $debugData;
    }

    // Add a test method to debug the exact values
    public function testTimeCalculation()
    {
        Log::info('=== TESTING TIME CALCULATION ===');
        Log::info('Raw values:', [
            'working_start_hour' => $this->working_start_hour,
            'working_end_hour' => $this->working_end_hour,
            'break_duration_hours' => $this->break_duration_hours
        ]);

        if ($this->working_start_hour && $this->working_end_hour) {
            $start = Carbon::parse($this->working_start_hour);
            $end = Carbon::parse($this->working_end_hour);

            Log::info('Parsed with Carbon::parse:', [
                'start' => $start->toDateTimeString(),
                'end' => $end->toDateTimeString(),
                'diff_minutes' => $start->diffInMinutes($end),
                'diff_hours' => $start->diffInHours($end),
                'is_end_after_start' => $end->greaterThan($start)
            ]);
        }

        $this->calculateWorkingHours();
    }

    public function save()
    {
        Log::info('=== SAVE METHOD CALLED ===');
        Log::info('Form data before validation:', [
            'project_id' => $this->project_id,
            'vehicle_id' => $this->vehicle_id,
            'project_id_type' => gettype($this->project_id),
            'vehicle_id_type' => gettype($this->vehicle_id),
            'project_id_empty' => empty($this->project_id),
            'vehicle_id_empty' => empty($this->vehicle_id),
        ]);

        // Ensure IDs are properly formatted for validation
        if ($this->project_id !== '' && $this->project_id !== null) {
            $this->project_id = (int) $this->project_id;
        }

        if ($this->vehicle_id !== '' && $this->vehicle_id !== null) {
            $this->vehicle_id = (int) $this->vehicle_id;
        }

        // Custom validations first
        $timeValidation = $this->validateTimes();
        $odometerValidation = $this->validateOdometer();

        if (!$timeValidation || !$odometerValidation) {
            session()->flash('error', 'Please fix the validation errors before saving.');
            return;
        }

        // Laravel validation
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'current_data' => [
                    'project_id' => $this->project_id,
                    'vehicle_id' => $this->vehicle_id,
                ]
            ]);

            // Flash error message for user
            session()->flash('error', 'Please check all required fields and try again.');
            throw $e;
        }

        $breakDurationInMinutes = 0;
        if (!empty($this->break_duration_hours) && is_numeric($this->break_duration_hours)) {
            $breakDurationInHours = (float) $this->break_duration_hours;
            if ($breakDurationInHours > 0) {
                $breakDurationInMinutes = round($breakDurationInHours * 60);
            }
        }

        try {
            TimesheetDaily::create([
                'user_id' => Auth::id(),
                'project_id' => $this->project_id,
                'vehicle_id' => $this->vehicle_id,
                'date' => $this->date,
                'working_start_hour' => Carbon::parse($this->working_start_hour),
                'working_end_hour' => Carbon::parse($this->working_end_hour),
                'break_duration_minutes' => $breakDurationInMinutes,
                'working_hours' => (float) $this->working_hours,
                'odometer_start' => $this->odometer_start,
                'odometer_ends' => $this->odometer_ends,
                'fuel_consumption_status' => $this->fuel_consumption_status,
                'fuel_consumption' => $this->fuel_consumption,
                'deduction_amount' => $this->deduction_amount ?: 0,
                'note' => $this->note,
            ]);

            session()->flash('success', 'Timesheet entry created successfully.');
            $this->dispatch('timesheetSaved');
            $this->dispatch('timesheet-saved');
            $this->resetForm();

            Log::info('Timesheet saved successfully');

        } catch (\Exception $e) {
            Log::error('Error saving timesheet:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'An error occurred while saving. Please try again.');
        }
    }

    public function resetForm()
    {
        $this->resetExcept('projects', 'vehicles');
        $this->date = now()->format('Y-m-d');
        $this->working_start_hour = now()->setHour(6)->setMinute(0)->setSecond(0)->format('Y-m-d\TH:i');
        $this->working_end_hour = now()->addHours(8)->format('Y-m-d\TH:i');
        $this->break_duration_hours = '1.0';
        $this->working_hours = '0.00';
        $this->odometer_start = '0';
        $this->odometer_ends = '0';
        $this->fuel_consumption_status = 'by_hours';
        $this->fuel_consumption = '0';
        $this->deduction_amount = '0';
        $this->note = '';

        $this->validation_errors = [
            'working_times' => '',
            'odometer' => ''
        ];

        $this->calculateWorkingHours();
        $this->dispatch('resetTimesheetFormSelects');
    }

    public function render()
    {
        return view('livewire.timesheet.create-form');
    }
}
