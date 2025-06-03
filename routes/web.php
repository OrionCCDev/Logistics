<?php

use App\Models\Project;
use App\Models\Vehicle;
use App\Models\Operator;
use App\Livewire\ReportsPage;
use App\Models\TimesheetDaily;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AnalyticsController;
use App\Livewire\ProjectVehicleTimesheetTable;
use App\Http\Controllers\ProjectVehicleController;
use App\Http\Controllers\TimesheetDailyController;
use App\Http\Controllers\VehicleTimesheetController;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');



        Route::middleware(['auth', 'role:orionAdmin,orionManager', 'verified'])->group(function () {
            Route::resource('users', UserController::class);
            Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::resources([
                'branches' => BranchController::class,
                'projects' => ProjectController::class,
                'suppliers' => SupplierController::class,
                'categories' => CategoryController::class,
                'operators' => OperatorController::class,
                'vehicles' => VehicleController::class,
                'invoices' => InvoiceController::class,
            ]);
            Route::resource('project_vehicles', ProjectVehicleController::class);
            Route::get('projects/{project}/fuel-consumption-summary', [ProjectController::class, 'fuelConsumptionSummary'])->name('projects.fuel_consumption_summary');
            Route::get('projects/{project}/fuel-consumption-summary/print', [ProjectController::class, 'printFuelConsumptionSummary'])->name('projects.print_fuel_consumption_summary');
            Route::get('suppliers/{supplier}/vehicles', [InvoiceController::class, 'getSupplierVehicles'])->name('suppliers.vehicles');
            Route::get('suppliers/{supplier}/get-vehicles', [SupplierController::class, 'getSupplierVehicles'])->name('suppliers.get_vehicles');
            Route::resource('timesheet', TimesheetDailyController::class)->parameters([
                'timesheet' => 'timesheetDaily'
            ])->except(['create', 'store']);
            Route::get('vehicles/{vehicle}/project/{project}/timesheet', [VehicleController::class, 'projectTimesheet'])->name('vehicles.project.timesheet');
            // Vehicle specific timesheet creation
            Route::post('vehicles/{vehicle}/timesheets', [VehicleTimesheetController::class, 'storeForVehicle'])->name('timesheets.storeForVehicle');

            Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
                    Route::prefix('reports')->name('reports.')->group(function () {
                        Route::get('/', [ReportsController::class, 'index'])->name('index');
                        Route::get('/timesheet', [ReportsController::class, 'timesheetReport'])->name('timesheet');
                        Route::get('/vehicle-utilization', [ReportsController::class, 'vehicleUtilization'])->name('vehicle-utilization');
                        Route::get('/project-summary', [ReportsController::class, 'projectSummary'])->name('project-summary');
                        Route::get('/fuel-consumption', [ReportsController::class, 'fuelConsumption'])->name('fuel-consumption');
                    });            Route::get('projects/{project}/timesheets', [App\Http\Controllers\ProjectController::class, 'projectTimesheets'])->name('projects.timesheets');

            // Compare Reports Routes
            Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
            Route::post('/compare/results', [CompareController::class, 'showResults'])->name('compare.results');
        });

        Route::resource('timesheet', TimesheetDailyController::class)->parameters([
            'timesheet' => 'timesheetDaily'
        ])->only(['create', 'store']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-timesheets', [App\Http\Controllers\TimesheetDailyController::class, 'indexPerUser'])->name('timesheet.indexPerUser');


});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/admin/dashboard', function () {
//         return view('dashboard');
//     })->middleware('role:orionadmin');

//     Route::get('/manager/users', function () {
//         return view('users.index');
//     })->middleware(['role:manager', 'permission:manage-users']);
// });

// Add or ensure these routes for TimesheetDaily exist
Route::resource('timesheets', TimesheetDailyController::class);
// If you prefer specific routes:
// Route::get('/timesheets/{timesheet}/edit', [TimesheetDailyController::class, 'edit'])->name('timesheets.edit');
// Route::put('/timesheets/{timesheet}', [TimesheetDailyController::class, 'update'])->name('timesheets.update');

require __DIR__.'/auth.php';
