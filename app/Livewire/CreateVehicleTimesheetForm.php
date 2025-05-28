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
    public $break_start_at;
    public $break_ends_at;
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
        $this->calculateWorkingHours(); // This will initialize working_hours_display correctly
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
            'working_start_hour' => 'nullable|date_format:Y-m-d\TH:i',
            'working_end_hour' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:working_start_hour',
            'break_start_at' => 'nullable|date_format:Y-m-d\TH:i',
            'break_ends_at' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:break_start_at',
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
        if (in_array($propertyName, ['working_start_hour', 'working_end_hour', 'break_start_at', 'break_ends_at'])) {
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
                    $this->working_hours_display = '0.00'; // Or handle as error string if preferred
                    Log::info('Calculation: Work end time is before start time. Display set to 0.00');
                    return;
                }

                $grossWorkInterval = $workStart->diff($workEnd);
                Log::info('Gross Work Interval properties: days=' . $grossWorkInterval->d . ', h=' . $grossWorkInterval->h . ', i=' . $grossWorkInterval->i . ', s=' . $grossWorkInterval->s . ', f=' . $grossWorkInterval->f . ', y=' . $grossWorkInterval->y . ', m=' . $grossWorkInterval->m . ', invert=' . $grossWorkInterval->invert . ', total_days(from_format)= ' . $grossWorkInterval->format('%a'));

                $grossWorkMinutes = 0;
                if ($grossWorkInterval->invert === 0) {
                    $totalDays = (int)$grossWorkInterval->format('%a');
                    Log::info('Total full days from interval format %a: ' . $totalDays);
                    $grossWorkMinutes = $totalDays * 24 * 60;
                    $grossWorkMinutes += $grossWorkInterval->h * 60;
                    $grossWorkMinutes += $grossWorkInterval->i;
                } else {
                    Log::warning('Gross work interval was inverted, treating as 0 minutes.');
                }
                Log::info('Calculation: Gross work minutes: ' . $grossWorkMinutes);

                $breakMinutes = 0;
                if ($this->break_start_at && $this->break_ends_at) {
                    $breakStart = Carbon::parse($this->break_start_at)->startOfMinute();
                    $breakEnd = Carbon::parse($this->break_ends_at)->startOfMinute();
                    Log::info('Normalized Parsed Break Start: ' . $breakStart->toIso8601String() . ', Normalized Parsed Break End: ' . $breakEnd->toIso8601String());

                    if ($breakEnd->gt($breakStart) && $breakStart->gte($workStart) && $breakEnd->lte($workEnd)) {
                        $breakInterval = $breakStart->diff($breakEnd);
                        Log::info('Break Interval properties: days=' . $breakInterval->d . ', h=' . $breakInterval->h . ', i=' . $breakInterval->i . ', total_days(from_format)= ' . $breakInterval->format('%a'));
                        $totalBreakDays = (int)$breakInterval->format('%a');
                        $breakMinutes = $totalBreakDays * 24 * 60;
                        $breakMinutes += $breakInterval->h * 60;
                        $breakMinutes += $breakInterval->i;
                        Log::info('Calculation: Valid break minutes: ' . $breakMinutes);
                    } else {
                        Log::info('Calculation: Invalid break period or outside working hours. Break minutes set to 0.');
                    }
                } else {
                    Log::info('Calculation: No break times provided. Break minutes set to 0.');
                }

                $netWorkMinutes = $grossWorkMinutes - $breakMinutes;
                Log::info('Calculation: Net work minutes (Gross - Break): ' . $netWorkMinutes);

                if ($netWorkMinutes < 0) {
                    $netWorkMinutes = 0;
                    Log::info('Calculation: Net work minutes was negative, corrected to 0.');
                }

                $hours = floor($netWorkMinutes / 60);
                $minutes = $netWorkMinutes % 60;

                // Convert to decimal hours
                if ($netWorkMinutes > 0) {
                    $this->working_hours_display = number_format($netWorkMinutes / 60, 2);
                } else {
                    $this->working_hours_display = '0.00';
                }
                Log::info('Final working_hours_display after calculation: ' . $this->working_hours_display);

                // Add this line to dispatch the event
                $this->dispatch('updateWorkingHoursDisplay', value: $this->working_hours_display);
                Log::info('Dispatched updateWorkingHoursDisplay event with value: ' . $this->working_hours_display);

            } catch (\Exception $e) {
                $this->working_hours_display = 'Error'; // Keep it simple for error display
                Log::error('Error in calculateWorkingHours: ' . $e->getMessage() . ' // Trace: ' . $e->getTraceAsString());
                // Dispatch event even on error to clear or show error state if needed
                $this->dispatch('updateWorkingHoursDisplay', value: $this->working_hours_display);
            }
        } else {
            $this->working_hours_display = '0.00';
            Log::info('Calculation: Not enough data for work hours calculation (start or end missing). Display set to 0.00');
            // Dispatch event on reset/clear
            $this->dispatch('updateWorkingHoursDisplay', value: $this->working_hours_display);
        }
    }

    public function saveTimesheet()
    {
        $validatedData = $this->validate();

        // Use the already calculated working_hours_display for consistency, convert back to float if needed
        // Or recalculate here if you prefer, but it should match display
        $calculated_working_hours = (float) $this->working_hours_display;
        // Ensure it's not negative if error strings were possible
        if (!is_numeric($this->working_hours_display) || $calculated_working_hours < 0) {
            // Attempt a final calculation if display was an error string or invalid
            // This is a fallback; ideally, validation prevents saving if times are illogical.
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

                    $breakMinutes = 0;
                    if ($this->break_start_at && $this->break_ends_at) {
                        $breakStart = Carbon::parse($this->break_start_at)->startOfMinute();
                        $breakEnd = Carbon::parse($this->break_ends_at)->startOfMinute();
                        if ($breakEnd->gt($breakStart) && $breakStart->gte($workStart) && $breakEnd->lte($workEnd)) {
                             $breakInterval = $breakStart->diff($breakEnd);
                             $totalBreakDays = (int)$breakInterval->format('%a');
                             $breakMinutes = ($totalBreakDays * 24 * 60) + ($breakInterval->h * 60) + $breakInterval->i;
                        }
                    }
                    $netWorkMinutes = $grossWorkMinutes - $breakMinutes;
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

        TimesheetDaily::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $this->vehicle->id,
            'project_id' => $validatedData['project_id'] ?: null,
            'date' => $validatedData['date'],
            'working_start_hour' => $validatedData['working_start_hour'],
            'working_end_hour' => $validatedData['working_end_hour'],
            'break_start_at' => $validatedData['break_start_at'] ?: null,
            'break_ends_at' => $validatedData['break_ends_at'] ?: null,
            'working_hours' => $calculated_working_hours, // Save the decimal format
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
        // $this->working_hours_display = '0.00'; // Let calculateWorkingHours set it
        $this->projects = Project::pluck('name', 'id');
        $this->calculateWorkingHours();
        $this->dispatch('resetForm');
    }

    public function render()
    {
        return view('livewire.create-vehicle-timesheet-form');
    }
}
