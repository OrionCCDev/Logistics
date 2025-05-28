<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Vehicle;
use App\Models\Project;
use App\Models\TimesheetDaily;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Supplier data
        $supplierCount = Supplier::count();
        $vehiclesPerSupplier = Supplier::withCount('vehicles')->orderBy('vehicles_count', 'desc')->get();
        $mostVehiclesSupplier = $vehiclesPerSupplier->first();

        // Vehicle data
        $vehicleCount = Vehicle::count();

        // Fuel consumption data
        $now = Carbon::now();
        $startOfWeek = $now->startOfWeek()->toDateString();
        $endOfWeek = $now->endOfWeek()->toDateString();
        $startOfMonth = $now->startOfMonth()->toDateString();
        $endOfMonth = $now->endOfMonth()->toDateString();

        $weeklyFuelConsumption = TimesheetDaily::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->selectRaw('vehicle_id, SUM(fuel_consumption) as total_fuel')
            ->groupBy('vehicle_id')
            ->orderBy('total_fuel', 'desc')
            ->with('vehicle') // Eager load vehicle
            ->take(5) // Get top 5
            ->get();

        $monthlyFuelConsumption = TimesheetDaily::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->selectRaw('vehicle_id, SUM(fuel_consumption) as total_fuel')
            ->groupBy('vehicle_id')
            ->orderBy('total_fuel', 'desc')
            ->with('vehicle') // Eager load vehicle
            ->take(5) // Get top 5
            ->get();

        // Project data
        $projectVehicleCount = Project::withCount('vehicles')->get()->sum('vehicles_count');

        $projectFuelConsumption = Project::with(['vehicles.timesheetDailies' => function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
        }])
        ->get()
        ->map(function ($project) {
            $totalFuel = $project->vehicles->flatMap->timesheetDailies->sum('fuel_consumption');
            return [
                'name' => $project->name,
                'total_fuel' => $totalFuel,
            ];
        })
        ->sortByDesc('total_fuel')
        ->take(5); // Get top 5

        // Vehicles per Supplier Chart Data
        $vehiclesPerSupplierChart = [
            'labels' => $vehiclesPerSupplier->pluck('name'),
            'data' => $vehiclesPerSupplier->pluck('vehicles_count'),
        ];

        // Monthly Fuel Consumption Chart Data (Top 5 Vehicles)
        $monthlyFuelChart = [
            'labels' => $monthlyFuelConsumption->map(fn($c) => $c->vehicle ? $c->vehicle->name : 'Unknown Vehicle'),
            'data' => $monthlyFuelConsumption->pluck('total_fuel'),
        ];

        // Project Fuel Consumption Chart Data (Top 5 Projects)
        $projectFuelChart = [
            'labels' => $projectFuelConsumption->map(fn($p) => $p['name']),
            'data' => $projectFuelConsumption->map(fn($p) => $p['total_fuel']),
        ];

        return view('analytics.index', compact(
            'supplierCount',
            'vehicleCount',
            'mostVehiclesSupplier',
            'vehiclesPerSupplier',
            'weeklyFuelConsumption',
            'monthlyFuelConsumption',
            'projectVehicleCount',
            'projectFuelConsumption',
            'vehiclesPerSupplierChart',
            'monthlyFuelChart',
            'projectFuelChart'
        ));
    }
}
