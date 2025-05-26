<?php

namespace App\Http\Controllers;

use App\Models\TimesheetDaily;
use App\Models\User;
use App\Models\Project;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TimesheetDailyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::orderBy('name')->get(['id', 'name', 'code']);
        $vehicles = Vehicle::with('supplier')->orderBy('plate_number')->get(['id', 'plate_number', 'vehicle_type']);
        $timesheets = TimesheetDaily::with(['user', 'project', 'vehicle'])->latest()->paginate(10);
        return view('timesheet.index', compact('timesheets', 'projects', 'vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->pluck('name', 'id');
        $projects = Project::orderBy('project_name')->pluck('project_name', 'id');
        $vehicles = Vehicle::orderBy('plate_number')->pluck('plate_number', 'id');
        return view('timesheet.create', compact('users', 'projects', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'date' => ['required', 'date', Rule::unique('timesheet_dailies')->where(function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })->ignore(null, 'id')],
            'project_id' => 'nullable|exists:projects,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'working_start_hour' => 'nullable|date_format:H:i',
            'working_end_hour' => 'nullable|date_format:H:i|after:working_start_hour',
            'break_start_at' => 'nullable|date_format:H:i',
            'break_ends_at' => 'nullable|date_format:H:i|after:break_start_at',
            'working_hours' => 'nullable|numeric|min:0',
            'odometer_start' => 'nullable|numeric|min:0',
            'odometer_ends' => 'nullable|numeric|min:0|gte:odometer_start',
            'fuel_consumption' => 'nullable|numeric|min:0',
            'deduction_amount' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted,approved,rejected',
            'fuel_consumption_status' => 'nullable|in:by_hours,by_odometer',
        ]);

        if (empty($validatedData['user_id'])) {
            $validatedData['user_id'] = Auth::id();
        }

        if (empty($validatedData['status'])) {
            $validatedData['status'] = 'draft';
        }

        TimesheetDaily::create($validatedData);

        return redirect()->route('timesheet.index')->with('success', 'Timesheet entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $timesheet = TimesheetDaily::findOrFail($id);
        $timesheet->load(['user', 'project', 'vehicle']);
        return view('timesheet.show', compact('timesheet'));
    }

    public function edit($id)
    {
        $timesheet = TimesheetDaily::findOrFail($id);
        $users = User::orderBy('name')->pluck('name', 'id');
        $projects = Project::orderBy('name')->pluck('name', 'id');
        $vehicles = Vehicle::orderBy('plate_number')->pluck('plate_number', 'id');
        return view('timesheet.edit', compact('timesheet', 'users', 'projects', 'vehicles'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $timesheet = TimesheetDaily::findOrFail($id);
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'date' => ['required', 'date', Rule::unique('timesheet_dailies')->where(function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })->ignore($timesheet->id, 'id')],
            'project_id' => 'nullable|exists:projects,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'working_start_hour' => 'nullable|date_format:H:i',
            'working_end_hour' => 'nullable|date_format:H:i|after:working_start_hour',
            'break_start_at' => 'nullable|date_format:H:i',
            'break_ends_at' => 'nullable|date_format:H:i|after:break_start_at',
            'working_hours' => 'nullable|numeric|min:0',
            'odometer_start' => 'nullable|numeric|min:0',
            'odometer_ends' => 'nullable|numeric|min:0|gte:odometer_start',
            'fuel_consumption' => 'nullable|numeric|min:0',
            'deduction_amount' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted,approved,rejected',
            'fuel_consumption_status' => 'nullable|in:by_hours,by_odometer',
        ]);

        if (empty($validatedData['user_id'])) {
            $validatedData['user_id'] = Auth::id();
        }

        if (empty($validatedData['status']) && !$request->user()->can('manage timesheets')) {
            unset($validatedData['status']);
        } elseif (empty($validatedData['status'])) {
            $validatedData['status'] = $timesheet->status;
        }

        $timesheet->update($validatedData);

        return redirect()->route('timesheet.index')->with('success', 'Timesheet entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $timesheet = TimesheetDaily::findOrFail($id);
        $timesheet->delete();
        return redirect()->route('timesheet.index')->with('success', 'Timesheet entry deleted successfully.');
    }
}
