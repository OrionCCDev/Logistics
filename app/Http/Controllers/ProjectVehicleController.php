<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectVehicle;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ProjectVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectVehicles = ProjectVehicle::with(['project', 'vehicle'])->latest()->paginate(10);
        return view('project_vehicles.index', compact('projectVehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        $vehicles = Vehicle::all();
        return view('project_vehicles.create', compact('projects', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'status' => 'required|in:active,inactive',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string'
        ]);

        ProjectVehicle::create($validated);

        return redirect()->route('project-vehicles.index')
            ->with('success', 'Project vehicle assigned successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectVehicle $projectVehicle)
    {
        return view('project_vehicles.show', compact('projectVehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectVehicle $projectVehicle)
    {
        $projects = Project::all();
        $vehicles = Vehicle::all();
        return view('project_vehicles.edit', compact('projectVehicle', 'projects', 'vehicles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectVehicle $projectVehicle)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'status' => 'required|in:active,inactive',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string'
        ]);

        $projectVehicle->update($validated);

        return redirect()->route('project-vehicles.index')
            ->with('success', 'Project vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectVehicle $projectVehicle)
    {
        $projectVehicle->delete();

        return redirect()->route('project-vehicles.index')
            ->with('success', 'Project vehicle removed successfully.');
    }
}
