<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimesheetDaily;
use App\Models\Project;
use App\Models\Vehicle;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportsController extends Controller
{
    /**
     * Display the reports dashboard
     */
    public function index()
    {
        try {

            // Get comprehensive statistics
            $totalTimesheets = TimesheetDaily::count();
            $totalProjects = Project::count();
            $totalVehicles = Vehicle::count();
            $totalUsers = User::count();



            $totalSuppliers = Supplier::count();

            // Get current month data
            $currentMonth = Carbon::now()->startOfMonth();
            $currentMonthTimesheets = TimesheetDaily::where('date', '>=', $currentMonth)->count();
            $currentMonthHours = TimesheetDaily::where('date', '>=', $currentMonth)->sum('working_hours');
            $currentMonthFuel = TimesheetDaily::where('date', '>=', $currentMonth)->sum('fuel_consumption');

            // Recent activity with all relationships
            $recentTimesheets = TimesheetDaily::with([
                'project:id,name',
                'vehicle:id,plate_number',
                'vehicle.supplier:id,name',
                'user:id,name'
            ])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            // Top performing projects (by hours)
            $topProjects = TimesheetDaily::select([
                'project_id',
                DB::raw('SUM(working_hours) as total_hours'),
                DB::raw('SUM(fuel_consumption) as total_fuel'),
                DB::raw('COUNT(*) as entries_count')
            ])
                ->with('project:id,name')
                ->groupBy('project_id')
                ->orderBy('total_hours', 'desc')
                ->limit(10)
                ->get();


            // Most used vehicles
            $topVehicles = TimesheetDaily::select([
                'vehicle_id',
                DB::raw('SUM(working_hours) as total_hours'),
                DB::raw('SUM(fuel_consumption) as total_fuel'),
                DB::raw('COUNT(*) as entries_count')
            ])
                ->with(['vehicle:id,plate_number', 'vehicle.supplier:id,name'])
                ->groupBy('vehicle_id')
                ->orderBy('entries_count', 'desc')
                ->limit(10)
                ->get();

            return view('reports.index', compact(



                'totalTimesheets',
                'totalProjects',
                'totalVehicles',
                'totalUsers',

                'totalSuppliers',
                'currentMonthTimesheets',
                'currentMonthHours',
                'currentMonthFuel',
                'recentTimesheets',
                'topProjects',
                'topVehicles'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading reports dashboard: ' . $e->getMessage());
        }
    }

    /**

     * Show comprehensive timesheet report with filters
     */
    public function timesheetReport(Request $request)
    {
        try {


            $query = TimesheetDaily::with([
                'project:id,name',
                'vehicle:id,plate_number',
                'vehicle.supplier:id,name',
                'user:id,name,email'
            ]);

            // Apply filters
            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }


            if ($request->filled('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }


            if ($request->filled('project_id')) {
                $query->where('project_id', $request->project_id);
            }


            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }


            if ($request->filled('supplier_id')) {
                $query->whereHas('vehicle.supplier', function($q) use ($request) {
                    $q->where('id', $request->supplier_id);
                });
            }


            if ($request->filled('vehicle_id')) {
                $query->where('vehicle_id', $request->vehicle_id);
            }

            // Get all filtered data
            $timesheets = $query->orderBy('date', 'desc')->get();






            // Get filter options
            $projects = Project::select('id', 'name')->orderBy('name')->get();
            $users = User::select('id', 'name')->orderBy('name')->get();
            $suppliers = Supplier::select('id', 'name')->orderBy('name')->get();
            $vehicles = Vehicle::select('id', 'plate_number')->orderBy('plate_number')->get();

            // Calculate summary statistics
            $summaryStats = [
                'total_entries' => $timesheets->count(),
                'total_hours' => $timesheets->sum('working_hours'),
                'total_fuel' => $timesheets->sum('fuel_consumption'),
                'avg_efficiency' => $timesheets->sum('working_hours') > 0 ?
                    $timesheets->sum('fuel_consumption') / $timesheets->sum('working_hours') : 0,
                'unique_projects' => $timesheets->pluck('project_id')->unique()->count(),
                'unique_vehicles' => $timesheets->pluck('vehicle_id')->unique()->count(),
                'unique_users' => $timesheets->pluck('user_id')->unique()->count(),
                'date_range' => [
                    'from' => $timesheets->min('date'),
                    'to' => $timesheets->max('date')
                ]
            ];

            return view('reports.timesheet-report', compact(
                'timesheets',
                'projects',
                'users',
                'suppliers',
                'vehicles',
                'summaryStats'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading timesheet report: ' . $e->getMessage());
        }
    }

    /**

     * Show comprehensive vehicle utilization report
     */
    public function vehicleUtilization(Request $request)
    {
        try {











            $query = TimesheetDaily::query();

            // Apply date filters
            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }


            if ($request->filled('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }


            if ($request->filled('supplier_id')) {
                $query->whereHas('vehicle.supplier', function($q) use ($request) {
                    $q->where('id', $request->supplier_id);
                });
            }




















            // Get vehicle statistics
            $vehicleStats = $query->select([
                'vehicle_id',
                DB::raw('SUM(working_hours) as total_hours'),
                DB::raw('SUM(fuel_consumption) as total_fuel'),
                DB::raw('COUNT(DISTINCT project_id) as projects_count'),
                DB::raw('COUNT(DISTINCT user_id) as users_count'),
                DB::raw('COUNT(*) as total_entries'),
                DB::raw('AVG(working_hours) as avg_hours_per_day'),
                DB::raw('AVG(fuel_consumption) as avg_fuel_per_day'),
                DB::raw('MIN(date) as first_used'),
                DB::raw('MAX(date) as last_used')
            ])
                ->groupBy('vehicle_id')
                ->get()
                ->map(function($stat) {
                    $vehicle = Vehicle::with(['supplier:id,name'])->find($stat->vehicle_id);
                    if ($vehicle) {
                        $stat->vehicle_plate = $vehicle->plate_number ?? 'N/A';
                        $stat->supplier_name = $vehicle->supplier->name ?? 'N/A';
                        $stat->avg_efficiency = $stat->total_hours > 0 ? $stat->total_fuel / $stat->total_hours : 0;

                        // Calculate utilization percentage (assuming 8 hours per day, 30 days per month)
                        $totalPossibleHours = 240; // 8 * 30
                        $stat->utilization_percentage = ($stat->total_hours / $totalPossibleHours) * 100;
                    } else {
                        $stat->vehicle_plate = 'N/A';
                        $stat->supplier_name = 'N/A';
                        $stat->avg_efficiency = 0;
                        $stat->utilization_percentage = 0;
                    }
                    return $stat;
                })
                ->sortByDesc('total_hours');

            $suppliers = Supplier::select('id', 'name')->orderBy('name')->get();

            return view('reports.vehicle-utilization', compact('vehicleStats', 'suppliers'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading vehicle utilization report: ' . $e->getMessage());
        }
    }

    /**

     * Show comprehensive project summary report
     */
    public function projectSummary(Request $request)
    {
        try {
            $query = TimesheetDaily::with([
                'project:id,name',
                'vehicle:id,plate_number',
                'vehicle.supplier:id,name',
                'user:id,name,email'
            ]);

            // Apply filters
            if ($request->filled('project_id')) {
                $query->where('project_id', $request->project_id);
                $selectedProject = Project::find($request->project_id);
                $projectName = $selectedProject ? $selectedProject->name : 'All Projects';
            } else {
                $projectName = 'All Projects';
            }

            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }

            // Get filtered data
            $timesheets = $query->orderBy('date', 'desc')->get();

            // Get projects for the filter dropdown
            $projects = Project::select('id', 'name')->orderBy('name')->get();

            return view('reports.project-summary', compact('timesheets', 'projects', 'projectName'));

        } catch (\Exception $e) {
            return back()->with('error', 'Error loading project summary report: ' . $e->getMessage());
        }
    }

    /**

     * Show comprehensive fuel consumption report
     */
    public function fuelConsumption(Request $request)
    {
        try {


            $query = TimesheetDaily::with([
                'project:id,name',
                'vehicle:id,plate_number',
                'vehicle.supplier:id,name',
                'user:id,name'
            ]);

            // Apply filters
            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }


            if ($request->filled('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }


            if ($request->filled('project_id')) {
                $query->where('project_id', $request->project_id);
            }


            if ($request->filled('vehicle_id')) {
                $query->where('vehicle_id', $request->vehicle_id);
            }


            if ($request->filled('supplier_id')) {
                $query->whereHas('vehicle.supplier', function($q) use ($request) {
                    $q->where('id', $request->supplier_id);
                });
            }

            // Get all fuel consumption data
            $fuelData = $query->orderBy('date', 'desc')->get();





            // Get filter options
            $projects = Project::select('id', 'name')->orderBy('name')->get();
            $vehicles = Vehicle::select('id', 'plate_number')->orderBy('plate_number')->get();
            $suppliers = Supplier::select('id', 'name')->orderBy('name')->get();

            // Calculate fuel consumption summary
            $fuelSummary = [
                'total_entries' => $fuelData->count(),
                'total_hours' => $fuelData->sum('working_hours'),
                'total_fuel' => $fuelData->sum('fuel_consumption'),
                'avg_efficiency' => $fuelData->sum('working_hours') > 0 ?
                    $fuelData->sum('fuel_consumption') / $fuelData->sum('working_hours') : 0,
                'unique_projects' => $fuelData->pluck('project_id')->unique()->count(),
                'unique_vehicles' => $fuelData->pluck('vehicle_id')->unique()->count(),
                'unique_users' => $fuelData->pluck('user_id')->unique()->count(),
                'date_range' => [
                    'from' => $fuelData->min('date'),
                    'to' => $fuelData->max('date')
                ]
            ];

            return view('reports.fuel-consumption', compact('fuelData', 'projects', 'vehicles', 'suppliers', 'fuelSummary'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading fuel consumption report: ' . $e->getMessage());
        }
    }
}
