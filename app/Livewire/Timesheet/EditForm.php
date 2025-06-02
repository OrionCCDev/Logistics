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

    #[Rule('nullable|numeric|min:0|max:24')]
    public $break_duration_hours = '1.0';

    public $working_hours = '0.00';

    #[Rule('nullable|numeric|min:0')]
    public $odometer_start = '0';

    #[Rule('nullable|numeric|min:0')]
    public $odometer_ends = '0';

    public $validation_errors = [
        'working_times' => '',
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
        $this->calculateWorkingHours();
    }

    public function fillFromModel($model)
    {
        $this->project_id = $model->project_id ?? '';
        $this->vehicle_id = $model->vehicle_id ?? '';
        $this->date = $model->date ? $model->date->format('Y-m-d') : '';

        $this->working_start_hour = $model->working_start_hour ?
            Carbon::parse($model->working_start_hour)->format('Y-m-d\\TH:i') : '';
        $this->working_end_hour = $model->working_end_hour ?
            Carbon::parse($model->working_end_hour)->format('Y-m-d\\TH:i') : '';

        if (isset($model->break_duration_minutes) && is_numeric($model->break_duration_minutes) && $model->break_duration_minutes >= 0) {
            $hours = floor($model->break_duration_minutes / 60);
            $minutes = $model->break_duration_minutes % 60;
            $this->break_duration_hours = sprintf('%d.%02d', $hours, $minutes);
        } else {
            $this->break_duration_hours = '0.00';
        }

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

        if (in_array($propertyName, ['working_start_hour', 'working_end_hour', 'break_duration_hours'])) {
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
        if ($propertyName === 'break_duration_hours') {
            // If you add a specific error key for break_duration_hours, clear it here
        }
        if (in_array($propertyName, ['odometer_start', 'odometer_ends'])) {
            $this->validation_errors['odometer'] = '';
        }
    }

    public function validateTimes()
    {
        try {
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

            if ($end > 0 && $end <= $start) {
                $this->validation_errors['odometer'] = 'Odometer end must be greater than odometer start';
                return false;
            }

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
            Log::info('Calculation START (EditForm)', [
                'start_input' => $this->working_start_hour,
                'end_input' => $this->working_end_hour,
                'break_hours_input' => $this->break_duration_hours
            ]);

            if (empty($this->working_start_hour) || empty($this->working_end_hour)) {
                $this->working_hours = '0.00';
                Log::info('Calculation ABORTED: Missing start or end time (EditForm)');
                return;
            }

            if (!empty($this->validation_errors['working_times'])) {
                $this->working_hours = '0.00';
                Log::info('Calculation ABORTED: Validation errors exist (EditForm)');
                return;
            }

            $startTime = Carbon::parse($this->working_start_hour);
            $endTime = Carbon::parse($this->working_end_hour);

            Log::info('Parsed Carbon dates (EditForm)', [
                'startTime' => $startTime->toDateTimeString(),
                'endTime' => $endTime->toDateTimeString()
            ]);

            if (!$startTime || !$endTime || $endTime->lessThanOrEqualTo($startTime)) {
                $this->working_hours = '0.00';
                Log::info('Calculation ABORTED: Invalid dates or end <= start (EditForm)');
                return;
            }

            $totalMinutes = $startTime->diffInMinutes($endTime);
            Log::info('Total minutes before break (EditForm)', ['totalMinutes' => $totalMinutes]);

            $breakMinutes = 0;
            if (!empty($this->break_duration_hours) && is_string($this->break_duration_hours)) {
                 $parts = explode('.', $this->break_duration_hours);
                 $hours = (int)($parts[0] ?? 0);
                 $minutes = (int)($parts[1] ?? 0);

                 if ($hours >= 0 && $minutes >= 0 && $minutes < 100) {
                    $breakMinutes = ($hours * 60) + $minutes;
                 } else {
                     Log::warning('Invalid break duration format or value (EditForm)', ['input' => $this->break_duration_hours]);
                 }

            } else if (is_numeric($this->break_duration_hours) && $this->break_duration_hours >= 0) {
                 $breakMinutes = round((float) $this->break_duration_hours * 60);
                 Log::info('Parsed numeric break duration (EditForm)', ['input' => $this->break_duration_hours, 'breakMinutes' => $breakMinutes]);
            }

            Log::info('Break minutes (EditForm)', ['breakMinutes' => $breakMinutes]);

            $netMinutes = $totalMinutes - $breakMinutes;
            if ($netMinutes < 0) $netMinutes = 0;

            Log::info('Net minutes after break (EditForm)', ['netMinutes' => $netMinutes]);

            $hours = floor($netMinutes / 60);
            $remainingMinutes = $netMinutes % 60;

            $this->working_hours = sprintf('%d.%02d', $hours, $remainingMinutes);

            Log::info('Calculation RESULT (EditForm)', [
                'result' => $this->working_hours,
                'hours' => $hours,
                'remainingMinutes' => $remainingMinutes
            ]);

        } catch (\Exception $e) {
            $this->working_hours = '0.00';
            Log::error('Calculation error (EditForm): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'start_input' => $this->working_start_hour,
                'end_input' => $this->working_end_hour,
                'break_hours_input' => $this->break_duration_hours
            ]);
        }
    }

    public function save()
    {
        $timeValidation = $this->validateTimes();
        $odometerValidation = $this->validateOdometer();

        if (!$timeValidation || !$odometerValidation) {
            session()->flash('error', 'Please fix the validation errors before saving.');
            return;
        }

        $this->validate();

        $breakDurationInMinutes = 0;
        if (!empty($this->break_duration_hours) && is_numeric($this->break_duration_hours)) {
            $breakDurationInHours = (float) $this->break_duration_hours;
            if ($breakDurationInHours > 0) {
                $breakDurationInMinutes = round($breakDurationInHours * 60);
            }
        }

        $this->timesheet->update([
            'project_id' => $this->project_id ?: null,
            'vehicle_id' => $this->vehicle_id ?: null,
            'date' => $this->date,
            'working_start_hour' => $this->working_start_hour ? Carbon::parse($this->working_start_hour) : null,
            'working_end_hour' => $this->working_end_hour ? Carbon::parse($this->working_end_hour) : null,
            'break_duration_minutes' => $breakDurationInMinutes,
            'working_hours' => (float) $this->working_hours,
            'odometer_start' => $this->odometer_start ?: null,
            'odometer_ends' => $this->odometer_ends ?: null,
            'fuel_consumption_status' => $this->fuel_consumption_status,
            'fuel_consumption' => $this->fuel_consumption ?: null,
            'deduction_amount' => $this->deduction_amount ?: 0,
            'note' => $this->note,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Timesheet entry updated successfully.');
        $this->dispatch('timesheetSaved');
    }

    public function render()
    {
        return view('livewire.timesheet.edit-form');
    }
}
