<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TimesheetDaily;
use App\Models\ProjectVehicle;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('branch')->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        return view('projects.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:projects',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'nullable|in:active,inactive,completed',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        try {
            DB::beginTransaction();

            $project = Project::create($validated);

            DB::commit();
            return redirect()->route('projects.index')
                ->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating project: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['branch', 'employees']);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $branches = Branch::all();
        return view('projects.edit', compact('project', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:projects,code,' . $project->id,
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'nullable|in:active,inactive,completed',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        try {
            DB::beginTransaction();

            $project->update($validated);

            DB::commit();
            return redirect()->route('projects.index')
                ->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating project: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            DB::beginTransaction();

            $project->delete();

            DB::commit();
            return redirect()->route('projects.index')
                ->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting project: ' . $e->getMessage());
        }
    }

    public function fuelConsumptionSummary(Project $project)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $timesheets = TimesheetDaily::where('project_id', $project->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with('vehicle')
            ->get();

        $totalFuelConsumption = $timesheets->sum('fuel_consumption');
        $totalWorkingHours = $timesheets->sum('working_hours');

        return view('projects.fuel_consumption_summary', compact('project', 'timesheets', 'totalFuelConsumption', 'totalWorkingHours'));
    }

    public function printFuelConsumptionSummary(Project $project)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $timesheets = TimesheetDaily::where('project_id', $project->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with('vehicle')
            ->get();

        $totalFuelConsumption = $timesheets->sum('fuel_consumption');
        $totalWorkingHours = $timesheets->sum('working_hours');

        return view('projects.fuel_consumption_print', compact('project', 'timesheets', 'totalFuelConsumption', 'totalWorkingHours'));
    }

    public function projectTimesheets(Request $request, Project $project)
    {
        $fromDate = $request->query('fromDate', now()->startOfMonth()->toDateString());
        $toDate = $request->query('toDate', now()->endOfMonth()->toDateString());

        $timesheets = TimesheetDaily::with('vehicle')
            ->where('project_id', $project->id)
            ->whereBetween('working_start_hour', [$fromDate, $toDate])
            ->where('working_hours', '>', 0)
            ->orderBy('working_start_hour', 'desc')
            ->paginate(15);

        return view('projects.project_timesheets', compact('project', 'fromDate', 'toDate', 'timesheets'));
    }
}
