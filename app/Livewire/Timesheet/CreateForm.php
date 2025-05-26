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

    #[Rule('nullable|date_format:Y-m-d\\TH:i')]
    public $break_start_at = '';

    #[Rule('nullable|date_format:Y-m-d\\TH:i')]
    public $break_ends_at = '';

    public $working_hours = '0.00';

    #[Rule('required|numeric|min:0')]
    public $odometer_start = '0';

    #[Rule('required|numeric|min:0')]
    public $odometer_ends = '0';

    // Validation error messages
    public $validation_errors = [
        'working_times' => '',
        'break_times' => '',
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

    public function mount()
    {
        $this->projects = Project::orderBy('name')->get();
        $this->vehicles = Vehicle::orderBy('plate_number')->get();
        $this->date = now()->format('Y-m-d');
        $this->working_start_hour = now()->format('Y-m-d\\TH:i');
        $this->working_end_hour = now()->addHours(8)->format('Y-m-d\\TH:i');
        $this->break_start_at = '';
        $this->break_ends_at = '';
        $this->calculateWorkingHours();
    }

    // Use updated() method instead of individual updatedProperty methods
    public function updated($propertyName)
    {
        Log::info("Property updated: $propertyName");

        // Clear previous validation errors for the updated field
        $this->clearValidationError($propertyName);

        // Only recalculate when time-related properties change
        if (in_array($propertyName, ['working_start_hour', 'working_end_hour', 'break_start_at', 'break_ends_at'])) {
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
        if (in_array($propertyName, ['break_start_at', 'break_ends_at'])) {
            $this->validation_errors['break_times'] = '';
        }
        if (in_array($propertyName, ['odometer_start', 'odometer_ends'])) {
            $this->validation_errors['odometer'] = '';
        }
    }

    public function validateTimes()
    {
        $errors = [];

        try {
            // Validate working hours
            if (!empty($this->working_start_hour) && !empty($this->working_end_hour)) {
                $startTime = Carbon::parse($this->working_start_hour);
                $endTime = Carbon::parse($this->working_end_hour);

                if ($endTime->lessThanOrEqualTo($startTime)) {
                    $errors[] = 'Working end time must be after start time';
                    $this->validation_errors['working_times'] = 'Working end time must be after start time';
                }
            }

            // Validate break hours if provided
            if (!empty($this->break_start_at) && !empty($this->break_ends_at)) {
                $breakStart = Carbon::parse($this->break_start_at);
                $breakEnd = Carbon::parse($this->break_ends_at);

                // Check if break end is after break start
                if ($breakEnd->lessThanOrEqualTo($breakStart)) {
                    $errors[] = 'Break end time must be after break start time';
                    $this->validation_errors['break_times'] = 'Break end time must be after break start time';
                    return false;
                }

                // Check if break times are within working hours
                if (!empty($this->working_start_hour) && !empty($this->working_end_hour)) {
                    $workStart = Carbon::parse($this->working_start_hour);
                    $workEnd = Carbon::parse($this->working_end_hour);

                    if ($breakStart->lessThan($workStart)) {
                        $errors[] = 'Break start time must be after working start time';
                        $this->validation_errors['break_times'] = 'Break start time must be after working start time';
                        return false;
                    }

                    if ($breakEnd->greaterThan($workEnd)) {
                        $errors[] = 'Break end time must be before working end time';
                        $this->validation_errors['break_times'] = 'Break end time must be before working end time';
                        return false;
                    }
                }
            }

            // Check if only one break time is provided
            if ((!empty($this->break_start_at) && empty($this->break_ends_at)) ||
                (empty($this->break_start_at) && !empty($this->break_ends_at))) {
                $this->validation_errors['break_times'] = 'Both break start and end times are required if using break time';
                return false;
            }

            // Clear break time errors if validation passes
            if (empty($errors)) {
                $this->validation_errors['break_times'] = '';
            }

            return empty($errors);

        } catch (\Exception $e) {
            Log::error('Time validation error: ' . $e->getMessage());
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
                'break_start' => $this->break_start_at,
                'break_end' => $this->break_ends_at
            ]);

            // Reset if no start or end time
            if (empty($this->working_start_hour) || empty($this->working_end_hour)) {
                $this->working_hours = '0.00';
                Log::info('Missing start or end time');
                return;
            }

            // Don't calculate if there are validation errors
            if (!empty($this->validation_errors['working_times']) ||
                !empty($this->validation_errors['break_times'])) {
                $this->working_hours = '0.00';
                Log::info('Validation errors present, not calculating');
                return;
            }

            // Parse start and end times using Carbon::parse (more robust)
            $startTime = Carbon::parse($this->working_start_hour);
            $endTime = Carbon::parse($this->working_end_hour);

            if (!$startTime || !$endTime) {
                $this->working_hours = '0.00';
                Log::error('Failed to parse start/end times');
                return;
            }

            Log::info('Times parsed successfully', [
                'start_parsed' => $startTime->format('Y-m-d H:i:s'),
                'end_parsed' => $endTime->format('Y-m-d H:i:s'),
                'start_timestamp' => $startTime->timestamp,
                'end_timestamp' => $endTime->timestamp
            ]);

            // Check if this is a valid time range
            if ($endTime->lessThanOrEqualTo($startTime)) {
                $this->working_hours = '0.00';
                Log::error('End time is not after start time');
                return;
            }

            // Calculate total working minutes (ensure positive result)
            $totalMinutes = $startTime->diffInMinutes($endTime);
            Log::info("Total minutes calculated: $totalMinutes");

            // Calculate break time if provided and valid
            $breakMinutes = 0;
            if (!empty($this->break_start_at) && !empty($this->break_ends_at) &&
                empty($this->validation_errors['break_times'])) {
                $breakStart = Carbon::parse($this->break_start_at);
                $breakEnd = Carbon::parse($this->break_ends_at);

                if ($breakStart && $breakEnd) {
                    Log::info('Break times parsed', [
                        'break_start_parsed' => $breakStart->format('Y-m-d H:i:s'),
                        'break_end_parsed' => $breakEnd->format('Y-m-d H:i:s'),
                        'break_start_timestamp' => $breakStart->timestamp,
                        'break_end_timestamp' => $breakEnd->timestamp
                    ]);

                    if ($breakEnd->greaterThan($breakStart) &&
                        $breakStart->greaterThanOrEqualTo($startTime) &&
                        $breakEnd->lessThanOrEqualTo($endTime)) {
                        $breakMinutes = $breakStart->diffInMinutes($breakEnd);
                        Log::info("Valid break period, minutes calculated: $breakMinutes");
                    } else {
                        Log::info('Break period is invalid or outside working hours');
                    }
                }
            }

            // Calculate net working time
            $netMinutes = $totalMinutes - $breakMinutes;
            if ($netMinutes < 0) $netMinutes = 0;

            // Convert to H.MM format (hours.minutes)
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

    // Add a test method to debug the exact values
    public function testTimeCalculation()
    {
        Log::info('=== TESTING TIME CALCULATION ===');
        Log::info('Raw values:', [
            'working_start_hour' => $this->working_start_hour,
            'working_end_hour' => $this->working_end_hour,
            'break_start_at' => $this->break_start_at,
            'break_ends_at' => $this->break_ends_at
        ]);

        // Test with simple Carbon parsing
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
        // Run custom validations before saving
        $timeValidation = $this->validateTimes();
        $odometerValidation = $this->validateOdometer();

        if (!$timeValidation || !$odometerValidation) {
            // Don't save if custom validations fail
            session()->flash('error', 'Please fix the validation errors before saving.');
            return;
        }

        // Run standard Laravel validation
        $this->validate();

        TimesheetDaily::create([
            'user_id' => Auth::id(),
            'project_id' => $this->project_id,
            'vehicle_id' => $this->vehicle_id,
            'date' => $this->date,
            'working_start_hour' => Carbon::parse($this->working_start_hour),
            'working_end_hour' => Carbon::parse($this->working_end_hour),
            'break_start_at' => $this->break_start_at ? Carbon::parse($this->break_start_at) : null,
            'break_ends_at' => $this->break_ends_at ? Carbon::parse($this->break_ends_at) : null,
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
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->resetExcept('projects', 'vehicles');
        $this->date = now()->format('Y-m-d');
        $this->working_start_hour = now()->format('Y-m-d\\TH:i');
        $this->working_end_hour = now()->addHours(8)->format('Y-m-d\\TH:i');
        $this->break_start_at = '';
        $this->break_ends_at = '';
        $this->working_hours = '0.00';
        $this->odometer_start = '0';
        $this->odometer_ends = '0';
        $this->fuel_consumption_status = 'by_hours';
        $this->fuel_consumption = '0';
        $this->deduction_amount = '0';
        $this->note = '';

        // Reset validation errors
        $this->validation_errors = [
            'working_times' => '',
            'break_times' => '',
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