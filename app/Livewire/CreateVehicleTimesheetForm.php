<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\Project;
use App\Models\TimesheetDaily;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CreateVehicleTimesheetForm extends Component
{
    public Vehicle $vehicle;
    public $projects = [];

    // Form fields
    public $date;
    public $project_id = ''; // Optional
    public $working_start_hour;
    public $working_end_hour;
    public $break_duration_hours = '1.0'; // Added, default 1 hour
    public $working_hours_display = '0.00'; // Initialize as decimal string
    public $odometer_start;
    public $odometer_ends;
    public $fuel_consumption_status = '';
    public $fuel_consumption;
    public $deduction_amount;
    public $notes;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
        $this->projects = Project::pluck('name', 'id');
        $this->date = now()->format('Y-m-d');
        $this->working_start_hour = now()->setHour(6)->setMinute(0)->setSecond(0)->format('Y-m-d\\TH:i'); // Default start
        $this->working_end_hour = now()->setHour(14)->setMinute(0)->setSecond(0)->format('Y-m-d\\TH:i'); // Default end (e.g. 8 hours later)
        $this->break_duration_hours = '1.0'; // Default break duration
        $this->calculateWorkingHours();
    }

    protected function rules()
    {
        return [
            'date' => [
                'required',
                'date',
                // Rule::unique('timesheet_dailies')->where(function ($query) {
                // return $query->where('user_id', Auth::id())
                // ->where('vehicle_id', $this->vehicle->id)
                // ->where('date', $this->date);
                // })
            ],
            'project_id' => 'required|exists:projects,id',
            'working_start_hour' => 'nullable|date_format:Y-m-d\\TH:i',
            'working_end_hour' => 'nullable|date_format:Y-m-d\\TH:i|after_or_equal:working_start_hour',
            'break_duration_hours' => 'nullable|numeric|min:0|max:24',
            'odometer_start' => 'nullable|numeric|min:0',
            'odometer_ends' => 'nullable|numeric|min:0|gte:odometer_start',
            'fuel_consumption_status' => 'nullable|string|in:by_hours,by_odometer',
            'fuel_consumption' => 'nullable|numeric|min:0',
            'deduction_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    public function updated($propertyName)
    {
        Log::info('updated propertyName: ' . $propertyName . ' with value: ' . $this->$propertyName);
        $this->validateOnly($propertyName);
        if (in_array($propertyName, ['working_start_hour', 'working_end_hour', 'break_duration_hours'])) {
            $this->calculateWorkingHours();
        }
    }

    public function calculateWorkingHours()
    {
        if ($this->working_start_hour && $this->working_end_hour) {
            try {
                $workStart = Carbon::parse($this->working_start_hour)->startOfMinute();
                $workEnd = Carbon::parse($this->working_end_hour)->startOfMinute();

                Log::info('Normalized Parsed Work Start: ' . $workStart->toIso8601String() . ', Normalized Parsed Work End: ' . $workEnd->toIso8601String());

                if ($workEnd->lt($workStart)) {
                    $this->working_hours_display = '0.00';
                    Log::info('Calculation: Work end time is before start time. Display set to 0.00');
                    return;
                }

                $grossWorkInterval = $workStart->diff($workEnd);
                $grossWorkMinutes = 0;
                if ($grossWorkInterval->invert === 0) {
                    $totalDays = (int)$grossWorkInterval->format('%a');
                    $grossWorkMinutes = $totalDays * 24 * 60;
                    $grossWorkMinutes += $grossWorkInterval->h * 60;
                    $grossWorkMinutes += $grossWorkInterval->i;
                } else {
                    Log::warning('Gross work interval was inverted, treating as 0 minutes.');
                }
                Log::info('Calculation: Gross work minutes: ' . $grossWorkMinutes);

                $breakMinutes = 0;
                if (!empty($this->break_duration_hours) && is_numeric($this->break_duration_hours)) {
                    $breakDurationInHours = (float) $this->break_duration_hours;
                    if ($breakDurationInHours > 0) {
                        $breakMinutes = round($breakDurationInHours * 60);
                        Log::info("Calculation: Valid break duration in hours: $breakDurationInHours, minutes: $breakMinutes");
                    }
                } else {
                    Log::info('Calculation: No break duration provided or not numeric. Break minutes set to 0.');
                }

                $netWorkMinutes = $grossWorkMinutes - $breakMinutes;
                Log::info('Calculation: Net work minutes (Gross - Break): ' . $netWorkMinutes);

                if ($netWorkMinutes < 0) {
                    $netWorkMinutes = 0;
                    Log::info('Calculation: Net work minutes was negative, corrected to 0.');
                }

                if ($netWorkMinutes > 0) {
                    $this->working_hours_display = number_format($netWorkMinutes / 60, 2);
                } else {
                    $this->working_hours_display = '0.00';
                }
                Log::info('Final working_hours_display after calculation: ' . $this->working_hours_display);
                $this->dispatch('updateWorkingHoursDisplay', value: $this->working_hours_display);

            } catch (\Exception $e) {
                $this->working_hours_display = 'Error';
                Log::error('Error in calculateWorkingHours: ' . $e->getMessage() . ' // Trace: ' . $e->getTraceAsString());
                $this->dispatch('updateWorkingHoursDisplay', value: $this->working_hours_display);
            }
        } else {
            $this->working_hours_display = '0.00';
            Log::info('Calculation: Not enough data for work hours calculation (start or end missing). Display set to 0.00');
            $this->dispatch('updateWorkingHoursDisplay', value: $this->working_hours_display);
        }
    }

    public function saveTimesheet()
    {
        $validatedData = $this->validate();
        $calculated_working_hours = (float) $this->working_hours_display;

        if (!is_numeric($this->working_hours_display) || $calculated_working_hours < 0) {
            if ($this->working_start_hour && $this->working_end_hour) {
                 try {
                    $workStart = Carbon::parse($this->working_start_hour)->startOfMinute();
                    $workEnd = Carbon::parse($this->working_end_hour)->startOfMinute();
                    $grossWorkMinutes = 0;
                    if($workEnd->gte($workStart)){
                        $grossInterval = $workStart->diff($workEnd);
                        $totalDays = (int)$grossInterval->format('%a');
                        $grossWorkMinutes = ($totalDays * 24 * 60) + ($grossInterval->h * 60) + $grossInterval->i;
                    }

                    $breakMinutesCalculation = 0;
                    if (!empty($this->break_duration_hours) && is_numeric($this->break_duration_hours)) {
                        $breakDurationInHours = (float) $this->break_duration_hours;
                        if ($breakDurationInHours > 0) {
                             $breakMinutesCalculation = round($breakDurationInHours * 60);
                        }
                    }
                    $netWorkMinutes = $grossWorkMinutes - $breakMinutesCalculation;
                    $calculated_working_hours = $netWorkMinutes > 0 ? round($netWorkMinutes / 60, 2) : 0;
                } catch (\Exception $e) {
                    Log::error('Error calculating final working hours for save: ' . $e->getMessage());
                    session()->flash('error', 'Could not calculate working hours for saving. Please check times.');
                    return;
                }
            } else {
                 $calculated_working_hours = 0;
            }
        }

        $breakDurationInMinutesDb = 0;
        if (!empty($validatedData['break_duration_hours']) && is_numeric($validatedData['break_duration_hours'])) {
            $breakDurationInHoursDb = (float) $validatedData['break_duration_hours'];
            if ($breakDurationInHoursDb > 0) {
                $breakDurationInMinutesDb = round($breakDurationInHoursDb * 60);
            }
        }

        TimesheetDaily::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $this->vehicle->id,
            'project_id' => $validatedData['project_id'] ?: null,
            'date' => $validatedData['date'],
            'working_start_hour' => $validatedData['working_start_hour'],
            'working_end_hour' => $validatedData['working_end_hour'],
            'break_duration_minutes' => $breakDurationInMinutesDb,
            'working_hours' => $calculated_working_hours,
            'odometer_start' => $validatedData['odometer_start'] ?: null,
            'odometer_ends' => $validatedData['odometer_ends'] ?: null,
            'fuel_consumption_status' => $validatedData['fuel_consumption_status'] ?: null,
            'fuel_consumption' => $validatedData['fuel_consumption'] ?: null,
            'deduction_amount' => $validatedData['deduction_amount'] ?: 0,
            'note' => $validatedData['notes'] ?: null,
            'status' => 'draft'
        ]);

        $this->resetForm();
        $this->dispatch('timesheetCreated');
        session()->flash('message', 'Timesheet entry created successfully.');
    }

    public function resetForm()
    {
        $this->resetExcept('vehicle', 'projects');
        $this->date = now()->format('Y-m-d');
        $this->working_start_hour = now()->setHour(6)->setMinute(0)->setSecond(0)->format('Y-m-d\\TH:i');
        $this->working_end_hour = now()->setHour(14)->setMinute(0)->setSecond(0)->format('Y-m-d\\TH:i');
        $this->break_duration_hours = '1.0'; // Reset break duration
        $this->project_id = '';
        $this->odometer_start = null;
        $this->odometer_ends = null;
        $this->fuel_consumption_status = '';
        $this->fuel_consumption = null;
        $this->deduction_amount = null;
        $this->notes = null;
        $this->calculateWorkingHours();
        $this->dispatch('resetForm');
    }

    public function render()
    {
        return view('livewire.create-vehicle-timesheet-form');
    }
}
