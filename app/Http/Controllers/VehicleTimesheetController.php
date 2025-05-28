<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\TimesheetDaily; // Changed to TimesheetDaily model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Add Log facade

class VehicleTimesheetController extends Controller // Renamed class
{
    /**
     * Store a newly created timesheet daily entry for a specific vehicle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function storeForVehicle(Request $request, Vehicle $vehicle)
    {
        $userId = $request->input('user_id', Auth::id());

        // Validation rules adapted from TimesheetDailyController and previous TimesheetController
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'date' => ['required', 'date', Rule::unique('timesheet_dailies')->where(function ($query) use ($request, $userId, $vehicle) {
                // Ensure date uniqueness for the combination of user, vehicle, and date for timesheet_dailies table
                return $query->where('user_id', $userId)
                             ->where('vehicle_id', $vehicle->id) // Assuming vehicle_id is part of the unique constraint here
                             ->where('date', $request->date);
            })->ignore(null, 'id')],
            'working_start_hour' => 'required|date_format:Y-m-d\TH:i', // Expecting datetime-local format
            'working_end_hour' => 'required|date_format:Y-m-d\TH:i|after_or_equal:working_start_hour',
            'break_start_at' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:working_start_hour|before_or_equal:working_end_hour',
            'break_ends_at' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:break_start_at|before_or_equal:working_end_hour',
            'working_hours' => 'nullable|numeric|min:0',
            'odometer_start' => 'nullable|numeric|min:0',
            'odometer_ends' => 'nullable|numeric|min:0|gte:odometer_start',
            'fuel_consumption_status' => 'nullable|in:by_hours,by_odometer',
            'fuel_consumption' => 'nullable|numeric|min:0',
            'deduction_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Prepare data for TimesheetDaily creation
        $dataToCreate = $validatedData; // Start with validated data
        $dataToCreate['vehicle_id'] = $vehicle->id; // Explicitly set vehicle_id
        $dataToCreate['status'] = 'draft'; // Default status

        // Auto-calculate working_hours if not provided by the form (e.g. if the JS calculation is bypassed or field not submitted)
        if (!$request->filled('working_hours') && !empty($validatedData['working_start_hour']) && !empty($validatedData['working_end_hour'])) {
            $workStart = Carbon::parse($validatedData['working_start_hour']);
            $workEnd = Carbon::parse($validatedData['working_end_hour']);
            $grossWorkMinutes = $workEnd->diffInMinutes($workStart); // Absolute difference

            $breakMinutes = 0;
            if (!empty($validatedData['break_start_at']) && !empty($validatedData['break_ends_at'])) {
                $breakStart = Carbon::parse($validatedData['break_start_at']);
                $breakEnd = Carbon::parse($validatedData['break_ends_at']);
                // Ensure breakEnd is after breakStart; validation should also enforce break is within work period.
                if ($breakEnd->gt($breakStart)) {
                    $breakMinutes = $breakEnd->diffInMinutes($breakStart); // Absolute difference
                }
            }

            $netWorkMinutes = $grossWorkMinutes - $breakMinutes;

            Log::info('Working hours calculation details:', [
                'vehicle_id' => $vehicle->id,
                'date' => $validatedData['date'],
                'working_start_hour' => $validatedData['working_start_hour'],
                'working_end_hour' => $validatedData['working_end_hour'],
                'break_start_at' => $validatedData['break_start_at'] ?? null,
                'break_ends_at' => $validatedData['break_ends_at'] ?? null,
                'gross_work_minutes' => $grossWorkMinutes,
                'break_minutes' => $breakMinutes,
                'net_work_minutes_before_clamp' => $netWorkMinutes,
            ]);

            if ($netWorkMinutes < 0) {
                Log::warning('Negative net working minutes calculated. Clamping to 0.', [
                    'vehicle_id' => $vehicle->id,
                    'date' => $validatedData['date'],
                    'calculated_net_minutes' => $netWorkMinutes,
                    'inputs' => $request->only(['working_start_hour', 'working_end_hour', 'break_start_at', 'break_ends_at'])
                ]);
                $netWorkMinutes = 0; // Clamp to 0 if negative
            }
            $dataToCreate['working_hours'] = round($netWorkMinutes / 60, 2);
        } elseif ($request->filled('working_hours')) {
            // If working_hours is submitted directly, ensure it's a number (validation already checks min:0)
            $dataToCreate['working_hours'] = (float) $validatedData['working_hours'];
        }

        // Rename notes to note if TimesheetDaily model expects 'note'
        if (array_key_exists('notes', $dataToCreate)) {
            $dataToCreate['note'] = $dataToCreate['notes'];
            unset($dataToCreate['notes']);
        }

        TimesheetDaily::create($dataToCreate);

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Timesheet entry created successfully for vehicle.');
    }
}
