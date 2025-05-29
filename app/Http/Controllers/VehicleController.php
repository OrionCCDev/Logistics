<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Project;
use App\Models\TimesheetDaily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::with('supplier' , 'projects')->latest()->paginate(10);
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operators = \App\Models\Operator::where('status', 'active')->get();
        $projects = \App\Models\Project::all();
        $suppliers = \App\Models\Supplier::all();
        return view('vehicles.create', compact('operators', 'projects', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'vehicle_year' => 'nullable|string|max:4',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vehicle_status' => 'nullable|in:active,inactive,maintenance',
            'vehicle_lpo_number' => 'nullable|string|max:255',
            'vehicle_lpo_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'vehicle_mulkia_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'operator_id' => 'nullable|exists:operators,id',
            'project_id' => 'nullable|exists:projects,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $data = $request->except(['vehicle_image', 'vehicle_lpo_document', 'vehicle_mulkia_document', 'operator_id', 'project_id']);
        $data['supplier_id'] = $request->supplier_id;
        $data['operator_id'] = $request->operator_id;

        if ($request->hasFile('vehicle_image')) {
            $image = $request->file('vehicle_image');
            $imageName = uniqid('vehicle_') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('dashAssets/uploads/vehicles/images'), $imageName);
            $data['vehicle_image'] = 'dashAssets/uploads/vehicles/images/' . $imageName;
        }

        if ($request->hasFile('vehicle_lpo_document')) {
            $lpoDoc = $request->file('vehicle_lpo_document');
            $lpoName = uniqid('lpo_') . '.' . $lpoDoc->getClientOriginalExtension();
            $lpoDoc->move(public_path('dashAssets/uploads/vehicles/lpo'), $lpoName);
            $data['vehicle_lpo_document'] = 'dashAssets/uploads/vehicles/lpo/' . $lpoName;
        }

        if ($request->hasFile('vehicle_mulkia_document')) {
            $mulkiaDoc = $request->file('vehicle_mulkia_document');
            $mulkiaName = uniqid('mulkia_') . '.' . $mulkiaDoc->getClientOriginalExtension();
            $mulkiaDoc->move(public_path('dashAssets/uploads/vehicles/mulkia'), $mulkiaName);
            $data['vehicle_mulkia_document'] = 'dashAssets/uploads/vehicles/mulkia/' . $mulkiaName;
        }

        $vehicle = Vehicle::create($data);

        // Assign operator if provided
        if ($request->filled('operator_id')) {
            $operator = \App\Models\Operator::find($request->operator_id);
            if ($operator) {
                $operator->update(['vehicle_id' => $vehicle->id]);
            }
        }

        // Assign project if provided
        if ($request->filled('project_id')) {
            \App\Models\ProjectVehicle::create([
                'project_id' => $request->project_id,
                'vehicle_id' => $vehicle->id,
                'status' => 'active',
                'start_date' => now(),
            ]);
        }

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Vehicle $vehicle)
    {
        $vehicle->load(['projectVehicles.project']);

        $fuelConsumptionQuery = $vehicle->timesheetDailies()
            ->whereNotNull('fuel_consumption')
            ->with('project')
            ->orderBy('date', 'desc');

        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $period = $request->input('period');
        $project_id = $request->input('project_id');

        if ($period) {
            switch ($period) {
                case 'this_week':
                    $date_from = Carbon::now()->startOfWeek()->toDateString();
                    $date_to = Carbon::now()->endOfWeek()->toDateString();
                    break;
                case 'this_month':
                    $date_from = Carbon::now()->startOfMonth()->toDateString();
                    $date_to = Carbon::now()->endOfMonth()->toDateString();
                    break;
                case 'this_year':
                    $date_from = Carbon::now()->startOfYear()->toDateString();
                    $date_to = Carbon::now()->endOfYear()->toDateString();
                    break;
            }
        }

        if ($date_from && $date_to) {
            $fuelConsumptionQuery->whereBetween('date', [$date_from, $date_to]);
        } elseif ($date_from) {
            $fuelConsumptionQuery->where('date', '>=', $date_from);
        } elseif ($date_to) {
            $fuelConsumptionQuery->where('date', '<=', $date_to);
        }

        if ($project_id) {
            $fuelConsumptionQuery->where('project_id', $project_id);
        }

        $fuelConsumptions = $fuelConsumptionQuery->paginate(31);

        // Get only projects assigned to this vehicle
        $projects = $vehicle->projects()->get();

        return view('vehicles.show', compact('vehicle', 'fuelConsumptions', 'date_from', 'date_to', 'period', 'project_id', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $operators = \App\Models\Operator::where('status', 'active')->get();
        $projects = \App\Models\Project::where('status', 'active')->get();
        $suppliers = \App\Models\Supplier::all();
        return view('vehicles.edit', compact('vehicle', 'operators', 'projects', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'vehicle_year' => 'nullable|string|max:4',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vehicle_status' => 'nullable|in:active,inactive,maintenance',
            'vehicle_lpo_number' => 'nullable|string|max:255',
            'vehicle_lpo_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'vehicle_mulkia_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'operator_id' => 'nullable|exists:operators,id',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $data = $request->except(['vehicle_image', 'vehicle_lpo_document', 'vehicle_mulkia_document', 'operator_id', 'project_id']);
        $data['supplier_id'] = $request->supplier_id;
        $data['operator_id'] = $request->operator_id;

        if ($request->hasFile('vehicle_image')) {
            if ($vehicle->vehicle_image) {
                $oldImagePath = public_path($vehicle->vehicle_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image = $request->file('vehicle_image');
            $imageName = uniqid('vehicle_') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('dashAssets/uploads/vehicles/images'), $imageName);
            $data['vehicle_image'] = 'dashAssets/uploads/vehicles/images/' . $imageName;
        }

        if ($request->hasFile('vehicle_lpo_document')) {
            if ($vehicle->vehicle_lpo_document) {
                $oldLpoPath = public_path($vehicle->vehicle_lpo_document);
                if (file_exists($oldLpoPath)) {
                    unlink($oldLpoPath);
                }
            }
            $lpoDoc = $request->file('vehicle_lpo_document');
            $lpoName = uniqid('lpo_') . '.' . $lpoDoc->getClientOriginalExtension();
            $lpoDoc->move(public_path('dashAssets/uploads/vehicles/lpo'), $lpoName);
            $data['vehicle_lpo_document'] = 'dashAssets/uploads/vehicles/lpo/' . $lpoName;
        }

        if ($request->hasFile('vehicle_mulkia_document')) {
            if ($vehicle->vehicle_mulkia_document) {
                $oldMulkiaPath = public_path($vehicle->vehicle_mulkia_document);
                if (file_exists($oldMulkiaPath)) {
                    unlink($oldMulkiaPath);
                }
            }
            $mulkiaDoc = $request->file('vehicle_mulkia_document');
            $mulkiaName = uniqid('mulkia_') . '.' . $mulkiaDoc->getClientOriginalExtension();
            $mulkiaDoc->move(public_path('dashAssets/uploads/vehicles/mulkia'), $mulkiaName);
            $data['vehicle_mulkia_document'] = 'dashAssets/uploads/vehicles/mulkia/' . $mulkiaName;
        }

        $vehicle->update($data);

        // Handle operator assignment
        if ($request->filled('operator_id')) {
            // Remove current operator assignment if any
            \App\Models\Operator::where('vehicle_id', $vehicle->id)->update(['vehicle_id' => null]);

            // Assign new operator
            $operator = \App\Models\Operator::find($request->operator_id);
            if ($operator) {
                $operator->update(['vehicle_id' => $vehicle->id]);
            }
        } else {
            // Remove operator assignment if operator_id is empty
            \App\Models\Operator::where('vehicle_id', $vehicle->id)->update(['vehicle_id' => null]);
        }

        // Handle project assignment
        if ($request->filled('project_id')) {
            // Check if project assignment already exists
            $projectVehicle = \App\Models\ProjectVehicle::where('vehicle_id', $vehicle->id)
                ->where('status', 'active')
                ->first();

            if ($projectVehicle) {
                // Update existing assignment
                $projectVehicle->update([
                    'project_id' => $request->project_id,
                    'status' => 'active',
                ]);
            } else {
                // Create new assignment
                \App\Models\ProjectVehicle::create([
                    'project_id' => $request->project_id,
                    'vehicle_id' => $vehicle->id,
                    'status' => 'active',
                    'start_date' => now(),
                ]);
            }
        } else {
            // Deactivate current project assignment if any
            \App\Models\ProjectVehicle::where('vehicle_id', $vehicle->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive', 'end_date' => now()]);
        }

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->vehicle_image) {
            $imagePath = public_path($vehicle->vehicle_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        if ($vehicle->vehicle_lpo_document) {
            $lpoPath = public_path($vehicle->vehicle_lpo_document);
            if (file_exists($lpoPath)) {
                unlink($lpoPath);
            }
        }
        if ($vehicle->vehicle_mulkia_document) {
            $mulkiaPath = public_path($vehicle->vehicle_mulkia_document);
            if (file_exists($mulkiaPath)) {
                unlink($mulkiaPath);
            }
        }

        // Remove associations from project_vehicles
        $vehicle->projectVehicles()->delete();

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    public function projectTimesheet(Vehicle $vehicle, Project $project)
    {
        $timesheets = TimesheetDaily::where('vehicle_id', $vehicle->id)
            ->where('project_id', $project->id)
            ->with(['user', 'vehicle', 'project'])
            ->latest('date')
            ->paginate(15);

        return view('vehicles.project_timesheet', compact('vehicle', 'project', 'timesheets'));
    }
}
