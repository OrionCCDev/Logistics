<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InvoiceController;
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
            Route::get('suppliers/{supplier}/vehicles', [InvoiceController::class, 'getSupplierVehicles'])->name('suppliers.vehicles');
            Route::get('suppliers/{supplier}/get-vehicles', [SupplierController::class, 'getSupplierVehicles'])->name('suppliers.get_vehicles');
            Route::resource('timesheet', TimesheetDailyController::class)->parameters([
                'timesheet' => 'timesheetDaily'
            ]);
            Route::get('vehicles/{vehicle}/project/{project}/timesheet', [VehicleController::class, 'projectTimesheet'])->name('vehicles.project.timesheet');
            // Vehicle specific timesheet creation
            Route::post('vehicles/{vehicle}/timesheets', [VehicleTimesheetController::class, 'storeForVehicle'])->name('timesheets.storeForVehicle');
        });


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/admin/dashboard', function () {
//         return view('dashboard');
//     })->middleware('role:orionadmin');

//     Route::get('/manager/users', function () {
//         return view('users.index');
//     })->middleware(['role:manager', 'permission:manage-users']);
// });

require __DIR__.'/auth.php';
