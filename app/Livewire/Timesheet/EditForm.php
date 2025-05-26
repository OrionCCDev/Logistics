<?php

namespace App\Livewire\Timesheet;

use Carbon\Carbon;
use App\Models\Project;
use App\Models\Vehicle;
use App\Models\TimesheetDaily;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EditForm extends Component
{
    public $projects = [];
    public $vehicles = [];
    public $timesheetId;
    public $timesheet;

    #[Rule('nullable|exists:projects,id')]
    public $project_id = '';

    #[Rule('nullable|exists:vehicles,id')]
    public $vehicle_id = '';

    #[Rule('required|date')]
    public $date = '';

    #[Rule('nullable|date_format:Y-m-d\\TH:i')]
    public $working_start_hour = '';

    #[Rule('nullable|date_format:Y-m-d\\TH:i')]
    public $working_end_hour = '';

    #[Rule('nullable|date_format:Y-m-d\\TH:i')]
    public $break_start_at = '';

    #[Rule('nullable|date_format:Y-m-d\\TH:i')]
    public $break_ends_at = '';

    public $working_hours = '0.00';

    #[Rule('nullable|numeric|min:0')]
    public $odometer_start = '0';

    #[Rule('nullable|numeric|min:0')]
    public $odometer_ends = '0';

    public $validation_errors = [
        'working_times' => '',
        'break_times' => '',
        'odometer' => ''
    ];

    #[Rule('required|in:by_hours,by_odometer')]
    public $fuel_consumption_status = 'by_hours';

    #[Rule('nullable|numeric|min:0')]
    public $fuel_consumption = '0';

    #[Rule('nullable|numeric|min:0')]
    public $deduction_amount = '0';

    #[Rule('nullable|string')]
    public $note = '';

    #[Rule('required|string')]
    public $status = 'draft';

    public function mount($timesheetId)
    {
        $this->projects = Project::orderBy('name')->get();
        $this->vehicles = Vehicle::orderBy('plate_number')->get();
        $this->timesheetId = $timesheetId;
        $this->timesheet = TimesheetDaily::findOrFail($timesheetId);
        $this->fillFromModel($this->timesheet);
    }

    public function fillFromModel($model)
    {
        $this->project_id = $model->project_id ?? '';
        $this->vehicle_id = $model->vehicle_id ?? '';
        $this->date = $model->date ? $model->date->format('Y-m-d') : '';

        // Handle datetime fields properly
        $this->working_start_hour = $model->working_start_hour ?
            Carbon::parse($model->working_start_hour)->format('Y-m-d\\TH:i') : '';
        $this->working_end_hour = $model->working_end_hour ?
            Carbon::parse($model->working_end_hour)->format('Y-m-d\\TH:i') : '';
        $this->break_start_at = $model->break_start_at ?
            Carbon::parse($model->break_start_at)->format('Y-m-d\\TH:i') : '';
        $this->break_ends_at = $model->break_ends_at ?
            Carbon::parse($model->break_ends_at)->format('Y-m-d\\TH:i') : '';

        $this->working_hours = $model->working_hours ?? '0.00';
        $this->odometer_start = $model->odometer_start ?? '0';
        $this->odometer_ends = $model->odometer_ends ?? '0';
        $this->fuel_consumption_status = $model->fuel_consumption_status ?? 'by_hours';
        $this->fuel_consumption = $model->fuel_consumption ?? '0';
        $this->deduction_amount = $model->deduction_amount ?? '0';
        $this->note = $model->note ?? '';
        $this->status = $model->status ?? 'draft';
    }

    public function updated($propertyName)
    {
        $this->clearValidationError($propertyName);

        if (in_array($propertyName, ['working_start_hour', 'working_end_hour', 'break_start_at', 'break_ends_at'])) {
            $this->validateTimes();
            $this->calculateWorkingHours();
        }

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
                    $this->validation_errors['working_times'] = 'Working end time must be after start time';
                    return false;
                }
            }

            // Validate break hours if provided
            if (!empty($this->break_start_at) && !empty($this->break_ends_at)) {
                $breakStart = Carbon::parse($this->break_start_at);
                $breakEnd = Carbon::parse($this->break_ends_at);

                // Check if break end is after break start
                if ($breakEnd->lessThanOrEqualTo($breakStart)) {
                    $this->validation_errors['break_times'] = 'Break end time must be after break start time';
                    return false;
                }

                // Check if break times are within working hours
                if (!empty($this->working_start_hour) && !empty($this->working_end_hour)) {
                    $workStart = Carbon::parse($this->working_start_hour);
                    $workEnd = Carbon::parse($this->working_end_hour);

                    if ($breakStart->lessThan($workStart)) {
                        $this->validation_errors['break_times'] = 'Break start time must be after working start time';
                        return false;
                    }

                    if ($breakEnd->greaterThan($workEnd)) {
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
            $this->validation_errors['break_times'] = '';
            $this->validation_errors['working_times'] = '';
            return true;

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

            if ($end > 0 && $end <= $start) {
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
                return;
            }

            // Don't calculate if there are validation errors
            if (!empty($this->validation_errors['working_times']) ||
                !empty($this->validation_errors['break_times'])) {
                $this->working_hours = '0.00';
                return;
            }

            // Parse start and end times
            $startTime = Carbon::parse($this->working_start_hour);
            $endTime = Carbon::parse($this->working_end_hour);

            if (!$startTime || !$endTime || $endTime->lessThanOrEqualTo($startTime)) {
                $this->working_hours = '0.00';
                return;
            }

            // Calculate total working minutes
            $totalMinutes = $startTime->diffInMinutes($endTime);

            // Calculate break time if provided and valid
            $breakMinutes = 0;
            if (!empty($this->break_start_at) && !empty($this->break_ends_at) &&
                empty($this->validation_errors['break_times'])) {
                $breakStart = Carbon::parse($this->break_start_at);
                $breakEnd = Carbon::parse($this->break_ends_at);

                if ($breakStart && $breakEnd && $breakEnd->greaterThan($breakStart) &&
                    $breakStart->greaterThanOrEqualTo($startTime) &&
                    $breakEnd->lessThanOrEqualTo($endTime)) {
                    $breakMinutes = $breakStart->diffInMinutes($breakEnd);
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
            Log::error('Calculation error: ' . $e->getMessage());
        }
    }

    public function save()
    {
        Log::info('Save method called');

        // Validate times and odometer
        $timeValidation = $this->validateTimes();
        $odometerValidation = $this->validateOdometer();

        if (!$timeValidation || !$odometerValidation) {
            session()->flash('error', 'Please fix the validation errors before saving.');
            return;
        }

        // Validate form data
        $this->validate();

        try {
            // Prepare update data
            $updateData = [
                'project_id' => $this->project_id ?: null,
                'vehicle_id' => $this->vehicle_id ?: null,
                'date' => $this->date,
                'working_start_hour' => $this->working_start_hour ? Carbon::parse($this->working_start_hour) : null,
                'working_end_hour' => $this->working_end_hour ? Carbon::parse($this->working_end_hour) : null,
                'break_start_at' => $this->break_start_at ? Carbon::parse($this->break_start_at) : null,
                'break_ends_at' => $this->break_ends_at ? Carbon::parse($this->break_ends_at) : null,
                'working_hours' => (float) $this->working_hours,
                'odometer_start' => (float) $this->odometer_start,
                'odometer_ends' => (float) $this->odometer_ends,
                'fuel_consumption_status' => $this->fuel_consumption_status,
                'fuel_consumption' => (float) $this->fuel_consumption,
                'deduction_amount' => (float) $this->deduction_amount ?: 0,
                'note' => $this->note,
                'status' => $this->status,
            ];

            Log::info('Updating timesheet with data:', $updateData);

            // Update the timesheet
            $updated = $this->timesheet->update($updateData);

            if ($updated) {
                session()->flash('success', 'Timesheet entry updated successfully.');
                $this->dispatch('timesheetUpdated');

                // Redirect to index
                return redirect()->route('timesheet.index');
            } else {
                session()->flash('error', 'Failed to update timesheet entry.');
            }

        } catch (\Exception $e) {
            Log::error('Error updating timesheet: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.timesheet.edit-form');
    }
}